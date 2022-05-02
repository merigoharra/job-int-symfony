<?php

namespace App\Entity;

use App\Repository\ReviewsRepository;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints\NotBlank as NotBlank;


/**
 * @ORM\Entity(repositoryClass=ReviewsRepository::class)
 * @ApiResource(
 *     collectionOperations={"GET", "POST"},
 *     itemOperations={"GET", "PUT", "PATCH"},
 *     normalizationContext={
 *       "groups"={"reviews_read"}
 *     }
 * )
 * @ApiFilter(SearchFilter::class)
 */
class Reviews
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"reviews_read"})
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * @Groups({"reviews_read"})
     * @NotBlank(message="Cannot be Blank, a content must be provided")
     */
    private $content;

    /**
     * @NotBlank(message="Cannot be Blank, a Story routes must be provided as /api/stories/{id}")
     * @ORM\ManyToOne(targetEntity=Stories::class, inversedBy="reviews")
     * @Groups({"reviews_read"})
     */
    private $story;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reviews")
     * @Groups({"reviews_read"})
     * @NotBlank(message="Cannot be Blank, a User routes must be provided as /api/users/{id}")
     */
    private $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getStory(): ?Stories
    {
        return $this->story;
    }

    public function setStory(?Stories $story): self
    {
        $this->story = $story;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }
}
