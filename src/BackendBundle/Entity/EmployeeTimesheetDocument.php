<?php

namespace BackendBundle\Entity;


/**
 * EmployeeTimesheetDocument
 */
class EmployeeTimesheetDocument extends AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $alt;

    /**
     * @var string
     */
    private $width;

    /**
     * @var string
     */
    private $height;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $fileName;

    /**
     * @var string
     */
    private $mime;

    /**
     * @var string
     */
    private $storageType;

    /**
     * @var string
     */
    private $size;

    /**
     * @var \DateTime
     */
    private $uploadedDate;

    /**
     * @var \BackendBundle\Entity\TimeSheet
     */
    private $timeSheet;


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
     * Set alt
     *
     * @param string $alt
     *
     * @return EmployeeTimesheetDocument
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * Set width
     *
     * @param string $width
     *
     * @return EmployeeTimesheetDocument
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width
     *
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set height
     *
     * @param string $height
     *
     * @return EmployeeTimesheetDocument
     */
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get height
     *
     * @return string
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return EmployeeTimesheetDocument
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set fileName
     *
     * @param string $fileName
     *
     * @return EmployeeTimesheetDocument
     */
    public function setFileName($fileName)
    {
        $this->fileName = $fileName;

        return $this;
    }

    /**
     * Get fileName
     *
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Set mime
     *
     * @param string $mime
     *
     * @return EmployeeTimesheetDocument
     */
    public function setMime($mime)
    {
        $this->mime = $mime;

        return $this;
    }

    /**
     * Get mime
     *
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set storageType
     *
     * @param string $storageType
     *
     * @return EmployeeTimesheetDocument
     */
    public function setStorageType($storageType)
    {
        $this->storageType = $storageType;

        return $this;
    }

    /**
     * Get storageType
     *
     * @return string
     */
    public function getStorageType()
    {
        return $this->storageType;
    }

    /**
     * Set size
     *
     * @param string $size
     *
     * @return EmployeeTimesheetDocument
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set uploadedDate
     *
     * @param \DateTime $uploadedDate
     *
     * @return EmployeeTimesheetDocument
     */
    public function setUploadedDate($uploadedDate)
    {
        $this->uploadedDate = $uploadedDate;

        return $this;
    }

    /**
     * Get uploadedDate
     *
     * @return \DateTime
     */
    public function getUploadedDate()
    {
        return $this->uploadedDate;
    }

    /**
     * Set timeSheet
     *
     * @param \BackendBundle\Entity\TimeSheet $timeSheet
     *
     * @return EmployeeTimesheetDocument
     */
    public function setTimeSheet(\BackendBundle\Entity\TimeSheet $timeSheet = null)
    {
        $this->timeSheet = $timeSheet;

        return $this;
    }

    /**
     * Get timeSheet
     *
     * @return \BackendBundle\Entity\TimeSheet
     */
    public function getTimeSheet()
    {
        return $this->timeSheet;
    }



    public function toArray() {
        return get_object_vars($this);
    }
}

