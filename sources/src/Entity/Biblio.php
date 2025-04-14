<?php

namespace App\Entity;

use App\Repository\BiblioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: BiblioRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Biblio
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 100)]
  private ?string $title = null;

  #[ORM\Column]
  private ?\DateTimeImmutable $createdAt = null;

  #[ORM\OneToOne(inversedBy: 'biblio', cascade: ['persist', 'remove'])]
  #[ORM\JoinColumn(nullable: false)]
  private ?User $user = null;

  #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'biblios', cascade: ['persist'])]
  private Collection $books;

  #[ORM\Column(nullable: true)]
  private ?\DateTimeImmutable $updatedAt = null;

  #[ORM\Column(type: Types::BIGINT, nullable: true)]
  private ?string $views = null;

  public function __construct()
  {
    $this->books = new ArrayCollection();
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

  public function getCreatedAt(): ?\DateTimeImmutable
  {
    return $this->createdAt;
  }

  #[ORM\PrePersist]
  public function setCreatedAt(): static
  {
    $this->createdAt = new \DateTimeImmutable();
    return $this;
  }

  public function getUser(): ?User
  {
    return $this->user;
  }

  public function setUser(User $user): static
  {
    $this->user = $user;

    return $this;
  }

  /**
   * @return Collection<int, Book>
   */
  public function getBooks(): Collection
  {
    return $this->books;
  }

  public function addBook(Book $book): static
  {
    if (!$this->books->contains($book)) {
      $this->books->add($book);
    }

    return $this;
  }

  public function removeBook(Book $book): static
  {
    $this->books->removeElement($book);

    return $this;
  }

  public function containsBook(Book $book): bool
  {
    return $this->books->contains($book);
  }

  public function getListThumbnail(): array
  {
      $return = [];
      /** @var Book $book */
      foreach($this->books as $book) {
          $thumbnails = $book->getThumbnails();
          if (!isset($thumbnails['small'])) {
              continue;
          }
          $return[] = $thumbnails['small'];
      }
      return $return;
  }

  public function getUpdatedAt(): ?\DateTimeImmutable
  {
      return $this->updatedAt;
  }

  public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
  {
      $this->updatedAt = $updatedAt;

      return $this;
  }

  public function getViews(): ?string
  {
      return $this->views;
  }

  public function setViews(?string $views): static
  {
      $this->views = $views;

      return $this;
  }

  public function updateCountViews(UserInterface $user): bool
  {
      if ($user === $this->getUser()) {
          return false;
      }
      $this->views += 1;
      return true;
  }
}
