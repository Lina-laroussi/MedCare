<?php

namespace App\Entity;

use App\Repository\RemboursementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



#[ORM\Entity(repositoryClass: RemboursementRepository::class)]
#[UniqueEntity(fields:['FicheAssurance'], message:"cette fiche d'assurance est déja utilisé dans une fiche de remboursement")]
class Remboursement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[Assert\Positive]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 1,
        max: 5,
        minMessage: 'Montant maximale doit etre composer de 1 numéros au minimum',
        maxMessage: 'Montant maximale ne doit pas dépasser 5 numéros ',
    )]
    #[Assert\Positive]
    #[ORM\Column]
    private ?float $montant_a_rembourser = null;
    
    #[Assert\Positive]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 2,
        max: 3,
        minMessage: 'Montant maximale doit etre composer de 2 numéros au minimum',
        maxMessage: 'Montant maximale ne doit pas dépasser 3 numéros ',
    )]
    #[ORM\Column]
    private ?float $montant_maximale = null;
    
    #[Assert\Positive]
    #[ORM\Column]
    private ?float $taux_remboursement = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_remboursement = null;
    
    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\OneToOne(inversedBy: 'remboursement', cascade: ['persist', 'remove'])]
    private ?FicheAssurance $FicheAssurance = null;



 



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantARembourser(): ?float
    {
        return $this->montant_a_rembourser;
    }

    public function setMontantARembourser(float $montant_a_rembourser): self
    {
        $this->montant_a_rembourser = $montant_a_rembourser;

        return $this;
    }

    public function getMontantMaximale(): ?float
    {
        return $this->montant_maximale;
    }

    public function setMontantMaximale(float $montant_maximale): self
    {
        $this->montant_maximale = $montant_maximale;

        return $this;
    }

    public function getTauxRemboursement(): ?float
    {
        return $this->taux_remboursement;
    }

    public function setTauxRemboursement(float $taux_remboursement): self
    {
        $this->taux_remboursement = $taux_remboursement;

        return $this;
    }
 
    public function getDateRemboursement(): ?\DateTimeInterface
    {
        return $this->date_remboursement;
    }

    public function setDateRemboursement(\DateTimeInterface $date_remboursement): self
    {
        $this->date_remboursement = $date_remboursement;

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

    public function getFicheAssurance(): ?FicheAssurance
    {
        return $this->FicheAssurance;
    }

    public function setFicheAssurance(?FicheAssurance $FicheAssurance): self
    {
        $this->FicheAssurance = $FicheAssurance;

        return $this;
    }




   
}
