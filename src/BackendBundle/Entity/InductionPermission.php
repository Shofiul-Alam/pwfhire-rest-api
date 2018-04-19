<?php

namespace BackendBundle\Entity;

/**
 * InductionPermission
 */
class InductionPermission extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \BackendBundle\Entity\Employee
     */
    private $employee;

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
     * Set employee
     *
     * @param \BackendBundle\Entity\Employee $employee
     *
     * @return InductionPermission
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
     * Set induction
     *
     * @param \BackendBundle\Entity\Induction $induction
     *
     * @return InductionPermission
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

