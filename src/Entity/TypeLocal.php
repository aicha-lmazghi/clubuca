<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TypeLocalRepository::class)
 */
class TypeLocal
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $label;
    
    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $type;
    /**
     * @ORM\OneToMany(targetEntity=Local::class, mappedBy="type")
     */
    private $locals;
    
    
    public function __construct()
    {
        $this->locals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }
    /**
     * @return Collection|Local[]
     */
    public function getLocals(): Collection
    {
        return $this->locals;
    }

    public function addLocal(Local $local): self
    {
        if (!$this->locals->contains($local)) {
            $this->locals[] = $local;
            $local->setType($this);
        }

        return $this;
    }

    public function removeLocal(Local $local): self
    {
        if ($this->locals->removeElement($local)) {
            // set the owning side to null (unless already changed)
            if ($local->getType() === $this) {
                $local->setType(null);
            }
        }

        return $this;
    }
}
