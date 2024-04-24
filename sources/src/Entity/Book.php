<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $publishedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $ISBN = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $addedAt = null;

    #[ORM\ManyToMany(targetEntity: Writer::class, mappedBy: 'books')]
    private Collection $writers;

    #[ORM\ManyToMany(targetEntity: Biblio::class, mappedBy: 'books')]
    private Collection $biblios;

    #[ORM\ManyToOne(inversedBy: 'books')]
    private ?PublishingHouse $publishingHouse = null;

    #[ORM\OneToMany(targetEntity: Notice::class, mappedBy: 'book', orphanRemoval: true)]
    private Collection $notices;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $apiId = null;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'book')]
    private Collection $comments;

    public function __construct()
    {
        $this->writers = new ArrayCollection();
        $this->biblios = new ArrayCollection();
        $this->notices = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeImmutable
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeImmutable $publishedAt): static
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getISBN(): ?string
    {
        return $this->ISBN;
    }

    public function setISBN(?string $ISBN): static
    {
        $this->ISBN = $ISBN;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }

    public function setAddedAt(?\DateTimeInterface $addedAt): static
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    /**
     * @return Collection<int, Writer>
     */
    public function getWriters(): Collection
    {
        return $this->writers;
    }

    public function addWriter(Writer $writer): static
    {
        if (!$this->writers->contains($writer)) {
            $this->writers->add($writer);
            $writer->addBook($this);
        }

        return $this;
    }

    public function removeWriter(Writer $writer): static
    {
        if ($this->writers->removeElement($writer)) {
            $writer->removeBook($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Biblio>
     */
    public function getBiblios(): Collection
    {
        return $this->biblios;
    }

    public function addBiblio(Biblio $biblio): static
    {
        if (!$this->biblios->contains($biblio)) {
            $this->biblios->add($biblio);
            $biblio->addBook($this);
        }

        return $this;
    }

    public function removeBiblio(Biblio $biblio): static
    {
        if ($this->biblios->removeElement($biblio)) {
            $biblio->removeBook($this);
        }

        return $this;
    }

    public function getPublishingHouse(): ?PublishingHouse
    {
        return $this->publishingHouse;
    }

    public function setPublishingHouse(?PublishingHouse $publishingHouse): static
    {
        $this->publishingHouse = $publishingHouse;

        return $this;
    }

    /**
     * @return Collection<int, Notice>
     */
    public function getNotices(): Collection
    {
        return $this->notices;
    }

    public function addNotice(Notice $notice): static
    {
        if (!$this->notices->contains($notice)) {
            $this->notices->add($notice);
            $notice->setBook($this);
        }

        return $this;
    }

    public function removeNotice(Notice $notice): static
    {
        if ($this->notices->removeElement($notice)) {
            // set the owning side to null (unless already changed)
            if ($notice->getBook() === $this) {
                $notice->setBook(null);
            }
        }

        return $this;
    }

    public function getApiId(): ?string
    {
        return $this->apiId;
    }

    public function setApiId(?string $apiId): static
    {
        $this->apiId = $apiId;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setBook($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getBook() === $this) {
                $comment->setBook(null);
            }
        }

        return $this;
    }
}
