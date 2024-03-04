<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, TwoFactorInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;



   




    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: "The username should not be blank.")]
    #[Assert\Length(
        min: 4,
        max: 50,
        minMessage: "The username must be at least {{ limit }} characters long.",
        maxMessage: "The username cannot be longer than {{ limit }} characters."
    )]
    private ?string $username = null;







    

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: "The email should not be blank.")]
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email.")]
    private ?string $email = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];








    #[ORM\Column]
    private ?string $password = null;

   



    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $authCode;

    #[ORM\Column(length: 255)]
    private ?string $iban = null;

    #[ORM\Column]
    private ?float $solde = null;



    /**
     * Return true if the user should do two-factor authentication.
     */
    public function isEmailAuthEnabled(): bool
    {
        return true;
    }

    /**
     * Return user email address.
     */
    public function getEmailAuthRecipient(): string
    {
        return $this->email;
    }

    /**
     * Return the authentication code.
     */
    public function getEmailAuthCode(): ?string
    {
        if (null === $this->authCode) {
            throw new \LogicException('The email authentication code was not set');
        }
        return $this->authCode;
    }

    /**
     * Set the authentication code.
     */
    public function setEmailAuthCode(string $authCode): void 
    {
        $this->authCode = $authCode;
    }



  

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;        
        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

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

    public function getSolde(): ?float
    {
        return $this->solde;
    }

    public function setSolde(float $solde): static
    {
        $this->solde = $solde;

        return $this;
    }
}
