<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CompanyRepository
 */
class CompanyRepository extends EntityRepository
{
    /**
     * Super Admin admin list Query
     *
     * @return \Doctrine\ORM\Query
     */
    public function getSuperAdminListQuery()
    {
        return $this->createQueryBuilder('c')
            ->addOrderBy('c.id', 'DESC')
            ->getQuery();
    }
}
