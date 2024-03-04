<?php

namespace App\Entity;

use App\Repository\CarteBancaireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[ORM\Entity(repositoryClass: CarteBancaireRepository::class)]
class CarteBancaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\Column(type: "string", length: 16)]
    private ?string $Num_carte = null;
    
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $Date_Exp = null;

    #[ORM\Column(type: "string", length: 3)]
    private ?string $cvv = null;

    #[ORM\ManyToOne(inversedBy: 'relation')]
    private ?Compte $compte = null;

    #[ORM\Column(type: "boolean")]
    private bool $isFrozen = false;

    public function __construct()
    {
        $this->isFrozen = false; // Optional due to default property value
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumCarte(): ?int
    {
        return $this->Num_carte;
    }

    public function setNumCarte(int $Num_carte): static
    {
        $this->Num_carte = $Num_carte;

        return $this;
    }

    public function getDateExp(): ?\DateTimeInterface
    {
        return $this->Date_Exp;
    }

    public function setDateExp(\DateTimeInterface $Date_Exp): static
    {
        $this->Date_Exp = $Date_Exp;

        return $this;
    }

    public function getCvv(): ?int
    {
        return $this->cvv;
    }

    public function setCvv(int $cvv): static
    {
        $this->cvv = $cvv;

        return $this;
    }

    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    public function setCompte(?Compte $compte): static
    {
        $this->compte = $compte;

        return $this;
    }

    public function __toString(): string
    {
        return (string) $this->id;
    }

    public function getIsFrozen(): bool
    {
        return $this->isFrozen;
    }

    // Setter for isFrozen
    public function setIsFrozen(bool $isFrozen): self
    {
        $this->isFrozen = $isFrozen;
        return $this;
    }
}
