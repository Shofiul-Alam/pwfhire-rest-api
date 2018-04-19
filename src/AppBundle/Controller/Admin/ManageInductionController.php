<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/11/2017
 * Time: 8:15 PM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Services\Helpers;
use BackendBundle\Entity\Field;
use BackendBundle\Entity\Form;
use BackendBundle\Entity\Induction;
use BackendBundle\Entity\UserInductionData;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;


class ManageInductionController extends AAdmin
{
    private $error = array();

    public function newAction(Request $request)
    {

        $this->entity = new Induction();
        return parent::newAction($request);
    }

    public function listAction(Request $request) {
        $this->entity = new Induction();

        return parent::listAction($request);
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

        if ($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $create = true;
            $em = $this->getDoctrine()->getManager();

            $user_id = $this->getEnitityId($params->user->id);
            $user = $em->getRepository('BackendBundle:User')->find($user_id);
            $induction = $em->getRepository('BackendBundle:Induction')->find($inductionId);

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

            }


//            $entityName = $this->entity->getEntityClassName();
//            $uniqueIdentifire = $this->getUniqueIdentifier($entityName);
//
//            if($uniqueIdentifire != null){
//                $isset_entity = $em->getRepository('BackendBundle:'.$entityName)->findBy(array(
//                    $uniqueIdentifire => $this->entity->{'get'.ucwords($uniqueIdentifire)}()
//                ));
//            } else {
//                $isset_entity = array();
//            }
//
//
//            if (count($this->error) > 0) {
//                $create = false;
//            }
//
//
//            if (count($isset_entity) == 0) {
//                if ($create) {
//                    $em->persist($this->entity);
//                    $em->flush();
//
//                    $data = array(
//                        'status' => "success",
//                        'code' => 200,
//                        'msg' => $entityName. ' is Successfully Created!!!',
//                        $entityName => $this->entity
//                    );
//                } else {
//                    $data = array_merge($this->duplicateError, array(
//                        'error_data' => $this->error
//                    ));
//
//                }
//
//            } else {
//                $data = array_merge($this->duplicateError, array(
//                    'error_data' => $this->error
//                ));
//            }
//
//        } else {
//            return $helpers->json($this->accessError);

        }

        return $helpers->json($this->accessError);
    }


    public function getAllSubmittedInductionsAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        if($token == null) {
            $token = $request->authorisation;
        }

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $this->entity = new UserInductionData();
            $em = $this->getDoctrine()->getManager();
            $entityName = $this->entity->getEntityClassName();
            $allData = $em->getRepository('BackendBundle:'.$entityName)->findAll();
            $createNewForm = false;
            $emptyArr = false;
            $removeField = true;

            $submittedFormsArr = array();

            $lastFormFieldId = null;
            $lastField = new Field();


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
            return $helpers->json($this->accessError);
        }

    }

    public function getAllowedInductionForEmployeeAction(Request $request) {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        $employeeId = $request->get('empId', null);

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $create = true;

            $em = $this->getDoctrine()->getManager();
            $empId = $this->getEnitityId($employeeId);
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
            $data = array_merge($this->accessError, array(
                'error_data' => $this->error
            ));
        }


        return $helpers->json($data);
    }

}