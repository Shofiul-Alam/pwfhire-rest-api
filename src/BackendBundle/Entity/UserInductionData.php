<?php

namespace BackendBundle\Entity;

/**
 * UserInductionData
 */
class UserInductionData extends AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $fieldId;

    /**
     * @var string
     */
    private $valuearrId;

    /**
     * @var \BackendBundle\Entity\Induction
     */
    private $induction;

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
     * Set value
     *
     * @param string $value
     *
     * @return UserInductionData
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set fieldId
     *
     * @param string $fieldId
     *
     * @return UserInductionData
     */
    public function setFieldId($fieldId)
    {
        $this->fieldId = $fieldId;

        return $this;
    }

    /**
     * Get fieldId
     *
     * @return string
     */
    public function getFieldId()
    {
        return $this->fieldId;
    }

    /**
     * Set valuearrId
     *
     * @param string $valuearrId
     *
     * @return UserInductionData
     */
    public function setValuearrId($valuearrId)
    {
        $this->valuearrId = $valuearrId;

        return $this;
    }

    /**
     * Get valuearrId
     *
     * @return string
     */
    public function getValuearrId()
    {
        return $this->valuearrId;
    }

    /**
     * Set induction
     *
     * @param \BackendBundle\Entity\Induction $induction
     *
     * @return UserInductionData
     */
    public function setInduction(\BackendBundle\Entity\Induction $induction = null)
    {
        $this->induction = $induction;

        return $this;
    }

    /**
     * Get induction
     *
     * @return \BackendBundle\Entity\Induction
     */
    public function getInduction()
    {
        return $this->induction;
    }

    /**
     * Set user
     *
     * @param \BackendBundle\Entity\User $user
     *
     * @return UserInductionData
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

