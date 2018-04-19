<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/11/2017
 * Time: 10:21 AM
 */

namespace AppBundle\Controller\Staff;


use AppBundle\Services\Helpers;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\EmployeeCategory;
use AppBundle\Controller\Core\AController;

class EmployeeCategoryController extends AController
{
    private $error = array();

    public function newAction(Request $request)
    {

        $this->entity = new EmployeeCategory();

       return parent::newAction($request);

    }

    public function listAction(Request $request) {
        $this->entity = new EmployeeCategory();

        return parent::listAction($request);
    }


    public function editAction(Request $request)
    {
        $this->entity = new EmployeeCategory();
        return parent::editAction($request);
    }

    public function deleteAction(Request $request)
    {
        $this->entity = new EmployeeCategory();
        return parent::removeAction($request);
    }





}