<?php

namespace App\Entity;

use App\Repository\ResesrvationDetailRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=ResesrvationDetailRepository::class)
 */
class ResesrvationDetail
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $dateDebut;

    /**
     * @ORM\Column(type="string")
     */
    private $dateFin;
    
     /**
     * @ORM\Column(type="integer")
     */
    private $nbrEnfant;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrAdulte;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $prixCalcule;

    /**
     * @ORM\ManyToOne(targetEntity=Reservation::class, inversedBy="resesrvationDetails")
     */

    private $reservation;

    /**
     * @ORM\ManyToOne(targetEntity=Local::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $local;


    public function getId(): ?int
        {
        return $this->id;
    }

    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    public function setDateDebut(string $dateDebut)
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin()
    {
        return $this->dateFin;
    }

    public function setDateFin(string $dateFin)
    {
        $this->dateFin = $dateFin;

        return $this;
    }
    public function getNbrEnfant(): ?int
    {
        return $this->nbrEnfant;
    }

    public function setNbrEnfant(int $nbrEnfant): self
    {
        $this->nbrEnfant = $nbrEnfant;

        return $this;
    }

    public function getNbrAdulte(): ?int
    {
        return $this->nbrAdulte;
    }

    public function setNbrAdulte(int $nbrAdulte): self
    {
        $this->nbrAdulte = $nbrAdulte;

        return $this;
    }


    public function getPrixCalcule(): ?float
    {
        return $this->prixCalcule;
    }

    public function setPrixCalcule(?float $prixCalcule): self
    {
        $this->prixCalcule = $prixCalcule;

        return $this;
    }

    public function getReservation(): ?Reservation
    {
        return $this->reservation;
    }

    public function setReservation(?Reservation $reservation): self
    {
        $this->reservation = $reservation;

        return $this;
    }

    public function getLocal(): ?Local
    {
        return $this->local;
    }

    public function setLocal(Local $local): self
    {
        $this->local = $local;

        return $this;
    }
}
