<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[Orm\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  private ?int $id = null;

  #[ORM\Column(length: 180)]
  private ?string $email = null;

  /**
   * @var list<string> The user roles
   */
  #[ORM\Column]
  private array $roles = [];

  /**
   * @var string|null The hashed password
   */
  #[ORM\Column]
  private ?string $password = null;

  #[ORM\OneToOne(mappedBy: 'user', cascade: ['persist', 'remove'])]
  private ?Biblio $biblio = null;

  #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'author')]
  private Collection $comments;

  #[ORM\Column]
  private ?\DateTimeImmutable $registerAt = null;

  #[ORM\OneToMany(targetEntity: Star::class, mappedBy: 'owner', orphanRemoval: true)]
  private Collection $stars;

  public function __construct()
  {
    $this->comments = new ArrayCollection();
    $this->stars = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): static
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
   * @see UserInterface
   *
   * @return list<string>
   */
  public function getRoles(): array
  {
    $roles = $this->roles;
    // guarantee every user at least has ROLE_USER
    $roles[] = 'ROLE_USER';

    return array_unique($roles);
  }

  /**
   * @param list<string> $roles
   */
  public function setRoles(array $roles): static
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

  public function setPassword(string $password): static
  {
    $this->password = $password;

    return $this;
  }

  /**
   * @see UserInterface
   */
  public function eraseCredentials(): void
  {
    // If you store any temporary, sensitive data on the user, clear it here
    // $this->plainPassword = null;
  }

  public function getBiblio(): ?Biblio
  {
    return $this->biblio;
  }

  public function setBiblio(Biblio $biblio): static
  {
    // set the owning side of the relation if necessary
    if ($biblio->getUser() !== $this) {
      $biblio->setUser($this);
    }

    $this->biblio = $biblio;

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
      $comment->setAuthor($this);
    }

    return $this;
  }

  public function removeComment(Comment $comment): static
  {
    if ($this->comments->removeElement($comment)) {
      if ($comment->getAuthor() === $this) {
        $comment->setAuthor(null);
      }
    }

    return $this;
  }

  public function getRegisterAt(): ?\DateTimeImmutable
  {
    return $this->registerAt;
  }

  #[Orm\PrePersist]
  public function setRegisterAt(): static
  {
    $this->registerAt = new \DateTimeImmutable();

    return $this;
  }

  /**
   * @return Collection<int, Star>
   */
  public function getStars(): Collection
  {
      return $this->stars;
  }

  public function addStar(Star $star): static
  {
      if (!$this->stars->contains($star)) {
          $this->stars->add($star);
          $star->setOwner($this);
      }

      return $this;
  }

  public function removeStar(Star $star): static
  {
      if ($this->stars->removeElement($star)) {
          // set the owning side to null (unless already changed)
          if ($star->getOwner() === $this) {
              $star->setOwner(null);
          }
      }

      return $this;
  }
}
