<?php

namespace App\Entity;

use App\Repository\CapaciteRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CapaciteRepository::class)
 */
class Capacite
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $adults;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $enfants;

     /**
     * @ORM\OneToMany(targetEntity=Local::class, mappedBy="capacite")
     */
    private $locals;

    /**
     * @return Collection|Local[]
     */
   /* public function getLocals(): Collection
    {
        return $this->locals;
    }

    public function addLocal(Local $local): self
    {
        if (!$this->locals->contains($local)) {
            $this->locals[] = $local;
            $local->setCapacite($this);
        }

        return $this;
    }

    public function removeLocal(Local $local): self
    {
        if ($this->locals->removeElement($local)) {
            // set the owning side to null (unless already changed)
            if ($local->getCapacite() === $this) {
                $local->setCapacite(null);
            }
        }

        return $this;
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdults(): ?int
    {
        return $this->adults;
    }

    public function setAdults(?int $adults): self
    {
        $this->adults = $adults;

        return $this;
    }

    public function getEnfants(): ?int
    {
        return $this->enfants;
    }

    public function setEnfants(?int $enfants): self
    {
        $this->enfants = $enfants;

        return $this;
    }*/
}
