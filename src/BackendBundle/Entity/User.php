<?php

namespace BackendBundle\Entity;

/**
 * User
 */
class User extends \BackendBundle\Entity\AEntity
{
    /**
     * @var integer
     */
    private $id;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $mobile;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var \BackendBundle\Entity\UserType
     */
    private $userType;

    /**
     * @var \Doctrine\Common\Collections\Collection
     */
    private $userFormDatas;

    /**
     * @var \BackendBundle\Entity\UserAvatar
     */
    private $userAvatar;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->userFormDatas = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add UserFormData
     *
     * @param \BackendBundle\Entity\UserFormData $userFormData
     *
     * @return User
     */
    public function addUserFormData(\BackendBundle\Entity\UserFormData $userFormData)
    {
        $this->userFormDatas[] = $userFormData;

        return $this;
    }

    /**
     * Remove UserFormData
     *
     * @param \BackendBundle\Entity\UserFormData $userFormData
     */
    public function removeUserFormData(\BackendBundle\Entity\UserFormData $userFormData)
    {
        $this->userFormDatas->removeElement($userFormData);
    }

    /**
     * Add UserFormData
     *
     * @param \BackendBundle\Entity\UserFormData $userFormData
     *
     * @return User
     */
    public function userFormDatas()
    {
        return  $this->userFormDatas;
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
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set firstName
     *
     * @param string $firstName
     *
     * @return User
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return User
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set mobile
     *
     * @param string $mobile
     *
     * @return User
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;

        return $this;
    }

    /**
     * Get mobile
     *
     * @return string
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return User
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set userType
     *
     * @param \BackendBundle\Entity\UserType $userType
     *
     * @return User
     */
    public function setUserType(\BackendBundle\Entity\UserType $userType = null)
    {
        $this->userType = $userType;

        return $this;
    }

    /**
     * Get userType
     *
     * @return \BackendBundle\Entity\UserType
     */
    public function getUserType()
    {
        return $this->userType;
    }

    /**
     * @return UserAvatar
     */
    public function getUserAvatar()
    {
        return $this->userAvatar;
    }

    /**
     * @param UserAvatar $userAvatar
     */
    public function setUserAvatar(UserAvatar $userAvatar)
    {
        $this->userAvatar = $userAvatar;
    }




    public function toArray() {
        return get_object_vars($this);
    }
}

