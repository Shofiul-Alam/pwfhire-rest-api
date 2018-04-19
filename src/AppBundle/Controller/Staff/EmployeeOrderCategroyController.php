<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/11/2017
 * Time: 10:22 AM
 */

namespace AppBundle\Controller\Staff;


use AppBundle\Services\Helpers;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\EmployeeOrderCategory;
use AppBundle\Controller\Core\AController;

class EmployeeOrderCategroyController extends AController
{
    private $error = array();

    public function newAction(Request $request)
    {

        $this->entity = new EmployeeOrderCategory();

        return parent::newAction($request);

    }

    public function listAction(Request $request) {
        $this->entity = new EmployeeOrderCategory();

        return parent::listAction($request);
    }


    public function editAction(Request $request)
    {
        $this->entity = new EmployeeOrderCategory();
        return parent::editAction($request);
    }

    public function deleteAction(Request $request)
    {
        $this->entity = new EmployeeOrderCategory();
        return parent::removeAction($request);
    }




}