<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/11/2017
 * Time: 8:15 PM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Services\Helpers;
use BackendBundle\Entity\EmployeeInduction;
use BackendBundle\Entity\EmployeeSkillCompetencyDocument;
use BackendBundle\Entity\UserDeclearation;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Employee;

class ManageEmployeeController extends AAdmin {
    private $error = array();

    public function newAction(Request $request)
    {

        $createdAt = new \DateTime("now");
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $avatar = $request->get('upload', null);
        $params = json_decode($json);
        $userAvatar = json_decode($avatar);

        $data = $this->CreateUser($params->user, $userAvatar);

        if($data['status'] === 'success' && $data['user'] != null) {
            $this->entity = new Employee();
            $user = $data['user'];
            $this->entity->setUser($user);

            $request->authorisation = $data['token'];

            return $this->registerEmployee($request);
        } else {
            return $helpers->json($this->registrationError);
        }



    }

    public function listAction(Request $request) {
        $this->entity = new Employee();

        return parent::listAction($request);
    }


    public function editAction(Request $request)
    {
        $this->entity = new Employee();
        return parent::editAction($request);
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

    public function uploadInductionAction (Request $request) {
        $token = $request->get('authorisation', null);

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $this->entity = new EmployeeInduction();
            return parent::addEmployeeInductionDocument($request);
        } else {
            return $this->accessError;
        }
    }

    public function archiveEmployeeAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $id = $this->getEnitityId($params->id);

            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->find($id);

            if($employee) {
                $employee->setArchived($params->archived);
                $em->persist($employee);
                $em->flush();
                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'Employee is Successfully Archived!!!',
                    'employee' => $employee
                );
            } else {
                $data = $this->error;
            }


        } else {
            $data = $this->accessError;
        }

        return $helpers->json($data);
    }

    public function unArchiveEmployeeAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $id = $this->getEnitityId($params->id);

            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->find($id);

            if($employee) {
                $employee->setIsDeleted(false);
                $em->persist($employee);
                $em->flush();
                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'Employee is Successfully Unarchived!!!',
                    'employee' => $employee
                );
            } else {
                $data = $this->error;
            }


        } else {
            $data = $this->accessError;
        }

        return $helpers->json($data);
    }

    public function approveEmployeeAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $id = $this->getEnitityId($params->id);

            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->find($id);

            if($employee) {
                $employee->setApproved($params->approved);
                $em->persist($employee);
                $em->flush();
                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'Employee is Successfully Approved!!!',
                    'employee' => $employee
                );
            } else {
                $data = $this->error;
            }


        } else {
            $data = $this->accessError;
        }

        return $helpers->json($data);
    }

    public function deleteEmployeeDocAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $em = $this->getDoctrine()->getManager();
            if($params->inductionId != null && $params->inductionName != null) {
                $entityName = 'EmployeeInduction';
                $inductionId = $this->getEnitityId($params->id);
                if($inductionId) {
                    $entity = $em->getRepository('BackendBundle:'.$entityName)->find($inductionId);
                    $skillCompetencyDoc = $entity->getEmployeeSkillDocument();
                }
            } else {
                $entityName = 'EmployeeSkillDocument';
                $id = $this->getEnitityId($params->documentId);
                if($id) {
                    $entity = $em->getRepository('BackendBundle:'.$entityName)->find($id);
                    $skillCompetencyDoc = $entity->getEmployeeSkillCompetencyDocument();
                }

            }

            if($entity) {
                $em->persist($entity);
                $em->remove($entity);
                $em->flush();
                if($skillCompetencyDoc) {
                    $em->remove($skillCompetencyDoc);
                    $em->flush();
                }
                if($params->documentName) {
                    $fs = new Filesystem();
                    $fs->remove('documents/'.$params->documentName);
                }

                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'Document is Successfully Deleted!!!',
                );
            } else {
                $data = $this->error;
            }


        } else {
            $data = $this->accessError;
        }

        return $helpers->json($data);
    }



    public function registerEmployee(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $userDeclearation = $request->get('userDeclearation', null);
        $userDeclearation = json_decode($userDeclearation);
        $token = $request->authorisation;
        $params->user = null;
        $params->userDeclearation = null;


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

                    $userDeclear = new UserDeclearation();
                    $this->prepareEntityData($userDeclear, $userDeclearation);
                    $userDeclear->setEmployee($this->entity);
                    $em->persist($userDeclear);
                    $em->flush();
                    $this->entity->setUserDeclearation($userDeclear);

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

    public function updateEmployeeDocAction(Request $request) {

        $json = $request->get('json', null);
        $params = json_decode($json);
        if(property_exists($params, 'induction')) {
            $this->entity = new EmployeeInduction();
        } else {
            $this->entity = new EmployeeSkillCompetencyDocument();
        }

        return parent::updateEmployeeDocAction($request);
    }




}