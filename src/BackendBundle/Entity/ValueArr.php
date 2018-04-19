<?php

namespace BackendBundle\Entity;

/**
 * Field
 */
class ValueArr extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var text
     */

    private $label;

    /**
     * @var text
     */

    private $value;

    /**
     * @var boolean
     */

    private $selected = false;

    /**
     * @var boolean
     */

    private $correct;

    /**
     * @var \BackendBundle\Entity\field
     */
    private $field;

    /**
     * @return text
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param text $option
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    /**
     * @return text
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param text $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return bool
     */
    public function isSelected()
    {
        return $this->selected;
    }

    /**
     * @param bool $correct
     */
    public function setSelected(bool $selected)
    {
        $this->selected = $selected;
    }

    /**
     * @return bool
     */
    public function isCorrect()
    {
        return $this->correct;
    }

    /**
     * @param bool $correct
     */
    public function setCorrect(bool $correct)
    {
        $this->correct = $correct;
    }

    /**
     * @return \BackendBundle\Entity\field
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @param field $field
     */
    public function setField(\BackendBundle\Entity\field $field)
    {
        $this->field = $field;
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



    public function toArray() {
        return get_object_vars($this);
    }
}
