<?php

namespace App\Repository;

use App\Entity\Event;
use App\Entity\Registration;
use App\Entity\User;
use App\Enum\RegistrationStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Registration::class);

    }
    public function save(?Registration $registration = null)
    {
        $this->getEntityManager()->flush($registration);
    }

    public function persist(?Registration $registration = null)
    {
        $this->getEntityManager()->persist($registration);
    }

    public function persistAndSave(?Registration $registration = null)
    {
        $this->persist($registration);
        $this->save($registration);
    }

    public function delete(?Registration $registration = null): void
    {
        $this->getEntityManager()->remove($registration);
        $this->getEntityManager()->flush();
    }

    public function findRegistrationByUser(User $user): ?array
    {
        $query = $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->setParameter('user', $user);

        return $query->getQuery()->getResult();
    }

    public function findEventByUser(User $user): ?array
    {
        $query = $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->setParameter('user', $user)
            ->andWhere('r.status IN (:statuses)')
            ->setParameter('statuses', [
                RegistrationStatus::Confirmed,
                RegistrationStatus::Waitlist,
            ]);

        return array_map(fn (Registration $registration) => $registration->getEvent(), $query->getQuery()->getResult());
    }

    public function findRegistrationByUserEvent(User $user, Event $event): ?Registration
    {
        $query = $this->createQueryBuilder('r')
            ->andWhere('r.user = :user')
            ->setParameter('user', $user)
            ->andWhere('r.event = :event')
            ->setParameter('event', $event)
            ->getQuery();

        return $query->getOneOrNullResult();
    }
}
