<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Client
 */
class Client extends \BackendBundle\Entity\AEntity
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
    private $contactPerson;

    /**
     * @var string
     */
    private $companyAbnNo;

    /**
     * @var string
     */
    private $companyAcn;

    /**
     * @var string
     */
    private $companyTfn;

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
     * @var boolean
     */
    private $archived = false;

    /**
     * @var \BackendBundle\Entity\User
     */
    private $user;

    public $firstTime = true;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $allocatedContact;

    public $splicedAllocatedContact;

    function __construct()
    {
        $this->allocatedContact = new \Doctrine\Common\Collections\ArrayCollection();
        $this->splicedAllocatedContact = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAllocatedContact()
    {
        return $this->allocatedContact;
    }

    /**
     * @param \BackendBundle\Entity\AllocatedContact
     */
    public function replaceAllocatedContact(AllocatedContact $contact)
    {
        if(count($this->splicedAllocatedContact) == 0 && $this->firstTime) {
            if(count($this->allocatedContact) != 0) {
                $this->removeAllocatedContact($contact);
            }
            $removeArr = $this->getAllocatedContact();
            $this->splicedAllocatedContact = new ArrayCollection();

            if(count($this->getAllocatedContact()) > 0) {

                foreach ($removeArr as $r) {

                    $this->splicedAllocatedContact->add($r);
                }
            }
            $contact->setClient($this);
            $this->allocatedContact->add($contact);
            $this->firstTime = false;
            $this->splicedAllocatedContact->removeElement($contact);

        } else {
            $this->splicedAllocatedContact->removeElement($contact);
            $this->removeAllocatedContact($contact);
            $this->allocatedContact->add($contact);
        }

    }
    public function getRemoveArr() {
        $arr = new ArrayCollection();
        foreach ($this->allocatedContact as $contact) {
                if($contact->getOrigId() != 0 || $contact->getOrigId() != null) {
                    $arr->add($contact);
                }
        }
        return $arr;
    }


//    public function emptySplicedAllocatedContact() {
//        if(count($this->splicedAllocatedContact) > 0) {
//            foreach($this->splicedAllocatedContact as $splice) {
//                $this->removeAllocatedContact($splice);
//            }
//            $this->splicedAllocatedContact = null;
//        }
//
//    }

    public function addAllocatedContact(AllocatedContact $contact)
    {
            $this->allocatedContact[] = $contact;
    }


    public function removeAllocatedContact(AllocatedContact $contact)
    {
        $this->allocatedContact->removeElement($contact);
    }

    public function unsetAllocatedContact()
    {
        $this->allocatedContact = [];
    }
    public function getAllContacts(){
        $arr = array();

        if($this->allocatedContact != null && count($this->allocatedContact) >0) {
            foreach ($this->allocatedContact as $allocContact) {
                $a['id'] = $allocContact->getId();
                $a['contact'] = $allocContact->getContact();
                array_push($arr, $a);
            }
        }

        return $arr;

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
     * Set companyName
     *
     * @param string $companyName
     *
     * @return Client
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
     * Set contactPerson
     *
     * @param string $contactPerson
     *
     * @return Client
     */
    public function setContactPerson($contactPerson)
    {
        $this->contactPerson = $contactPerson;

        return $this;
    }

    /**
     * Get contactPerson
     *
     * @return string
     */
    public function getContactPerson()
    {
        return $this->contactPerson;
    }

    /**
     * Set companyAbnNo
     *
     * @param string $companyAbnNo
     *
     * @return Client
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
     * Set companyAcn
     *
     * @param string $companyAcn
     *
     * @return Client
     */
    public function setCompanyAcn($companyAcn)
    {
        $this->companyAcn = $companyAcn;

        return $this;
    }

    /**
     * Get companyAcn
     *
     * @return string
     */
    public function getCompanyAcn()
    {
        return $this->companyAcn;
    }

    /**
     * Set companyTfn
     *
     * @param string $companyTfn
     *
     * @return Client
     */
    public function setCompanyTfn($companyTfn)
    {
        $this->companyTfn = $companyTfn;

        return $this;
    }

    /**
     * Get companyTfn
     *
     * @return string
     */
    public function getCompanyTfn()
    {
        return $this->companyTfn;
    }

    /**
     * Set landlineNo
     *
     * @param string $landlineNo
     *
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return bool
     */
    public function isArchived()
    {
        return $this->archived;
    }

    /**
     * @param bool $archived
     */
    public function setArchived(bool $archived)
    {
        $this->archived = $archived;
    }

    /**
     * @return bool
     */
    public function isFirstTime()
    {
        return $this->firstTime;
    }

    /**
     * @param bool $firstTime
     */
    public function setFirstTime($firstTime)
    {
        $this->firstTime = $firstTime;
    }



    /**
     * Set user
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return Client
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

