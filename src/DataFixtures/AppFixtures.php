<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Event;
use App\Entity\Registration;
use App\Entity\User;
use App\Enum\EventStatus;
use App\Enum\RegistrationStatus;
use App\Enum\RoleUser;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Bundle\MakerBundle\EventRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $this->user($manager, 'Max', 'maxencevast@gmail.com', 'azerty', [RoleUser::User,RoleUser::Admin]);
        $organizer = $this->user($manager, 'organizer', 'organizer@eventhub.test', 'azerty', [RoleUser::Organizer]);
        $this->user($manager, 'admin', 'admin@eventhub.test', 'azerty', [RoleUser::Admin]);
        $user = $this->user($manager, 'user', 'user@eventhub.test', 'azerty', [RoleUser::User]);

        $CategoryName = ['Sport', 'Class', 'Competition', 'Video'];

        foreach ($CategoryName as $name)
        {

            $slug = trim(strtolower(preg_replace('/[^a-zA-Z0-9]+/', '-', iconv('UTF-8', 'ASCII//TRANSLIT', $name))), '-');


            $category = (new Category());
            $category->setName($name);
            $category->setSlug($slug);

            $manager->persist($category);

            $categorys[]=$category;
        }

        for($i=1; $i <= 8; $i++)
        {
            $start = new \DateTimeImmutable('+'.($i + 1).' days 18:00');
            $statuses = EventStatus::cases();
            $randomStatus = $statuses[array_rand($statuses)];

            $event = (new Event());
            $event->setTitle('EventHub #'.$i);
            $event->setSlug('event-'.$i);
            $event->setDescription('Une description');
            $event->setStartAt($start);
            $event->setEndAt($start->modify('5 hours'));
            $event->setStatus($randomStatus);
            $event->setOrganizer($organizer);
            $event->setCategory($categorys[$i%count($categorys)]);;
            $event->setCapacity(1);

            $manager->persist($event);

            $events[]=$event;
        }

        for($i=1; $i <= 8; $i++){
            $event = $events[$i%count($events)];
            if ($event->getStatus() ===  EventStatus::Published) continue;

            $statuses = RegistrationStatus::cases();
            $randomStatus = $statuses[array_rand($statuses)];

            $registration = new Registration();
            $registration->setStatus($randomStatus);
            $registration->setEvent($event);
            $registration->setUser($user);

            $manager->persist($registration);
        }

        $manager->flush();

    }

    private function user(ObjectManager $manager, string $username, string $email, string $password, array $roles): User
    {
        $user = new User();
        $user->setUsername($username);
        $user->setEmail($email);
        $user->setRoles($roles);
        $user->setPassword($this->hasher->hashPassword($user, $password));
        $manager->persist($user);
        return $user;
    }
}
