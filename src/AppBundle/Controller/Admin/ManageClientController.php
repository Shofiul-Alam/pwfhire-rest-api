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
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Employee;
use BackendBundle\Entity\Client;


class ManageClientController extends AAdmin {
    private $error = array();

    public function newAction(Request $request)
    {

        $createdAt = new \DateTime("now");
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $avatar =  json_decode($request->get('upload', null));

        $data = $this->CreateUser($params->user, $avatar);

        if($data['status'] === 'success' && $data['user'] != null) {
            $this->entity = new Client();
            $user = $data['user'];
            $this->entity->setUser($user);
            $params->user = null;

            $request->authorisation = $data['token'];

            return $this->registerClient($request);
        } else {
            return $helpers->json($this->registrationError);
        }



    }

    public function listAction(Request $request) {
        $this->entity = new Client();
        return parent::listAction($request);
    }


    public function editAction(Request $request)
    {
        $this->entity = new Client();
        return parent::editAction($request);
    }

    public function registerClient(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $contacts = $request->get('contacts', null);
//        $contactArray = json_decode($contacts);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);
        $params->user = null;


        if($this->isAuthenticated($token) && $this->isAdmin($token)) {
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

//                    foreach ($contactArray as $contact) {
//                        $contactId = $this->getEnitityId($contact);
//                        $contact = $em->getRepository('BackendBundle:Contact')->find($contactId);
//                        $contactAllocation = new AllocatedContact();
//                        $contactAllocation->setClient($this->entity);
//                        $contactAllocation->setContact($contact);
//                        $em->persist($contactAllocation);
//                        $em->flush();
//                    }

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

    private function resetContacts($contactIds, $clientId) {
        $em = $this->getDoctrine()->getManager();
        $id = $this->getEnitityId($clientId);
        $client = $em->getRepository('BackendBundle:Client')->find($id);


    }

    public function clientContactsAction(Request $request) {
        $this->entity = new Client();
        return parent::getContacts($request);
    }

    public function archiveClientAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $id = $this->getEnitityId($params->id);

            $em = $this->getDoctrine()->getManager();
            $client = $em->getRepository('BackendBundle:Client')->find($id);

            if($client) {
                $client->setArchived($params->archived);
                $em->persist($client);
                $em->flush();
                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'Client is Successfully Archived!!!',
                    'employee' => $client
                );
            } else {
                $data = $this->error;
            }


        } else {
            $data = $this->accessError;
        }

        return $helpers->json($data);
    }

}