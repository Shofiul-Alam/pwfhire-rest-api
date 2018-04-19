<?php

namespace BackendBundle\Entity;

/**
 * Job
 */
class Job extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $chargeRate;

    /**
     * @var string
     *
     */
    private $payscale;

    /**
     * @var boolean
     *
     */
    private $archived = false;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $induction;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $skillCompetencyList;



    public $splicedInduction = array();
    public $splicedSkillCompetencyList;
    public $splicedTask = array();

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->induction = new \Doctrine\Common\Collections\ArrayCollection();
        $this->skillCompetencyList = new \Doctrine\Common\Collections\ArrayCollection();
        $this->splicedSkillCompetencyList =  new \Doctrine\Common\Collections\ArrayCollection();

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
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }



    /**
     * Set chargeRate
     *
     * @param string $chargeRate
     *
     * @return Job
     */
    public function setChargeRate($chargeRate)
    {
        $this->chargeRate = $chargeRate;

        return $this;
    }

    /**
     * Get chargeRate
     *
     * @return string
     */
    public function getChargeRate()
    {
        return $this->chargeRate;
    }

    /**
     * Set payscale
     *
     * @param string $payscale
     *
     * @return Job
     */
    public function setPayscale($payscale)
    {
        $this->payscale = $payscale;

        return $this;
    }

    /**
     * Get payscale
     *
     * @return string
     */
    public function getPayscale()
    {
        return $this->payscale;
    }

    /**
     * Add induction
     *
     * @param \BackendBundle\Entity\Induction $induction
     *
     * @return Job
     */
    public function addInduction(\BackendBundle\Entity\Induction $induction)
    {
        if(count($this->induction)>0 && count($this->splicedInduction)==0 && $this->splicedInduction != null) {
            $removeArr = $this->induction->toArray();
            $this->induction = $removeArr;
        }
        $this->induction[] = $induction;

        return $this;
    }

    /**
     * Remove induction
     *
     * @param \BackendBundle\Entity\Induction $induction
     */
    public function removeInduction(\BackendBundle\Entity\Induction $induction)
    {
        $this->induction->removeElement($induction);
    }

    /**
     * Get induction
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getInduction()
    {
        return $this->induction;
    }
    /**
     * @param \BackendBundle\Entity\Induction
     */
    public function replaceInduction(\BackendBundle\Entity\Induction $induction)
    {
        if(count($this->splicedInduction) == 0) {
            $this->removeInduction($induction);
            $removeArr = $this->induction->toArray();
            if(count($removeArr) == 0) {
                $this->splicedInduction = null;
            } else {
                $this->splicedInduction = $removeArr;
            }
//            $induction->setJob($this);
            $this->induction[] = $induction;
        } else {
            $this->induction->removeElement($induction);
            $this->removeInduction($induction);
            $this->induction[] = $induction;
        }


    }


    /**
     * Add skillCompetencyList
     *
     * @param \BackendBundle\Entity\SkillCompetencyList $skillCompetencyList
     *
     * @return Job
     */
    public function addSkillCompetencyList(\BackendBundle\Entity\SkillCompetencyList $skillCompetencyList)
    {
        if(count($this->skillCompetencyList)>0 && count($this->splicedSkillCompetencyList)==0 && $this->splicedSkillCompetencyList != null) {
            $removeArr = $this->skillCompetencyList->toArray();
            $this->skillCompetencyList = $removeArr;
        }
        $this->skillCompetencyList[] = $skillCompetencyList;

        return $this;
    }

    /**
     * Remove skillCompetencyList
     *
     * @param \BackendBundle\Entity\SkillCompetencyList $skillCompetencyList
     */
    public function removeSkillCompetencyList(\BackendBundle\Entity\SkillCompetencyList $skillCompetencyList)
    {
        $this->skillCompetencyList->removeElement($skillCompetencyList);
    }

    /**
     * Get skillCompetencyList
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSkillCompetencyList()
    {
        return $this->skillCompetencyList;
    }
    /**
     * @param \BackendBundle\Entity\Induction
     */
    public function replaceSkillCompetencyList(\BackendBundle\Entity\SkillCompetencyList $skillCompetencyList)
    {
        if(count($this->splicedSkillCompetencyList) == 0) {
            $this->removeSkillCompetencyList($skillCompetencyList);
            $removeArr = $this->induction->toArray();
            if(count($removeArr) == 0) {
                $this->splicedSkillCompetencyList = null;
            } else {
                $this->splicedSkillCompetencyList = $removeArr;
            }
//            $induction->setJob($this);
            $this->skillCompetencyList[] = $skillCompetencyList;
        } else {
            $this->skillCompetencyList->removeElement($skillCompetencyList);
            $this->removeSkillCompetencyList($skillCompetencyList);
            $this->skillCompetencyList[] = $skillCompetencyList;
        }


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



    public function toArray() {
        return get_object_vars($this);
    }
}

