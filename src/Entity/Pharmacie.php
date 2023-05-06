<?php

namespace App\Entity;

use App\Repository\PharmacieRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: PharmacieRepository::class)]
class Pharmacie
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["pharmacies"])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez ajouter l'adresse de votre pharmacie")]
    #[Groups(["pharmacies"])]

    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez ajouter le nom de votre pharmacie")]
    #[Groups(["pharmacies"])]

    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez ajouter votre numéro de télephone")]

    #[Assert\Length(
        min: 8,
        max: 15,
        minMessage: 'Votre numéro doit etre composer de 8 numéros au minimum',
        maxMessage: 'Non valide',
    )]
    #[Groups(["pharmacies"])]

    private ?string $num_tel = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank (message:"Veuillez entrer une description")]
    #[Groups(["pharmacies"])]

    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez choisir votre état")]
    #[Groups(["pharmacies"])]


    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez ajouter votre horaire de travail")]
    #[Groups(["pharmacies"])]


    private ?string $horaire = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez ajouter votre email")]
    #[Assert\Email(message: "Le mail n'est pas valide")]
    #[Groups(["pharmacies"])]

    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Veuillez ajouter la matricule de votre pharmacie")]
    #[Groups(["pharmacies"])]


    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    #[Groups(["pharmacies"])]

    private ?string $services = null;

    #[ORM\OneToMany(mappedBy: 'pharmacie', targetEntity: Facture::class , cascade: ['persist', 'remove'])]
    private Collection $factures;

    #[ORM\Column(length: 255)]
    private ?string $Gouvernorat = null;

    #[ORM\OneToOne(inversedBy: 'pharmacie', cascade: ['persist', 'remove'])]
    private ?User $pharmacien = null;

  

    public function __construct()
    {
        $this->factures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse = null): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom = null): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->num_tel;
    }

    public function setNumTel(string $num_tel = null): self
    {
        $this->num_tel = $num_tel;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description = null ): self
    {
        $this->description = $description;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat = null ): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getHoraire(): ?string
    {
        return $this->horaire;
    }

    public function setHoraire(string $horaire = null ): self
    {
        $this->horaire = $horaire;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email = null ): self
    {
        $this->email = $email;

        return $this;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule = null): self
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getServices(): ?string
    {
        return $this->services;
    }

    public function setServices(string $services = null ): self
    {
        $this->services = $services;

        return $this;
    }
    public function getPharmacien(): ?User
    {
        return $this->pharmacien;
    }

    public function setPharmacien(?User $pharmacien): self
    {
        $this->pharmacien = $pharmacien;

        return $this;
    }

    /**
     * @return Collection<int, Facture>
     */
    public function getFactures(): Collection
    {
        return $this->factures;
    }

    public function addFacture(Facture $facture): self
    {
        if (!$this->factures->contains($facture)) {
            $this->factures->add($facture);
            $facture->setPharmacie($this);
        }

        return $this;
    }

    public function removeFacture(Facture $facture): self
    {
        if ($this->factures->removeElement($facture)) {
            // set the owning side to null (unless already changed)
            if ($facture->getPharmacie() === $this) {
                $facture->setPharmacie(null);
            }
        }

        return $this;
    }
    public function __toString() :string {
        return $this->nom;
    }

    public function getGouvernorat(): ?string
    {
        return $this->Gouvernorat;
    }

    public function setGouvernorat(string $Gouvernorat): self
    {
        $this->Gouvernorat = $Gouvernorat;

        return $this;
    }

  
}
