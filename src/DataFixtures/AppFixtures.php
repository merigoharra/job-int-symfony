<?php

namespace App\DataFixtures;

use App\Entity\Reviews;
use App\Entity\Stories;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture {

    /*
     * Use for user password hashing
     */
    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder) {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager): void {

        $faker = Factory::create('fr_FR');

        /*
         * Creating fixtures for data testing
         * (note: I got some troubles for the reviews I'll gratefully talk about that during interview about the best way to link a review to a random user, so I just put all created reviews only for current created user)
         */
        //Create Fake User
        for ($u=0; $u<30; $u++){
            $user = new User();
            $hash = $this->encoder->hashPassword($user, $faker->password);
            $user->setEmail($faker->email)
                 ->setFirstName($faker->firstName)
                 ->setLastName($faker->lastName)
                 ->setPassword($hash);
            $manager->persist($user);
            // Create fake story
            for ($s=0; $s<mt_rand(3, 7); $s++ ){
                $story = new Stories();
                $story->setUser($user)
                       ->setTitle($faker->sentence(6, true))
                       ->setContent($faker->realText(mt_rand(50, 200)))
                       ->setCreatedAt($faker->dateTime);
                $manager->persist($story);
                // Create fake Review
                for($r=0; $r< mt_rand(1,10); $r++){
                    $review = new Reviews();
                    $review->setContent($faker->sentence(mt_rand(1,7)))
                        ->setStory($story)
                        ->setUser($user);
                    $manager->persist($review);
                }
            }
        }
        $manager->flush();
    }
}
