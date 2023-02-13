<?php

namespace App\Entity;

use App\Repository\HistoriqueRemboursementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HistoriqueRemboursementRepository::class)]
class HistoriqueRemboursement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $montant_annuel = null;

    #[ORM\OneToMany(mappedBy: 'historiqueRemboursement', targetEntity: Remboursement::class)]
    private Collection $remboursements;

    public function __construct()
    {
        $this->remboursements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontantAnnuel(): ?float
    {
        return $this->montant_annuel;
    }

    public function setMontantAnnuel(float $montant_annuel): self
    {
        $this->montant_annuel = $montant_annuel;

        return $this;
    }

    /**
     * @return Collection<int, Remboursement>
     */
    public function getRemboursements(): Collection
    {
        return $this->remboursements;
    }

    public function addRemboursement(Remboursement $remboursement): self
    {
        if (!$this->remboursements->contains($remboursement)) {
            $this->remboursements->add($remboursement);
            $remboursement->setHistoriqueRemboursement($this);
        }

        return $this;
    }

    public function removeRemboursement(Remboursement $remboursement): self
    {
        if ($this->remboursements->removeElement($remboursement)) {
            // set the owning side to null (unless already changed)
            if ($remboursement->getHistoriqueRemboursement() === $this) {
                $remboursement->setHistoriqueRemboursement(null);
            }
        }

        return $this;
    }
}
