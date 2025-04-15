<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $code = null;

    #[ORM\ManyToMany(targetEntity: Book::class, inversedBy: 'tags')]
    private Collection $books;

    #[ORM\Column(length: 8, nullable: true)]
    private ?string $color = null;

    #[ORM\ManyToMany(targetEntity: Biblio::class, mappedBy: 'tags')]
    private Collection $biblios;

    public function __construct()
    {
        $this->books = new ArrayCollection();
        $this->biblios = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

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

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(?string $color): static
    {
        $this->color = $color;

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
            $biblio->addTag($this);
        }

        return $this;
    }

    public function removeBiblio(Biblio $biblio): static
    {
        if ($this->biblios->removeElement($biblio)) {
            $biblio->removeTag($this);
        }

        return $this;
    }
}
