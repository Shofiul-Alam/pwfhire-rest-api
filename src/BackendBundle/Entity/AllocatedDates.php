<?php

namespace BackendBundle\Entity;

/**
 * AllocatedDates
 */
class AllocatedDates extends AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $day;

    /**
     * @var string
     */
    private $respond;

    /**
     * @var boolean
     */
    private $cancelallocation = false;

    /**
     * @var boolean
     */
    private $accecptallocation = false;

    /**
     * @var boolean
     */
    private $requestsend = false;

    /**
     * @var \BackendBundle\Entity\EmployeeAllocation
     */
    private $employeeAllocation;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return AllocatedDates
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set day
     *
     * @param string $day
     *
     * @return AllocatedDates
     */
    public function setDay($day = null)
    {
        $this->day = $day;

        return $this;
    }

    /**
     * Get day
     *
     * @return string
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set respond
     *
     * @param string $respond
     *
     * @return AllocatedDates
     */
    public function setRespond($respond = null)
    {
        $this->respond = $respond;

        return $this;
    }

    /**
     * Get respond
     *
     * @return string
     */
    public function getRespond()
    {
        return $this->respond;
    }

    /**
     * Set cancelallocation
     *
     * @param boolean $cancelallocation
     *
     * @return AllocatedDates
     */
    public function setCancelallocation($cancelallocation = null)
    {
        $this->cancelallocation = $cancelallocation;

        return $this;
    }

    /**
     * Get cancelallocation
     *
     * @return boolean
     */
    public function getCancelallocation()
    {
        return $this->cancelallocation;
    }

    /**
     * Set accecptallocation
     *
     * @param boolean $accecptallocation
     *
     * @return AllocatedDates
     */
    public function setAccecptallocation($accecptallocation = null)
    {
        $this->accecptallocation = $accecptallocation;

        return $this;
    }

    /**
     * Get accecptallocation
     *
     * @return boolean
     */
    public function getAccecptallocation()
    {
        return $this->accecptallocation;
    }

    /**
     * Set requestsend
     *
     * @param boolean $requestsend
     *
     * @return AllocatedDates
     */
    public function setRequestsend($requestsend = null)
    {
        $this->requestsend = $requestsend;

        return $this;
    }

    /**
     * Get requestsend
     *
     * @return boolean
     */
    public function getRequestsend()
    {
        return $this->requestsend;
    }

    /**
     * Set employeeAllocation
     *
     * @param \BackendBundle\Entity\EmployeeAllocation $employeeAllocation
     *
     * @return AllocatedDates
     */
    public function setEmployeeAllocation(\BackendBundle\Entity\EmployeeAllocation $employeeAllocation)
    {
        $this->employeeAllocation = $employeeAllocation;

        return $this;
    }

    /**
     * Get employeeAllocation
     *
     * @return \BackendBundle\Entity\EmployeeAllocation
     */
    public function getEmployeeAllocation()
    {
        return $this->employeeAllocation;
    }

    public function toArray() {
        return get_object_vars($this);
    }
}

