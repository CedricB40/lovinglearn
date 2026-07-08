<?php

namespace App\Entity;

use App\Repository\SubjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SubjectRepository::class)]
#[ORM\Table(name: 'subjects')]
class Subject
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le nom du sujet ne peut pas être vide')]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'Le nom doit faire au minimum {{ limit }} caractères',
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères'
    )]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description ne peut pas être vide')]
    #[Assert\Length(
        min: 10,
        minMessage: 'La description doit faire au minimum {{ limit }} caractères'
    )]
    private ?string $description = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le contenu ne peut pas être vide')]
    #[Assert\Length(
        min: 20,
        minMessage: 'Le contenu doit faire au minimum {{ limit }} caractères'
    )]
    private ?string $content = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'subjects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Theme $theme = null;

    /**
     * @var Collection<int, Like>
     */
    #[ORM\OneToMany(targetEntity: Like::class, mappedBy: 'subject')]
    private Collection $likes;

    public function __construct()
    {
        $this->likes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;
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

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;
        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;
        return $this;
    }

    public function getTheme(): ?Theme
    {
        return $this->theme;
    }

    public function setTheme(?Theme $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * @return Collection<int, Like>
     */
    public function getLikes(): Collection
    {
        return $this->likes;
    }

    public function addLike(Like $like): static
    {
        if (!$this->likes->contains($like)) {
            $this->likes->add($like);
            $like->setSubject($this);
        }

        return $this;
    }

    public function removeLike(Like $like): static
    {
        if ($this->likes->removeElement($like)) {
            // set the owning side to null (unless already changed)
            if ($like->getSubject() === $this) {
                $like->setSubject(null);
            }
        }

        return $this;
    }
}
