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
use BackendBundle\Entity\EmployeeTimesheetDocument;
use BackendBundle\Entity\TimeSheet;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Controller\UserController;

class TimeSheetController extends AEmployeeController
{
    private $error = array();


    public function uploadTimeSheetAction (Request $request) {

        $params = json_decode($request->get('json', null));
        $upload = json_decode($request->get('upload', null));
        $token = $request->get('authorisation', null);
        $helpers = $this->get(Helpers::class);
        $fs = new Filesystem();

        if($this->isAuthenticated($token) && $this->isEmployee($token)) {

            $create = true;
            $this->entity = new TimeSheet();
            $identity = $this->getIdentity($request);


            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->findBy(array('user'=>$identity->sub));




            if ($create) {
                $this->prepareEntityData($this->entity, $params);
            }

            $this->entity->setEmployee($employee[0]);





            $entityName = $this->entity->getEntityClassName();



            if ($create) {

                $em->persist($this->entity);
                $em->flush();

                $document = $this->addTimeSheet($this->entity, $upload);
                $em->persist($document);
                $em->flush();

                $tmpId = $this->getEnitityId($upload->id);
                $tmpUpload = $em->getRepository('BackendBundle:Tmpimage')->find($tmpId);
                $em->remove($tmpUpload);
                $em->flush();
                $fs->remove('tmp/'.$upload->name);



                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' =>'TimeSheet is Successfully uploaded!!!',
                    'employeeTimeSheet' => $document
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

    public function updateTimeSheetAction(Request $request) {



        $this->entity = new TimeSheet();


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

                if($entity->getEmployeeTimesheetDocument()) {
                    $document = $entity->getEmployeeTimesheetDocument();
                    if($upload && $upload != null) {
                        $documentEntity = $this->updateTimeSheetDocment($document, $upload);
                        $entity->setEmployeeTimeSheetDocument($documentEntity);
                        if($documentEntity->getTimeSheet() == null) {
                            $documentEntity->setTimeSheet($entity);
                        }
                    }


                } else {


                    $documentEntity = new EmployeeTimesheetDocument();
                    if($upload && $upload != null) {
                        $this->updateTimeSheetDocment($documentEntity, $upload);
                        $documentEntity->setTimeSheet($entity);
                        $entity->setEmployeeTimeSheetDocument($documentEntity);
                    }
                }


            }
            if(count($this->error) > 0) {
                $sqlExecute = false;
            }
            if ($sqlExecute) {
                $em->persist($entity);
                $em->persist($documentEntity);
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

    public function archiveTimeSheetAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);

        if($this->isAuthenticated($token) && $this->isEmployee($token)) {

            $identity = $this->getIdentity($request);
            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->findBy(array('user'=>$identity->sub));
            $empId = $employee->getId();

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

            if($entity && $entity->getEmployee()->getId() == $empId) {
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

    public function allTimeSheetAction (Request $request) {

        $token = $request->get('authorisation', null);
        $helpers = $this->get(Helpers::class);


        if($this->isAuthenticated($token) && $this->isEmployee($token)) {

            $identity = $this->getIdentity($request);


            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->findBy(array('user'=>$identity->sub));
            $employeeId = $this->getEnitityId($employee[0]->getId());

            $dql = "SELECT c FROM BackendBundle:TimeSheet c WHERE c.employee=$employeeId ORDER BY c.id ASC";


            $query = $em->createQuery($dql);


            $page = $request->query->getInt('page', 1);

            $paginator = $this->get('knp_paginator');
            $items_perPage = 10;
            $pagination = $paginator->paginate($query, $page, $items_perPage);
            $total_items_count = $pagination->getTotalItemCount();

//            $data = $this->getModifiedData($pagination->getItems());

            $data = array(
                "status" => "success",
                'code' => 200,
                'total_items_count' => $total_items_count,
                'page_actual' => $page,
                'items_per_page' => $items_perPage,
                'total_pages' => ceil($total_items_count / $items_perPage),
                'data' => $pagination

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