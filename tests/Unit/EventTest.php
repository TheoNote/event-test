<?php

namespace App\Tests\Unit;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\User;
use App\Enum\EventStatus;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class EventTest extends TestCase
{
    private Event $event;

    protected function setUp(): void
    {
        $this->event = new Event();
    }

    public function testConstructorInit(): void
    {
        $this->assertNull($this->event->getId());
        $this->assertInstanceOf(ArrayCollection::class , $this->event->getRegistrations());
    }

    public function testTitleGetterAndSetter(): void
    {
        $this->event->setTitle('test');
        $this->assertSame('test', $this->event->getTitle());
        $this->assertIsString($this->event->getTitle());
    }

    public function testDescriptionGetterAndSetter(): void
    {
        $this->event->setDescription('test');
        $this->assertSame('test', $this->event->getDescription());
        $this->assertIsString($this->event->getDescription());
    }

    public function testCategoryGetterAndSetter(): void
    {
        $category = new Category();
        $this->event->setCategory($category);
        $this->assertSame($category, $this->event->getCategory());
        $this->assertInstanceOf(Category::class , $this->event->getCategory());
    }

    public function testEventStatusGetterAndSetter(): void
    {
        $this->event->setStatus(EventStatus::Cancelled);
        $this->assertSame(EventStatus::Cancelled, $this->event->getStatus());
        $this->assertInstanceOf(EventStatus::class, $this->event->getStatus());
    }

    public function testOrganizerGetterAndSetter(): void
    {
        $organizer = new User();
        $this->event->setOrganizer($organizer);
        $this->assertSame($organizer, $this->event->getOrganizer());
        $this->assertInstanceOf(User::class , $this->event->getOrganizer());
    }
}
