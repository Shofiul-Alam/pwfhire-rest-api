<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/11/2017
 * Time: 10:21 AM
 */

namespace AppBundle\Controller\Staff;


use AppBundle\Form\DocumentType;
use AppBundle\Form\EmployeeSkillEompetencyDocumentType;
use AppBundle\Form\TestType;
use AppBundle\Services\Helpers;
use BackendBundle\Entity\Base\AImage;
use BackendBundle\Entity\EmployeeSkillCompetencyDocument;
use BackendBundle\Entity\EmployeeSkillDocument;
use BackendBundle\Entity\Test;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Employee;
use AppBundle\Controller\Core\AController;

class EmployeeController extends AController
{
    private $error = array();

    public function newAction(Request $request)
    {

        $createdAt = new \DateTime("now");
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);

        $data = $this->CreateUser($params->user);

        if($data['status'] === 'success' && $data['user'] != null) {
            $this->entity = new Employee();
            $user = $data['user'];
            $this->entity->setUser($user);

            $request->authorisation = $data['token'];

            return $this->registerEmployee($request, $user);
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

    public function addCompitencyAction (Request $request) {

//        $employeeSkillCompetencyDocument = new EmployeeSkillCompetencyDocument();
//        $document = new EmployeeSkillDocument();
//        $employee = new Employee();
//
//
//        $employeeSkillCompetencyDocumentForm = $this->createForm(EmployeeSkillEompetencyDocumentType::class, null);
//
//        $employeeSkillCompetencyDocumentForm->submit($request->request->all());
//
//        $employeeSkillCompetencyDocumentForm->handleRequest($request);
//
//        $documentForm = $this->createForm(DocumentType::class, $document);
//        $documentForm->handleRequest($request);

//        $file = $request->files->get('document', null);



//        if ($documentForm->isSubmitted() && $documentForm->isValid()) {
//
//            $em = $this->getDoctrine()->getManager();
//            $em->persist($document);
//            $em->flush();
//        }

        return parent::addEmployeeSkillDocument($request);
    }

    public function registerEmployee(Request $request, $user)
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

            $this->entity->setUser($user);

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

    public function getSkillCompetencyDocumentsAction(Request $request) {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);


        if($this->isAuthenticated($token)) {

            $identity = $this->getIdentity($request);

            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->findBy(array('user'=>$identity->sub));
            $empId = $this->getEnitityId($employee[0]->getId());

//            $employeeSkillDocuments = $em->getRepository('BackendBundle:EmployeeSkillCompetencyDocument')->findBy(array('employee' => $employee[0]));
//
//            $entityName = $this->entity->getEntityClassName();

            $dql = "SELECT c FROM BackendBundle:EmployeeSkillCompetencyDocument c WHERE c.employee = $empId ORDER BY c.id ASC";


            $query = $em->createQuery($dql);

            $page = $request->query->getInt('page', 1);

            $paginator = $this->get('knp_paginator');
            $items_perPage = 10;
            $pagination = $paginator->paginate($query, $page, $items_perPage);
            $total_items_count = $pagination->getTotalItemCount();

            $dql2 = "SELECT i FROM BackendBundle:EmployeeInduction i WHERE i.employee = $empId ORDER BY i.id ASC";

            $query2 = $em->createQuery($dql2);

            $paginator2 = $this->get('knp_paginator');
            $pagination2 = $paginator2->paginate($query2, $page, $items_perPage);
            $total_items_count2 = $pagination2->getTotalItemCount();

            $obj = new \stdClass();
            $documentsArr = array();
            if(count($pagination->getItems()) > 0) {
                foreach ($pagination->getItems() as $item) {
                    $obj->skillCompetencyDocId = $item->getId();
                    $obj->issueDate = $item->getIssueDate();
                    $obj->expiryDate = $item->getExpiryDate();
                    $obj->description = $item->getDescription();
                    $obj->employeeId = $item->getEmployee()->getId();
                    $obj->employeeName = $item->getEmployee()->getUser()->getFirstName(). " " . $item->getEmployee()->getUser()->getLastName();
                    $obj->skillId = $item->getSkillCompetencyList()->getId();
                    $obj->skillName = $item->getSkillCompetencyList()->getName();
                    $firstDoc = $item->getDocuments()[0];
                    if($firstDoc != null) {
                        $obj->documentId = $firstDoc->getId();
                        $obj->documentPath = $firstDoc->getPath();
                        $obj->documentName = $firstDoc->getFileName();
                    }

                    array_push($documentsArr, $this->objectToArray($obj));

                    $obj = new \stdClass();


                }
            }
            if(count($pagination2->getItems()) > 0) {
                foreach ($pagination2->getItems() as $item) {

                    $obj->employeeId = $item->getEmployee()->getId();
                    $obj->employeeName = $item->getEmployee()->getUser()->getFirstName(). " " . $item->getEmployee()->getUser()->getLastName();
                    $obj->inductionId = $item->getInduction()->getId();
                    $obj->inductionName = $item->getInduction()->getName();
                    $obj->description = $item->getDescription();
                    $obj->id = $item->getId();
                    $firstDoc = $item->getEmployeeSkillDocument();
                    if($firstDoc != null) {
                        $obj->documentId = $firstDoc->getId();
                        $obj->documentPath = $firstDoc->getPath();
                        $obj->documentName = $firstDoc->getFileName();
                    }

                    array_push($documentsArr, $this->objectToArray($obj));


                }
            }



//            $data = $this->getModifiedData($pagination->getItems());

            if($total_items_count > $total_items_count2) {
                $total_items_count = $total_items_count;
            } else {
                $total_items_count = $total_items_count2;
            }

            $data = array(
                "status" => "success",
                'code'  => 200,
                'total_items_count'   => $total_items_count,
                'page_actual' => $page,
                'items_per_page' => $items_perPage,
                'total_pages'   => ceil($total_items_count / $items_perPage),
                'data' => $documentsArr

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

    public function objectToArray($d) {
        if (is_object($d)) {
            // Gets the properties of the given object
            // with get_object_vars function
            return get_object_vars($d);
        }
    }



}