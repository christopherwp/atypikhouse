<?php

namespace App\Entity;

use App\Entity\Facility;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\HouseRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use ApiPlatform\Metadata\ApiResource;

#[ORM\Entity(repositoryClass: HouseRepository::class)]

class House
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 8)]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale: 8)]
    private ?string $longitude = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $capacity = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $num_rooms = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $num_bedrooms = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $num_bathrooms = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $daily_price = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'houses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Category $category = null;

    #[ORM\OneToMany(targetEntity: Media::class, mappedBy: 'house', orphanRemoval: true)]
    private Collection $media;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'house')]
    private Collection $comments;

    #[ORM\OneToMany(targetEntity: Rent::class, mappedBy: 'house')]
    private Collection $rents;

    #[ORM\ManyToOne(inversedBy: 'house')] 
    private ?User $user = null;


    #[ORM\OneToMany(targetEntity: Facility::class, mappedBy: 'house')]

    private Collection $facilities;

    #[ORM\Column]
    private ?bool $actif = null;

    // Admin - Dashboard - Gabriela ->
    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'houses')]
    #[ORM\JoinColumn(nullable: false)]
    #[ORM\JoinColumn(name: "proprietaire_id", referencedColumnName: "id")]
    private ?User $proprietaire = null;

  
    #[ORM\OneToMany(mappedBy: 'house', targetEntity: Images::class, orphanRemoval: true, cascade:['persist'])]
    private Collection $images;


    // Getters et setters pour $proprietaire
    public function getProprietaire(): ?User

    {
        return $this->proprietaire;
    }

    public function setProprietaire(?User $proprietaire): self
    {
        $this->proprietaire = $proprietaire;
        return $this;
    }
    // Admin - Dashboard - Gabriela <-


    public function __construct()
    {
        $this->media = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->rents = new ArrayCollection();

       
        $this->images = new ArrayCollection();

        $this->facilities = new ArrayCollection();

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(string $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(string $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getNumRooms(): ?int
    {
        return $this->num_rooms;
    }

    public function setNumRooms(int $num_rooms): static
    {
        $this->num_rooms = $num_rooms;

        return $this;
    }

    public function getNumBedrooms(): ?int
    {
        return $this->num_bedrooms;
    }

    public function setNumBedrooms(int $num_bedrooms): static
    {
        $this->num_bedrooms = $num_bedrooms;

        return $this;
    }

    public function getNumBathrooms(): ?int
    {
        return $this->num_bathrooms;
    }

    public function setNumBathrooms(int $num_bathrooms): static
    {
        $this->num_bathrooms = $num_bathrooms;

        return $this;
    }

    public function getDailyPrice(): ?int
    {
        return $this->daily_price;
    }

    public function setDailyPrice(int $daily_price): static
    {
        $this->daily_price = $daily_price;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, Media>
     */
    public function getMedia(): Collection
    {
        return $this->media;
    }

    public function addMedium(Media $medium): static
    {
        if (!$this->media->contains($medium)) {
            $this->media->add($medium);
            $medium->setHouse($this);
        }

        return $this;
    }

    public function removeMedium(Media $medium): static
    {
        if ($this->media->removeElement($medium)) {
            // set the owning side to null (unless already changed)
            if ($medium->getHouse() === $this) {
                $medium->setHouse(null);
            }
        }

        return $this;
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
            $comment->setHouseId($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getHouseId() === $this) {
                $comment->setHouseId(null);
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

    public function addRent(Rent $rent): self
    {
        if (!$this->rents->contains($rent)) {
            $this->rents->add($rent);
            $rent->setHouse($this);
        }

        return $this;
    }

    public function removeRent(Rent $rent): self
    {
        if ($this->rents->removeElement($rent)) {
            // set the owning side to null (unless already changed)
            if ($rent->getHouse() === $this) {
                $rent->setHouse(null);
            }
        }

        return $this;
    }

    

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, facility>
     */
    public function getFacilities(): Collection
    {
        return $this->facilities;
    }

    public function setFacility(?Facility $facility): static
    {
        $this->facilities = $facility;

        return $this;
    }
    public function addFacility(Facility $facility): static
    {
        if (!$this->facilities->contains($facility)) {
            $this->facilities->add($facility);
            $facility->setHouse($this);
        }
    
        return $this;
    }


    public function removeFacility(Facility $facility): static
{
    if ($this->facilities->removeElement($facility)) {
        // set the owning side to null (unless already changed)
        if ($facility->getHouse() === $this) {
            $facility->setHouse(null);
        }
    }

    return $this;
}
    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;

        return $this;
    }


   /**
 * @return Collection|Images[]
 */
public function getImages(): Collection
{
    return $this->images;
}

public function addImage(Images $image): self 
{
    if (!$this->images->contains($image)) {
        $this->images[] = $image;
        $image->setHouse($this);
    }

    return $this;
}


public function removeImage(Images $image): self
{
    if ($this->images->removeElement($image)) {
        // définir le côté propriétaire sur null (sauf si déjà modifié)
        if ($image->getHouse() === $this) {
            $image->setHouse(null);
        }
    }
    return $this;
}




    
}
