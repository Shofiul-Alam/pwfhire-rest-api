<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 23/2/18
 * Time: 11:03 AM
 */

namespace AppBundle\Entity;


use BackendBundle\Entity\Client;
use BackendBundle\Entity\EmployeeAllocation;
use BackendBundle\Entity\Order;
use BackendBundle\Entity\Project;
use BackendBundle\Entity\Task;

class EmployeeAllocations {
    public $employeeAllocation;

    public function __construct(EmployeeAllocation $employeeAllocation)
    {
        $allocatedDates = $employeeAllocation->getAllocatedDates();

        if(count($allocatedDates) > 0) {
            $newTask = new Task();
            $newProject = new Project();
            $newClient = new Client();
            $newOrder = new Order();
            $newEmployeeAllocation = new EmployeeAllocation();

            $newClient->setEncryptedId($employeeAllocation->getTask()->getOrder()->getProject()->getClient()->getId());
            $newClient->setCompanyName($employeeAllocation->getTask()->getOrder()->getProject()->getClient()->getCompanyName());

            $newProject->setEncryptedId($employeeAllocation->getTask()->getOrder()->getProject()->getId());
            $newProject->setProjectAddress($employeeAllocation->getTask()->getOrder()->getProject()->getProjectAddress());
            $newProject->setClient($newClient);

            $newOrder->setEncryptedId($employeeAllocation->getTask()->getOrder()->getId());
            $newOrder->splicedAllocatedContact = $employeeAllocation->getTask()->getOrder()->getAllocatedContact();
            $newOrder->setProject($newProject);

            $newTask->setEncryptedId($employeeAllocation->getTask()->getId());
            $newTask->setTaskName($employeeAllocation->getTask()->getTaskName());
            $newTask->setStartTime($employeeAllocation->getTask()->getStartTime());
            $newTask->setEndTime($employeeAllocation->getTask()->getEndTime());
            $newTask->addJob($employeeAllocation->getTask()->getJob()[0]);
            $newTask->setOrder($newOrder);

            $newEmployeeAllocation->setEncryptedId($employeeAllocation->getId());
            $newEmployeeAllocation->setTask($newTask);
            $newEmployeeAllocation->setEmployee($employeeAllocation->getEmployee());
            $newEmployeeAllocation->setRequestsendall($employeeAllocation->getRequestsendall());
            $newEmployeeAllocation->setAcceptall($employeeAllocation->getAcceptall());
            $newEmployeeAllocation->setAcceptpartially($employeeAllocation->getAcceptpartially());
            $newEmployeeAllocation->setCancelall($employeeAllocation->getCancelall());
            $newEmployeeAllocation->setRequestsendpartially($employeeAllocation->getRequestsendpartially());
            $newEmployeeAllocation->setSms($employeeAllocation->getSms());



                $this->employeeAllocation = $newEmployeeAllocation;

        }

    }
}