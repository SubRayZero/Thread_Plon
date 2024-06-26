<?php

namespace App\Entity;

use App\Repository\ThreadRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThreadRepository::class)]
class Thread
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $body = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated_at = null;

    #[ORM\Column(length: 100)]
    private ?string $status = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'thread_id')]
    private ?User $user = null;

    /**
     * @var Collection<int, Category>
     */
    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'threads')]
    private Collection $category_id;

    /**
     * @var Collection<int, ResponseEntity>
     */
    #[ORM\OneToMany(targetEntity: ResponseEntity::class, mappedBy: 'thread')]
    private Collection $response_id;

    /**
     * @var Collection<int, Vote>
     */
    #[ORM\OneToMany(targetEntity: Vote::class, mappedBy: 'thread')]
    private Collection $vote_id;

    public function __construct()
    {
        $this->category_id = new ArrayCollection();
        $this->response_id = new ArrayCollection();
        $this->vote_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getBody(): ?string
    {
        return $this->body;
    }

    public function setBody(string $body): static
    {
        $this->body = $body;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeInterface $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategoryId(): Collection
    {
        return $this->category_id;
    }

    public function addCategoryId(Category $categoryId): static
    {
        if (!$this->category_id->contains($categoryId)) {
            $this->category_id->add($categoryId);
        }

        return $this;
    }

    public function removeCategoryId(Category $categoryId): static
    {
        $this->category_id->removeElement($categoryId);

        return $this;
    }

    /**
     * @return Collection<int, ResponseEntity>
     */
    public function getResponseId(): Collection
    {
        return $this->response_id;
    }

    public function addResponseId(ResponseEntity $responseId): static
    {
        if (!$this->response_id->contains($responseId)) {
            $this->response_id->add($responseId);
            $responseId->setThread($this);
        }

        return $this;
    }

    public function removeResponseId(ResponseEntity $responseId): static
    {
        if ($this->response_id->removeElement($responseId)) {
            // set the owning side to null (unless already changed)
            if ($responseId->getThread() === $this) {
                $responseId->setThread(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Vote>
     */
    public function getVoteId(): Collection
    {
        return $this->vote_id;
    }

    public function addVoteId(Vote $voteId): static
    {
        if (!$this->vote_id->contains($voteId)) {
            $this->vote_id->add($voteId);
            $voteId->setThread($this);
        }

        return $this;
    }

    public function removeVoteId(Vote $voteId): static
    {
        if ($this->vote_id->removeElement($voteId)) {
            // set the owning side to null (unless already changed)
            if ($voteId->getThread() === $this) {
                $voteId->setThread(null);
            }
        }

        return $this;
    }
}
