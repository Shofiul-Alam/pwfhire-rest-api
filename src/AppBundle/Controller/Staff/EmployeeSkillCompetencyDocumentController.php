<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/11/2017
 * Time: 10:21 AM
 */

namespace AppBundle\Controller\Staff;


use AppBundle\Controller\Core\AEmployeeController;
use AppBundle\Services\Helpers;
use BackendBundle\Entity\EmployeeSkillCompetencyDocument;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Controller\UserController;

class EmployeeSkillCompetencyDocumentController extends AEmployeeController
{
    private $error = array();


    public function addSkillCompetencyAction (Request $request) {

        $params = json_decode($request->get('json', null));
        $upload = json_decode($request->get('upload', null));
        $token = $request->get('authorisation', null);
        $helpers = $this->get(Helpers::class);
        $fs = new Filesystem();

        if($this->isAuthenticated($token) && $this->isEmployee($token)) {

            $create = true;
            $this->entity = new EmployeeSkillCompetencyDocument();

            if ($create) {
                $this->prepareEntityData($this->entity, $params);
            }



            $em = $this->getDoctrine()->getManager();

            $entityName = $this->entity->getEntityClassName();



            if ($create) {

                $em->persist($this->entity);
                $em->flush();

                $document = $this->addDocument($this->entity, $upload);
                $em->persist($document);
                $em->flush();

                $tmpId = $this->getEnitityId($upload->id);
                $tmpUpload = $em->getRepository('BackendBundle:Tmpimage')->find($tmpId);
                $em->remove($tmpUpload);
                $em->flush();
                $fs->remove('tmp/'.$upload->name);



                $obj = new \stdClass();
                $empSkillDoc = $document->getEmployeeSkillCompetencyDocument();
                $obj->issueDate = $empSkillDoc->getIssueDate();
                $obj->expiryDate = $empSkillDoc->getExpiryDate();
                $obj->employeeId = $empSkillDoc->getEmployee()->getId();
                $obj->employeeName = $empSkillDoc->getEmployee()->getUser()->getFirstName(). " " . $this->entity->getEmployee()->getUser()->getLastName();
                $obj->skillId = $empSkillDoc->getSkillCompetencyList()->getId();
                $obj->skillName = $empSkillDoc->getSkillCompetencyList()->getName();
                $obj->documentId = $document->getId();
                $obj->documentPath = $document->getPath();
                $obj->documentName = $document->getFileName();
                $objArr = $this->objectToArray($obj);

                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => $entityName. ' is Successfully Created!!!',
                    'document' => $objArr
                );
            } else {
                $data = array_merge($this->duplicateError, array(
                    'error_data' => $this->error
                ));

            }
        } else {
            return $this->accessError;
        }

        return $helpers->json($data);

    }

    public function updateSkillCompetencyAction(Request $request) {

        $json = $request->get('json', null);
        $params = json_decode($json);
        if(property_exists($params, 'induction')) {
            $this->entity = new EmployeeInduction();
        } else {
            $this->entity = new EmployeeSkillCompetencyDocument();
        }

        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        $json = $request->get('json', null);

        $params = json_decode($json);
        $upload = json_decode($request->get('upload', null));
        $sqlExecute = false;


        if ($this->isAuthenticated($token) && $this->isEmployee($token)) {

            $identity = $this->getIdentity($request);
            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->findBy(array('user'=>$identity->sub));
            $empId = $employee[0]->getId();

            $em = $this->getDoctrine()->getManager();

            $id = $this->getEnitityId($params->id);

            if($id && $empId == $params->employee->id) {
                $sqlExecute = true;
            }

            $entityClassName = $this->entity->getEntityClassName();

            if ($sqlExecute) {

                $entity = $em->getRepository('BackendBundle:'.$entityClassName)->find($id);
                $this->prepareEntityData($entity, $params);

                if($entityClassName == 'EmployeeSkillCompetencyDocument') {
                    $documents = $entity->getDocuments();
                    $documentEntity = $documents[0];
                    if($upload && $upload != null) {
                        $this->updateEmployeeDocment($documentEntity, $upload);
//                        $entity->setDocument($documentEntity);
                    }


                } else {
                    $documentEntity = $entity->getEmployeeSkillDocument();
                    if($upload && $upload != null) {
                        $this->updateEmployeeDocment($documentEntity, $upload);
                        $entity->setEmployeeSkillDocument($documentEntity);
                    }
                }


            }
            if(count($this->error) > 0) {
                $sqlExecute = false;
            }
            if ($sqlExecute) {
                $em->persist($entity);
                $em->flush();


//                $data = $this->getSingleModifiedData($entity);
//                if(count($this->getCollectionProperty($entity)) > 0) {
//                    $collectionProperties = $this->getCollectionProperty($entity);
//                    foreach ($collectionProperties as $prop) {
//                        if(count($entity->{'spliced'.$prop}) > 0) {
//                            foreach ($entity->{'spliced'.$prop} as $dEntity) {
//                                $em->remove($dEntity);
//                                $em->flush();
//                            }
//                        }
//                    }
//                }


                $data = array(
                    'status' => $entityClassName." is successfully updated",
                    'code' => 200,
                    'msg' => 'Updated Successfully!!!',
                    'data' => $entity
                );
            } else {

                $data = array_merge($this->updateError, array(
                    'data_error' => $this->error
                ));
            }

        } else {
            $data = $this->updateError;
        }

        return $helpers->json($data);
    }

    public function deleteSkillCompetencyAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);

        if($this->isAuthenticated($token) && $this->isEmployee($token)) {

            $identity = $this->getIdentity($request);
            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->findBy(array('user'=>$identity->sub));
            $empId = $employee[0]->getId();

            $em = $this->getDoctrine()->getManager();
            if($params->inductionId != null && $params->inductionName != null) {
                $entityName = 'EmployeeInduction';
                $inductionId = $this->getEnitityId($params->id);
                if($inductionId) {
                    $entity = $em->getRepository('BackendBundle:'.$entityName)->find($inductionId);
                    $skillCompetencyDoc = $entity->getEmployeeSkillDocument();
                }
            } else {
                $entityName = 'EmployeeSkillCompetencyDocument';
                $id = $this->getEnitityId($params->skillCompetencyDocId);
                if($id) {
                    $skillCompetencyDoc  = $em->getRepository('BackendBundle:'.$entityName)->find($id);
                    $entity = $skillCompetencyDoc->getDocuments()[0];

                    if($skillCompetencyDoc && $skillCompetencyDoc->getEmployee()->getId() == $empId) {
                        if($entity) {
                            $em->remove($entity);
                            $em->flush();
                        }

                        $em->persist($skillCompetencyDoc);
                        $em->remove($skillCompetencyDoc);
                        $em->flush();

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
                }

            }


        } else {
            $data = $this->accessError;
        }

        return $helpers->json($data);
    }

    public function getSkillListAction (Request $request) {

        $token = $request->get('authorisation', null);
        $helpers = $this->get(Helpers::class);


        if($this->isAuthenticated($token) && $this->isEmployee($token)) {

            $em = $this->getDoctrine()->getEntityManager();

            $dql = "SELECT c FROM BackendBundle:SkillCompetencyList c ORDER BY c.id ASC";


            $query = $em->createQuery($dql);
            $result= $query->getResult();



//            $page = $request->query->getInt('page', 1);
//
//            $paginator = $this->get('knp_paginator');
//            $items_perPage = 10;
//            $pagination = $paginator->paginate($query, $page, $items_perPage);
//            $total_items_count = $pagination->getTotalItemCount();

//            $data = $this->getModifiedData($pagination->getItems());

            $data = array(
                "status" => "success",
                'code' => 200,
//                'total_items_count' => $total_items_count,
//                'page_actual' => $page,
//                'items_per_page' => $items_perPage,
//                'total_pages' => ceil($total_items_count / $items_perPage),
                'data' => $result

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