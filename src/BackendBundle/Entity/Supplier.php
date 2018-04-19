<?php

namespace BackendBundle\Entity;

/**
 * Supplier
 */
class Supplier extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $companyName;

    /**
     * @var string
     */
    private $companyAbnNo;

    /**
     * @var string
     */
    private $landlineNo;

    /**
     * @var string
     */
    private $mobileNo;

    /**
     * @var string
     */
    private $accountPayableNo;

    /**
     * @var string
     */
    private $accountPayableEmail;

    /**
     * @var string
     */
    private $accountPayablePersonDetails;

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
    private $chargeRates;

    /**
     * @var string
     */
    private $inductions;

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
     * Set companyName
     *
     * @param string $companyName
     *
     * @return Supplier
     */
    public function setCompanyName($companyName)
    {
        $this->companyName = $companyName;

        return $this;
    }

    /**
     * Get companyName
     *
     * @return string
     */
    public function getCompanyName()
    {
        return $this->companyName;
    }

    /**
     * Set companyAbnNo
     *
     * @param string $companyAbnNo
     *
     * @return Supplier
     */
    public function setCompanyAbnNo($companyAbnNo)
    {
        $this->companyAbnNo = $companyAbnNo;

        return $this;
    }

    /**
     * Get companyAbnNo
     *
     * @return string
     */
    public function getCompanyAbnNo()
    {
        return $this->companyAbnNo;
    }

    /**
     * Set landlineNo
     *
     * @param string $landlineNo
     *
     * @return Supplier
     */
    public function setLandlineNo($landlineNo)
    {
        $this->landlineNo = $landlineNo;

        return $this;
    }

    /**
     * Get landlineNo
     *
     * @return string
     */
    public function getLandlineNo()
    {
        return $this->landlineNo;
    }

    /**
     * Set mobileNo
     *
     * @param string $mobileNo
     *
     * @return Supplier
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
     * Set accountPayableNo
     *
     * @param string $accountPayableNo
     *
     * @return Supplier
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
     * Set accountPayableEmail
     *
     * @param string $accountPayableEmail
     *
     * @return Supplier
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
     * Set accountPayablePersonDetails
     *
     * @param string $accountPayablePersonDetails
     *
     * @return Supplier
     */
    public function setAccountPayablePersonDetails($accountPayablePersonDetails)
    {
        $this->accountPayablePersonDetails = $accountPayablePersonDetails;

        return $this;
    }

    /**
     * Get accountPayablePersonDetails
     *
     * @return string
     */
    public function getAccountPayablePersonDetails()
    {
        return $this->accountPayablePersonDetails;
    }

    /**
     * Set creditLimit
     *
     * @param string $creditLimit
     *
     * @return Supplier
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
     * @return Supplier
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
     * @return Supplier
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
     * Set chargeRates
     *
     * @param string $chargeRates
     *
     * @return Supplier
     */
    public function setChargeRates($chargeRates)
    {
        $this->chargeRates = $chargeRates;

        return $this;
    }

    /**
     * Get chargeRates
     *
     * @return string
     */
    public function getChargeRates()
    {
        return $this->chargeRates;
    }

    /**
     * Set inductions
     *
     * @param string $inductions
     *
     * @return Supplier
     */
    public function setInductions($inductions)
    {
        $this->inductions = $inductions;

        return $this;
    }

    /**
     * Get inductions
     *
     * @return string
     */
    public function getInductions()
    {
        return $this->inductions;
    }

    /**
     * Set extra
     *
     * @param string $extra
     *
     * @return Supplier
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
     * @return Supplier
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

