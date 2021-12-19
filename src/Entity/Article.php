<?php

namespace App\Entity;

use App\Repository\ArticleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JetBrains\PhpStorm\Pure;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=ArticleRepository::class)
 */
class Article
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=3, max=255)
     */
    private $nom;

    /**
     * @ORM\Column(type="text")
     * @Assert\Length(min=3, max=255)
     */
    private $description;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=0)
     * @Assert\Range(min=1, max=9999)
     */
    private $prix;

    /**
     * @ORM\ManyToOne(targetEntity=Categorie::class, inversedBy="articles")
     * @ORM\JoinColumn(nullable=false)
     */
    private $categorie;

    /**
     * @ORM\OneToMany(targetEntity=Attachment::class, mappedBy="article", cascade={"persist", "remove"}, orphanRemoval=true)
     */
    private $attachments;

    #[Pure]
    public function __construct()
    {
        $this->attachments = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     * @return $this
     */
    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return $this
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrix(): ?string
    {
        return $this->prix;
    }

    /**
     * @param string $prix
     * @return $this
     */
    public function setPrix(string $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * @return Categorie|null
     */
    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    /**
     * @param Categorie|null $categorie
     * @return $this
     */
    public function setCategorie(?Categorie $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection|Attachment[]
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    /**
     * @param Attachment $attachment
     * @return $this
     */
    public function addAttachment(Attachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
            $attachment->setArticle($this);
        }

        return $this;
    }

    /**
     * @param Attachment $attachment
     * @return $this
     */
    public function removeAttachment(Attachment $attachment): self
    {
        if ($this->attachments->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getArticle() === $this) {
                $attachment->setArticle(null);
            }
        }

        return $this;
    }
}
