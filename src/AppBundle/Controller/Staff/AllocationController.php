<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/11/2017
 * Time: 10:21 AM
 */

namespace AppBundle\Controller\Staff;


use AppBundle\Entity\EmployeeAllocations;
use AppBundle\Services\Helpers;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Controller\Core\AController;

class AllocationController extends AController
{
    private $error = array();



    public function getEmployeeAllocationsAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', false);


        if($this->isAuthenticated($token)) {
            $create = true;
            $identity = $this->getIdentity($request);


            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->findBy(array('user'=>$identity->sub));
            $allocations = $em->getRepository('BackendBundle:AllocatedDates')->findEmployeeAllocations($em, $employee[0]);

            $allocationArr = array();

            if(count($allocations) > 0) {
                foreach($allocations as $allocation) {
                    $newEmpAlloc = new EmployeeAllocations($allocation->getEmployeeAllocation());
                    $allocation->setEmployeeAllocation($newEmpAlloc->employeeAllocation);
                    array_push($allocationArr, $allocation);
                }
            }




                    $data = array(
                        'status' => "success",
                        'code' => 200,
                        'msg' => ' All employee allocations!!!',
                        'data' => $allocationArr
                    );

            } else {
                $data = array_merge($this->duplicateError, array(
                    'error_data' => $this->error
                ));
            }


        return $helpers->json($data);
    }


    public function getEmployeePendingAllocationsAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', false);


        if($this->isAuthenticated($token)) {
            $create = true;
            $identity = $this->getIdentity($request);


            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->findBy(array('user'=>$identity->sub));
            $allocations = $em->getRepository('BackendBundle:AllocatedDates')->findEmployeePendingAllocations($em, $employee[0]);

            $allocationArr = array();

            if(count($allocations) > 0) {
                foreach($allocations as $allocation) {
                    $newEmpAlloc = new EmployeeAllocations($allocation->getEmployeeAllocation());
                    $allocation->setEmployeeAllocation($newEmpAlloc->employeeAllocation);
                    array_push($allocationArr, $allocation);
                }
            }




            $data = array(
                'status' => "success",
                'code' => 200,
                'msg' => ' All employee allocations!!!',
                'data' => $allocationArr
            );

        } else {
            $data = array_merge($this->duplicateError, array(
                'error_data' => $this->error
            ));
        }


        return $helpers->json($data);
    }

    public function getEmployeeAcceptedAllocationsAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', false);


        if($this->isAuthenticated($token)) {
            $create = true;
            $identity = $this->getIdentity($request);


            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->findBy(array('user'=>$identity->sub));
            $allocations = $em->getRepository('BackendBundle:AllocatedDates')->findEmployeeAcceptedAllocations($em, $employee[0]);

            $allocationArr = array();

            if(count($allocations) > 0) {
                foreach($allocations as $allocation) {
                    $newEmpAlloc = new EmployeeAllocations($allocation->getEmployeeAllocation());
                    $allocation->setEmployeeAllocation($newEmpAlloc->employeeAllocation);
                    array_push($allocationArr, $allocation);
                }
            }




            $data = array(
                'status' => "success",
                'code' => 200,
                'msg' => ' All employee allocations!!!',
                'data' => $allocationArr
            );

        } else {
            $data = array_merge($this->duplicateError, array(
                'error_data' => $this->error
            ));
        }


        return $helpers->json($data);
    }

    public function acceptAllocationAction(Request $request) {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', false);
        $json = $request->get('json', false);
        $params = json_decode($json);


        if($this->isAuthenticated($token)) {
            $create = true;
            $identity = $this->getIdentity($request);
            $allocationId = $this->getEnitityId($params->id);


            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->findBy(array('user'=>$identity->sub));
            $allocation = $em->getRepository('BackendBundle:AllocatedDates')->find($allocationId);
            $employeeAlloction = $allocation->getEmployeeAllocation();
            $allocationEmpEncryptId = $employeeAlloction->getEmployee()->getId();

            if($employee[0]->getId() == $allocationEmpEncryptId) {

                $allocation->setAccecptallocation(true);
                $allocation->setCancelallocation(false);
                $allocation->setRequestsend(true);

                $em->persist($allocation);
                $em->flush();

                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => ' All employee allocations!!!',
                    'data' => $allocation
                );
            } else {
                $data = array_merge($this->accessError, array(
                    'error_data' => $this->error
                ));
            }



        } else {
            $data = array_merge($this->accessError, array(
                'error_data' => $this->error
            ));
        }


        return $helpers->json($data);
    }

    public function denyAllocationAction(Request $request) {

        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', false);
        $json = $request->get('json', false);
        $params = json_decode($json);


        if($this->isAuthenticated($token)) {
            $create = true;
            $identity = $this->getIdentity($request);
            $allocationId = $this->getEnitityId($params->id);


            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->findBy(array('user'=>$identity->sub));
            $allocation = $em->getRepository('BackendBundle:AllocatedDates')->find($allocationId);
            $employeeAlloction = $allocation->getEmployeeAllocation();
            $allocationEmpEncryptId = $employeeAlloction->getEmployee()->getId();

            if($employee[0]->getId() == $allocationEmpEncryptId) {

                $allocation->setCancelallocation(true);
                $allocation->setAccecptallocation(false);
                $allocation->setRequestsend(true);

                $em->persist($allocation);
                $em->flush();

                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => ' All employee allocations!!!',
                    'data' => $allocation
                );
            } else {
                $data = array_merge($this->accessError, array(
                    'error_data' => $this->error
                ));
            }



        } else {
            $data = array_merge($this->accessError, array(
                'error_data' => $this->error
            ));
        }


        return $helpers->json($data);
    }



}