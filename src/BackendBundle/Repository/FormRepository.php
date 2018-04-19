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

class FormRepository extends EntityRepository
{
    public function findForms()
    {

       $queryBuilder = $this->_em->createQueryBuilder();

        $query = $queryBuilder
                    ->select('f')
                    ->from('BackendBundle:Form', 'f')
                    ->leftJoin('f.fieldsArr', 'fa')
                    ->andwhere('fa.appliedRemove = false');

        $result = $this->getEntityManager()->createQuery($query)->getResult();
        return $query;
    }

}