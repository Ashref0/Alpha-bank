<?php

namespace App\Entity;

use App\Repository\CompteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CompteRepository::class)]
class Compte
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255)]
    private ?string $num_compte = null;
    

    #[ORM\Column(length: 255)]
    private ?string $Type_compte = null;

 

    #[ORM\OneToMany(mappedBy: 'compte', targetEntity: CarteBancaire::class)]
    private Collection $relation;


    #[ORM\OneToMany(mappedBy: 'compte', targetEntity: Etablissement::class)]
    private Collection $etablissements;



    public function getEtablissements(): Collection
    {
        return $this->etablissements;
    }

    // Add method to add an Etablissement
    public function addEtablissement(Etablissement $etablissement): self
    {
        if (!$this->etablissements->contains($etablissement)) {
            $this->etablissements[] = $etablissement;
            $etablissement->setCompte($this);
        }

        return $this;
    }

    // Add method to remove an Etablissement
    public function removeEtablissement(Etablissement $etablissement): self
    {
        if ($this->etablissements->removeElement($etablissement)) {
            // set the owning side to null (unless already changed)
            if ($etablissement->getCompte() === $this) {
                $etablissement->setCompte(null);
            }
        }

        return $this;
    }

    public function __construct()
    {
                $this->num_compte = 'AL' . $this->generateRandomNumber(10);
                $this->etablissements = new ArrayCollection();
        $this->relation = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCompte(): ?string
    {
        return $this->num_compte;
    }

    public function setNumCompte(string $num_compte): self {
        $this->num_compte = $num_compte;
        return $this;
    }

    


    public function getTypeCompte(): ?string
    {
        return $this->Type_compte;
    }

    public function setTypeCompte(string $Type_compte): static
    {
        $this->Type_compte = $Type_compte;

        return $this;
    }

  

    /**
     * @return Collection<int, CarteBancaire>
     */
    public function getRelation(): Collection
    {
        return $this->relation;
    }

    public function addRelation(CarteBancaire $relation): static
    {
        if (!$this->relation->contains($relation)) {
            $this->relation->add($relation);
            $relation->setCompte($this);
        }

        return $this;
    }

    public function removeRelation(CarteBancaire $relation): static
    {
        if ($this->relation->removeElement($relation)) {
            // set the owning side to null (unless already changed)
            if ($relation->getCompte() === $this) {
                $relation->setCompte(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }


    private function generateRandomNumber($length) {
        $numbers = '0123456789';
        $numberStr = '';
        for ($i = 0; $i < $length; $i++) {
            $numberStr .= $numbers[rand(0, strlen($numbers) - 1)];
        }
        return $numberStr;
    }


}


