<?php

namespace App\Entity;

use App\Repository\RemboursementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RemboursementRepository::class)]
class Remboursement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $Montant = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $DateRemboursement = null;

    #[ORM\ManyToOne(inversedBy: 'Remboursements')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Credit $Credit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMontant(): ?float
    {
        return $this->Montant;
    }

    public function setMontant(float $Montant): static
    {
        $this->Montant = $Montant;

        return $this;
    }

    public function getDateRemboursement(): ?\DateTimeInterface
    {
        return $this->DateRemboursement;
    }

    public function setDateRemboursement(\DateTimeInterface $DateRemboursement): static
    {
        $this->DateRemboursement = $DateRemboursement;

        return $this;
    }

    public function getCredit(): ?Credit
    {
        return $this->Credit;
    }

    public function setCredit(?Credit $Credit): static
    {
        $this->Credit = $Credit;

        return $this;
    }
}
