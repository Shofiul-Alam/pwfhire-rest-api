<?php

namespace BackendBundle\Entity;

/**
 * Contact
 */
class Test extends \BackendBundle\Entity\AEntity
{
    private $name = "";

    /**
     * @return string
     */
    public function getName(): string
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


}

