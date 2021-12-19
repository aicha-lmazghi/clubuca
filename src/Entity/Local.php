<?php

namespace App\Entity;

use App\Repository\LocalRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LocalRepository::class)
 */
class Local
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;
    
    /**
     * @ORM\Column(type="integer", length=255)
     */
    private $maxEnfant;
     /**
     * @ORM\Column(type="integer", length=255)
     */
    private $maxAdulte;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /** 
     * @ORM\OneToMany(targetEntity=Tarif::class, mappedBy="local")
     */
    private $tarif;

    /**
     * @ORM\ManyToOne(targetEntity=TypeLocal::class, inversedBy="locals")
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
    public function getMaxEnfant(): ?int
    {
        return $this->maxEnfant;
    }
    public function setMaxEnfant(?int $maxEnfant): self
    {
        $this->maxEnfant = $maxEnfant;
        
        return $this;
    }

    public function getMaxAdulte(): ?int
    {
         return  $this->maxAdulte;
    }

    public function setMaxAdulte(?int $maxAdulte): self
    {
        $this->maxAdulte = $maxAdulte;

        return $this;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }
   /**
     * @return Collection|Tarif[]
     */
    public function getTarif(): ?Collection
    {
        return $this->tarif;
    }

    public function addTarif(Tarif $tarif): self
    {
        if (!$this->tarif->contains($tarif)) {
            $this->tarif[] = $tarif;
            $tarif->setLocal($this);
        }

        return $this;
    }

    public function removeResesrvationDetail(Tarif $tarif): self
    {
        if ($this->tarif->removeElement($tarif)) {
            // set the owning side to null (unless already changed)
            if ($tarif->getLocal() === $this) {
                $tarif->setLocal(null);
            }
        }

        return $this;
    }


    public function getType(): ?TypeLocal
    {
        return $this->type;
    }

    public function setType(?TypeLocal $type): self
    {
        $this->type = $type;

        return $this;
    }
   
}
