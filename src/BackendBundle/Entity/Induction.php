<?php

namespace BackendBundle\Entity;

/**
 * Induction
 */
class Induction extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var \BackendBundle\Entity\Form
     */
    private $form;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $job;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->job = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     *
     * @return Induction
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set form
     *
     * @param \BackendBundle\Entity\Form $form
     *
     * @return Induction
     */
    public function setForm(\BackendBundle\Entity\Form $form = null)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * Get form
     *
     * @return \BackendBundle\Entity\Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * Add job
     *
     * @param \BackendBundle\Entity\Job $job
     *
     * @return Induction
     */
    public function addJob(\BackendBundle\Entity\Job $job)
    {
        $this->job[] = $job;

        return $this;
    }

    /**
     * Remove job
     *
     * @param \BackendBundle\Entity\Job $job
     */
    public function removeJob(\BackendBundle\Entity\Job $job)
    {
        $this->job->removeElement($job);
    }

    /**
     * Get job
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getJob()
    {
        return $this->job;
    }
    public function toArray() {
        return get_object_vars($this);
    }
}

