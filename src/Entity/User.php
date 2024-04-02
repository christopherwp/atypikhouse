<?php


namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Regex(
        pattern: '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*+.-]).{8,}$/m',
        htmlPattern: "^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*+.-]).{8,}$",
        match: true,
        message: 'Attention ! Votre mot de passe doit respecter les consignes ci-dessous :',
    )]
    private ?string $password = null;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'user')]
    private Collection $comments;

    #[ORM\OneToMany(targetEntity: Rent::class, mappedBy: 'user')]
    private Collection $rents;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $numCarteIdentite = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;


     // admin : Gabriela ->
    #[ORM\Column(type: 'boolean')]
    private bool $isActive = true;
  
     public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->rents = new ArrayCollection();
        $this->house = new ArrayCollection();
        $this->houses = new ArrayCollection(); // -> Dashboard admin 
        // Initialisez isActive à true ou à la valeur désirée dans le constructeur
    }

    // Getter pour isActive
    public function isActive(): bool
    {
        return $this->isActive;
    }

    // Setter pour isActive
    public function setIsActive(bool $isActive): self
    {
        $this->isActive = $isActive;
        return $this;
    }

    #[ORM\Column(type: "string", length: 50, nullable: true)]
    private $type;

    const TYPE_LOCATAIRE = 'locataire';
    const TYPE_PROPRIETAIRE = 'proprietaire';

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    #[ORM\OneToMany(targetEntity: House::class, mappedBy: 'owner', orphanRemoval: true)]
    private Collection $houses;

    // Getters et setters pour $houses
    /**
     * @return Collection<int, House>
     */
    public function getHouses(): Collection
    {
        return $this->houses;
    }

    public function addOwnedHouse(House $house): self
    {
        if (!$this->houses->contains($house)) {
            $this->houses[] = $house;
            $house->setOwner($this);
        }
        return $this;
    }

    public function removeOwnedHouse(House $house): self
    {
        if ($this->houses->removeElement($house)) {
            // Définir le côté propriété à null (sauf s'il a déjà été changé)
            if ($house->getOwner() === $this) {
                $house->setOwner(null);
            }
        }
        return $this;
    }
    // admin - Dashboard - Gabriela <-  
  
  
 
    #[ORM\OneToMany(targetEntity: House::class, mappedBy: 'user')]
    private Collection $house;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
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
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function addRole($role)
    {
        if (!in_array($role, $this->roles, true)) {
            $this->roles[] = $role;
        }

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setUserId($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getUserId() === $this) {
                $comment->setUserId(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Rent>
     */
    public function getRents(): Collection
    {
        return $this->rents;
    }

    public function addRent(Rent $rent): static
    {
        if (!$this->rents->contains($rent)) {
            $this->rents->add($rent);
            $rent->setUserId($this);
        }

        return $this;
    }

    public function removeRent(Rent $rent): static
    {
        if ($this->rents->removeElement($rent)) {
            // set the owning side to null (unless already changed)
            if ($rent->getUserId() === $this) {
                $rent->setUserId(null);
            }
        }

        return $this;
    }


    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getNumCarteIdentite(): ?string
    {
        return $this->numCarteIdentite;
    }

    public function setNumCarteIdentite(?string $numCarteIdentite): static
    {
        $this->numCarteIdentite = $numCarteIdentite;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse)
    {
        $this->adresse = $adresse;

    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;


        return $this;
    }

    /**
     * @return Collection<int, House>
     */
    public function getHouse(): Collection
    {
        return $this->house;
    }

    public function addHouse(House $house): static
    {
        if (!$this->house->contains($house)) {
            $this->house->add($house);
            $house->setUser($this);
        }

        return $this;
    }

    public function removeHouse(House $house): static
    {
        if ($this->house->removeElement($house)) {
            // set the owning side to null (unless already changed)
            if ($house->getUser() === $this) {
                $house->setUser(null);
            }
        }

        return $this;
    }
}    