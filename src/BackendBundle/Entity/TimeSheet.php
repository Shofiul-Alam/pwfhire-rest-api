<?php

namespace BackendBundle\Entity;

/**
 * TimeSheet
 */
class TimeSheet extends AEntity
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
     * @var \DateTime
     */
    private $startTime;

    /**
     * @var \DateTime
     */
    private $finishTime;

    /**
     * @var string
     */
    private $break;

    /**
     * @var string
     */
    private $hoursWorked;

    /**
     * @var string
     */
    private $weekDay;

    /**
     * @var string
     */
    private $workerTimesheetInstructions;

    /**
     * @var string
     */
    private $clientTimesheetInstruction;

    /**
     * @var boolean
     */
    private $approved;

    /**
     * @var \BackendBundle\Entity\AllocatedDates
     */
    private $allocatedDates;

    /**
     * @var \BackendBundle\Entity\Employee
     */
    private $employee;

    /**
     * @var \BackendBundle\Entity\EmployeeTimesheetDocument
     */

    private $employeeTimesheetDocument;


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
     * @return TimeSheet
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
     * Set startTime
     *
     * @param \DateTime $startTime
     *
     * @return TimeSheet
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return \DateTime
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set finishTime
     *
     * @param \DateTime $finishTime
     *
     * @return TimeSheet
     */
    public function setFinishTime($finishTime)
    {
        $this->finishTime = $finishTime;

        return $this;
    }

    /**
     * Get finishTime
     *
     * @return \DateTime
     */
    public function getFinishTime()
    {
        return $this->finishTime;
    }

    /**
     * Set break
     *
     * @param string $break
     *
     * @return TimeSheet
     */
    public function setBreak($break)
    {
        $this->break = $break;

        return $this;
    }

    /**
     * Get break
     *
     * @return string
     */
    public function getBreak()
    {
        return $this->break;
    }

    /**
     * Set hoursWorked
     *
     * @param string $hoursWorked
     *
     * @return TimeSheet
     */
    public function setHoursWorked($hoursWorked)
    {
        $this->hoursWorked = $hoursWorked;

        return $this;
    }

    /**
     * Get hoursWorked
     *
     * @return string
     */
    public function getHoursWorked()
    {
        return $this->hoursWorked;
    }

    /**
     * Set weekDay
     *
     * @param string $weekDay
     *
     * @return TimeSheet
     */
    public function setWeekDay($weekDay)
    {
        $this->weekDay = $weekDay;

        return $this;
    }

    /**
     * Get weekDay
     *
     * @return string
     */
    public function getWeekDay()
    {
        return $this->weekDay;
    }

    /**
     * Set workerTimesheetInstructions
     *
     * @param string $workerTimesheetInstructions
     *
     * @return TimeSheet
     */
    public function setWorkerTimesheetInstructions($workerTimesheetInstructions)
    {
        $this->workerTimesheetInstructions = $workerTimesheetInstructions;

        return $this;
    }

    /**
     * Get workerTimesheetInstructions
     *
     * @return string
     */
    public function getWorkerTimesheetInstructions()
    {
        return $this->workerTimesheetInstructions;
    }

    /**
     * Set clientTimesheetInstruction
     *
     * @param string $clientTimesheetInstruction
     *
     * @return TimeSheet
     */
    public function setClientTimesheetInstruction($clientTimesheetInstruction)
    {
        $this->clientTimesheetInstruction = $clientTimesheetInstruction;

        return $this;
    }

    /**
     * Get clientTimesheetInstruction
     *
     * @return string
     */
    public function getClientTimesheetInstruction()
    {
        return $this->clientTimesheetInstruction;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     *
     * @return TimeSheet
     */
    public function setApproved($approved)
    {
        $this->approved = $approved;

        return $this;
    }

    /**
     * Get approved
     *
     * @return boolean
     */
    public function getApproved()
    {
        return $this->approved;
    }

    /**
     * Set allocatedDates
     *
     * @param \BackendBundle\Entity\AllocatedDates $allocatedDates
     *
     * @return TimeSheet
     */
    public function setAllocatedDates(\BackendBundle\Entity\AllocatedDates $allocatedDates = null)
    {
        $this->allocatedDates = $allocatedDates;

        return $this;
    }

    /**
     * Get allocatedDates
     *
     * @return \BackendBundle\Entity\AllocatedDates
     */
    public function getAllocatedDates()
    {
        return $this->allocatedDates;
    }

    /**
     * Set employee
     *
     * @param \BackendBundle\Entity\Employee $employee
     *
     * @return TimeSheet
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
     * @return EmployeeTimesheetDocument
     */
    public function getEmployeeTimesheetDocument()
    {
        return $this->employeeTimesheetDocument;
    }

//    /**
//     * @param EmployeeTimesheetDocument $employeeTimesheetDocument
//     */
    public function setEmployeeTimesheetDocument(EmployeeTimesheetDocument $employeeTimesheetDocument)
    {
        $this->employeeTimesheetDocument = $employeeTimesheetDocument;
    }



    public function toArray() {
        return get_object_vars($this);
    }
}

