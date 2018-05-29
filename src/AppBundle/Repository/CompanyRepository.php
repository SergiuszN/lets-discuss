<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CompanyRepository
 */
class CompanyRepository extends EntityRepository
{
    public function getSuperAdminListQuery()
    {
        return $this->createQueryBuilder('c')
            ->addOrderBy('c.id', 'DESC')
            ->getQuery();
    }
}
