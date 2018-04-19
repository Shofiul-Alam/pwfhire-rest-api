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
use AppBundle\Services\Extension\Touple\Tuple;
use AppBundle\Services\JwtAuth;
use BackendBundle\Entity\Task;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;

class TaskRepository extends EntityRepository
{
    public function findProjectTasks(\Doctrine\ORM\QueryBuilder $queryBuilder, $order)
    {

        $query = $queryBuilder
            ->select('t')
            ->from('BackendBundle:Task', 't')
            ->andwhere('t.order = :o')
            ->andWhere('t.archived = 0')
            ->orderBy('t.startDate', 'DESC')
            ->setParameter('o', $order);


        $result = $query->getQuery()->getResult();
        return $result;
    }

    public function findEmployeeForTask(\Doctrine\ORM\QueryBuilder $queryBuilder, Task $task, JwtAuth $jwt, EntityManager $em)
    {
        $job = $task->getJob();
        $order = $task->getOrder();
        $project = $order->getProject();
        $lattitude = $project->getLattitude();
        $longtitude = $project->getLongitude();
        $allocatedInductions = $project->getAllocatedInduction();
        $allocatedSkillCompetency = $job[0]->getSkillCompetencyList();
        if($allocatedSkillCompetency[0]) {
            $skillComId = $jwt->decodeId($allocatedSkillCompetency[0]->getId());
        }



        $inductionIdArr = array();
        $indCount = 0;
        if(count($allocatedInductions) > 0) {
            foreach ($allocatedInductions as $allocatedInduction) {
                $indCount++;
                $induction = $allocatedInduction->getInduction();
                $id = $jwt->decodeId($induction->getId());

                    array_push($inductionIdArr, $id);

            }
        }

        $value = '('.implode(', ', (array) $inductionIdArr).')';



        $queryBuilder1 = $em->createQueryBuilder();
        $queryBuilder2 = $em->createQueryBuilder();
        $queryBuilder3 = $em->createQueryBuilder();

        $inductionQuery = $queryBuilder1
                            ->select('ie.id')
                            ->from('BackendBundle:EmployeeInduction', 'i')
                            ->leftJoin('i.employee', 'ie')
                            ->leftJoin('i.induction', 'ic')
                            ->where('ic.id IN '.$value)
                            ->groupBy('ie.id')
                            ->having('COUNT(DISTINCT ic.id) = :icdCount')
                            ->setParameter('icdCount', $indCount)
                            ->getQuery();

        $inductionResult = $inductionQuery->getResult();
        $inductionParams = $this->getInConditions($inductionResult);

        $skillComQuery = $this->getEntityManager()
                                    ->createQuery("SELECT se.id FROM BackendBundle\Entity\EmployeeSkillCompetencyDocument s
                                                  LEFT JOIN s.employee se  LEFT JOIN s.skillCompetencyList sc
                                                  WHERE sc.id IN ($skillComId)
                                                  GROUP BY se.id
                                                  HAVING COUNT(DISTINCT (sc.id)) = 1
                                                  AND se.id IN $inductionParams");
        $empIds = $skillComQuery->getResult();

        $empInParams = $this->getInConditions($empIds);

        $em = $queryBuilder2->getEntityManager();
        $config = $em->getConfiguration();
        $config->addCustomNumericFunction('acos', Acos::class);
        $config->addCustomNumericFunction('cos', Cos::class);
        $config->addCustomNumericFunction('radians', Radians::class);
        $config->addCustomNumericFunction('sin', Sin::class);




        $empQuery =  $queryBuilder2
                        ->select("e, (3959 * acos(cos
                                    (radians('".$lattitude."')) * cos(radians(e.lattitude)) * cos(radians
                                        (e.longitude) - radians('".$longtitude."')) + sin(radians('".$lattitude."')) * sin
                                    (radians(e.lattitude)))) as distance")
                        ->from('BackendBundle:Employee', 'e')
                        ->where('e.id IN'.$empInParams)
                        ->andWhere('e.archived = 0')
                        ->orderBy("distance", "ASC")
                        ->getQuery();

        $resultEmployee= $empQuery->getResult();
//        $array = array();

//        if(is_array($resultEmployee)) {
//            foreach ($resultEmployee as $emplo) {
//                array_push($array, $emplo[0]);
//            }
//        }



        //Sort By Distance Algorithm.

//        if($skillComId != null && count($inductionIdArr) > 0 && $indCount != 0) {
//            $query = $this->getEntityManager()->createQuery("SELECT e FROM BackendBundle\Entity\Employee e  WHERE e.id IN
//                                                            ( SELECT se.id FROM BackendBundle\Entity\EmployeeSkillCompetencyDocument s
//                                                              LEFT JOIN s.employee se  LEFT JOIN s.skillCompetencyList sc
//                                                              WHERE sc.id IN ($skillComId)
//                                                              GROUP BY se.id
//                                                              HAVING COUNT(DISTINCT (sc.id)) = 1
//                                                              AND se.id IN (
//                                                                SELECT ie.id FROM BackendBundle\Entity\EmployeeInduction i
//                                                                LEFT JOIN i.employee ie
//                                                                LEFT JOIN i.induction ic
//                                                                WHERE ic.id IN ($inductionIdArr)
//                                                                GROUP BY ie.id
//                                                                HAVING COUNT(DISTINCT (ic.id)) = $indCount
//                                                                )
//                                                              ) AND e.archived = 0
//                                                              ");
//
//            $result = $query->getResult();
//        }

        return $resultEmployee;
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
