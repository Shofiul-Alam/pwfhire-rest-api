<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/11/2017
 * Time: 8:15 PM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Services\Helpers;
use AppBundle\Services\Twilio\TwilioConfig;
use BackendBundle\Entity\Config;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;



class IntegrationController extends AAdmin
{
    private $error = array();

    public function getTwilioConfigAction (Request $request) {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $em = $this->getDoctrine()->getManager();
            $configEntity = new TwilioConfig($em);


            $data = array(
                "status" => "success",
                'code'  => 200,
                'data' => $configEntity

            );
        } else {
            $data = array(
                "status" => "error",
                'code'  => 400,
                'msg'   => 'Authorization not valid'
            );
        }

        return $helpers->json($data);
    }

    public function integrateTwilioAction (Request $request) {
            $helpers = $this->get(Helpers::class);
            $token = $request->get('authorisation', null);
            $configurations = json_decode($request->get('json', null));

            if($this->isAuthenticated($token) && $this->isAdmin($token)) {

                $em = $this->getDoctrine()->getManager();
                $twilioConfig = array();

                if(count($configurations) > 0) {
                    foreach($configurations as $configuration) {
                        $configEntity = new Config();
                        $configEntity->setCategory('twilio');
                        $configEntity->setProperty($configuration->property);
                        $configEntity->setValue($configuration->value);

                        $em->persist($configEntity);
                        $em->flush();
                        array_push($twilioConfig, $configEntity);
                    }
                }



                $data = array(
                    "status" => "success",
                    'code'  => 200,
                    'data' => $twilioConfig

                );
            } else {
                $data = array(
                    "status" => "error",
                    'code'  => 400,
                    'msg'   => 'Authorization not valid'
                );
            }

            return $helpers->json($data);
        }

    public function updateTwilioConfigAction (Request $request) {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        $configurations = json_decode($request->get('json', null));

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $em = $this->getDoctrine()->getManager();
            $twilioConfig = array();

            if(count($configurations) > 0) {
                foreach($configurations as $configuration) {
                    $configEntity = $em->getRepository('BackendBundle:Config')->find($this->getEnitityId($configuration->id));
                    $configEntity->setProperty($configuration->property);
                    $configEntity->setValue($configuration->value);

                    $em->persist($configEntity);
                    $em->flush();
                    array_push($twilioConfig, $configEntity);
                }
            }



            $data = array(
                "status" => "success",
                'code'  => 200,
                'data' => $twilioConfig

            );
        } else {
            $data = array(
                "status" => "error",
                'code'  => 400,
                'msg'   => 'Authorization not valid'
            );
        }

        return $helpers->json($data);
    }


}