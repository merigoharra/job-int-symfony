<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ApiResource(
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "PUT", "PATCH"},
 *     normalizationContext={
 *       "groups"={"users_read"}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"firstName":"partial", "lastName", "id"})
 * @UniqueEntity("email", message="This email is already used")
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"users_read", "stories_read", "reviews_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"users_read"})
     * @Assert\NotBlank(message="Cannot be blank, an email must be provided")
     * @Assert\Email(message="must be a valid Email")
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     * @Groups({"users_read"})
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups({"users_read"})
     * @Assert\NotBlank(message="Cannot be blank, a password must be provided")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity=Stories::class, mappedBy="user")
     * @Groups({"users_read"})
     * @ApiSubresource()
     */
    private $stories;

    /**
     * @ORM\OneToMany(targetEntity=Reviews::class, mappedBy="user")
     * @Groups({"users_read"})
     * @ApiSubresource()
     */
    private $reviews;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users_read", "stories_read", "reviews_read"})
     * @Assert\NotBlank(message="Cannot be blank, a firstName must be provided")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"users_read", "stories_read", "reviews_read"})
     * @Assert\NotBlank(message="Cannot be blank, a lastName must be provided")
     */
    private $lastName;

    public function __construct()
    {
        $this->stories = new ArrayCollection();
        $this->reviews = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Stories>
     */
    public function getStories(): Collection
    {
        return $this->stories;
    }

    public function addStory(Stories $story): self
    {
        if (!$this->stories->contains($story)) {
            $this->stories[] = $story;
            $story->setUser($this);
        }

        return $this;
    }

    public function removeStory(Stories $story): self
    {
        if ($this->stories->removeElement($story)) {
            // set the owning side to null (unless already changed)
            if ($story->getUser() === $this) {
                $story->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Reviews>
     */
    public function getReviews(): Collection
    {
        return $this->reviews;
    }

    public function addReview(Reviews $review): self
    {
        if (!$this->reviews->contains($review)) {
            $this->reviews[] = $review;
            $review->setUser($this);
        }

        return $this;
    }

    public function removeReview(Reviews $review): self
    {
        if ($this->reviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getUser() === $this) {
                $review->setUser(null);
            }
        }

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }
}
