<?php

namespace App\Entity;

use App\Repository\EtablissementRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtablissementRepository::class)]
class Etablissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Nom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column]
    private ?int $num_contact = null;

    #[ORM\ManyToOne(targetEntity: Compte::class, inversedBy: 'etablissements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Compte $compte = null;

    // Getter for Compte
    public function getCompte(): ?Compte
    {
        return $this->compte;
    }

    // Setter for Compte
    public function setCompte(?Compte $compte): self
    {
        $this->compte = $compte;
        return $this;
    }
    



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): static
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getNumContact(): ?int
    {
        return $this->num_contact;
    }

    public function setNumContact(int $num_contact): static
    {
        $this->num_contact = $num_contact;

        return $this;
    }


    public function __toString(): string
    {
        return (string) $this->id;
    }
}
