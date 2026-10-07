<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);

    }
    public function save(?Category $user = null)
    {
        $this->getEntityManager()->flush($user);
    }

    public function persist(?Category $user = null)
    {
        $this->getEntityManager()->persist($user);
    }

    public function persistAndSave(?Category $user = null)
    {
        $this->persist($user);
        $this->save($user);
    }
}
