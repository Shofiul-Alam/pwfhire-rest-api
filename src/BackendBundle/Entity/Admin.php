<?php

namespace BackendBundle\Entity;

/**
 * Admin
 */
class Admin extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var DateTime
     */
    private $dob;

    /**
     * @return DateTime
     */
    public function getDob()
    {
        return $this->dob;
    }

    /**
     * @param DateTime $dob
     */
    public function setDob($dob)
    {
        $this->dob = $dob;
    }
    /**
     * @var string
     */
    private $abnNo;

    /**
     * @var string
     */
    private $mobileNo;

    /**
     * @var string
     */
    private $accountPayableEmail;

    /**
     * @var string
     */
    private $accountPayableNo;

    /**
     * @var string
     */
    private $creditLimit;

    /**
     * @var \DateTime
     */
    private $invoiceDueDate;

    /**
     * @var string
     */
    private $comments;

    /**
     * @var string
     */
    private $extra;

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
     * Set abnNo
     *
     * @param string $abnNo
     *
     * @return Admin
     */
    public function setAbnNo($abnNo)
    {
        $this->abnNo = $abnNo;

        return $this;
    }

    /**
     * Get abnNo
     *
     * @return string
     */
    public function getAbnNo()
    {
        return $this->abnNo;
    }

    /**
     * Set mobileNo
     *
     * @param string $mobileNo
     *
     * @return Admin
     */
    public function setMobileNo($mobileNo)
    {
        $this->mobileNo = $mobileNo;

        return $this;
    }

    /**
     * Get mobileNo
     *
     * @return string
     */
    public function getMobileNo()
    {
        return $this->mobileNo;
    }

    /**
     * Set accountPayableEmail
     *
     * @param string $accountPayableEmail
     *
     * @return Admin
     */
    public function setAccountPayableEmail($accountPayableEmail)
    {
        $this->accountPayableEmail = $accountPayableEmail;

        return $this;
    }

    /**
     * Get accountPayableEmail
     *
     * @return string
     */
    public function getAccountPayableEmail()
    {
        return $this->accountPayableEmail;
    }

    /**
     * Set accountPayableNo
     *
     * @param string $accountPayableNo
     *
     * @return Admin
     */
    public function setAccountPayableNo($accountPayableNo)
    {
        $this->accountPayableNo = $accountPayableNo;

        return $this;
    }

    /**
     * Get accountPayableNo
     *
     * @return string
     */
    public function getAccountPayableNo()
    {
        return $this->accountPayableNo;
    }

    /**
     * Set creditLimit
     *
     * @param string $creditLimit
     *
     * @return Admin
     */
    public function setCreditLimit($creditLimit)
    {
        $this->creditLimit = $creditLimit;

        return $this;
    }

    /**
     * Get creditLimit
     *
     * @return string
     */
    public function getCreditLimit()
    {
        return $this->creditLimit;
    }

    /**
     * Set invoiceDueDate
     *
     * @param \DateTime $invoiceDueDate
     *
     * @return Admin
     */
    public function setInvoiceDueDate($invoiceDueDate)
    {
        $this->invoiceDueDate = $invoiceDueDate;

        return $this;
    }

    /**
     * Get invoiceDueDate
     *
     * @return \DateTime
     */
    public function getInvoiceDueDate()
    {
        return $this->invoiceDueDate;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return Admin
     */
    public function setComments($comments)
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * Get comments
     *
     * @return string
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Set extra
     *
     * @param string $extra
     *
     * @return Admin
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get extra
     *
     * @return string
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * Set user
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return Admin
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
