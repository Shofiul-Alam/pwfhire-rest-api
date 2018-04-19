<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/11/2017
 * Time: 8:15 PM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Services\Helpers;
use BackendBundle\Entity\AllocatedContact;
use BackendBundle\Entity\Contact;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Employee;
use BackendBundle\Entity\Client;


class ManageContactController extends AAdmin {
    private $error = array();

    public function newAction(Request $request)
    {

        $createdAt = new \DateTime("now");
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);


        $data = $this->CreateUser($params->user, $avatar=null);

        if($data['status'] === 'success' && $data['user'] != null) {
            $this->entity = new Contact();
            $user = $data['user'];
            $this->entity->setUser($user);


            return $this->registerContact($request);
        } else {
            return $helpers->json($this->registrationError);
        }



    }

    public function listAction(Request $request) {
        $this->entity = new Contact();

        return parent::listAction($request);
    }


    public function editAction(Request $request)
    {
        $this->entity = new Contact();
        return parent::editAction($request);
    }

    public function registerContact(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $client = json_decode($request->get('client', null));
        $params->user = null;
        $token = $request->get('authorisation', null);


        if($this->isAuthenticated($token)) {
            $create = true;

            if ($create) {
                $this->prepareEntityData($this->entity, $params);
            }



            $em = $this->getDoctrine()->getManager();

            $entityName = $this->entity->getEntityClassName();
            $uniqueIdentifire = $this->getUniqueIdentifier($entityName);
            $persist = false;

            if($this->entity->{'get'.ucwords($uniqueIdentifire)}()!= null) {
                $isset_entity = $em->getRepository('BackendBundle:'.$entityName)->findBy(array(
                    $uniqueIdentifire => $this->entity->{'get'.ucwords($uniqueIdentifire)}()
                ));
            } else {
                $persist = true;
            }


            if (count($this->error) > 0) {
                $create = false;
            }


            if (count($isset_entity) == 0 || $persist) {
                if ($create) {
                    $em->persist($this->entity);
                    $em->flush();

                    if($client) {
                       $cId = $this->getEnitityId($client->id);
                       $client = $em->getRepository('BackendBundle:Client')->find($cId);
                       $allocatedContact = new AllocatedContact();
                       $allocatedContact->setContact($this->entity);
                       $allocatedContact->setClient($client);
                       $client->addAllocatedContact($allocatedContact);
                       $em->persist($client);
                       $em->flush();
                    }
                    $data = array(
                        'status' => "success",
                        'code' => 200,
                        'msg' => $entityName. ' is Successfully Created!!!',
                        $entityName => $this->entity
                    );
                } else {
                    $data = array_merge($this->duplicateError, array(
                        'error_data' => $this->error
                    ));

                }

            } else {
                $data = array_merge($this->duplicateError, array(
                    'error_data' => $this->error
                ));
            }

        } else {
            return $helpers->json($this->accessError);
        }



        return $helpers->json($data);
    }

}