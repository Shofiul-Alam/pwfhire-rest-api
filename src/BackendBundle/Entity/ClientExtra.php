<?php

namespace BackendBundle\Entity;

/**
 * ClientExtra
 */
class ClientExtra extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $description;

    /**
     * @var \BackendBundle\Entity\Client
     */
    private $client;


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
     * Set title
     *
     * @param string $title
     *
     * @return ClientExtra
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ClientExtra
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
     * Set client
     *
     * @param \BackendBundle\Entity\Client $client
     *
     * @return ClientExtra
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

    public function toArray() {
        return get_object_vars($this);
    }
}
