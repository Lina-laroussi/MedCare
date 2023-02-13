<?php

namespace App\Entity;

use App\Repository\MedicamentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MedicamentRepository::class)]
class Medicament
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $disponibilite = null;

    #[ORM\OneToMany(mappedBy: 'medicament', targetEntity: Stock::class)]
    private Collection $stock;

    #[ORM\ManyToMany(targetEntity: FicheSoin::class, inversedBy: 'medicaments')]
    private Collection $fiche_soin;

    public function __construct()
    {
        $this->stock = new ArrayCollection();
        $this->fiche_soin = new ArrayCollection();
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

    public function getDisponibilite(): ?string
    {
        return $this->disponibilite;
    }

    public function setDisponibilite(string $disponibilite): self
    {
        $this->disponibilite = $disponibilite;

        return $this;
    }

    /**
     * @return Collection<int, Stock>
     */
    public function getStock(): Collection
    {
        return $this->stock;
    }

    public function addStock(Stock $stock): self
    {
        if (!$this->stock->contains($stock)) {
            $this->stock->add($stock);
            $stock->setMedicament($this);
        }

        return $this;
    }

    public function removeStock(Stock $stock): self
    {
        if ($this->stock->removeElement($stock)) {
            // set the owning side to null (unless already changed)
            if ($stock->getMedicament() === $this) {
                $stock->setMedicament(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FicheSoin>
     */
    public function getFicheSoin(): Collection
    {
        return $this->fiche_soin;
    }

    public function addFicheSoin(FicheSoin $ficheSoin): self
    {
        if (!$this->fiche_soin->contains($ficheSoin)) {
            $this->fiche_soin->add($ficheSoin);
        }

        return $this;
    }

    public function removeFicheSoin(FicheSoin $ficheSoin): self
    {
        $this->fiche_soin->removeElement($ficheSoin);

        return $this;
    }
}
