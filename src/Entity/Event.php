<?php

namespace App\Entity;

use App\Enum\EventStatus;
use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;


#[Entity(repositoryClass: EventRepository::class)]
#[Table(name: 'events')]
class Event
{
    #[Id, Column(type: 'integer'), GeneratedValue]
    private ?int $id = null;

    #[Column(type: 'string', length: 100)]
    #[Assert\NotBlank, Assert\Type('string')]
    private string $title;

    #[Column(type: 'string', length: 100, unique: true, nullable: false)]
    #[Assert\Type('string')]
    private string $slug;

    #[Column(type: 'text')]
    #[Assert\NotBlank, Assert\Type('string')]
    private string $description;

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $startAt;

    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: false)]
    private \DateTimeImmutable $endAt;

    #[column(type: 'integer')]
    #[Assert\NotBlank, Assert\Type('integer')]
    private int $capacity;

    #[Column(enumType: EventStatus::class)]
    private EventStatus $status;

    #[ManyToOne(targetEntity: User::class, inversedBy: 'events')]
    private User $organizer;

    #[ManyToOne(targetEntity: Category::class, inversedBy: 'events')]
    private Category $category;

    #[OneToMany(targetEntity: Registration::class, mappedBy: 'user')]
    private Collection $Registrations;

    #[Assert\Callback]
    public function validateDates(ExecutionContextInterface $context, mixed $payload): void
    {
        if (isset($this->startAt) && isset($this->endAt)) {
            if ($this->endAt <= $this->startAt) {
                $context->buildViolation('La date de fin doit être strictement supérieure à la date de début.')
                    ->atPath('endAt')
                    ->addViolation();
            }
        }
    }


    function __construct()
    {
        $this->Registrations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getTitle(): ?string
    {
        return $this->title;
    }
    public function setTitle(string $title){
        $this->title = $title;
    }
    public function getSlug(): string
    {
        return $this->slug;
    }
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }
    public function getDescription(): ?string
    {
        return $this->description;
    }
    public function setDescription(string $description)
    {
        $this->description = $description;
    }
    public function getStartAt(): \DateTimeImmutable
    {
        return $this->startAt;
    }
    public function setStartAt(\DateTimeImmutable $startAt)
    {
        $this->startAt = $startAt;
    }
    public function getEndAt(): \DateTimeImmutable
    {
        return $this->endAt;
    }
    public function setEndAt(\DateTimeImmutable $endAt)
    {
        $this->endAt = $endAt;
    }
    public function getCapacity(): int
    {
        return $this->capacity;
    }
    public function setCapacity(int $capacity)
    {
        $this->capacity = $capacity;
    }
    public function getStatus(): EventStatus
    {
        return $this->status;
    }
    public function setStatus(EventStatus $eventStatus)
    {
        $this->status = $eventStatus;
    }
    public function getOrganizer(): User
    {
        return $this->organizer;
    }
    public function setOrganizer(User $organizer)
    {
        $this->organizer = $organizer;
    }
    public function getCategory(): Category
    {
        return $this->category;
    }
    public function setCategory(Category $category)
    {
        $this->category = $category;
    }

    public function getRegistrations(): Collection
    {
        return $this->Registrations;
    }

}
