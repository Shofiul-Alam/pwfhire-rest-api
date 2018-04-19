<?php

namespace BackendBundle\Entity;

/**
 * SkillCompetencyList
 */
class SkillCompetencyList extends \BackendBundle\Entity\AEntity
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
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->encodeId($this->id);
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return SkillCompetencyList
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }



    public function toArray() {
        return get_object_vars($this);
    }
}

