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
use BackendBundle\Entity\Employee;
use BackendBundle\Entity\EmployeeInduction;
use BackendBundle\Entity\Field;
use BackendBundle\Entity\Form;
use BackendBundle\Entity\Induction;
use BackendBundle\Entity\User;
use BackendBundle\Entity\UserInductionData;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Client;
use AppBundle\Controller\UserController;

class InductionController extends AEmployeeController
{
    private $error = array();

    public function getInductionAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', false);


        if($this->isAuthenticated($token)) {
            $create = true;
            $identity = $this->getIdentity($request);


            $em = $this->getDoctrine()->getManager();
            $employee = $em->getRepository('BackendBundle:Employee')->findBy(array('user' => $identity->sub));
            $empId = $this->getEnitityId($employee[0]->getId());
            $inductions = $em->getRepository('BackendBundle:InductionPermission')->findBy(array('employee'=>$empId));


            $inductionArr = array();

            if(count($inductions) > 0) {
                foreach($inductions as $induction) {

                    array_push($inductionArr, $induction);
                }
            }



            $data = array(
                'status' => "success",
                'code' => 200,
                'msg' => ' All inductions!!!',
                'data' => $inductionArr
            );

        } else {
            $data = array_merge($this->duplicateError, array(
                'error_data' => $this->error
            ));
        }


        return $helpers->json($data);


    }


    public function saveInductionDataAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);
        $inductionId = $this->getEnitityId($request->get('induction', null));


        if ($token == null) {
            $token = $request->authorisation;
        }

        if ($this->isAuthenticated($token) && $this->isEmployee($token)) {
            $create = false;
            $identity = $this->getIdentity($request);
            $em = $this->getDoctrine()->getManager();


            $user_id =$identity->sub;
            $user = $em->getRepository('BackendBundle:User')->find($user_id);
            $employee = $em->getRepository('BackendBundle:Employee')->findBy(array('user'=>$user_id));
            $induction = $em->getRepository('BackendBundle:Induction')->find($inductionId);

            if($this->hasInductionPermission($induction, $em, $employee[0] )) {
                $create = true;

            }


            if ($create) {


                foreach ($params->form->fieldsArr as $field) {
                    $userInductionData = new UserInductionData();
                    if ($field->valueArr != null) {
                        $valueArrId = $this->getEnitityId($field->valueArr->id);
                        $userInductionData->setValueArrId($valueArrId);
                    } else {
                        $userInductionData->setValue($field->value);
                    }
                    $userInductionData->setFieldId($this->getEnitityId($field->fieldId));
                    $userInductionData->setInduction($induction);
                    $userInductionData->setUser($user);
                    $em->persist($userInductionData);
                    $em->persist($user);
                    $em->flush();
                }

                $data = array(
                        'status' => "success",
                        'code' => 200,
                        'msg' => ' Induction submitted Successfully!!!',
                        'inductionData' => $userInductionData
                    );

            } else {
                $data = $this->accessError;
            }



        }

        return $helpers->json($data);
    }

    public function getInductionListAction (Request $request) {



    }

    public function getEmployeeSubmittedInductionsAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        if($token == null) {
            $token = $request->authorisation;
        }

        if($this->isAuthenticated($token) && $this->isEmployee($token)) {

            $identity = $this->getIdentity($request);
            $user_id = $identity->sub;


            $this->entity = new UserInductionData();
            $em = $this->getDoctrine()->getManager();
            $entityName = $this->entity->getEntityClassName();
            $allData = $em->getRepository('BackendBundle:'.$entityName)->findBy(array('user' => $user_id));
            $createNewForm = false;
            $emptyArr = false;
            $removeField = true;

            $submittedFormsArr = array();

            $lastFormFieldId = null;
            $lastField = new Field();

        if(count($allData) > 0) {
            foreach($allData as $key => $data) {


                if($emptyArr) {
                    $data->getInduction()->getForm()->emptyFieldsArr();
                    $emptyArr = false;
                    $removeField = false;
                }

                $induction = $data->getInduction();


                $user = $data->getUser();
                $nextData = $allData[$key+1];
                if($nextData) {
                    $nexField = $em->getRepository('BackendBundle:Field')->find($nextData->getFieldId());
                } else {
                    $nexField = false;
                }

                $field = $em->getRepository('BackendBundle:Field')->find($data->getFieldId());
                if($key != 0 && $nexField) {
                    if($nexField->getType() == 'header') {
                        $createNewForm = true;
                    }

                }


                if ($lastFormFieldId == $data->getFieldId()) {
//                    $field->emptyValueArr();
                    $valueA = $em->getRepository('BackendBundle:ValueArr')->find($data->getValueArrId());
                    $valueA->setSelected(true);
                    $field->addValueArr($valueA);
                    $data->getInduction()->getForm()->addFieldArr($field);

                }
                else {


                    if($data->getValue() != null) {
                        $field->setValue($data->getValue());
                    } elseif($data->getValueArrId() != null) {
                        if(!$field->appliedRemove){
                            $field->emptyValueArr();
                        }
                        $valueArr = $em->getRepository('BackendBundle:ValueArr')->find($data->getValueArrId());
                        $valueArr->setSelected(true);
                        $field->addValueArr($valueArr);
                    }
                    if($removeField) {
                        $data->getInduction()->getForm()->removeFieldsArr($field);
                    }

                    $data->getInduction()->getForm()->addFieldArr($field);

                    $lastFormFieldId = $data->getFieldId();
                }

                if($createNewForm || count($allData) == ($key+1)) {
                    $userInductionData = new UserInductionData();
                    $newForm = new Form();
                    $newInduction = new Induction();
                    $form = $data->getInduction()->getForm();
                    $fieldArr = $form->getFieldsArr();
                    $normId = $this->getEnitityId($form->getId());
                    $fQ = $em->getRepository('BackendBundle:Form')->find($normId);
                    $newForm->setId($this->getEnitityId($fQ->getId()));
                    $newForm->setFormName($form->getFormName());
                    $newForm->replaceFieldsArr($fieldArr);
                    $newInduction->setEncryptedId($induction->getId());
                    $newInduction->setName($induction->getName());
                    $newInduction->setForm($newForm);
                    $userInductionData->setInduction($newInduction);
                    $userInductionData->setUser($user);
                    $userInduct = $userInductionData;
                    array_push($submittedFormsArr, $userInduct);
                    if(count($allData) == ($key+1)) {
                        if ($submittedFormsArr) {
                            $data = array(
                                'status' => "success",
                                'code' => 200,
                                'msg' => 'All forms retrive Successfully!!!',
                                "data" => $submittedFormsArr
                            );

                        }


                        return $helpers->json($data);
                    }

                    $createNewForm = false;
                    $emptyArr = true;


                }


            }
        } else {
            return $helpers->json($this->simpleError);
        }



        } else {
            return $helpers->json($this->accessError);
        }

    }

    public function updateInductionDataAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);
        $inductionId = $this->getEnitityId($request->get('induction', null));


        if ($token == null) {
            $token = $request->authorisation;
        }

        if ($this->isAuthenticated($token) && $this->isEmployee($token)) {
            $update = false;
            $identity = $this->getIdentity($request);
            $em = $this->getDoctrine()->getManager();


            $user_id =$identity->sub;
            $user = $em->getRepository('BackendBundle:User')->find($user_id);
            $employee = $em->getRepository('BackendBundle:Employee')->findBy(array('user'=>$user_id));
            $induction = $em->getRepository('BackendBundle:Induction')->find($inductionId);

            if($this->hasInductionPermission($induction, $em, $employee[0] )) {
                $update = true;

            }


            $inductionDataFromDb = $em->getRepository('BackendBundle:UserInductionData')
                                            ->findBy(array('induction' => $inductionId,
                                                'user' => $user_id,

                                            ));

            if ($update) {

                foreach ($params->induction->form->fieldsArr as $field) {


                    $resultArr = $this->prepareFieldData($inductionDataFromDb, $field);
                    $inductionDataFromDb = $resultArr['inductionDataFromDb'];
                    $userInductionData = $resultArr['userInductionData'];

                    if($userInductionData->getInduction() == null) {
                        $userInductionData->setFieldId($this->getEnitityId($field->fieldId));
                        $userInductionData->setInduction($induction);
                        $userInductionData->setUser($user);
                    }

                    $em->persist($userInductionData);
                    $em->persist($user);
                    $em->flush();

                }

                foreach($inductionDataFromDb as $dbData) {
                    $em->remove($dbData);
                    $em->flush();
                }

                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => ' Induction submitted Successfully!!!',
                    'inductionData' => $userInductionData
                );

            } else {
                $data = $this->accessError;
            }



        }

        return $helpers->json($data);
    }

    protected function hasInductionPermission(Induction $induction, EntityManager $em, Employee $employee) {

        $hasPermission = false;
        $inductionId = $this->getEnitityId($induction->getId());
        $empId = $this->getEnitityId($employee->getId());
        $permitedInduction = $em->getRepository('BackendBundle:InductionPermission')->findBy(array('induction' => $inductionId, 'employee' => $empId));
        $permitedInduction = $permitedInduction[0];

        if($permitedInduction) {
            if($permitedInduction->getEmployee()->getId() == $employee->getId()) {
                $hasPermission = true;
            }
        }

        return $hasPermission;

    }

    public function prepareFieldData($inductionDataFromDB, $field) {

        foreach($inductionDataFromDB as $key => $data) {

            if($data->getFieldId() == $this->getEnitityId($field->fieldId)) {
                if($field->valueArr != null) {

                    $valueArrId = $this->getEnitityId($field->valueArr->id);
                    $data->setValueArrId($valueArrId);

                    unset($inductionDataFromDB[$key]);
                    $resultArr['inductionDataFromDb'] = $inductionDataFromDB;
                    $resultArr['userInductionData'] = $data;

                    return $resultArr;

                } else {
                    $data->setValue($field->value);
                    unset($inductionDataFromDB[$key]);
                    $resultArr['inductionDataFromDb'] = $inductionDataFromDB;
                    $resultArr['userInductionData'] = $data;

                    return $resultArr;
                }
            }
        }

        $data = new UserInductionData();

        if($field->valueArr != null) {

            $valueArrId = $this->getEnitityId($field->valueArr->id);
            $data->setValueArrId($valueArrId);

            $resultArr['inductionDataFromDb'] = $inductionDataFromDB;
            $resultArr['userInductionData'] = $data;

            return $resultArr;

        } else {
            $data->setValue($field->value);
            $resultArr['inductionDataFromDb'] = $inductionDataFromDB;
            $resultArr['userInductionData'] = $data;

            return $resultArr;
        }

    }

    public function uploadEmployeeInductionAction(Request $request) {

            $params = json_decode($request->get('json', null));
            $upload = json_decode($request->get('upload', null));
            $token = $request->get('authorisation', null);
            $helpers = $this->get(Helpers::class);
            $fs = new Filesystem();

            $this->entity = new EmployeeInduction();


        if ($this->isAuthenticated($token) && $this->isEmployee($token)) {
                $create = false;
                $identity = $this->getIdentity($request);


                $em = $this->getDoctrine()->getManager();
                $employee = $em->getRepository('BackendBundle:Employee')->findBy(array('user' => $identity->sub));

                $entityName = $this->entity->getEntityClassName();

                $this->prepareEntityData($this->entity, $params);
                $ind = $this->entity->getInduction();

            if($this->hasInductionPermission($ind, $em, $employee[0])) {
                $create = true;
            }


            if ($create) {
                $document = $this->addInductionDocument($upload);
                $this->entity->setEmployeeSkillDocument($document);

                $em->persist($this->entity);
                $em->flush();

                $tmpId = $this->getEnitityId($upload->id);
                $tmpUpload = $em->getRepository('BackendBundle:Tmpimage')->find($tmpId);
                $em->remove($tmpUpload);
                $em->flush();
                $fs->remove('tmp/' . $upload->name);


                $obj = new \stdClass();

                $obj->employeeId = $this->entity->getEmployee()->getId();
                $obj->employeeName = $this->entity->getEmployee()->getUser()->getFirstName() . " " . $this->entity->getEmployee()->getUser()->getLastName();
                $obj->inductionId = $this->entity->getInduction()->getId();
                $obj->inductionName = $this->entity->getInduction()->getName();
                $obj->documentId = $document->getId();
                $obj->documentPath = $document->getPath();
                $obj->documentName = $document->getFileName();
                $objArr = $this->objectToArray($obj);

                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => $entityName . ' is Successfully Created!!!',
                    'document' => $objArr
                );
            } else {
                $data = array_merge($this->duplicateError, array(
                    'error_data' => $this->error
                ));

            }
        }


            return $helpers->json($data);

    }








}