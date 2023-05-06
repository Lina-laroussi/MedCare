<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Attachment;



#[ORM\Entity(repositoryClass: FactureRepository::class)]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez ajouter le montant du facture")]
    #[Assert\Positive(message: "Non valide")]
    private ?float $montant = null;
    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

   #[ORM\Column]
    private ?string $image_signature = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez ajouter le numéro du facture")]
    private ?string $num_facture = null;

    //#[ORM\OneToOne(mappedBy: 'facture' , cascade: ['persist', 'remove'])]
    //private ?Ordonnance $ordonnance = null;

    
    #[ORM\OneToOne(inversedBy: 'facture')]
    private ?Ordonnance $ordonnance = null;


    #[ORM\ManyToOne(inversedBy: 'factures')]
    private ?Pharmacie $pharmacie = null;
    public function __toString() :string {
        return $this->pharmacie;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant=null): self
    {
        $this->montant = $montant;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date =null): self
    {
        $this->date = $date;

        return $this;
    }

    public function getImageSignature(): ?string
    {
        return $this->image_signature;
    }

    public function setImageSignature(string $image_signature = null): self
    {
        $this->image_signature = $image_signature;

        return $this;
    }

    public function getNumFacture(): ?string
    {
        return $this->num_facture;
    }

    public function setNumFacture(string $num_facture = null): self
    {
        $this->num_facture = $num_facture;

        return $this;
    }

    public function getOrdonnance(): ?Ordonnance
    {
        return $this->ordonnance;
    }

    public function setOrdonnance(?Ordonnance $ordonnance = null): self
    {
        // unset the owning side of the relation if necessary
        if ($ordonnance === null && $this->ordonnance !== null) {
            $this->ordonnance->setFacture(null);
        }

        // set the owning side of the relation if necessary
        if ($ordonnance !== null && $ordonnance->getFacture() !== $this) {
            $ordonnance->setFacture($this);
        }

        $this->ordonnance = $ordonnance;

        return $this;
    }

    public function getPharmacie(): ?Pharmacie
    {
        return $this->pharmacie;
    }

    public function setPharmacie(?Pharmacie $pharmacie=null): self
    {
        $this->pharmacie = $pharmacie;

        return $this;
    }
   
    
}
