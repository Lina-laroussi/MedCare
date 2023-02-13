<?php

namespace App\Entity;

use App\Repository\PlanningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanningRepository::class)]
class Planning
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\OneToMany(mappedBy: 'planning_medecin', targetEntity: RendezVous::class)]
    private Collection $mes_rendez_vous;

    #[ORM\OneToOne(inversedBy: 'planning', cascade: ['persist', 'remove'])]
    private ?User $medecin = null;

    public function __construct()
    {
        $this->mes_rendez_vous = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getMesRendezVous(): Collection
    {
        return $this->mes_rendez_vous;
    }

    public function addMesRendezVou(RendezVous $mesRendezVou): self
    {
        if (!$this->mes_rendez_vous->contains($mesRendezVou)) {
            $this->mes_rendez_vous->add($mesRendezVou);
            $mesRendezVou->setPlanningMedecin($this);
        }

        return $this;
    }

    public function removeMesRendezVou(RendezVous $mesRendezVou): self
    {
        if ($this->mes_rendez_vous->removeElement($mesRendezVou)) {
            // set the owning side to null (unless already changed)
            if ($mesRendezVou->getPlanningMedecin() === $this) {
                $mesRendezVou->setPlanningMedecin(null);
            }
        }

        return $this;
    }

    public function getMedecin(): ?User
    {
        return $this->medecin;
    }

    public function setMedecin(?User $medecin): self
    {
        $this->medecin = $medecin;

        return $this;
    }
}
