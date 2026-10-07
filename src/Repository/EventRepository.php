<?php

namespace App\Repository;

use App\Entity\Event;
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

    public function findEventPublished(): array
    {
        $query = $this->createQueryBuilder('e')
            ->andWhere('e.status = :status')
            ->setParameter('status', EventStatus::Published);

        return $query->getQuery()->getResult();
    }

}
