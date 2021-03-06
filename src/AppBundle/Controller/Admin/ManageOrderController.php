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
use BackendBundle\Entity\AllocatedSkillCompetency;
use BackendBundle\Entity\EmployeeSkillCompetencyDocument;
use BackendBundle\Entity\Order;
use BackendBundle\Entity\Project;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Employee;


class ManageOrderController extends AAdmin {
    private $error = array();

    public function newAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $this->entity = new Order();
        return parent::newAction($request);

    }

    public function listAction(Request $request) {
        $this->entity = new Order();

        return parent::listAction($request);
    }


    public function editAction(Request $request)
    {
        $this->entity = new Order();
        return parent::editAction($request);
    }

    public function getProjectOrdersAction(Request $request)
    {
        $this->entity = new Order();
        return parent::listAction($request);
    }

    public function setContacts($contacts, $client, $project) {
        $contactsData = array();
        foreach($contacts as $contactStd) {
            $em = $this->getDoctrine()->getManager();
            $contact = $em->getRepository('BackendBundle:Contact')->find($this->getEnitityId($contactStd->id));
            $newContactAllocation = new AllocatedContact();
            $newContactAllocation->setContact($contact);
            $newContactAllocation->setClient($client);
            $newContactAllocation->setProject($project);

            $em->persist($newContactAllocation);
            $em->flush();
            $contactD['id'] = $contactStd->id;
            $contactD['text'] = $contactStd->text;
            array_push($contactsData, $contactD);

        }

        return $contactsData;
    }
    public function setSkillCompetencies($skillCompetencies, $project) {
        $skillCompetencyArr = array();
        foreach($skillCompetencies as $skillCompetency) {
            $em = $this->getDoctrine()->getManager();
            $skillCompetencyObj = $em->getRepository('BackendBundle:SkillCompetencyList')->find($this->getEnitityId($skillCompetency->id));
            $newSkillAllocation = new AllocatedSkillCompetency();
            $newSkillAllocation->setProject($project);
            $newSkillAllocation->setSkillCompetencyList($skillCompetencyObj);

            $em->persist($newSkillAllocation);
            $em->flush();
            $skill['id'] = $skillCompetencyObj->getId();
            $skill['text'] = $skillCompetencyObj->getName();
            array_push($skillCompetencyArr, $skill);

        }
        return $skillCompetencyArr;
    }

    public function addSkillCompetencyAction (Request $request) {

        $token = $request->get('authorisation', null);

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $this->entity = new EmployeeSkillCompetencyDocument();

            return parent::addEmployeeDocument($request);
        } else {
            return $this->accessError;
        }

    }

    public function registerEmployee(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->authorisation;


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

    public function employeeAllDocumentsAction(Request $request) {
        $this->entity = new Employee();

        return parent::getAllSkillCompetencyDocuments($request);
    }

    public function archiveOrderAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $id = $this->getEnitityId($params->id);

            $em = $this->getDoctrine()->getManager();
            $order = $em->getRepository('BackendBundle:Order')->find($id);

            if($order) {
                $order->setArchived($params->archived);;
                $em->persist($order);
                $em->flush();
                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'Order is Successfully Archived!!!',
                    'employee' => $order
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