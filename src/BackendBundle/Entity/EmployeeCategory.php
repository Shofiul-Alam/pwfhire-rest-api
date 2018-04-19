<?php

namespace BackendBundle\Entity;

/**
 * EmployeeCategory
 */
class EmployeeCategory extends AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $categoryName;

    /**
     * @var string
     */
    private $pricePerHour;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $employee;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->employee = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set categoryName
     *
     * @param string $categoryName
     *
     * @return EmployeeCategory
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * Get categoryName
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * Set pricePerHour
     *
     * @param string $pricePerHour
     *
     * @return EmployeeCategory
     */
    public function setPricePerHour($pricePerHour)
    {
        $this->pricePerHour = $pricePerHour;

        return $this;
    }

    /**
     * Get pricePerHour
     *
     * @return string
     */
    public function getPricePerHour()
    {
        return $this->pricePerHour;
    }

    /**
     * Add employee
     *
     * @param \BackendBundle\Entity\Employee $employee
     *
     * @return EmployeeCategory
     */
    public function addEmployee(\BackendBundle\Entity\Employee $employee)
    {
        $this->employee[] = $employee;

        return $this;
    }

    /**
     * Remove employee
     *
     * @param \BackendBundle\Entity\Employee $employee
     */
    public function removeEmployee(\BackendBundle\Entity\Employee $employee)
    {
        $this->employee->removeElement($employee);
    }

    /**
     * Get employee
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEmployee()
    {
        return $this->employee;
    }

    public function toArray() {
        return get_object_vars($this);
    }
}
