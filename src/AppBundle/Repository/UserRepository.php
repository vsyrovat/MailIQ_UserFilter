<?php declare(strict_types=1);

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function findByFilter($filter)
    {
        $qb = $this->createQueryBuilder('u')->where($filter);

        $q = $qb->getQuery();

        return $q->execute();
    }
}
