<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2/2/18
 * Time: 12:27 PM
 */
namespace BackendBundle\Repository;

use AppBundle\Services\Extension\Query\Acos;
use AppBundle\Services\Extension\Query\Cos;
use AppBundle\Services\Extension\Query\Radians;
use AppBundle\Services\Extension\Query\Sin;
use AppBundle\Services\JwtAuth;
use BackendBundle\Entity\Employee;
use BackendBundle\Entity\Task;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class AllocatedDatesRepository extends EntityRepository
{
    public function findEmployeeAllocations(EntityManager $em, Employee $employee)
    {
        $queryBuilder1 = $em->createQueryBuilder();
        $queryBuilder2 = $em->createQueryBuilder();
        $employeeAllocationQuery = $queryBuilder1
            ->select('a.id')
            ->from('BackendBundle:EmployeeAllocation', 'a')
            ->leftJoin('a.employee', 'ae')
            ->andwhere('ae = :emp')
            ->setParameter('emp', $employee)
            ->getQuery();

        $employeeAllocationIdArr = $employeeAllocationQuery->getResult();
        $result = '';
        if(count($employeeAllocationIdArr)>0) {
            $employeeAllocationIds = $this->getInConditions($employeeAllocationIdArr);

            $allocationsDates = $queryBuilder2
                ->select("ad")
                ->from('BackendBundle:AllocatedDates', 'ad')
                ->leftJoin('ad.employeeAllocation', 'ade')
                ->where('ade.id IN'.$employeeAllocationIds)
                ->orderBy("ad.id", "DESC")
                ->getQuery();

            $result = $allocationsDates->getResult();

        }

        return $result;
    }

    public function findEmployeePendingAllocations(EntityManager $em, Employee $employee)
    {
        $queryBuilder1 = $em->createQueryBuilder();
        $queryBuilder2 = $em->createQueryBuilder();
        $employeeAllocationQuery = $queryBuilder1
            ->select('a.id')
            ->from('BackendBundle:EmployeeAllocation', 'a')
            ->leftJoin('a.employee', 'ae')
            ->andwhere('ae = :emp')
            ->setParameter('emp', $employee)
            ->getQuery();

        $employeeAllocationIdArr = $employeeAllocationQuery->getResult();
        $result = '';
        if(count($employeeAllocationIdArr)>0) {
            $employeeAllocationIds = $this->getInConditions($employeeAllocationIdArr);

            $allocationsDates = $queryBuilder2
                ->select("ad")
                ->from('BackendBundle:AllocatedDates', 'ad')
                ->leftJoin('ad.employeeAllocation', 'ade')
                ->where('ade.id IN'.$employeeAllocationIds)
                ->andWhere('ad.cancelallocation = 0')
                ->andWhere('ad.accecptallocation = 0')
                ->orderBy("ad.id", "DESC")
                ->getQuery();

            $result = $allocationsDates->getResult();

        }

        return $result;
    }

    public function findEmployeeAcceptedAllocations(EntityManager $em, Employee $employee)
    {
        $queryBuilder1 = $em->createQueryBuilder();
        $queryBuilder2 = $em->createQueryBuilder();
        $employeeAllocationQuery = $queryBuilder1
            ->select('a.id')
            ->from('BackendBundle:EmployeeAllocation', 'a')
            ->leftJoin('a.employee', 'ae')
            ->andwhere('ae = :emp')
            ->setParameter('emp', $employee)
            ->getQuery();

        $employeeAllocationIdArr = $employeeAllocationQuery->getResult();
        $result = '';
        if(count($employeeAllocationIdArr)>0) {
            $employeeAllocationIds = $this->getInConditions($employeeAllocationIdArr);

            $allocationsDates = $queryBuilder2
                ->select('ad')
                ->from('BackendBundle:AllocatedDates', 'ad')
                ->leftJoin('ad.employeeAllocation', 'ade')
                ->where('ade.id IN'.$employeeAllocationIds)
                ->andWhere('ad.cancelallocation = 0')
                ->andWhere('ad.accecptallocation = 1')
                ->orderBy("ad.id", "DESC")
                ->getQuery();

            $result = $allocationsDates->getResult();

        }

        return $result;
    }

    public function findEmployeeAllocationFromDateRange(EntityManager $em, Employee $employee, $startDate, $endDate) {

        $queryBuilder1 = $em->createQueryBuilder();
        $queryBuilder2 = $em->createQueryBuilder();
        $employeeAllocationQuery = $queryBuilder1
            ->select('a.id')
            ->from('BackendBundle:EmployeeAllocation', 'a')
            ->leftJoin('a.employee', 'ae')
            ->andwhere('ae = :emp')
            ->setParameter('emp', $employee)
            ->getQuery();

        $employeeAllocationIdArr = $employeeAllocationQuery->getResult();
        $result = '';
        if(count($employeeAllocationIdArr)>0) {
            $employeeAllocationIds = $this->getInConditions($employeeAllocationIdArr);

            $allocationsDates = $queryBuilder2
                ->select("ad")
                ->from('BackendBundle:AllocatedDates', 'ad')
                ->leftJoin('ad.employeeAllocation', 'ade')
                ->where('ade.id IN'.$employeeAllocationIds)
                ->andWhere('ad.date >= :startDate')
                ->andWhere('ad.date < :endDate')
                ->setParameter('startDate', $startDate)
                ->setParameter('endDate', $endDate)
                ->orderBy("ad.id", "DESC")
                ->getQuery();

            $result = $allocationsDates->getResult();

        }

        return $result;

    }




    function getInConditions($array) {
        $params = array();

        foreach ($array as $item) {
            array_push($params, $item['id']);
        }

        $value = '('.implode(', ', (array) $params).')';
        return $value;
    }

}
