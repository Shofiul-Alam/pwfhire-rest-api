<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 28/2/18
 * Time: 3:31 PM
 */

namespace AppBundle\Services\Twilio;


use Doctrine\ORM\EntityManager;

class TwilioConfig {
    private $twilioSID;
    private $twilioToken;
    private $twilioMobileNo;
    private $twilioAlphaNumericID;

    private $configurations;

    public function __construct(EntityManager $em)
    {
        $this->configurations = $em->getRepository('BackendBundle:Config')->findBy(array("category" => "twilio"));
        $this->getTwilioSID();
        $this->getTwilioToken();
        $this->getTwilioMobileNo();
        $this->getTwilioAlphaNumericId();
    }

    /**
     * @return mixed
     */
    public function getTwilioSID()
    {
        foreach ($this->configurations as $configuration) {
            if($configuration->getProperty() == "twilioSID") {
                return $this->twilioSID = $configuration->getValue();
            }
        }
    }

    /**
     * @return mixed
     */
    public function getTwilioToken()
    {
        foreach ($this->configurations as $configuration) {
            if($configuration->getProperty() == "twilioToken") {
                return $this->twilioToken = $configuration->getValue();
            }
        }
    }


    /**
     * @return mixed
     */
    public function getTwilioMobileNo()
    {
        foreach ($this->configurations as $configuration) {
            if($configuration->getProperty() == "twilioMobileNo") {
                return $this->twilioMobileNo = $configuration->getValue();
            }
        }
    }
    /**
     * @return mixed
     */
    public function getTwilioAlphaNumericId()
    {
        foreach ($this->configurations as $configuration) {
            if($configuration->getProperty() == "twilioAlphaNumericId") {
                return $this->twilioAlphaNumericID = $configuration->getValue();
            }
        }
    }

    public function getConfigurations() {
        return $this->configurations;
    }
}