<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $mot_de_passe = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_naissance = null;

    #[ORM\Column(length: 255)]
    private ?string $num_tel = null;

    #[ORM\Column(length: 255)]
    private ?string $sexe = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $groupe_sanguin = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $roles = [];

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\Column(length: 255)]
    private ?string $specialite = null;

    #[ORM\Column(length: 255)]
    private ?string $etat_civil = null;

    #[ORM\Column(length: 255)]
    private ?string $cin = null;

    #[ORM\Column(length: 255)]
    private ?string $num_securite_sociale = null;

    #[ORM\OneToOne(mappedBy: 'medecin', cascade: ['persist', 'remove'])]
    private ?Cabinet $cabinet = null;

    #[ORM\ManyToMany(targetEntity: Cabinet::class, mappedBy: 'patients')]
    private Collection $cabinets;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: RendezVous::class)]
    private Collection $rendez_vous_patient;

    #[ORM\OneToOne(mappedBy: 'medecin', cascade: ['persist', 'remove'])]
    private ?Planning $planning = null;

    #[ORM\OneToOne(mappedBy: 'pharmacien', cascade: ['persist', 'remove'])]
    private ?Pharmacie $pharmacie = null;

    #[ORM\OneToMany(mappedBy: 'assureur', targetEntity: FicheSoin::class)]
    private Collection $fichesoins_assureur;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: FicheSoin::class)]
    private Collection $fichesoins_patient;

    #[ORM\ManyToOne(inversedBy: 'assureur')]
    private ?Assurance $assurance_assureur = null;

    #[ORM\ManyToOne(inversedBy: 'patients')]
    private ?Assurance $assurance = null;

    #[ORM\OneToOne(mappedBy: 'admin_para', cascade: ['persist', 'remove'])]
    private ?Parapharmacie $parapharmacie = null;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: Commande::class)]
    private Collection $commandes;

    public function __construct()
    {
        $this->cabinets = new ArrayCollection();
        $this->rendez_vous_patient = new ArrayCollection();
        $this->fichesoins_assureur = new ArrayCollection();
        $this->fichesoins_patient = new ArrayCollection();
        $this->commandes = new ArrayCollection();
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

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

    public function getMotDePasse(): ?string
    {
        return $this->mot_de_passe;
    }

    public function setMotDePasse(string $mot_de_passe): self
    {
        $this->mot_de_passe = $mot_de_passe;

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

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->date_de_naissance;
    }

    public function setDateDeNaissance(\DateTimeInterface $date_de_naissance): self
    {
        $this->date_de_naissance = $date_de_naissance;

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

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

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

    public function getGroupeSanguin(): ?string
    {
        return $this->groupe_sanguin;
    }

    public function setGroupeSanguin(string $groupe_sanguin): self
    {
        $this->groupe_sanguin = $groupe_sanguin;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getEtatCivil(): ?string
    {
        return $this->etat_civil;
    }

    public function setEtatCivil(string $etat_civil): self
    {
        $this->etat_civil = $etat_civil;

        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): self
    {
        $this->cin = $cin;

        return $this;
    }

    public function getNumSecuriteSociale(): ?string
    {
        return $this->num_securite_sociale;
    }

    public function setNumSecuriteSociale(string $num_securite_sociale): self
    {
        $this->num_securite_sociale = $num_securite_sociale;

        return $this;
    }

    public function getCabinet(): ?Cabinet
    {
        return $this->cabinet;
    }

    public function setCabinet(?Cabinet $cabinet): self
    {
        // unset the owning side of the relation if necessary
        if ($cabinet === null && $this->cabinet !== null) {
            $this->cabinet->setMedecin(null);
        }

        // set the owning side of the relation if necessary
        if ($cabinet !== null && $cabinet->getMedecin() !== $this) {
            $cabinet->setMedecin($this);
        }

        $this->cabinet = $cabinet;

        return $this;
    }

    /**
     * @return Collection<int, Cabinet>
     */
    public function getCabinets(): Collection
    {
        return $this->cabinets;
    }

    public function addCabinet(Cabinet $cabinet): self
    {
        if (!$this->cabinets->contains($cabinet)) {
            $this->cabinets->add($cabinet);
            $cabinet->addPatient($this);
        }

        return $this;
    }

    public function removeCabinet(Cabinet $cabinet): self
    {
        if ($this->cabinets->removeElement($cabinet)) {
            $cabinet->removePatient($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVousPatient(): Collection
    {
        return $this->rendez_vous_patient;
    }

    public function addRendezVousPatient(RendezVous $rendezVousPatient): self
    {
        if (!$this->rendez_vous_patient->contains($rendezVousPatient)) {
            $this->rendez_vous_patient->add($rendezVousPatient);
            $rendezVousPatient->setPatient($this);
        }

        return $this;
    }

    public function removeRendezVousPatient(RendezVous $rendezVousPatient): self
    {
        if ($this->rendez_vous_patient->removeElement($rendezVousPatient)) {
            // set the owning side to null (unless already changed)
            if ($rendezVousPatient->getPatient() === $this) {
                $rendezVousPatient->setPatient(null);
            }
        }

        return $this;
    }

    public function getPlanning(): ?Planning
    {
        return $this->planning;
    }

    public function setPlanning(?Planning $planning): self
    {
        // unset the owning side of the relation if necessary
        if ($planning === null && $this->planning !== null) {
            $this->planning->setMedecin(null);
        }

        // set the owning side of the relation if necessary
        if ($planning !== null && $planning->getMedecin() !== $this) {
            $planning->setMedecin($this);
        }

        $this->planning = $planning;

        return $this;
    }

    public function getPharmacie(): ?Pharmacie
    {
        return $this->pharmacie;
    }

    public function setPharmacie(?Pharmacie $pharmacie): self
    {
        // unset the owning side of the relation if necessary
        if ($pharmacie === null && $this->pharmacie !== null) {
            $this->pharmacie->setPharmacien(null);
        }

        // set the owning side of the relation if necessary
        if ($pharmacie !== null && $pharmacie->getPharmacien() !== $this) {
            $pharmacie->setPharmacien($this);
        }

        $this->pharmacie = $pharmacie;

        return $this;
    }

    /**
     * @return Collection<int, FicheSoin>
     */
    public function getFichesoinsAssureur(): Collection
    {
        return $this->fichesoins_assureur;
    }

    public function addFichesoinsAssureur(FicheSoin $fichesoinsAssureur): self
    {
        if (!$this->fichesoins_assureur->contains($fichesoinsAssureur)) {
            $this->fichesoins_assureur->add($fichesoinsAssureur);
            $fichesoinsAssureur->setAssureur($this);
        }

        return $this;
    }

    public function removeFichesoinsAssureur(FicheSoin $fichesoinsAssureur): self
    {
        if ($this->fichesoins_assureur->removeElement($fichesoinsAssureur)) {
            // set the owning side to null (unless already changed)
            if ($fichesoinsAssureur->getAssureur() === $this) {
                $fichesoinsAssureur->setAssureur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FicheSoin>
     */
    public function getFichesoinsPatient(): Collection
    {
        return $this->fichesoins_patient;
    }

    public function addFichesoinsPatient(FicheSoin $fichesoinsPatient): self
    {
        if (!$this->fichesoins_patient->contains($fichesoinsPatient)) {
            $this->fichesoins_patient->add($fichesoinsPatient);
            $fichesoinsPatient->setPatient($this);
        }

        return $this;
    }

    public function removeFichesoinsPatient(FicheSoin $fichesoinsPatient): self
    {
        if ($this->fichesoins_patient->removeElement($fichesoinsPatient)) {
            // set the owning side to null (unless already changed)
            if ($fichesoinsPatient->getPatient() === $this) {
                $fichesoinsPatient->setPatient(null);
            }
        }

        return $this;
    }

    public function getAssuranceAssureur(): ?Assurance
    {
        return $this->assurance_assureur;
    }

    public function setAssuranceAssureur(?Assurance $assurance_assureur): self
    {
        $this->assurance_assureur = $assurance_assureur;

        return $this;
    }

    public function getAssurance(): ?Assurance
    {
        return $this->assurance;
    }

    public function setAssurance(?Assurance $assurance): self
    {
        $this->assurance = $assurance;

        return $this;
    }

    public function getParapharmacie(): ?Parapharmacie
    {
        return $this->parapharmacie;
    }

    public function setParapharmacie(?Parapharmacie $parapharmacie): self
    {
        // unset the owning side of the relation if necessary
        if ($parapharmacie === null && $this->parapharmacie !== null) {
            $this->parapharmacie->setAdminPara(null);
        }

        // set the owning side of the relation if necessary
        if ($parapharmacie !== null && $parapharmacie->getAdminPara() !== $this) {
            $parapharmacie->setAdminPara($this);
        }

        $this->parapharmacie = $parapharmacie;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes->add($commande);
            $commande->setPatient($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getPatient() === $this) {
                $commande->setPatient(null);
            }
        }

        return $this;
    }
}
