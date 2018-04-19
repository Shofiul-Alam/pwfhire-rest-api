<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 1/10/2017
 * Time: 8:23 PM
 */

namespace AppBundle\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class Helpers {

    public $manager;

    public function __construct($manager) {
        $this->manager = $manager;
    }

    public function json($data) {
        $normalizer = new ObjectNormalizer();
        $normalizer->setCircularReferenceLimit(1);
// Add Circular reference handler
        $normalizer->setCircularReferenceHandler(function ($object) {
            return $object->getId();
        });
        $normalizers = array($normalizer);
        $encoders = array("json" => new JsonEncode());
        $serializer = new Serializer($normalizers, $encoders);
        $json = $serializer->serialize($data, 'json');


//        $normalizers = array (new GetSetMethodNormalizer());
//        $encoders = array("json" => new JsonEncode());
//        $serializer = new Serializer($normalizers, $encoders);
//        $json = $serializer->serialize($data, 'json');
        $response = new Response();
        $response->setContent($json);

        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}