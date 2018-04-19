<?php

namespace BackendBundle\Entity;

/**
 * AllocatedSkillCompetency
 */
class AllocatedSkillCompetency extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var \BackendBundle\Entity\Project
     */
    private $project;

    /**
     * @var \BackendBundle\Entity\SkillCompetencyList
     */
    private $skillCompetencyList;


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
     * Set project
     *
     * @param \BackendBundle\Entity\Project $project
     *
     * @return AllocatedSkillCompetency
     */
    public function setProject(\BackendBundle\Entity\Project $project = null)
    {
        $this->project = $project;

        return $this;
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

    /**
     * Set skillCompetencyList
     *
     * @param \BackendBundle\Entity\SkillCompetencyList $skillCompetencyList
     *
     * @return AllocatedSkillCompetency
     */
    public function setSkillCompetencyList(\BackendBundle\Entity\SkillCompetencyList $skillCompetencyList = null)
    {
        $this->skillCompetencyList = $skillCompetencyList;

        return $this;
    }

    /**
     * Get skillCompetencyList
     *
     * @return \BackendBundle\Entity\SkillCompetencyList
     */
    public function getSkillCompetencyList()
    {
        return $this->skillCompetencyList;
    }

    public function toArray() {
        return get_object_vars($this);
    }
}

