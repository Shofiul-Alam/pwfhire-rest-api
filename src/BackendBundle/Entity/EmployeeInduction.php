<?php

namespace BackendBundle\Entity;

/**
 * EmployeeInduction
 */
class EmployeeInduction extends AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \DateTime
     */
    private $uploadedDate;

    /**
     * @var \DateTime
     */
    private $modifiedDate;

    /**
     * @var \BackendBundle\Entity\Employee
     */
    private $employee;

    /**
     * @var \BackendBundle\Entity\EmployeeSkillDocument
     */
    private $employeeSkillDocument;

    /**
     * @var \BackendBundle\Entity\Induction
     */
    private $induction;


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
     * Set description
     *
     * @param string $description
     *
     * @return EmployeeInduction
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set uploadedDate
     *
     * @param \DateTime $uploadedDate
     *
     * @return EmployeeInduction
     */
    public function setUploadedDate($uploadedDate)
    {
        $this->uploadedDate = $uploadedDate;

        return $this;
    }

    /**
     * Get uploadedDate
     *
     * @return \DateTime
     */
    public function getUploadedDate()
    {
        return $this->uploadedDate;
    }

    /**
     * Set modifiedDate
     *
     * @param \DateTime $modifiedDate
     *
     * @return EmployeeInduction
     */
    public function setModifiedDate($modifiedDate)
    {
        $this->modifiedDate = $modifiedDate;

        return $this;
    }

    /**
     * Get modifiedDate
     *
     * @return \DateTime
     */
    public function getModifiedDate()
    {
        return $this->modifiedDate;
    }

    /**
     * Set employee
     *
     * @param \BackendBundle\Entity\Employee $employee
     *
     * @return EmployeeInduction
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
     * Set employeeSkillDocument
     *
     * @param \BackendBundle\Entity\EmployeeSkillDocument $employeeSkillDocument
     *
     * @return EmployeeInduction
     */
    public function setEmployeeSkillDocument(\BackendBundle\Entity\EmployeeSkillDocument $employeeSkillDocument = null)
    {
        $this->employeeSkillDocument = $employeeSkillDocument;

        return $this;
    }

    /**
     * Get employeeSkillDocument
     *
     * @return \BackendBundle\Entity\EmployeeSkillDocument
     */
    public function getEmployeeSkillDocument()
    {
        return $this->employeeSkillDocument;
    }

    /**
     * Set induction
     *
     * @param \BackendBundle\Entity\Induction $induction
     *
     * @return EmployeeInduction
     */
    public function setInduction(\BackendBundle\Entity\Induction $induction = null)
    {
        $this->induction = $induction;

        return $this;
    }

    /**
     * Get induction
     *
     * @return \BackendBundle\Entity\Induction
     */
    public function getInduction()
    {
        return $this->induction;
    }

    public function toArray() {
        return get_object_vars($this);
    }
}

