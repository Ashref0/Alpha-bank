<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

// Définition de l'entité Client avec ses annotations ORM
#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client
{
    // Annotation ORM pour définir l'identifiant de l'entité
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Annotations ORM pour définir les colonnes de la table client
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $cin = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'Client')]
    private Collection $transactions;

    #[ORM\Column]
    private ?float $solde = null;

    #[ORM\Column(length: 255)]
    private ?string $iban = null;

    #[ORM\OneToMany(targetEntity: Transaction::class, mappedBy: 'Client2')]
    private Collection $transactions2;

    // Constructeur pour initialiser la collection de transactions
    public function __construct()
    {
        $this->transactions = new ArrayCollection();
        $this->transactions2 = new ArrayCollection();
    }

    // Getters et setters pour accéder et définir les propriétés de l'entité
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getCin(): ?string
    {
        return $this->cin;
    }

    public function setCin(string $cin): static
    {
        $this->cin = $cin;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(float $solde): static
    {
        $this->solde = $solde;
        return $this;
    }

    public function getIban(): ?string
    {
        return $this->iban;
    }

    public function setIban(string $iban): static
    {
        $this->iban = $iban;
        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions(): Collection
    {
        return $this->transactions;
    }

    // Méthode pour ajouter une transaction à la collection
    public function addTransaction(Transaction $transaction): static
    {
        if (!$this->transactions->contains($transaction)) {
            $this->transactions->add($transaction);
            $transaction->setClient($this);
        }
        return $this;
    }

    // Méthode pour supprimer une transaction de la collection
    public function removeTransaction(Transaction $transaction): static
    {
        if ($this->transactions->removeElement($transaction)) {
            if ($transaction->getClient() === $this) {
                $transaction->setClient(null);
            }
        }
        return $this;
    }

    /**
     * @return Collection<int, Transaction>
     */
    public function getTransactions2(): Collection
    {
        return $this->transactions2;
    }

    public function addTransactions2(Transaction $transactions2): static
    {
        if (!$this->transactions2->contains($transactions2)) {
            $this->transactions2->add($transactions2);
            $transactions2->setClient2($this);
        }

        return $this;
    }

    public function removeTransactions2(Transaction $transactions2): static
    {
        if ($this->transactions2->removeElement($transactions2)) {
            // set the owning side to null (unless already changed)
            if ($transactions2->getClient2() === $this) {
                $transactions2->setClient2(null);
            }
        }

        return $this;
    }
}


