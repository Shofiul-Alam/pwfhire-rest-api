<?php

namespace BackendBundle\Entity;

/**
 * Contact
 */
class Contact extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $emargencyContact;

    /**
     * @var string
     */
    private $landPhone;

    /**
     * @var string
     */
    private $address;

    /**
     * @var \BackendBundle\Entity\User
     */
    private $user;


    private $text = "";

    function __construct()
    {
        $this->text = $this->emargencyContact . '(' . $this->landPhone . ')';
    }

    /**
     * @return string
     */
    public function getText()
    {
        return $this->text = $this->getEmargencyContact() . '(' . $this->getLandPhone() . ')';
    }

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }


    private function getOrigId() {
        return $this->id;
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
     * Set emargencyContact
     *
     * @param string $emargencyContact
     *
     * @return Contact
     */
    public function setEmargencyContact($emargencyContact)
    {
        $this->emargencyContact = $emargencyContact;

        return $this;
    }

    /**
     * Get emargencyContact
     *
     * @return string
     */
    public function getEmargencyContact()
    {
        return $this->emargencyContact;
    }

    /**
     * Set landPhone
     *
     * @param string $landPhone
     *
     * @return Contact
     */
    public function setLandPhone($landPhone)
    {
        $this->landPhone = $landPhone;

        return $this;
    }

    /**
     * Get landPhone
     *
     * @return string
     */
    public function getLandPhone()
    {
        return $this->landPhone;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return Contact
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
     * Set user
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return Contact
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




    public function toArray() {
        return get_object_vars($this);
    }
}

