<?php

namespace App\Entity;


use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[Entity(repositoryClass: UserRepository::class)]
#[Table(name: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[Id, Column(type: 'integer'), GeneratedValue]
    private ?int $id = null;

    #[Column(type: 'string', length: 100, nullable: false)]
    #[Assert\NotBlank, Assert\Type('string')]
    private string $username;

    #[Column(type: 'string', length: 180, unique: true)]
    #[Assert\Email]
    private string $email;

    #[Column(type: 'string', nullable: false)]
    #[Assert\NotBlank, Assert\PasswordStrength]
    private string $password;

    #[Column(type: 'json')]
    #[Assert\NotBlank]
    private array $roles = ['ROLE_USER'];

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $createdAt;

    #[OneToMany(targetEntity: Event::class, mappedBy: 'user')]
    private Collection $Events;

    #[OneToMany(targetEntity: Registration::class, mappedBy: 'user')]
    private Collection $Registrations;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->Events = new ArrayCollection();
        $this->Registrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setEmail(string $email): void
    {
        $this->email = strtolower(trim($email));
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getRoles(): array
    {
        return array_unique([...$this->roles]);
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function setRoles(array $roles)
    {
        $this->roles = $roles;
    }

    public function getEvents(): Collection
    {
        return $this->Events;
    }

    public function getRegistrations(): Collection
    {
        return $this->Registrations;
    }

    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }
    public function getUserIdentifier(): string
    {
        return $this->username;
    }
}
