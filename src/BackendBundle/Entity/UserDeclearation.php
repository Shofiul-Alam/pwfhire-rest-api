<?php

namespace BackendBundle\Entity;

/**
 * UserDeclearation
 */
class UserDeclearation extends AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var boolean
     */
    private $isIslander;

    /**
     * @var boolean
     */
    private $isAboriginal;

    /**
     * @var boolean
     */
    private $hasDoneCrime;

    /**
     * @var string
     */
    private $crimeDetails;


    /**
     * @var \BackendBundle\Entity\Employee
     */
    private $employee;


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
     * Set isIslander
     *
     * @param boolean $isIslander
     *
     * @return UserDeclearation
     */
    public function setIsIslander($isIslander)
    {
        $this->isIslander = $isIslander;

        return $this;
    }

    /**
     * Get isIslander
     *
     * @return boolean
     */
    public function getIsIslander()
    {
        return $this->isIslander;
    }

    /**
     * Set isAboriginal
     *
     * @param boolean $isAboriginal
     *
     * @return UserDeclearation
     */
    public function setIsAboriginal($isAboriginal)
    {
        $this->isAboriginal = $isAboriginal;

        return $this;
    }

    /**
     * Get isAboriginal
     *
     * @return boolean
     */
    public function getIsAboriginal()
    {
        return $this->isAboriginal;
    }

    /**
     * Set hasDoneCrime
     *
     * @param boolean $hasDoneCrime
     *
     * @return UserDeclearation
     */
    public function setHasDoneCrime($hasDoneCrime)
    {
        $this->hasDoneCrime = $hasDoneCrime;

        return $this;
    }

    /**
     * Get hasDoneCrime
     *
     * @return boolean
     */
    public function getHasDoneCrime()
    {
        return $this->hasDoneCrime;
    }

    /**
     * Set crimeDetails
     *
     * @param string $crimeDetails
     *
     * @return UserDeclearation
     */
    public function setCrimeDetails($crimeDetails)
    {
        $this->crimeDetails = $crimeDetails;

        return $this;
    }

    /**
     * Get crimeDetails
     *
     * @return string
     */
    public function getCrimeDetails()
    {
        return $this->crimeDetails;
    }


    /**
     * Set user
     *
     * @param \BackendBundle\Entity\Employee $employee
     *
     * @return UserDeclearation
     */
    public function setEmployee(\BackendBundle\Entity\Employee $employee = null)
    {
        $this->employee = $employee;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BackendBundle\Entity\User
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    public function toArray() {
        return get_object_vars($this);
    }
}

