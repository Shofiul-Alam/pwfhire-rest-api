<?php

namespace BackendBundle\Entity;

/**
 * AllocatedInduction
 */
class AllocatedInduction extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \BackendBundle\Entity\Induction
     */
    private $induction;

    /**
     * @var \BackendBundle\Entity\Project
     */
    private $project;


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
     * Set induction
     *
     * @param \BackendBundle\Entity\Induction $induction
     *
     * @return AllocatedInduction
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

    /**
     * Set project
     *
     * @param \BackendBundle\Entity\Project $project
     *
     * @return AllocatedInduction
     */
    public function setProject(\BackendBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Get project
     *
     * @return \BackendBundle\Entity\Project
     */
    public function getProject()
    {
        return $this->project;
    }

    public function toArray() {
        return get_object_vars($this);
    }
}

