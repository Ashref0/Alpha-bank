<?php

namespace App\Entity;

use App\Repository\TransactionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: TransactionRepository::class)]
class Transaction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;   

    #[ORM\Column(type: "date")]
    private ?\DateTimeInterface $date_transaction = null;
 

    #[ORM\Column(length: 255)]
    /**
     * @Assert\Length(max=10)
     */
    private ?string $nom_destinataire = null;

  

    #[ORM\Column(length: 255)]
    private ?string $prenom_destinataire = null;

  

    #[ORM\Column(length: 255)]
    private ?string $iban_destinataire = null;

    #[ORM\Column(type: "float")]
    private ?float $montant = null;

    #[ORM\ManyToOne(targetEntity: Client::class, inversedBy: 'transactions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Client $client = null;


    #[ORM\Column(length: 255)]
    private ?string $iban = null;

    #[ORM\ManyToOne(targetEntity: Client::class,inversedBy: 'transactions2')]
    private ?Client $Client2 = null;
 

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateTransaction(): ?\DateTimeInterface
    {
        return $this->date_transaction;
    }

    public function setDateTransaction(\DateTimeInterface $date_transaction): static
    {
        $this->date_transaction = $date_transaction;
        return $this;
    }

    public function getNomDestinataire(): ?string
    {
        return $this->nom_destinataire;
    }

    public function setNomDestinataire(string $nom_destinataire): static
    {
        $this->nom_destinataire = $nom_destinataire;
        return $this;
    }

    public function getPrenomDestinataire(): ?string
    {
        return $this->prenom_destinataire;
    }

    public function setPrenomDestinataire(string $prenom_destinataire): static
    {
        $this->prenom_destinataire = $prenom_destinataire;
        return $this;
    }

    public function getIbanDestinataire(): ?string
    {
        return $this->iban_destinataire;
    }

    public function setIbanDestinataire(string $iban_destinataire): static
    {
        $this->iban_destinataire = $iban_destinataire;
        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): static
    {
        $this->montant = $montant;
        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;
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

    public function getClient2(): ?Client
    {
        return $this->Client2;
    }

    public function setClient2(?Client $Client2): static
    {
        $this->Client2 = $Client2;

        return $this;
    }
}

