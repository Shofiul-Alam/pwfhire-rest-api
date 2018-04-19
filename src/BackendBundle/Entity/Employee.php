<?php

namespace BackendBundle\Entity;


/**
 * Employee
 */

class Employee extends AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $dob;

    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $nationality;

    /**
     * @var string
     */
    private $emergencyContactName;

    /**
     * @var string
     */
    private $emergencyContactMobile;

    /**
     * @var string
     */
    private $bankName;

    /**
     * @var string
     */
    private $bankBsb;

    /**
     * @var string
     */
    private $bankAccountNo;

    /**
     * @var string
     */
    private $tfnNo;

    /**
     * @var string
     */
    private $abnNo;

    /**
     * @var string
     */
    private $superannuationName;

    /**
     * @var string
     */
    private $superannuationNo;

    /**
     * @var boolean
     */
    private $approved;

    /**
     * @var double
     */
    private $lattitude;

    /**
     * @var double
     */
    private $longitude;

    /**
     * @var \BackendBundle\Entity\User
     */
    private $user;

    /**
     * @var \BackendBundle\Entity\EmployeeCategory
     */
    private $employeeCategory;


    /**
     * @var \BackendBundle\Entity\EmployeeOrderCategory
     */
    private $employeeOrderCategory;


    /**
     * @var \BackendBundle\Entity\UserDeclearation
     */
    private $userDeclearation;


    private $archived = false;

    /**
     * @return bool
     */
    public function isArchived()
    {
        return $this->archived;
    }

    /**
     * @param bool $isDeleted
     */
    public function setArchived(bool $archived)
    {
        $this->archived = $archived;
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
     * Set dob
     *
     * @param \DateTime $dob
     *
     * @return Employee
     */
    public function setDob($dob)
    {
        $this->dob = $dob;

        return $this;
    }

    /**
     * Get dob
     *
     * @return \DateTime
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Employee
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set nationality
     *
     * @param string $nationality
     *
     * @return Employee
     */
    public function setNationality($nationality)
    {
        $this->nationality = $nationality;

        return $this;
    }

    /**
     * Get nationality
     *
     * @return string
     */
    public function getNationality()
    {
        return $this->nationality;
    }

    /**
     * Set emergencyContactName
     *
     * @param string $emergencyContactName
     *
     * @return Employee
     */
    public function setEmergencyContactName($emergencyContactName)
    {
        $this->emergencyContactName = $emergencyContactName;

        return $this;
    }

    /**
     * Get emergencyContactName
     *
     * @return string
     */
    public function getEmergencyContactName()
    {
        return $this->emergencyContactName;
    }

    /**
     * Set emergencyContactMobile
     *
     * @param string $emergencyContactMobile
     *
     * @return Employee
     */
    public function setEmergencyContactMobile($emergencyContactMobile)
    {
        $this->emergencyContactMobile = $emergencyContactMobile;

        return $this;
    }

    /**
     * Get emergencyContactMobile
     *
     * @return string
     */
    public function getEmergencyContactMobile()
    {
        return $this->emergencyContactMobile;
    }

    /**
     * Set bankName
     *
     * @param string $bankName
     *
     * @return Employee
     */
    public function setBankName($bankName)
    {
        $this->bankName = $bankName;

        return $this;
    }

    /**
     * Get bankName
     *
     * @return string
     */
    public function getBankName()
    {
        return $this->bankName;
    }

    /**
     * Set bankBsb
     *
     * @param string $bankBsb
     *
     * @return Employee
     */
    public function setBankBsb($bankBsb)
    {
        $this->bankBsb = $bankBsb;

        return $this;
    }

    /**
     * Get bankBsb
     *
     * @return string
     */
    public function getBankBsb()
    {
        return $this->bankBsb;
    }

    /**
     * Set bankAccountNo
     *
     * @param string $bankAccountNo
     *
     * @return Employee
     */
    public function setBankAccountNo($bankAccountNo)
    {
        $this->bankAccountNo = $bankAccountNo;

        return $this;
    }

    /**
     * Get bankAccountNo
     *
     * @return string
     */
    public function getBankAccountNo()
    {
        return $this->bankAccountNo;
    }

    /**
     * Set tfnNo
     *
     * @param string $tfnNo
     *
     * @return Employee
     */
    public function setTfnNo($tfnNo)
    {
        $this->tfnNo = $tfnNo;

        return $this;
    }

    /**
     * Get tfnNo
     *
     * @return string
     */
    public function getTfnNo()
    {
        return $this->tfnNo;
    }

    /**
     * Set abnNo
     *
     * @param string $abnNo
     *
     * @return Employee
     */
    public function setAbnNo($abnNo)
    {
        $this->abnNo = $abnNo;

        return $this;
    }

    /**
     * Get abnNo
     *
     * @return string
     */
    public function getAbnNo()
    {
        return $this->abnNo;
    }

    /**
     * Set superannuationName
     *
     * @param string $superannuationName
     *
     * @return Employee
     */
    public function setSuperannuationName($superannuationName)
    {
        $this->superannuationName = $superannuationName;

        return $this;
    }

    /**
     * Get superannuationName
     *
     * @return string
     */
    public function getSuperannuationName()
    {
        return $this->superannuationName;
    }

    /**
     * Set superannuationNo
     *
     * @param string $superannuationNo
     *
     * @return Employee
     */
    public function setSuperannuationNo($superannuationNo)
    {
        $this->superannuationNo = $superannuationNo;

        return $this;
    }

    /**
     * Get superannuationNo
     *
     * @return string
     */
    public function getSuperannuationNo()
    {
        return $this->superannuationNo;
    }

    /**
     * Set approved
     *
     * @param boolean $approved
     *
     * @return Employee
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
     * @return float
     */
    public function getLattitude()
    {
        return $this->lattitude;
    }

    /**
     * @param float $lattitude
     */
    public function setLattitude(float $lattitude)
    {
        $this->lattitude = $lattitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude)
    {
        $this->longitude = $longitude;
    }



    /**
     * Set user
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return Employee
     */
    public function setUser(\BackendBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \BackendBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set employeeCategory
     *
     * @param \BackendBundle\Entity\EmployeeCategory $employeeCategory
     *
     * @return Employee
     */
    public function setEmployeeCategory(\BackendBundle\Entity\EmployeeCategory $employeeCategory = null)
    {
        $this->employeeCategory = $employeeCategory;

        return $this;
    }

    /**
     * Get employeeCategory
     *
     * @return \BackendBundle\Entity\EmployeeCategory
     */
    public function getEmployeeCategory()
    {
        return $this->employeeCategory;
    }


    /**
     * Set employeeOrderCategory
     *
     * @param \BackendBundle\Entity\EmployeeOrderCategory $employeeOrderCategory
     *
     * @return Employee
     */
    public function setEmployeeOrderCategory(\BackendBundle\Entity\EmployeeOrderCategory $employeeOrderCategory = null)
    {
        $this->employeeOrderCategory = $employeeOrderCategory;

        return $this;
    }

    /**
     * Get employeeOrderCategory
     *
     * @return \BackendBundle\Entity\EmployeeOrderCategory
     */
    public function getEmployeeOrderCategory()
    {
        return $this->employeeOrderCategory;
    }

    /**
     * @return UserDeclearation
     */
    public function getUserDeclearation()
    {
        return $this->userDeclearation;
    }

    /**
     * @param UserDeclearation $userDeclearation
     */
    public function setUserDeclearation(UserDeclearation $userDeclearation = null)
    {
        $this->userDeclearation = $userDeclearation;
    }

    public function toArray() {
        return get_object_vars($this);
    }
}
