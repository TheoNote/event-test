<?php

namespace App\Entity;

use App\Enum\RegistrationStatus;
use App\Repository\RegistrationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[Entity(repositoryClass: RegistrationRepository::class)]
#[Table(name: 'registrations')]
#[UniqueConstraint(name: 'uniq_registration_user_event', columns: ['user_id', 'event_id'])]
#[UniqueEntity(fields: ['user', 'event'])]
class Registration
{

    #[Id, Column(type: 'integer'), GeneratedValue]
    private ?int $id = null;
    #[ManyToOne(targetEntity: User::class)]
    #[JoinColumn(nullable: false)]
    private User $user;

    #[ManyToOne(targetEntity: Event::class)]
    #[JoinColumn(nullable: false)]
    private Event $event;

    #[Column(enumType: RegistrationStatus::class)]
    private RegistrationStatus $status;

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $createdAt;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getUser(): ?User
    {
        return $this->user;
    }
    public function setUser(?User $user)
    {
        $this->user = $user;
    }
    public function getEvent(): ?Event
    {
        return $this->event;
    }
    public function setEvent(?Event $event)
    {
        $this->event = $event;
    }
    public function getStatus(): RegistrationStatus
    {
        return $this->status;
    }
    public function setStatus(RegistrationStatus $registrationStatus)
    {
        $this->status = $registrationStatus;
    }
    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
