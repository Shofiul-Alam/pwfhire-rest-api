<?php

namespace BackendBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Field
 */
class Field extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $classname;


    /**
     * @var string
     */
    private $defaultValue;

    /**
     * @var boolean
     */
    private $required;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $placeholder;

    /**
     * @var string
     */
    private $name;

    /**
     * @var boolean
     */
    private $access;

    /**
     * @var string
     */
    private $subtype;

    /**
     * @var boolean
     */
    private $inline;

    /**
     * @var string
     */
    private $value;

    /**
     * @var string
     */
    private $min;

    /**
     * @var string
     */
    private $max;

    /**
     * @var \BackendBundle\Entity\Form
     */
    private $form;

    /**
     * @var \BackendBundle\Entity\ValueArr
     */
    private $valueArr;

    public $splicedValueArr;


    public $appliedRemove = false;

    public $firstTime = true;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->valueArr = new \Doctrine\Common\Collections\ArrayCollection();
        $this->splicedValueArr = new \Doctrine\Common\Collections\ArrayCollection();
    }



    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getValueArr()
    {
        return $this->valueArr;
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $values
     */
    public function addValueArr(\BackendBundle\Entity\ValueArr $value)
    {
        $this->valueArr->add($value);
    }

    /**
     * @param \Doctrine\Common\Collections\ArrayCollection $values
     */
    public function removeValueArr(\BackendBundle\Entity\ValueArr $value)
    {
        $this->valueArr->removeElement($value);
    }



    public function replaceValueArr(\BackendBundle\Entity\ValueArr $valueArr) {
        if(count($this->splicedValueArr) == 0 && $this->firstTime) {
            if(count($this->fieldsArr) != 0) {
                $this->removeValueArr($valueArr);
            }
            $removeArr = $this->getValueArr();
            $this->splicedValueArr = new ArrayCollection();

            if(count($this->getValueArr()) > 0) {

                foreach ($removeArr as $r) {

                    $this->splicedValueArr->add($r);
                }
            }
            $this->valueArr->add($valueArr);
            $this->firstTime = false;
            $this->splicedValueArr->removeElement($valueArr);

        } else {
            $this->splicedValueArr->removeElement($valueArr);
            $this->removeValueArr($valueArr);
            $this->valueArr->add($valueArr);
        }
    }

    public function emptyValueArr() {
        return $this->valueArr = [];
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
     * Set label
     *
     * @param string $label
     *
     * @return Field
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return Field
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }


    /**
     * Set defaultValue
     *
     * @param string $defaultValue
     *
     * @return Field
     */
    public function setDefaultValue($defaultValue)
    {
        $this->defaultValue = $defaultValue;

        return $this;
    }

    /**
     * Get defaultValue
     *
     * @return string
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * Set required
     *
     * @param boolean $required
     *
     * @return Field
     */
    public function setRequired($required)
    {
        $this->required = $required;

        return $this;
    }

    /**
     * Get required
     *
     * @return boolean
     */
    public function getRequired()
    {
        return $this->required;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Field
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set placeholder
     *
     * @param string $placeholder
     *
     * @return Field
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get placeholder
     *
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Field
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

    /**
     * Set access
     *
     * @param boolean $access
     *
     * @return Field
     */
    public function setAccess($access)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Get access
     *
     * @return boolean
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Set subtype
     *
     * @param string $subtype
     *
     * @return Field
     */
    public function setSubType($subtype)
    {
        $this->subtype = $subtype;

        return $this;
    }

    /**
     * Get subtype
     *
     * @return string
     */
    public function getSubType()
    {
        return $this->subtype;
    }

    /**
     * Set inline
     *
     * @param boolean $inline
     *
     * @return Field
     */
    public function setInline($inline)
    {
        $this->inline = $inline;

        return $this;
    }

    /**
     * Get inline
     *
     * @return boolean
     */
    public function getInline()
    {
        return $this->inline;
    }

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Field
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
     * Set min
     *
     * @param string $min
     *
     * @return Field
     */
    public function setMin($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * Get min
     *
     * @return string
     */
    public function getMin()
    {
        return $this->min;
    }

    /**
     * Set max
     *
     * @param string $max
     *
     * @return Field
     */
    public function setMax($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * Get max
     *
     * @return string
     */
    public function getMax()
    {
        return $this->max;
    }

    /**
     * Set form
     *
     * @param \BackendBundle\Entity\Form $form
     *
     * @return Field
     */
    public function setForm(\BackendBundle\Entity\Form $form = null)
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return string
     */
    public function getClassname()
    {
        return $this->classname;
    }

    /**
     * @param string $classname
     */
    public function setClassname(string $classname)
    {
        $this->classname = $classname;
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

    /**
     * Get form
     *
     * @return \BackendBundle\Entity\Form
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * @return bool
     */
    public function isAppliedRemove(): bool
    {
        return $this->appliedRemove;
    }

    /**
     * @param bool $appliedRemove
     */
    public function setAppliedRemove(bool $appliedRemove)
    {
        $this->appliedRemove = $appliedRemove;
    }



    public function toArray() {
        return get_object_vars($this);
    }


}
