<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/11/2017
 * Time: 8:15 PM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\EmployeeDocWithBookedDate;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;
use AppBundle\Services\Twilio\TwilioConfig;
use BackendBundle\Entity\AllocatedDates;
use BackendBundle\Entity\EmployeeAllocation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;



class ManageAllocationController extends AAdmin
{
    private $error = array();

    public function listAction(Request $request) {
        $this->entity = new EmployeeAllocation();

        return parent::listAction($request);
    }

    public function sendAllocationsAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $newEmployeeAllocationsArr = json_decode($json);
        $taskId = $request->get('taskId', null);
        if($taskId) {
            $taskId = $this->getEnitityId($taskId);
        }

        $token = $request->get('authorisation', null);
        $mailer = $this->get('mailer');


        if($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $em = $this->getDoctrine()->getManager();
            foreach ($newEmployeeAllocationsArr as $newEmployeeAllocation) {
                $task = $em->getRepository('BackendBundle:Task')->find($this->getEnitityId($newEmployeeAllocation->task->id));
                $employee = $em->getRepository('BackendBundle:Employee')->find($this->getEnitityId($newEmployeeAllocation->employee->id));

                $newAllocation = $em->getRepository('BackendBundle:EmployeeAllocation')->findDuplicateAllocation($em, $employee, $task);


                if($newAllocation) {
                    $allocatedDatesParams = $newEmployeeAllocation->allocatedDates;
                    $newAllocation = $newAllocation[0];
                    foreach($allocatedDatesParams as $allocatedDatesParam) {
                        if($allocatedDatesParam->id == '0' || $allocatedDatesParam->id == null) {
                            $newAllocatedDate = new AllocatedDates();
                            $this->prepareEntityData($newAllocatedDate, $allocatedDatesParam);
                            $newAllocation->addAllocatedDates($newAllocatedDate);
                        }
                    }

                }
                elseif($newEmployeeAllocation->id == 0) {
                    $newAllocation = new EmployeeAllocation();
                    $this->prepareEntityData($newAllocation, $newEmployeeAllocation);
                }
                else {
                    $employeeAllocId = $this->getEnitityId($newEmployeeAllocation->id);
                    $newAllocation = $em->getRepository('BackendBundle:EmployeeAllocation')->find($employeeAllocId);
                    $this->prepareEntityData($newAllocation, $newEmployeeAllocation);

                }

                $mailTo = $newAllocation->getEmployee()->getUser()->getEmail();
                $body = $newAllocation->getSms();
                $taskDates = $newAllocation->getTask()->getEndDate()->diff($newAllocation->getTask()->getStartDate())->days;
                $allocatedDates = count($newAllocation->getAllocatedDates());

                if($taskDates == $allocatedDates) {
                    $fullAlloc = true;
                } else {
                    $partiallyAlloc = true;
                }

                $res = $this->sendEmail($mailer, $mailTo, $body);
                if($res && $fullAlloc) {
                    $newAllocation->setRequestsendall(true);
                } elseif($res && $partiallyAlloc) {
                    $newAllocation->setRequestsendpartially(true);
                }

                $mobileNo = $this->formatMobileNo($newAllocation->getEmployee()->getUser()->getMobile());
                $this->sendSMS($em, $body, $mobileNo);


                $em->persist($newAllocation);
                $em->flush();



            }


            $allocations = $em->getRepository('BackendBundle:EmployeeAllocation')->findBy(array('task' => $taskId));

            if($allocations) {
//                $em->persist($task);
//                $em->flush();



                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'Allocation successfully distributed!!!',
                    'allocations' => $allocations
                );
            } else {
                $data = $this->error;
            }


        } else {
            $data = $this->accessError;
        }

        return $helpers->json($data);
    }


    public function getAllocationsForTaskAction(Request $request) {
        $token = $request->get('authorisation', null);
        $json = json_decode($request->get('json', null));
        $helpers = $this->get(Helpers::class);

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $id = $this->getEnitityId($json->id);

            $em = $this->getDoctrine()->getManager();
            $task = $em->getRepository('BackendBundle:Task')->find($id);

            $allocations = $em->getRepository('BackendBundle:EmployeeAllocation')->findTaskAllocations($em, $task);


            if(count($allocations) > 0) {
//                $em->persist($task);
//                $em->flush();



                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'Assigned Allocations!!!',
                    'employeeAllocations' => $allocations
                );
            } else {
                $queryBuilder = $em->createQueryBuilder();


                $jwt = $this->get(JwtAuth::class);

                $allocations = $em->getRepository('BackendBundle:Task')->findEmployeeForTask($queryBuilder, $task, $jwt, $em);

                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'Assigne new Allocation for the task!!!',
                    'allocations' => $allocations
                );
            }


        } else {
            $data = $this->accessError;
        }

        return $helpers->json($data);
    }

    public function getEmployeesForAllocationAction(Request $request) {

        $token = $request->get('authorisation', null);
        $json = json_decode($request->get('json', null));
        $helpers = $this->get(Helpers::class);

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $id = $this->getEnitityId($json->id);

            $em = $this->getDoctrine()->getManager();
            $task = $em->getRepository('BackendBundle:Task')->find($id);


                $queryBuilder = $em->createQueryBuilder();


                $jwt = $this->get(JwtAuth::class);

                $allocations = $em->getRepository('BackendBundle:Task')->findEmployeeForTask($queryBuilder, $task, $jwt, $em);

                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'Assigne new Allocation for the task!!!',
                    'allocations' => $allocations
                );


        } else {
            $data = $this->accessError;
        }

        return $helpers->json($data);
    }

    private function sendEmail(\Swift_Mailer $mailer, $emailTo, $body) {
        $message = (new \Swift_Message('Employee Allocation'))
            ->setFrom('shofiul.au@gmail.com')
            ->setTo($emailTo)
            ->setBody(
               $body
            );

        $res = $mailer->send($message);

        return $res;
    }

    protected function formatMobileNo($mobileNo) {
        $mobileNo = str_replace(' ', '', $mobileNo);
        if(substr($mobileNo, 0, 1) == '0') {
            $mobileNo = "+61".substr($mobileNo, 1);
        }elseif(substr($mobileNo, 0, 3) == "+61") {
            $mobileNo = $mobileNo;
        }

        return $mobileNo;
    }

    protected function sendSMS($em, $sms, $customerMobile) {

        $config = new TwilioConfig($em);


        $client = new \Twilio\Rest\Client($config->getTwilioSID(), $config->getTwilioToken());


        $client->messages
            ->create(
                $customerMobile,
                array(
                    "from" => $config->getTwilioalphaNumericID(),
                    "body" => $sms,
                )
            );
    }

    public function findEmployeeBookDateDocAction(Request $request) {

        $token = $request->get('authorisation', null);
        $json = json_decode($request->get('json', null));
        $helpers = $this->get(Helpers::class);

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $id = $this->getEnitityId($json->id);

            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->find($id);
            $startDate = $this->processDate($json->startDate);
            $endDate = $this->processDate($json->endDate);
            $allocatedDates = $em->getRepository('BackendBundle:AllocatedDates')->findEmployeeAllocationFromDateRange($em, $employee, $startDate, $endDate);
            $employeeSkillDocuments = $em->getRepository('BackendBundle:EmployeeSkillCompetencyDocument')->findBy(array('employee' => $employee));

            $employeeDocWithBookedDate = new EmployeeDocWithBookedDate();
            $employeeDocWithBookedDate->docs = $employeeSkillDocuments;
            $employeeDocWithBookedDate->bookedDates = $allocatedDates;

            $data = array(
                'status' => "success",
                'code' => 200,
                'msg' => 'Successfully retrived employee booked dates and documents!!!',
                'data' => $employeeDocWithBookedDate
            );


        } else {
            $data = $this->accessError;
        }

        return $helpers->json($data);
    }

    private function processDate($dob) {

        if($dob != null && $dob->year != null) {
            $formatedDob = $dob->year . '/' . $dob->month . '/' . $dob->day;
            $sql_dob = new \DateTime($formatedDob);

        }

        return date_format($sql_dob, 'Y-m-d H:i:s');

    }


}