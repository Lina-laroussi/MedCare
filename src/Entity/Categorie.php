<?php

namespace App\Entity;

use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("categories")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:" Le nom ne peut pas être vide")]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: "Le nom doit avoir au moins {{ limit }} caractères",
        maxMessage: "Le nom ne doit pas dépasser {{ limit }} caractères"
    )]
    #[Groups("categories")]
    private ?string $nom = null;

    public function __toString()
    {
        return $this->nom;
    }

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:" Le description ne peut pas être vide")]
    #[Groups("categories")]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Groups("categories")]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:" La marque ne peut pas être vide")]
    #[Groups("categories")]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    #[Groups("categories")]
    private ?string $groupe_age = null;

    #[ORM\ManyToOne]
    private ?User $admin = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Produit::class)]
    private Collection $produits;

    public function __construct()
    {
        $this->produits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getGroupeAge(): ?string
    {
        return $this->groupe_age;
    }

    public function setGroupeAge(string $groupe_age): self
    {
        $this->groupe_age = $groupe_age;

        return $this;
    }

    public function getAdmin(): ?User
    {
        return $this->admin;
    }

    public function setAdmin(?User $admin): self
    {
        $this->admin = $admin;

        return $this;
    }

    /**
     * @return Collection<int, Produit>
     */
    public function getProduits(): Collection
    {
        return $this->produits;
    }

    public function addProduit(Produit $produit): self
    {
        if (!$this->produits->contains($produit)) {
            $this->produits->add($produit);
            $produit->setCategorie($this);
        }

        return $this;
    }

    public function removeProduit(Produit $produit): self
    {
        if ($this->produits->removeElement($produit)) {
            // set the owning side to null (unless already changed)
            if ($produit->getCategorie() === $this) {
                $produit->setCategorie(null);
            }
        }

        return $this;
    }
}
?>