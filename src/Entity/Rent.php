<?php

namespace App\Entity;

use App\Repository\RentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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
    private ?House $house = null;

    #[ORM\ManyToOne(inversedBy: 'rents')]
    private ?User $user = null;

    #[ORM\Column(nullable: true)]
    private ?bool $reservation = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPaid = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $end_date = null;

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

    public function getHouseId(): ?House
    {
        return $this->house;
    }

    public function setHouseId(?House $house): static
    {
        $this->house = $house;

        return $this;
    }

    public function getUserId(): ?User
    {
        return $this->user;
    }

    public function setUserId(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function isReservation(): ?bool
    {
        return $this->reservation;
    }

    public function setReservation(?bool $reservation): static
    {
        $this->reservation = $reservation;

        
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

    public function findPaidRents()
    {
        return $this->createQueryBuilder('r')
            ->where('r.reservation = :paid')
            ->setParameter('paid', true)
            ->getQuery()
            ->getResult();
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->end_date;
    }

    public function setEndDate(?\DateTimeInterface $end_date): static
    {
        $this->end_date = $end_date;

        return $this;
    }
}
