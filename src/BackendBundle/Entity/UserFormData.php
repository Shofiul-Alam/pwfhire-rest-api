<?php

namespace BackendBundle\Entity;

/**
 * UserFormData
 */
class UserFormData extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var integer
     */
    private $valueArrId;



    /**
     * @var integer
     */
    private $fieldId;


    /**
     * @var \BackendBundle\Entity\Form
     */
    private $form;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add User
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return UserFormData
     */
    public function addUser(\BackendBundle\Entity\user $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove User
     *
     * @param \BackendBundle\Entity\User $userFormData
     */
    public function removeUser(\BackendBundle\Entity\user $user)
    {
        $this->users->removeElement($user);
    }

    public function getUsers() {
        return $this->users;
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
     * Set value
     *
     * @param string $value
     *
     * @return UserFormData
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getFieldId()
    {
        return $this->fieldId;
    }

    /**
     * @param int $fieldId
     */
    public function setFieldId(int $fieldId)
    {
        $this->fieldId = $fieldId;
    }

    /**
     * Set form
     *
     * @param \BackendBundle\Entity\Form $form
     *
     * @return UserFormData
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
     * @return int
     */
    public function getValueArrId()
    {
        return $this->valueArrId;
    }

    /**
     * @param int $valueArr_id
     */
    public function setValueArrId(int $valueArrId)
    {
        $this->valueArrId = $valueArrId;
    }


    public function toArray() {
        return get_object_vars($this);
    }
}

