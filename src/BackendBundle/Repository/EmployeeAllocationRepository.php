<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2/2/18
 * Time: 12:27 PM
 */
namespace BackendBundle\Repository;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class EmployeeAllocationRepository extends EntityRepository
{

    //Qualified


    public function findTaskAllocations(EntityManager $em, $task)
    {
        $queryBuilder = $em->createQueryBuilder();
        $query = $queryBuilder
            ->select('ea')
            ->from('BackendBundle:EmployeeAllocation', 'ea')
            ->andwhere('ea.task = :eaTask')
            ->setParameter('eaTask', $task);


        $result = $query->getQuery()->getResult();
        return $result;
    }

    public function findDuplicateAllocation(EntityManager $em, $employee, $task)
    {
        $queryBuilder = $em->createQueryBuilder();
        $query = $queryBuilder
            ->select('ea')
            ->from('BackendBundle:EmployeeAllocation', 'ea')
            ->andwhere('ea.task = :eaTask')
            ->andWhere('ea.employee = :eaEmployee')
            ->setParameter('eaTask', $task)
            ->setParameter('eaEmployee', $employee);


        $result = $query->getQuery()->getResult();
        return $result;
    }


}