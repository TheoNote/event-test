<?php

namespace App\Repository;

use App\Entity\Registration;
use Doctrine\Persistence\ManagerRegistry;

class RegistrationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Registration::class);

    }
    public function save(?Registration $user = null)
    {
        $this->getEntityManager()->flush($user);
    }

    public function persist(?Registration $user = null)
    {
        $this->getEntityManager()->persist($user);
    }

    public function persistAndSave(?Registration $user = null)
    {
        $this->persist($user);
        $this->save($user);
    }
}
