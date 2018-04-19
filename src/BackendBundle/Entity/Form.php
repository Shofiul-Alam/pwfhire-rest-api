<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Form
 */
class Form extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $formName;

    /**
     * @var \BackendBundle\Entity\Field
     */
    private $fieldsArr;

    public $splicedFieldsArr;

    public $firstTime = true;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->fieldsArr = new \Doctrine\Common\Collections\ArrayCollection();
        $this->splicedFieldsArr = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return Field
     */
    public function getFieldsArr()
    {
        return $this->fieldsArr;
    }

    public function addFieldsArr(Field $fieldsArr)
    {
        return $this->fieldsArr->add($fieldsArr);
    }


    /**
     * Remove User
     *
     * @param Field $fields
     */
    public function removeFieldsArr(Field $fieldsArr)
    {
        $this->fieldsArr->removeElement($fieldsArr);
    }

    /**
     * Remove User
     *
     * @param Field $fields
     */
    public function replaceFieldsArr($fieldsArr)
    {
        if(count($this->splicedFieldsArr) == 0 && $this->firstTime) {
            if(count($this->fieldsArr) != 0) {
                $this->removeFieldsArr($fieldsArr);
            }
            $removeArr = $this->getFieldsArr();
            $this->splicedFieldsArr = new ArrayCollection();

            if(count($this->getFieldsArr()) > 0) {

                foreach ($removeArr as $r) {

                    $this->splicedFieldsArr->add($r);
                }
            }
            $this->fieldsArr->add($fieldsArr);
            $this->firstTime = false;
            $this->splicedFieldsArr->removeElement($fieldsArr);

        } else {
            $this->splicedFieldsArr->removeElement($fieldsArr);
            $this->removeFieldsArr($fieldsArr);
            $this->fieldsArr->add($fieldsArr);
        }
    }

    public function emptyFieldsArr() {
        $this->fieldsArr = null;
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
    public function setId($id)
    {
        return $this->id = $id;
    }

    /**
     * Set formName
     *
     * @param string $formName
     *
     * @return Form
     */
    public function setFormName($formName)
    {
        $this->formName = $formName;

        return $this;
    }

    /**
     * Get formName
     *
     * @return string
     */
    public function getFormName()
    {
        return $this->formName;
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
    public function setFirstTime(bool $firstTime)
    {
        $this->firstTime = $firstTime;
    }

    public function toArray() {
        return get_object_vars($this);
    }
}

