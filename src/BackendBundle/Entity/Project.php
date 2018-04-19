<?php

namespace BackendBundle\Entity;
use BackendBundle\Entity\AllocatedSkillCompetency;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Project
 */
class Project extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $projectName;

    /**
     * @var string
     */
    private $projectAddress;

    /**
     * @var string
     */
    private $projectRatesRules;

    /**
     * @var string
     */
    private $projectInductions;

    /**
     * @var \BackendBundle\Entity\Client
     */
    private $client;
    /**
     * @var string
     */
    private $projectCreated;

    /**
     * @var string
     */
    private $projectUpdated;

    /**
     * @var string
     */
    private $porjectUpdatedBy;

    /**
     * @var boolean
     */
    private $archived;

    /**
     * @var double
     */
    private $lattitude;

    /**
     * @var double
     */
    private $longitude;


    private $allocatedContact;

    private $allocatedInduction;

    private $allocatedSkillCompetency;

    public $splicedAllocatedContact;
    public $splicedAllocatedSkillCompetency;
    public $splicedAllocatedInduction;

    public $firstTime = true;


    function __construct()
    {
        $this->allocatedContact = new \Doctrine\Common\Collections\ArrayCollection();
        $this->allocatedInduction = new \Doctrine\Common\Collections\ArrayCollection();
        $this->allocatedSkillCompetency = new \Doctrine\Common\Collections\ArrayCollection();
        $this->splicedAllocatedContact = new \Doctrine\Common\Collections\ArrayCollection();
        $this->splicedAllocatedSkillCompetency = new \Doctrine\Common\Collections\ArrayCollection();
        $this->splicedAllocatedInduction = new \Doctrine\Common\Collections\ArrayCollection();
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
            $contact->setProject($this);
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

    /** Allocated Skill Comptency Methods started from here  */

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAllocatedSkillCompetency()
    {
        return $this->allocatedSkillCompetency;
    }

    public function replaceAllocatedSkillCompetency(AllocatedSkillCompetency $skillCompetency)
    {
        if(count($this->splicedAllocatedSkillCompetency) == 0 && $this->firstTime) {
            if(count($this->allocatedSkillCompetency) != 0) {
                $this->removeAllocatedSkillCompetency($skillCompetency);
            }

            $removeArr = $this->getAllocatedSkillCompetency();
            if(count($removeArr) == 0) {
                $this->splicedAllocatedSkillCompetency = new ArrayCollection();
            } else {
                $this->splicedAllocatedSkillCompetency = $removeArr;
            }
            $skillCompetency->setProject($this);
            $this->allocatedSkillCompetency->add($skillCompetency);
        } else {
            $this->splicedAllocatedSkillCompetency->removeElement($skillCompetency);
            $this->removeAllocatedSkillCompetency($skillCompetency);
            $this->allocatedSkillCompetency->add($skillCompetency);
        }


    }

    public function addAllocatedSkillCompetency(AllocatedSkillCompetency $skillCompetency)
    {
        if(count($this->allocatedkillCompetency)>0 && count($this->splicedAllocatedSkillCompetency)==0 && $this->splicedAllocatedSkillCompetency != null) {
            $removeArr = $this->allocatedSkillCompetency;
            $this->splicedAllocatedSkillCompetency = $removeArr;
        }
        $this->allocatedSkillCompetency[] = $skillCompetency;
    }


    public function removeAllocatedSkillCompetency(AllocatedSkillCompetency $skillCompetency)
    {
        $this->allocatedSkillCompetency->removeElement($skillCompetency);
    }
    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAllocatedInduction()
    {
        return $this->allocatedInduction;
    }

    public function replaceAllocatedInduction(AllocatedInduction $induction)
    {
        if(count($this->splicedAllocatedInduction) == 0 && $this->firstTime) {
            if(count($this->allocatedInduction) != 0) {
                $this->removeAllocatedInduction($induction);
            }

            $removeArr = $this->getAllocatedInduction();
            $this->splicedAllocatedInduction = new ArrayCollection();
            if(count($removeArr)  > 0) {

                foreach ($removeArr as $r) {

                    $this->splicedAllocatedInduction->add($r);
                }
            }
            $induction->setProject($this);
            $this->allocatedInduction->add($induction);
//            $this->splicedAllocatedInduction->removeElement($induction);
            $this->firstTime = false;
        } else {

            $this->removeAllocatedInduction($induction);
            $this->allocatedInduction->add($induction);
            $this->splicedAllocatedInduction->removeElement($induction);
        }


    }

    public function addAllocatedInduction(AllocatedInduction $induction)
    {
        if(count($this->allocatedInduction)>0 && count($this->splicedAllocatedInduction)==0 && $this->splicedAllocatedInduction != null) {
            $removeArr = $this->allocatedInduction;
            $this->splicedAllocatedInduction = $removeArr;
        }
        $this->allocatedInduction[] = $induction;
    }


    public function removeAllocatedInduction(AllocatedInduction $induction)
    {
        $this->allocatedInduction->removeElement($induction);
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
     * Set projectName
     *
     * @param string $projectName
     *
     * @return Project
     */
    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;

        return $this;
    }

    /**
     * Get projectName
     *
     * @return string
     */
    public function getProjectName()
    {
        return $this->projectName;
    }

    /**
     * Set projectAddress
     *
     * @param string $projectAddress
     *
     * @return Project
     */
    public function setProjectAddress($projectAddress)
    {
        $this->projectAddress = $projectAddress;

        return $this;
    }

    /**
     * Get projectAddress
     *
     * @return string
     */
    public function getProjectAddress()
    {
        return $this->projectAddress;
    }


    /**
     * Set projectRatesRules
     *
     * @param string $projectRatesRules
     *
     * @return Project
     */
    public function setProjectRatesRules($projectRatesRules)
    {
        $this->projectRatesRules = $projectRatesRules;

        return $this;
    }

    /**
     * Get projectRatesRules
     *
     * @return string
     */
    public function getProjectRatesRules()
    {
        return $this->projectRatesRules;
    }

    /**
     * Set projectInductions
     *
     * @param string $projectInductions
     *
     * @return Project
     */
    public function setProjectInductions($projectInductions)
    {
        $this->projectInductions = $projectInductions;

        return $this;
    }

    /**
     * Get projectInductions
     *
     * @return string
     */
    public function getProjectInductions()
    {
        return $this->projectInductions;
    }

    /**
     * Set client
     *
     * @param \BackendBundle\Entity\Client $client
     *
     * @return Project
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


    /**
     * Set projectCreated
     *
     * @param string $projectCreated
     *
     * @return Project
     */
    public function setProjectCreated($projectCreated)
    {
        $this->projectCreated = $projectCreated;

        return $this;
    }

    /**
     * Get projectCreated
     *
     * @return string
     */
    public function getProjectCreated()
    {
        return $this->projectCreated;
    }

    /**
     * Set projectUpdated
     *
     * @param string $projectUpdated
     *
     * @return Project
     */
    public function setProjectUpdated($projectUpdated)
    {
        $this->projectUpdated = $projectUpdated;

        return $this;
    }

    /**
     * Get projectUpdated
     *
     * @return string
     */
    public function getProjectUpdated()
    {
        return $this->projectUpdated;
    }

    /**
     * Set porjectUpdatedBy
     *
     * @param string $porjectUpdatedBy
     *
     * @return Project
     */
    public function setPorjectUpdatedBy($porjectUpdatedBy)
    {
        $this->porjectUpdatedBy = $porjectUpdatedBy;

        return $this;
    }

    /**
     * Get porjectUpdatedBy
     *
     * @return string
     */
    public function getPorjectUpdatedBy()
    {
        return $this->porjectUpdatedBy;
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
     * @return float
     */
    public function getLattitude()
    {
        return $this->lattitude;
    }

    /**
     * @param float $lattitude
     */
    public function setLattitude(float $lattitude)
    {
        $this->lattitude = $lattitude;
    }

    /**
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * @param float $longitude
     */
    public function setLongitude(float $longitude)
    {
        $this->longitude = $longitude;
    }



}

