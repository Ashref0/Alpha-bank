<?php

namespace App\Entity;

use App\Repository\AdminRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdminRepository::class)]
class Admin
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $loginAdmin = null;

    #[ORM\Column(length: 50)]
    private ?string $passwordAdmin = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLoginAdmin(): ?string
    {
        return $this->loginAdmin;
    }

    public function setLoginAdmin(string $loginAdmin): static
    {
        $this->loginAdmin = $loginAdmin;

        return $this;
    }

    public function getPasswordAdmin(): ?string
    {
        return $this->passwordAdmin;
    }

    public function setPasswordAdmin(string $passwordAdmin): static
    {
        $this->passwordAdmin = $passwordAdmin;

        return $this;
    }
}
