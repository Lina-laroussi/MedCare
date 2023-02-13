<?php

namespace App\Entity;

use App\Repository\FicheSoinRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheSoinRepository::class)]
class FicheSoin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToMany(targetEntity: Medicament::class, mappedBy: 'fiche_soin')]
    private Collection $medicaments;

    #[ORM\ManyToOne]
    private ?User $pharmacien = null;

    #[ORM\ManyToOne(inversedBy: 'fichesoins_assureur')]
    private ?User $assureur = null;

    #[ORM\ManyToOne(inversedBy: 'fichesoins_patient')]
    private ?User $patient = null;

    #[ORM\OneToOne(inversedBy: 'ficheSoin', cascade: ['persist', 'remove'])]
    private ?Remboursement $remboursement = null;

    public function __construct()
    {
        $this->medicaments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection<int, Medicament>
     */
    public function getMedicaments(): Collection
    {
        return $this->medicaments;
    }

    public function addMedicament(Medicament $medicament): self
    {
        if (!$this->medicaments->contains($medicament)) {
            $this->medicaments->add($medicament);
            $medicament->addFicheSoin($this);
        }

        return $this;
    }

    public function removeMedicament(Medicament $medicament): self
    {
        if ($this->medicaments->removeElement($medicament)) {
            $medicament->removeFicheSoin($this);
        }

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

    public function getAssureur(): ?User
    {
        return $this->assureur;
    }

    public function setAssureur(?User $assureur): self
    {
        $this->assureur = $assureur;

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getRemboursement(): ?Remboursement
    {
        return $this->remboursement;
    }

    public function setRemboursement(?Remboursement $remboursement): self
    {
        $this->remboursement = $remboursement;

        return $this;
    }
}
