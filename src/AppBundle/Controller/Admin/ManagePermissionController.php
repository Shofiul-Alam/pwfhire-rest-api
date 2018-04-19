<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/11/2017
 * Time: 8:15 PM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;
use BackendBundle\Entity\InductionPermission;
use BackendBundle\Entity\Task;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;


class ManagePermissionController extends AAdmin
{
    private $error = array();



    public function inductionPermissionAction(Request $request) {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        $json = json_decode($request->get('json', null));



        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $em = $this->getDoctrine()->getManager();
//            $queryBuilder = $em->createQueryBuilder();
            $induction_permission = new InductionPermission();
            $empId = $this->getEnitityId($json->employee->id);
            $inductionId = $this->getEnitityId($json->induction->id);
            $employee = $em->getRepository('BackendBundle:Employee')->find($empId);
            $induction = $em->getRepository('BackendBundle:Induction')->find($inductionId);

            $induction_permission->setEmployee($employee);
            $induction_permission->setInduction($induction);


            $em->persist($induction_permission);
            $em->flush();



            $data = array(
                "status" => "success",
                'code'  => 200,
                'msg'  => "Induction access permission given to the employee!",
                'data' => $induction_permission

            );
        } else {
            $data = array(
                "status" => "error",
                'code'  => 400,
                'msg'   => 'Authorization not valid'
            );
        }

        return $helpers->json($data);
    }



}