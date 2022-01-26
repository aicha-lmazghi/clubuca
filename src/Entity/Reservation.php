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
     * @ORM\Column(type="string", nullable=true)
     */
    private $dateReservation;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $total;
    
    /**
     * @ORM\OneToMany(targetEntity=ResesrvationDetail::class, mappedBy="reservation",orphanRemoval=true)
     */
    private $resesrvationDetails;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     */
    private $member;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $etat;

    public function __construct()
    {
        $this->resesrvationDetails = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateReservation()
    {
        return $this->dateReservation;
    }

    public function setDateReservation(?string $dateReservation): self
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

    public function getMember(): ?User
    {
        return $this->member;
    }

    public function setMember(?User $member): self
    {
        $this->member = $member;

        return $this;
    }

    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(?int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }
}
