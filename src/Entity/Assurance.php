<?php

namespace App\Entity;

use App\Repository\AssuranceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AssuranceRepository::class)]
class Assurance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $num_tel = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'assurance_assureur', targetEntity: User::class)]
    private Collection $assureur;

    #[ORM\OneToMany(mappedBy: 'assurance', targetEntity: User::class)]
    private Collection $patients;

    public function __construct()
    {
        $this->assureur = new ArrayCollection();
        $this->patients = new ArrayCollection();
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNumTel(): ?string
    {
        return $this->num_tel;
    }

    public function setNumTel(string $num_tel): self
    {
        $this->num_tel = $num_tel;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

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

    /**
     * @return Collection<int, User>
     */
    public function getAssureur(): Collection
    {
        return $this->assureur;
    }

    public function addAssureur(User $assureur): self
    {
        if (!$this->assureur->contains($assureur)) {
            $this->assureur->add($assureur);
            $assureur->setAssuranceAssureur($this);
        }

        return $this;
    }

    public function removeAssureur(User $assureur): self
    {
        if ($this->assureur->removeElement($assureur)) {
            // set the owning side to null (unless already changed)
            if ($assureur->getAssuranceAssureur() === $this) {
                $assureur->setAssuranceAssureur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getPatients(): Collection
    {
        return $this->patients;
    }

    public function addPatient(User $patient): self
    {
        if (!$this->patients->contains($patient)) {
            $this->patients->add($patient);
            $patient->setAssurance($this);
        }

        return $this;
    }

    public function removePatient(User $patient): self
    {
        if ($this->patients->removeElement($patient)) {
            // set the owning side to null (unless already changed)
            if ($patient->getAssurance() === $this) {
                $patient->setAssurance(null);
            }
        }

        return $this;
    }
}
