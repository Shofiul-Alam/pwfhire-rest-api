<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Task
 */
class Task extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $taskName;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var integer
     */
    private $startTime;

    /**
     * @var integer
     */
    private $endTime;

    /**
     * @var float
     */
    private $chargeRate;

    /**
     * @var float
     */
    private $payRate;

    /**
     * @var integer
     */
    private $numberOfEmployees;

    /**
     * @var boolean
     */
    private $archived = false;

    /**
     * @var \BackendBundle\Entity\Order
     */
    private $order;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $job;

    public $splicedJob;

    public $firstTime = true;

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
     * Constructor
     */
    public function __construct()
    {
        $this->job = new \Doctrine\Common\Collections\ArrayCollection();
        $this->splicedJob = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Task
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Task
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set startTime
     *
     * @param integer $startTime
     *
     * @return Task
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;

        return $this;
    }

    /**
     * Get startTime
     *
     * @return integer
     */
    public function getStartTime()
    {
        return $this->startTime;
    }

    /**
     * Set endTime
     *
     * @param integer $endTime
     *
     * @return Task
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;

        return $this;
    }

    /**
     * Get endTime
     *
     * @return integer
     */
    public function getEndTime()
    {
        return $this->endTime;
    }

    /**
     * Set order
     *
     * @param \BackendBundle\Entity\Order $order
     *
     * @return Task
     */
    public function setOrder(\BackendBundle\Entity\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order
     *
     * @return \BackendBundle\Entity\Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Add job
     *
     * @param \BackendBundle\Entity\Job $job
     *
     * @return Task
     */
    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getjob()
    {
        return $this->job;
    }

    /**
     * @param \BackendBundle\Entity\AllocatedContact
     */
    public function replaceJob(Job $job)
    {
        if(count($this->splicedJob) == 0 && $this->firstTime) {
            if(count($this->job) != 0) {
                $this->removeJob($job);
            }
            $removeArr = $this->getJob();
            $this->splicedJob = new ArrayCollection();

            if(count($this->getJob()) > 0) {

                foreach ($removeArr as $r) {

                    $this->splicedJob->add($r);
                }
            }
            $this->job->add($job);
            $this->firstTime = false;
            $this->splicedJob->removeElement($job);

        } else {
            $this->splicedJob->removeElement($job);
            $this->removeJob($job);
            $this->job->add($job);
        }

    }

    public function addJob(Job $job)
    {
        return $this->job->add($job);
    }


    public function removeJob(Job $job)
    {
        $this->job->removeElement($job);
    }


    /**
     * @return float
     */
    public function getChargeRate()
    {
        return $this->chargeRate;
    }

    /**
     * @param float $chargeRate
     */
    public function setChargeRate(float $chargeRate)
    {
        $this->chargeRate = $chargeRate;
    }

    /**
     * @return float
     */
    public function getPayRate()
    {
        return $this->payRate;
    }

    /**
     * @param float $payRate
     */
    public function setPayRate(float $payRate)
    {
        $this->payRate = $payRate;
    }

    /**
     * @return int
     */
    public function getNumberOfEmployees()
    {
        return $this->numberOfEmployees;
    }

    /**
     * @param int $numberOfEmployees
     */
    public function setNumberOfEmployees(int $numberOfEmployees)
    {
        $this->numberOfEmployees = $numberOfEmployees;
    }

    /**
     * @return mixed
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * @param mixed $archived
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
    }

    /**
     * @return \DateTime
     */
    public function getTaskName()
    {
        return $this->taskName;
    }

    /**
     * @param \DateTime $taskName
     */
    public function setTaskName($taskName)
    {
        $this->taskName = $taskName;
    }




    public function toArray() {
        return get_object_vars($this);
    }
}

