<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Category::class);

    }
    public function save(?Category $category = null)
    {
        $this->getEntityManager()->flush($category);
    }

    public function persist(?Category $category = null)
    {
        $this->getEntityManager()->persist($category);
    }

    public function persistAndSave(?Category $category = null)
    {
        $this->persist($category);
        $this->save($category);
    }

    public function delete(?Category $category = null): void
    {
        $this->getEntityManager()->remove($category);
        $this->getEntityManager()->flush();
    }
}
