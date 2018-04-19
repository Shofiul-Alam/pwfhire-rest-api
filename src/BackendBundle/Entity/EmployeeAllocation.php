<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * EmployeeAllocation
 */
class EmployeeAllocation extends AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $cancelall;

    /**
     * @var boolean
     */
    private $acceptpartially;

    /**
     * @var boolean
     */
    private $acceptall;

    /**
     * @var boolean
     */
    private $requestsendpartially;

    /**
     * @var boolean
     */
    private $requestsendall;

    /**
     * @var string
     */
    private $sms;

    /**
     * @var \BackendBundle\Entity\Employee
     */
    private $employee;

    /**
     * @var \BackendBundle\Entity\Task
     */
    private $task;


    private $allocatedDates;

    public $splicedAllocatedDates;

    public $firstTime = false;


    function __construct()
    {
        $this->allocatedDates = new ArrayCollection();
        $this->splicedAllocatedDates = new ArrayCollection();
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAllocatedDates()
    {
        return $this->allocatedDates;
    }

    /**
     * @param \BackendBundle\Entity\AllocatedContact
     */
    public function replaceAllocatedDates(AllocatedDates $allocatedDates)
    {
        if(count($this->splicedAllocatedDates) == 0 && $this->firstTime) {
            if(count($this->allocatedDates) != 0) {
                $this->removeAllocatedDates($allocatedDates);
            }
            $removeArr = $this->getAllocatedDates();
            $this->splicedAllocatedDates = new ArrayCollection();

            if(count($this->getAllocatedDates()) > 0) {

                foreach ($removeArr as $r) {

                    $this->splicedAllocatedDates->add($r);
                }
            }
            $allocatedDates->setEmployeeAllocation($this);
            $this->allocatedDates->add($allocatedDates);
            $this->firstTime = false;
            $this->splicedAllocatedDates->removeElement($allocatedDates);

        } else {
            $this->splicedAllocatedDates->removeElement($allocatedDates);
            $this->removeAllocatedDates($allocatedDates);
            $this->allocatedDates->add($allocatedDates);
        }

    }

    public function addAllocatedDates(AllocatedDates $allocatedDates)
    {
        return $this->allocatedDates->add($allocatedDates);
    }


    public function removeAllocatedDates(AllocatedDates $allocatedDates)
    {
        $this->allocatedDates->removeElement($allocatedDates);
    }

    public function unsetAllocatedDates()
    {
        $this->allocatedDates = [];
    }

    /**
     * @return bool
     */
    public function isFirstTime()
    {
        return $this->firstTime;
    }

    /**
     * @param bool $firstTime
     */
    public function setFirstTime(bool $firstTime)
    {
        $this->firstTime = $firstTime;
    }



    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->encodeId($this->id);
    }

    /**
     * Set cancelall
     *
     * @param boolean $cancelall
     *
     * @return EmployeeAllocation
     */
    public function setCancelall($cancelall)
    {
        $this->cancelall = $cancelall;

        return $this;
    }

    /**
     * Get cancelall
     *
     * @return boolean
     */
    public function getCancelall()
    {
        return $this->cancelall;
    }

    /**
     * Set acceptpartially
     *
     * @param boolean $acceptpartially
     *
     * @return EmployeeAllocation
     */
    public function setAcceptpartially($acceptpartially)
    {
        $this->acceptpartially = $acceptpartially;

        return $this;
    }

    /**
     * Get acceptpartially
     *
     * @return boolean
     */
    public function getAcceptpartially()
    {
        return $this->acceptpartially;
    }

    /**
     * Set acceptall
     *
     * @param boolean $acceptall
     *
     * @return EmployeeAllocation
     */
    public function setAcceptall($acceptall)
    {
        $this->acceptall = $acceptall;

        return $this;
    }

    /**
     * Get acceptall
     *
     * @return boolean
     */
    public function getAcceptall()
    {
        return $this->acceptall;
    }

    /**
     * Set requestsendpartially
     *
     * @param boolean $requestsendpartially
     *
     * @return EmployeeAllocation
     */
    public function setRequestsendpartially($requestsendpartially)
    {
        $this->requestsendpartially = $requestsendpartially;

        return $this;
    }

    /**
     * Get requestsendpartially
     *
     * @return boolean
     */
    public function getRequestsendpartially()
    {
        return $this->requestsendpartially;
    }

    /**
     * Set requestsendall
     *
     * @param boolean $requestsendall
     *
     * @return EmployeeAllocation
     */
    public function setRequestsendall($requestsendall)
    {
        $this->requestsendall = $requestsendall;

        return $this;
    }

    /**
     * Get requestsendall
     *
     * @return boolean
     */
    public function getRequestsendall()
    {
        return $this->requestsendall;
    }

    /**
     * Set employee
     *
     * @param \BackendBundle\Entity\Employee $employee
     *
     * @return EmployeeAllocation
     */
    public function setEmployee(\BackendBundle\Entity\Employee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get employee
     *
     * @return \BackendBundle\Entity\Employee
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    /**
     * Set task
     *
     * @param \BackendBundle\Entity\Task $task
     *
     * @return EmployeeAllocation
     */
    public function setTask(\BackendBundle\Entity\Task $task = null)
    {
        $this->task = $task;

        return $this;
    }

    /**
     * Get task
     *
     * @return \BackendBundle\Entity\Task
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @return string
     */
    public function getSms()
    {
        return $this->sms;
    }

    /**
     * @param string $sms
     */
    public function setSms(string $sms)
    {
        $this->sms = $sms;
    }



    public function toArray() {
        return get_object_vars($this);
    }
}

