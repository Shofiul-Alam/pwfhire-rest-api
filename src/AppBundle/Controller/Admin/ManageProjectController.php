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
use BackendBundle\Entity\Project;
use BackendBundle\Entity\SkillCompetencyList;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Employee;


class ManageProjectController extends AAdmin {
    private $error = array();

    public function newAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $this->entity = new Project();
        return parent::newAction($request);

    }

    public function listAction(Request $request) {
        $this->entity = new Project();

        return parent::listAction($request);
    }


    public function editAction(Request $request)
    {
        $this->entity = new Project();
        return parent::editAction($request);
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

    public function getClientProjectsAction(Request $request) {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        $clientId = $request->get('clientId', null);



        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $em = $this->getDoctrine()->getManager();
            $queryBuilder = $em->createQueryBuilder();
            $client = $em->getRepository('BackendBundle:Client')->find($this->getEnitityId($clientId));
            $projects = $em->getRepository('BackendBundle:Project')->findClientProject($queryBuilder, $client);



            $data = array(
                "status" => "success",
                'code'  => 200,
                'total_items_count'   => count($projects),
                'data' => $projects

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

    public function archiveProjectAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $id = $this->getEnitityId($params->id);

            $em = $this->getDoctrine()->getManager();
            $project = $em->getRepository('BackendBundle:Project')->find($id);

            if($project) {
                $project->setArchived($params->archived);
                $em->persist($project);
                $em->flush();
                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'Project is Successfully Archived!!!',
                    'project' => $project
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