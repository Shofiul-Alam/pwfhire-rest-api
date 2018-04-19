<?php

namespace BackendBundle\Entity;

/**
 * InvoicePayable
 */
class InvoicePayable extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $invoicePayableId;

    /**
     * @var string
     */
    private $invoicePayablecol;


    /**
     * Get invoicePayableId
     *
     * @return integer
     */
    public function getInvoicePayableId()
    {
        return $this->invoicePayableId;
    }

    /**
     * Set invoicePayablecol
     *
     * @param string $invoicePayablecol
     *
     * @return InvoicePayable
     */
    public function setInvoicePayablecol($invoicePayablecol)
    {
        $this->invoicePayablecol = $invoicePayablecol;

        return $this;
    }

    /**
     * Get invoicePayablecol
     *
     * @return string
     */
    public function getInvoicePayablecol()
    {
        return $this->invoicePayablecol;
    }



    public function toArray() {
        return get_object_vars($this);
    }
}

