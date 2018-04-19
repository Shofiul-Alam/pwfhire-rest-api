<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Order
 */
class Order extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $orderTitle;

    /**
     * @var \DateTime
     */
    private $startDate;

    /**
     * @var \DateTime
     */
    private $endDate;

    /**
     * @var string
     */
    private $orderStatus;

    /**
     * @var string
     */
    private $orderDescription;

    /**
     * @var string
     */
    private $contactDetails;

    /**
     * @var string
     */
    private $comments;

    /**
     * @var boolean
     */
    private $archived;


    /**
     * @var string
     */
    private $taskReferenceId;

    /**
     * @var \BackendBundle\Entity\Project
     */
    private $project;


    private $allocatedContact;

    public $splicedAllocatedContact;

    public $firstTime = true;

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
            $contact->setOrder($this);
            $this->allocatedContact->add($contact);
            $this->firstTime = false;
            $this->splicedAllocatedContact->removeElement($contact);

        } else {
            $this->splicedAllocatedContact->removeElement($contact);
            $this->removeAllocatedContact($contact);
            $this->allocatedContact->add($contact);
        }

    }

    public function addAllocatedContact(AllocatedContact $contact)
    {
        if(count($this->allocatedContact)>0 && count($this->splicedAllocatedContact)==0 && $this->splicedAllocatedContact != null) {
            $removeArr = $this->allocatedContact;
            $this->splicedAllocatedContact = $removeArr;
        }
       return $this->allocatedContact->add($contact);
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
     * @return string
     */
    public function getOrderTitle()
    {
        return $this->orderTitle;
    }

    /**
     * @param string $orderTitle
     */
    public function setOrderTitle(string $orderTitle)
    {
        $this->orderTitle = $orderTitle;
    }



    /**
     * Set startDate
     *
     * @param \DateTime $startDate
     *
     * @return Order
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;

        return $this;
    }

    /**
     * Get startDate
     *
     * @return \DateTime
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Set endDate
     *
     * @param \DateTime $endDate
     *
     * @return Order
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;

        return $this;
    }

    /**
     * Get endDate
     *
     * @return \DateTime
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Set orderStatus
     *
     * @param string $orderStatus
     *
     * @return Order
     */
    public function setOrderStatus($orderStatus)
    {
        $this->orderStatus = $orderStatus;

        return $this;
    }

    /**
     * Get orderStatus
     *
     * @return string
     */
    public function getOrderStatus()
    {
        return $this->orderStatus;
    }

    /**
     * Set orderDescription
     *
     * @param string $orderDescription
     *
     * @return Order
     */
    public function setOrderDescription($orderDescription)
    {
        $this->orderDescription = $orderDescription;

        return $this;
    }

    /**
     * Get orderDescription
     *
     * @return string
     */
    public function getOrderDescription()
    {
        return $this->orderDescription;
    }

    /**
     * Set contactDetails
     *
     * @param string $contactDetails
     *
     * @return Order
     */
    public function setContactDetails($contactDetails)
    {
        $this->contactDetails = $contactDetails;

        return $this;
    }

    /**
     * Get contactDetails
     *
     * @return string
     */
    public function getContactDetails()
    {
        return $this->contactDetails;
    }

    /**
     * Set comments
     *
     * @param string $comments
     *
     * @return Order
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
     * Set taskReferenceId
     *
     * @param string $taskReferenceId
     *
     * @return Order
     */
    public function setTaskReferenceId($taskReferenceId)
    {
        $this->taskReferenceId = $taskReferenceId;

        return $this;
    }

    /**
     * Get taskReferenceId
     *
     * @return string
     */
    public function getTaskReferenceId()
    {
        return $this->taskReferenceId;
    }

    /**
     * Set project
     *
     * @param \BackendBundle\Entity\Project $project
     *
     * @return Order
     */
    public function setProject(\BackendBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
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

