<?php

namespace App\DataFixtures;

use App\Entity\Reviews;
use App\Entity\Stories;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        /*
         * Creating fixtures for data testing
         * (note: I got some troubles for the reviews I'll gratefully talk about that during interview about the best way to link a review to a random user, so I just put all created reviews only for current created user)
         */
        for ($u=0; $u<30; $u++){
            $user = new User();
            $user->setEmail($faker->email)
                 ->setFirstName($faker->firstName)
                 ->setLastName($faker->lastName)
                 ->setPassword($faker->password);
            $manager->persist($user);
            for ($s=0; $s<mt_rand(3, 7); $s++ ){
                $story = new Stories();
                $story->setUser($user)
                       ->setTitle($faker->sentence(6, true))
                       ->setContent($faker->realText(mt_rand(50, 200)));
                $manager->persist($story);
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
