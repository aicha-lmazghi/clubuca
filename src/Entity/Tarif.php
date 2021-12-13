<?php

namespace App\Entity;

use App\Repository\TarifRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TarifRepository::class)
 */
class Tarif
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrEnfant;

    /**
     * @ORM\Column(type="integer")
     */
    private $nbrAdulte; 

    /**
     * @ORM\Column(type="float")
     */
    private $prix;

     /**
     * @ORM\ManyToOne(targetEntity=Local::class, inversedBy="tarifs")
     */
    private $local;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getLocal(): ?Local
    {
        return $this->local;
    }

    public function setLocal(?Local $local): self
    {
        $this->local = $local;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}
