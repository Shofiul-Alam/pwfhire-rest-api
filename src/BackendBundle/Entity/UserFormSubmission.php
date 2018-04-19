<?php

namespace BackendBundle\Entity;

/**
 * UserFormSubmission
 */
class UserFormSubmission extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \BackendBundle\Entity\Form
     */
    private $form;

    /**
     * @var \BackendBundle\Entity\User
     */
    private $user;


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
     * Set form
     *
     * @param \BackendBundle\Entity\Form $form
     *
     * @return UserFormSubmission
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
     * Set employee
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return UserFormSubmission
     */
    public function setUser(\BackendBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get employee
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

