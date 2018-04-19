<?php

namespace BackendBundle\Entity;

use BackendBundle\Entity\Base\AImage;

use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;



class EmployeeSkillDocument extends \BackendBundle\Entity\AEntity
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
    private $path = "/documents";

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
     * @var \BackendBundle\Entity\EmployeeSkillCompetencyDocument
     */
    private $employeeSkillCompetencyDocument;


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
     * @return EmployeeSkillDocument
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
     * @return EmployeeSkillDocument
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
     * @return EmployeeSkillDocument
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
     * @return EmployeeSkillDocument
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
     * @return EmployeeSkillDocument
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
     * @return EmployeeSkillDocument
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
     * @return EmployeeSkillDocument
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
     * @return EmployeeSkillDocument
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
     * @return EmployeeSkillDocument
     */
//    public function setUploadedDate($uploadedDate)
//    {
//        $this->uploadedDate = $uploadedDate;
//
//        return $this;
//    }

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
     * Set employeeSkillCompetencyDocument
     *
     * @param \BackendBundle\Entity\EmployeeSkillCompetencyDocument $employeeSkillCompetencyDocument
     *
     * @return EmployeeSkillDocument
     */
    public function setEmployeeSkillCompetencyDocument(\BackendBundle\Entity\EmployeeSkillCompetencyDocument $employeeSkillCompetencyDocument = null)
    {
        $this->employeeSkillCompetencyDocument = $employeeSkillCompetencyDocument;

        return $this;
    }

    /**
     * Get employeeSkillCompetencyDocument
     *
     * @return \BackendBundle\Entity\EmployeeSkillCompetencyDocument
     */
    public function getEmployeeSkillCompetencyDocument()
    {
        return $this->employeeSkillCompetencyDocument;
    }



    public function toArray() {
        return get_object_vars($this);
    }
}

