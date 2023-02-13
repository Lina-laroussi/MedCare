<?php

namespace App\Entity;

use App\Repository\RemboursementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RemboursementRepository::class)]
class Remboursement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant_a_rembourser = null;

    #[ORM\OneToOne(mappedBy: 'remboursement', cascade: ['persist', 'remove'])]
    private ?FicheSoin $ficheSoin = null;

    #[ORM\ManyToOne(inversedBy: 'remboursements')]
    private ?HistoriqueRemboursement $historiqueRemboursement = null;

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

    public function getFicheSoin(): ?FicheSoin
    {
        return $this->ficheSoin;
    }

    public function setFicheSoin(?FicheSoin $ficheSoin): self
    {
        // unset the owning side of the relation if necessary
        if ($ficheSoin === null && $this->ficheSoin !== null) {
            $this->ficheSoin->setRemboursement(null);
        }

        // set the owning side of the relation if necessary
        if ($ficheSoin !== null && $ficheSoin->getRemboursement() !== $this) {
            $ficheSoin->setRemboursement($this);
        }

        $this->ficheSoin = $ficheSoin;

        return $this;
    }

    public function getHistoriqueRemboursement(): ?HistoriqueRemboursement
    {
        return $this->historiqueRemboursement;
    }

    public function setHistoriqueRemboursement(?HistoriqueRemboursement $historiqueRemboursement): self
    {
        $this->historiqueRemboursement = $historiqueRemboursement;

        return $this;
    }
}
