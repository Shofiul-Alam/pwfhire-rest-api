<?php

namespace BackendBundle\Entity;

/**
 * AllocatedContact
 */
class AllocatedContact extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \BackendBundle\Entity\Client
     */
    private $client;

    /**
     * @var \BackendBundle\Entity\Contact
     */
    private $contact;

    /**
     * @var \BackendBundle\Entity\Project
     */
    private $project;

    /**
     * @var \BackendBundle\Entity\Order
     */
    private $order;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->encodeId($this->id);
    }

    public function getOrigId() {
        return $this->id;
    }

    /**
     * Set client
     *
     * @param \BackendBundle\Entity\Client $client
     *
     * @return AllocatedContact
     */
    public function setClient(\BackendBundle\Entity\Client $client = null)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \BackendBundle\Entity\Client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set contact
     *
     * @param \BackendBundle\Entity\Contact $contact
     *
     * @return AllocatedContact
     */
    public function setContact(\BackendBundle\Entity\Contact $contact = null)
    {
        $this->contact = $contact;

        return $this;
    }

    /**
     * Get contact
     *
     * @return \BackendBundle\Entity\Contact
     */
    public function getContact()
    {
        return $this->contact;
    }

    /**
     * Set project
     *
     * @param \BackendBundle\Entity\Project $project
     *
     * @return AllocatedContact
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

    /**
     * @return Order
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param Order $order
     */
    public function setOrder(Order $order)
    {
        $this->order = $order;
    }

    public function toArray() {
        return get_object_vars($this);
    }
}

