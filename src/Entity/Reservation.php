<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateReservation;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total;

    /**
     * @ORM\OneToMany(targetEntity=ResesrvationDetail::class, mappedBy="reservation")
     */
    private $resesrvationDetails;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $membre;

    public function __construct()
    {
        $this->resesrvationDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReservation(): ?\DateTimeInterface
    {
        return $this->dateReservation;
    }

    public function setDateReservation(?\DateTimeInterface $dateReservation): self
    {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    public function getTotal(): ?float
    {
        return $this->total;
    }

    public function setTotal(?float $total): self
    {
        $this->total = $total;

        return $this;
    }

    /**
     * @return Collection|ResesrvationDetail[]
     */
    public function getResesrvationDetails(): Collection
    {
        return $this->resesrvationDetails;
    }

    public function addResesrvationDetail(ResesrvationDetail $resesrvationDetail): self
    {
        if (!$this->resesrvationDetails->contains($resesrvationDetail)) {
            $this->resesrvationDetails[] = $resesrvationDetail;
            $resesrvationDetail->setReservation($this);
        }

        return $this;
    }

    public function removeResesrvationDetail(ResesrvationDetail $resesrvationDetail): self
    {
        if ($this->resesrvationDetails->removeElement($resesrvationDetail)) {
            // set the owning side to null (unless already changed)
            if ($resesrvationDetail->getReservation() === $this) {
                $resesrvationDetail->setReservation(null);
            }
        }

        return $this;
    }

    public function getMembre(): ?User
    {
        return $this->membre;
    }

    public function setMembre(?User $membre): self
    {
        $this->membre = $membre;

        return $this;
    }
}
