<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Validator\Constraints as Assert;

#[Entity(repositoryClass: CategoryRepository::class)]
#[Table(name: 'categories')]
class Category
{
    #[Id, Column(type: 'integer'), GeneratedValue]
    private ?int $id = null;

    #[Column(type: 'string', length: 100, unique: true)]
    #[Assert\NotBlank, Assert\Type('string')]
    private string $name;
    #[Column(type: 'string', length: 100, unique: true)]
    #[Assert\NotBlank, Assert\Type('string')]
    private string $slug;

    #[OneToMany(targetEntity: Event::class, mappedBy: 'user')]
    private Collection $Events;


    public function __construct()
    {
        $this->Events = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function setName(string $name)
    {
        $this->name = $name;
    }
    public function getSlug(): string
    {
        return $this->slug;
    }
    public function setSlug(string $slug)
    {
        $this->slug = $slug;
    }

    public function getEvents(): Collection
    {
        return $this->Events;
    }
}
