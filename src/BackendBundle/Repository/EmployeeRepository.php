<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2/2/18
 * Time: 12:27 PM
 */
namespace BackendBundle\Repository;

use Doctrine\ORM\EntityRepository;

class EmployeeRepository extends EntityRepository
{




    public function findClientProject(\Doctrine\ORM\QueryBuilder $queryBuilder, $client)
    {

        $query = $queryBuilder
                    ->select('p')
                    ->from('BackendBundle:Project', 'p')
                    ->andwhere('p.client = :cl')
                    ->andWhere('p.archived = 0')
                    ->orderBy('p.projectUpdated', 'DESC')
                    ->setParameter('cl', $client);


        $result = $query->getQuery()->getResult();
        return $result;
    }

}