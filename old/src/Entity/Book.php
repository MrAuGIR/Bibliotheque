<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 */
class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $publishedAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ISBN;

    /**
     * @ORM\Column(type="datetime")
     */
    private $addedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="books")
     */
    private $editor;

    /**
     * @ORM\ManyToMany(targetEntity=Biblio::class, mappedBy="books")
     */
    private $biblios;

    /**
     * @ORM\OneToMany(targetEntity=Comments::class, mappedBy="book")
     */
    private $comments;

    /**
     * @ORM\ManyToMany(targetEntity=Type::class, mappedBy="books")
     */
    private $types;

    /**
     * @ORM\ManyToOne(targetEntity=PublishingHouse::class, inversedBy="books")
     */
    private $publishingHouse;


    /**
     * @ORM\OneToOne(targetEntity=Cover::class,  inversedBy="book")
     */
    private $cover;

    /**
     * @ORM\ManyToMany(targetEntity=Writer::class, mappedBy="books")
     */
    private $writers;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $slug;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isApiBook;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $apiId;

    public function __construct()
    {
        $this->biblios = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->types = new ArrayCollection();
        $this->writers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    public function setPublishedAt(?\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    public function getISBN(): ?string
    {
        return $this->ISBN;
    }

    public function setISBN(?string $ISBN): self
    {
        $this->ISBN = $ISBN;

        return $this;
    }

    public function getAddedAt(): ?\DateTimeInterface
    {
        return $this->addedAt;
    }

    public function getCover(): ?Cover
    {
        return $this->cover;
    }

    public function setAddedAt(\DateTimeInterface $addedAt): self
    {
        $this->addedAt = $addedAt;

        return $this;
    }

    public function getEditor(): ?User
    {
        return $this->editor;
    }

    public function setEditor(?User $editor): self
    {
        $this->editor = $editor;

        return $this;
    }

    /**
     * @return Collection|Biblio[]
     */
    public function getBiblios(): Collection
    {
        return $this->biblios;
    }

    public function addBiblio(Biblio $biblio): self
    {
        if (!$this->biblios->contains($biblio)) {
            $this->biblios[] = $biblio;
            $biblio->addBook($this);
        }

        return $this;
    }

    public function removeBiblio(Biblio $biblio): self
    {
        if ($this->biblios->removeElement($biblio)) {
            $biblio->removeBook($this);
        }

        return $this;
    }

    /**
     * @return Collection|Comments[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setBook($this);
        }

        return $this;
    }

    public function removeComment(Comments $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getBook() === $this) {
                $comment->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Type[]
     */
    public function getTypes(): Collection
    {
        return $this->types;
    }

    public function addType(Type $type): self
    {
        if (!$this->types->contains($type)) {
            $this->types[] = $type;
            $type->addBook($this);
        }

        return $this;
    }

    public function removeType(Type $type): self
    {
        if ($this->types->removeElement($type)) {
            $type->removeBook($this);
        }

        return $this;
    }

    public function getPublishingHouse(): ?PublishingHouse
    {
        return $this->publishingHouse;
    }

    public function setPublishingHouse(?PublishingHouse $publishingHouse): self
    {
        $this->publishingHouse = $publishingHouse;

        return $this;
    }

    public function setCover(?Cover $cover): self
    {
        $this->cover = $cover;

        return $this;
    }

    /**
     * @return Collection|Writer[]
     */
    public function getWriters(): Collection
    {
        return $this->writers;
    }

    public function addWriter(Writer $writer): self
    {
        if (!$this->writers->contains($writer)) {
            $this->writers[] = $writer;
            $writer->addBook($this);
        }

        return $this;
    }

    public function removeWriter(Writer $writer): self
    {
        if ($this->writers->removeElement($writer)) {
            $writer->removeBook($this);
        }

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getIsApiBook(): ?bool
    {
        return $this->isApiBook;
    }

    public function setIsApiBook(bool $isApiBook): self
    {
        $this->isApiBook = $isApiBook;

        return $this;
    }

    public function getApiId(): ?string
    {
        return $this->apiId;
    }

    public function setApiId(?string $apiId): self
    {
        $this->apiId = $apiId;

        return $this;
    }
    
    /**
     * Permet de savoir si ce livre est déjà dans la biblio de l'utilisateur
     *
     */
    public function isAddByUser(User $user, int $bookApiId = null):bool
    {
        //dans le cas d'un livre depuis l'api google
        if($bookApiId != null){
            $biblio = $user->getBiblio();
            foreach($biblio->getBooks() as $book){
               if($book->getApiId === $bookApiId) return true;
            }

            return false;
        }

        //dans le cas d'un livre depuis la biblio d'un utilisateur
        foreach($this->getBiblios() as $biblio){
            if($biblio->getUserOwner() === $user) return true;
        }

        return false;
    }
}
