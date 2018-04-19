<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 1/10/2017
 * Time: 8:23 PM
 */

namespace AppBundle\Services\Twilio;



class TwilioSMS
{
    public static function sendSMS($sms, $vendorMobileNo) {

        $config = new \XLite\Module\Shofi\TwilioSMS\TwilioConfig();


        $client = new \Twilio\Rest\Client($config->getTwilioSID(), $config->getTwilioToken());


        $client->messages
            ->create(
                $vendorMobileNo,
                array(
                    "from" => $config->getTwilioalphaNumericID(),
                    "body" => $sms,
                )
            );

    }
}

