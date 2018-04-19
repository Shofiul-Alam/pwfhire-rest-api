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
use BackendBundle\Entity\UserFormData;
use BackendBundle\Entity\UserFormSubmission;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;


class ManageFormController extends AAdmin
{
    private $error = array();

    public function addFormAction(Request $request)
    {
        $this->entity = new Form();
        $em = $this->getDoctrine()->getManager();
        $entityName = $this->entity->getEntityClassName();


        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);
        if ($token == null) {
            $token = $request->authorisation;
        }

        if ($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $create = true;

            if ($create) {
//                $this->prepareEntityData($this->entity, $params);
                if ($params->name) {
                    $this->entity->setFormName($params->name);
                } else {
                    $day = date("Y-m-d");
                    $this->entity->setFormName('Form-' . $day);
                }

            }


//            $uniqueIdentifire = $this->getUniqueIdentifier($entityName);
//
//
//            if ($uniqueIdentifire != null) {
//                $isset_entity = $em->getRepository('BackendBundle:' . $entityName)->findBy(array(
//                    $uniqueIdentifire => $this->entity->{'get' . ucwords($uniqueIdentifire)}()
//                ));
//            } else {
//                $isset_entity = array();
//            }

            $isset_entity =array();

            if (count($this->error) > 0) {
                $create = false;
            }

            if (count($isset_entity) == 0) {
                if ($create) {
                    $em->persist($this->entity);
                    $em->flush();
                    $create = true;
                }
            }

            if ($create) {
                $formDatas = $params->formData;
                if (is_array($formDatas)) {
                    foreach ($formDatas as $key=>$formData) {
                        $formField = new Field();
                        $this->prepareEntityData($formField, $formData);
                        $formField->setForm($this->entity);
                        $em->persist($formField);
                        $em->flush();
                    }
                }
            }


            if (count($isset_entity) == 0) {
                if ($create) {
                    $entity = $em->getRepository('BackendBundle:Form')->find(29);
                    $data = array(
                        'status' => "success",
                        'code' => 200,
                        'msg' => $entityName . ' is Successfully Created!!!',
                        $entityName => $entity
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

    public function updateFormAction(Request $request)
    {
        $this->entity = new Form();
        $entityName = $this->entity->getEntityClassName();


        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);
        if ($token == null) {
            $token = $request->authorisation;
        }

        if ($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $create = true;

            $formId = $this->getEnitityId($params->id);

            $em = $this->getDoctrine()->getManager();
            $form = $em->getRepository('BackendBundle:Form')->find($formId);
            $this->entity = $form;

            if ($create) {
                $this->prepareEntityData($this->entity, $params);
            }


            $isset_entity =array();

            if (count($this->error) > 0) {
                $create = false;
            }

            if (count($isset_entity) == 0) {
                if ($create) {
                    $em->persist($this->entity);
                    $em->flush();
                    $create = true;
                }
            }

            $entity = $this->entity;

            if(count($this->getCollectionProperty($entity)) > 0) {
                $collectionProperties = $this->getCollectionProperty($entity);
                foreach ($collectionProperties as $prop) {
                    if(count($entity->{'spliced'.$prop}) > 0) {
                        foreach ($entity->{'spliced'.$prop} as $dEntity) {

                                $dEntity->setForm(null);
                                $em->persist($dEntity);
                                $em->flush();

                        }
                    }
                }
            }


            if ($this->entity) {
                if ($create) {

                    $data = array(
                        'status' => "success",
                        'code' => 200,
                        'msg' => $entityName . ' is Successfully Created!!!',
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

    public function getAllFormsAction(Request $request) {

        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        if($token == null) {
            $token = $request->authorisation;
        }

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $this->entity = new Form();
            $em = $this->getDoctrine()->getManager();
            $entityName = $this->entity->getEntityClassName();
            $forms = $em->getRepository('BackendBundle:'.$entityName)->findAll();

            $em->createQuery();


            if ($forms) {
                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'All forms retrive Successfully!!!',
                    "data" => $forms
                );

            } else {
                return $helpers->json($this->accessError);
            }



            return $helpers->json($data);
        }



    }

    public function getFormAction(Request $request) {

        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        $id = json_decode($request->get('json', null));

        if($token == null) {
            $token = $request->authorisation;
        }

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $entityId = $this->getEnitityId($id->id);
            $em = $this->getDoctrine()->getManager();
            $form = $em->getRepository('BackendBundle:Form')->find($entityId);


            if ($form) {
                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'Form retrive Successfully!!!',
                    "data" => $form
                );

            } else {
                return $helpers->json($this->accessError);
            }



            return $helpers->json($data);
        }



    }


    public function saveFormDataAction(Request $request) {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);


        if($token == null) {
            $token = $request->authorisation;
        }

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $create = true;
            $em = $this->getDoctrine()->getManager();

            $user_id = $this->getEnitityId($params->user->id);
            $user = $em->getRepository('BackendBundle:User')->find($user_id);

            if ($create) {

                $form_id = $this->getEnitityId($params->form->id);
                $form = $em->getRepository('BackendBundle:Form')->find($form_id);
                foreach ($params->form->fieldsArr as $field) {
                    $userFormData = new UserFormData();
                    if($field->valueArr != null) {
                        $valueArrId = $this->getEnitityId($field->valueArr->id);
                        $userFormData->setValueArrId($valueArrId);
                    } else {
                        $userFormData->setValue($field->value);
                    }
                    $userFormData->setFieldId($this->getEnitityId($field->fieldId));
                    $userFormData->setForm($form);
                    $userFormData->addUser($user);
                    $user->addUserFormData($userFormData);
                    $em->persist($userFormData);
                    $em->persist($user);
                    $em->flush();
                    $data = array(
                        'status' => "success",
                        'code' => 200,
                        'msg' =>  'Form submission is Successfull !!!',
                        'data' => $userFormData
                    );
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



        return $helpers->json($data);
    }

    public function getAllSubmittedFormsAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        if($token == null) {
            $token = $request->authorisation;
        }

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $this->entity = new UserFormData();
            $em = $this->getDoctrine()->getManager();
            $entityName = $this->entity->getEntityClassName();
            $allData = $em->getRepository('BackendBundle:'.$entityName)->findAll();

            $submittedFormsArr = array();
            $fieldArr = array();
            $oldFormId = '';

            foreach($allData as $key => $data) {
                $currentFormId = $data->getForm()->getId();
                $form = $data->getForm();
                $users = $data->getUsers();
                if($key == 0 || $currentFormId == $oldFormId) {
                    foreach($form->getFieldsArr() as $field) {
                        if($this->getEnitityId($field->getId()) == $data->getFieldId()) {
                            if($data->getValue() != null) {
                                $field->setValue($data->getValue());
                            } elseif($data->getValueArrId() != null) {
                               if(!$field->appliedRemove){
                                   $field->emptyValueArr();
                               }
                                $valueArr = $em->getRepository('BackendBundle:ValueArr')->find($data->getValueArrId());
                                $field->addValueArr($valueArr);
                            }
                            $form->removeFieldsArr($field);
                            $form->addFieldsArr($field);
                        }
                    }
                } else {
                    $submittedForm = new UserFormData();
                    $submittedForm->setForm($form);
                    $submittedForm->addUser($users[0]);
                    array_push($submittedFormsArr, $submittedForm);
                }

                $oldFormId = $currentFormId;

            }
            $submittedForm = new UserFormData();
            $submittedForm->setForm($form);
            $submittedForm->addUser($users[0]);
            array_push($submittedFormsArr, $submittedForm);

            if ($submittedFormsArr) {
                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'All forms retrive Successfully!!!',
                    "data" => $submittedFormsArr
                );

            } else {
                return $helpers->json($this->accessError);
            }



            return $helpers->json($data);
        }
    }

}