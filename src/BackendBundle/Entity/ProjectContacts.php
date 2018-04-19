<?php

namespace BackendBundle\Entity;

/**
 * ProjectContacts
 */
class ProjectContacts extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $projectContactId;

    /**
     * @var string
     */
    private $projectId;

    /**
     * @var string
     */
    private $personsReferenceId;


    /**
     * Get projectContactId
     *
     * @return integer
     */
    public function getProjectContactId()
    {
        return $this->projectContactId;
    }

    /**
     * Set projectId
     *
     * @param string $projectId
     *
     * @return ProjectContacts
     */
    public function setProjectId($projectId)
    {
        $this->projectId = $projectId;

        return $this;
    }

    /**
     * Get projectId
     *
     * @return string
     */
    public function getProjectId()
    {
        return $this->projectId;
    }

    /**
     * Set personsReferenceId
     *
     * @param string $personsReferenceId
     *
     * @return ProjectContacts
     */
    public function setPersonsReferenceId($personsReferenceId)
    {
        $this->personsReferenceId = $personsReferenceId;

        return $this;
    }

    /**
     * Get personsReferenceId
     *
     * @return string
     */
    public function getPersonsReferenceId()
    {
        return $this->personsReferenceId;
    }



    public function toArray() {
        return get_object_vars($this);
    }
}
