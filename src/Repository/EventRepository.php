<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\User;
use App\Enum\EventStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class EventRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Event::class);

    }
    public function save(?Event $user = null)
    {
        $this->getEntityManager()->flush($user);
    }

    public function persist(?Event $user = null)
    {
        $this->getEntityManager()->persist($user);
    }

    public function persistAndSave(?Event $user = null)
    {
        $this->persist($user);
        $this->save($user);
    }

    public function findEventsPublished(): array
    {
        $query = $this->createQueryBuilder('e')
            ->andWhere('e.status = :status')
            ->setParameter('status', EventStatus::Published);

        return $query->getQuery()->getResult();
    }

    public function findEventBySlug(string $slug): ?Event
    {
        $query = $this->createQueryBuilder('e')
            ->andWhere('e.slug = :slug')
            ->setParameter('slug', $slug)
            ->getQuery();
        return $query->getOneOrNullResult();
    }

    public function findEventsByOrganizer(User $organizer): array
    {
        $query = $this->createQueryBuilder('e')
            ->andWhere('e.organizer = :organizer')
            ->setParameter('organizer', $organizer);

        return $query->getQuery()->getResult();
    }
}
