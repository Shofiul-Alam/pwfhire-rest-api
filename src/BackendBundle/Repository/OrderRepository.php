<?php

/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2/2/18
 * Time: 12:27 PM
 */
namespace BackendBundle\Repository;

use AppBundle\Services\JwtAuth;
use Doctrine\ORM\EntityRepository;

class OrderRepository extends EntityRepository
{
    public function findProjectOrders(\Doctrine\ORM\QueryBuilder $queryBuilder, $params, $jwt)
    {
        $condition = $queryBuilder->expr()->orX();
        foreach ($params as $key => $param) {
            if($key == 'id') {
                $param = $jwt->decodeId($param);
            }
            $condition->add($queryBuilder->expr()->eq('p.'.$key, $param));
        }
        $query = $queryBuilder
                    ->select('o')
                    ->from('BackendBundle:Order', 'o')
                    ->innerJoin('o.project', 'p')
                    ->andwhere($condition)
                    ->andWhere('o.archived = 0')
                    ->orderBy('o.startDate', 'DESC');

//        $result = $this->getEntityManager()->createQuery($query)->getResult();
        return $query;
    }

}