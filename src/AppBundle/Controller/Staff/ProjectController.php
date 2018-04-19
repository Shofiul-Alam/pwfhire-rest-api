<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/11/2017
 * Time: 10:21 AM
 */

namespace AppBundle\Controller\Staff;

use AppBundle\Controller\Core\AEmployeeController;
use Symfony\Component\HttpFoundation\Request;
use BackendBundle\Entity\Project;
use AppBundle\Controller\Core\AController;

class ProjectController extends AEmployeeController
{
    private $error = array();

    public function newAction(Request $request)
    {

        $this->entity = new Project();

       return parent::newAction($request);

    }

    public function listAction(Request $request) {
        $this->entity = new Project();

        return parent::listAction($request);
    }


    public function editAction(Request $request)
    {
        $this->entity = new Project();
        return parent::editAction($request);
    }





}