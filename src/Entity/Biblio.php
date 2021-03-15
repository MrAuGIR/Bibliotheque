<?php

namespace App\Entity;

use App\Repository\BiblioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BiblioRepository::class)
 */
class Biblio
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $countView;

    /**
     * @ORM\ManyToMany(targetEntity=Book::class, inversedBy="biblios")
     */
    private $books;

    /**
     * @ORM\OneToOne(targetEntity=User::class, inversedBy="biblio", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $UserOwner;

    public function __construct()
    {
        $this->books = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getCountView(): ?int
    {
        return $this->countView;
    }

    public function setCountView(?int $countView): self
    {
        $this->countView = $countView;

        return $this;
    }

    /**
     * @return Collection|Book[]
     */
    public function getBooks(): Collection
    {
        return $this->books;
    }

    public function addBook(Book $book): self
    {
        if (!$this->books->contains($book)) {
            $this->books[] = $book;
        }

        return $this;
    }

    public function removeBook(Book $book): self
    {
        $this->books->removeElement($book);

        return $this;
    }

    public function getUserOwner(): ?User
    {
        return $this->UserOwner;
    }

    public function setUserOwner(User $UserOwner): self
    {
        $this->UserOwner = $UserOwner;

        return $this;
    }
}
