<?php

namespace BackendBundle\Entity;

/**
 * EmployeeSkillCompetencyDocument
 */
class EmployeeSkillCompetencyDocument extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $issueDate;

    /**
     * @var \DateTime
     */
    private $expiryDate;


    private $description;

    /**
     * @var \BackendBundle\Entity\Employee
     */
    private $employee;

    /**
     * @var \BackendBundle\Entity\SkillCompetencyList
     */
    private $skillCompetencyList;

    /**
     * @var \BackendBundle\Entity\EmployeeSkillDocument
     */
    private $documents;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->documents = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getDocuments() {
        return $this->documents;
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
     * Set issueDate
     *
     * @param \DateTime $issueDate
     *
     * @return EmployeeSkillCompetencyDocument
     */
    public function setIssueDate($issueDate)
    {
        $this->issueDate = $issueDate;

        return $this;
    }

    /**
     * Get issueDate
     *
     * @return \DateTime
     */
    public function getIssueDate()
    {
        return $this->issueDate;
    }

    /**
     * Set expiryDate
     *
     * @param \DateTime $expiryDate
     *
     * @return EmployeeSkillCompetencyDocument
     */
    public function setExpiryDate($expiryDate)
    {
        $this->expiryDate = $expiryDate;

        return $this;
    }

    /**
     * Get expiryDate
     *
     * @return \DateTime
     */
    public function getExpiryDate()
    {
        return $this->expiryDate;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * Set employee
     *
     * @param \BackendBundle\Entity\Employee $employee
     *
     * @return EmployeeSkillCompetencyDocument
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
     * Set skillCompetencyList
     *
     * @param \BackendBundle\Entity\SkillCompetencyList $skillCompetencyList
     *
     * @return EmployeeSkillCompetencyDocument
     */
    public function setSkillCompetencyList(\BackendBundle\Entity\SkillCompetencyList $skillCompetencyList = null)
    {
        $this->skillCompetencyList = $skillCompetencyList;

        return $this;
    }

    /**
     * Get skillCompetencyList
     *
     * @return \BackendBundle\Entity\SkillCompetencyList
     */
    public function getSkillCompetencyList()
    {
        return $this->skillCompetencyList;
    }



    public function toArray() {
        return get_object_vars($this);
    }
}

