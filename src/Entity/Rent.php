<?php

namespace App\Entity;

use App\Repository\RentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\User;

#[ORM\Entity(repositoryClass: RentRepository::class)]
class Rent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $start_date = null;

    #[ORM\Column]
    private ?int $num_days = null;

    #[ORM\Column]
    private ?int $total_price = null;

    #[ORM\ManyToOne(inversedBy: 'rents')]
    #[ORM\JoinColumn(name:"house_id", referencedColumnName:"id")]
    private ?House $house = null;

    #[ORM\ManyToOne(inversedBy: 'rents')]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPaid = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end_date = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "rentsAsProprietaire")]
    #[ORM\JoinColumn(name: "proprietaire_id", referencedColumnName: "id", nullable: true)]
    private $proprietaire;

    #[ORM\Column(type: "string", length: 36, nullable: true)]
    private ?string $transactionId;
    
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->start_date;
    }

    public function setStartDate(\DateTimeInterface $start_date): static
    {
        $this->start_date = $start_date;

        return $this;
    }

    public function getNumDays(): ?int
    {
        return $this->num_days;
    }

    public function setNumDays(int $num_days): static
    {
        $this->num_days = $num_days;

        return $this;
    }

    public function getTotalPrice(): ?int
    {
        return $this->total_price;
    }

    public function setTotalPrice(int $total_price): static
    {
        $this->total_price = $total_price;

        return $this;
    }

    public function getHouse(): ?House
    {
        return $this->house;
    }

    public function setHouse(?House $house): self
    {
        $this->house = $house;

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

    public function isTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function setTransactionId(string $transactionId ): self
    {
        $this->transactionId = $transactionId;
        return $this;
    }

    // Récuperer les paiements et locations
    public function isPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setPaid(?bool $isPaid): static
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    // recuperer user_proprietaire -> historique dashboard admin //
    public function getProprietaire(): ?User
    {
        return $this->proprietaire;
    }

    public function setProprietaire(?User $proprietaire): self
    {
        $this->proprietaire = $proprietaire;

        return $this;
    }

    // gerer calendrier
    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeInterface $end_date): self
    {
        $this->end_date = $end_date;

        return $this;
    }

}
