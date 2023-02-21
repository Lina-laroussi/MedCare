<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true,nullable:true)]
    #[Assert\NotBlank(message: "Veuillez renseigner votre email")]
    #[Assert\Email(message: "Votre email n'est pas valide")]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotBlank(message: "Veuillez renseigner votre mot de passe")]
   /* #[Assert\Regex(
        pattern: "/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/",
        message:"Votre mot de passe doit comporter au moins huit caractères, dont des lettres majuscules et minuscules, un chiffre et un symbole"
    )]*/
    private ?string $password = null;

    #[ORM\Column(length: 255,nullable:true)]
    #[Assert\NotBlank(message: "Veuillez renseigner votre nom")]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Votre nom doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Votre nom ne peut pas dépasser {{ limit }} caractères',
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255,nullable:true)]
    #[Assert\NotBlank(message: "Veuillez renseigner votre prénom")]
    #[Assert\Length(
        min: 2,
        max: 20,
        minMessage: 'Votre prénom doit comporter au moins {{ limit }} caractères',
        maxMessage: 'Votre prénom ne peut pas dépasser {{ limit }} caractères',
    )]
    private ?string $prenom = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $description = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $num_tel = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $age = null;

    #[ORM\Column(type: Types::DATE_MUTABLE,nullable:true)]
    private ?\DateTimeInterface $date_de_naissance = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $sexe = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $specialite = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date_de_creation = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $cin = null;

    #[ORM\Column(nullable:true)]
    private ?int $num_securite_sociale = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $image = null;

    #[ORM\Column(type: Types::DATE_MUTABLE,nullable:true)]
    private ?\DateTimeInterface $date_de_modification = null;

    #[ORM\Column(length: 255,nullable:true)]
    private ?string $etat = null;

    #[ORM\OneToMany(mappedBy: 'patient', targetEntity: RendezVous::class)]
    private Collection $rendezVouses;

    #[ORM\OneToMany(mappedBy: 'medecin', targetEntity: Planning::class)]
    private Collection $plannings;

    #[ORM\OneToMany(mappedBy: 'assureur', targetEntity: FicheAssurance::class)]
    private Collection $ficheAssurances;

    #[ORM\OneToOne(mappedBy: 'pharmacien', cascade: ['persist', 'remove'])]
    private ?Pharmacie $pharmacie = null;


    public function __construct()
    {
        $this->rendezVouses = new ArrayCollection();
        $this->plannings = new ArrayCollection();
        $this->ficheAssurances = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email = null): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom = null): self
    {
        $this->prenom = $prenom;

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

    public function getAge(): ?string
    {
        return $this->age;
    }

    public function setAge(string $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getDateDeNaissance(): ?\DateTimeInterface
    {
        return $this->date_de_naissance;
    }

    public function setDateDeNaissance(\DateTimeInterface $date_de_naissance = null): self
    {
        $this->date_de_naissance = $date_de_naissance;

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

    public function getSpecialite(): ?string
    {
        return $this->specialite;
    }

    public function setSpecialite(string $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getDateDeCreation(): ?\DateTimeInterface
    {
        return $this->date_de_creation;
    }

    public function setDateDeCreation(\DateTimeInterface $date_de_creation): self
    {
        $this->date_de_creation = $date_de_creation;

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

    public function getNumSecuriteSociale(): ?int
    {
        return $this->num_securite_sociale;
    }

    public function setNumSecuriteSociale(int $num_securite_sociale): self
    {
        $this->num_securite_sociale = $num_securite_sociale;

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

    public function getDateDeModification(): ?\DateTimeInterface
    {
        return $this->date_de_modification;
    }

    public function setDateDeModification(\DateTimeInterface $date_de_modification): self
    {
        $this->date_de_modification = $date_de_modification;

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

    /**
     * @return Collection<int, RendezVous>
     */
    public function getRendezVouses(): Collection
    {
        return $this->rendezVouses;
    }

    public function addRendezVouse(RendezVous $rendezVouse): self
    {
        if (!$this->rendezVouses->contains($rendezVouse)) {
            $this->rendezVouses->add($rendezVouse);
            $rendezVouse->setPatient($this);
        }

        return $this;
    }

    public function removeRendezVouse(RendezVous $rendezVouse): self
    {
        if ($this->rendezVouses->removeElement($rendezVouse)) {
            // set the owning side to null (unless already changed)
            if ($rendezVouse->getPatient() === $this) {
                $rendezVouse->setPatient(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Planning>
     */
    public function getPlannings(): Collection
    {
        return $this->plannings;
    }

    public function addPlanning(Planning $planning): self
    {
        if (!$this->plannings->contains($planning)) {
            $this->plannings->add($planning);
            $planning->setMedecin($this);
        }

        return $this;
    }

    public function removePlanning(Planning $planning): self
    {
        if ($this->plannings->removeElement($planning)) {
            // set the owning side to null (unless already changed)
            if ($planning->getMedecin() === $this) {
                $planning->setMedecin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FicheAssurance>
     */
    public function getFicheAssurances(): Collection
    {
        return $this->ficheAssurances;
    }

    public function addFicheAssurance(FicheAssurance $ficheAssurance): self
    {
        if (!$this->ficheAssurances->contains($ficheAssurance)) {
            $this->ficheAssurances->add($ficheAssurance);
            $ficheAssurance->setAssureur($this);
        }

        return $this;
    }

    public function removeFicheAssurance(FicheAssurance $ficheAssurance): self
    {
        if ($this->ficheAssurances->removeElement($ficheAssurance)) {
            // set the owning side to null (unless already changed)
            if ($ficheAssurance->getAssureur() === $this) {
                $ficheAssurance->setAssureur(null);
            }
        }

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


}
