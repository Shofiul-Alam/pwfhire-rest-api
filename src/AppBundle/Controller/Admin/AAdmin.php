<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/11/2017
 * Time: 8:13 PM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Controller\Core\Auth;
use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;
use BackendBundle\Entity\EmployeeSkillDocument;
use BackendBundle\Entity\Tmpimage;
use BackendBundle\Entity\UserAvatar;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\User;


class AAdmin extends Auth {
    protected $entity;

    private $error = array();

    private function getJwt() {
        return $this->get(JwtAuth::class);
    }

    public function newAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
//        if($params->projectInductions != null) {
//            $params->projectInductions = null;
//        }
//        if($params->allocatedInduction != null) {
//            $allocatedInduction = $params->allocatedInduction;
//            $params->allocatedInduction = null;
//        }

        $token = $request->get('authorisation', null);
        if($token == null) {
            $token = $request->authorisation;
        }

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $create = true;

            if ($create) {
                $this->prepareEntityData($this->entity, $params);
            }



            $em = $this->getDoctrine()->getManager();

            $entityName = $this->entity->getEntityClassName();
            $uniqueIdentifire = $this->getUniqueIdentifier($entityName);

            if($uniqueIdentifire != null){
                $isset_entity = $em->getRepository('BackendBundle:'.$entityName)->findBy(array(
                    $uniqueIdentifire => $this->entity->{'get'.ucwords($uniqueIdentifire)}()
                ));
            } else {
                $isset_entity = array();
            }


            if (count($this->error) > 0) {
                $create = false;
            }


            if (count($isset_entity) == 0) {
                if ($create) {
                    $em->persist($this->entity);
                    $em->flush();
//                    if($allocatedInduction) {
//                        $param = new \stdClass();
//                        $param->allocatedInduction = $allocatedInduction;
//                       $this->prepareEntityData($this->entity, $param);
//                       $em->persist($this->entity);
//                       $em->flush();
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

    public function listAction(Request$request) {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        $filters = $request->get('filters', null);
        $filterParams = json_decode($filters);



        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $entity = $this->entity;

            $em = $this->getDoctrine()->getManager();
            $queryBuilder = $em->createQueryBuilder();

            $entityName = $this->entity->getEntityClassName();

            if(property_exists($this->entity, 'archived') && $filterParams == null
            ) {
                $dql = "SELECT c FROM BackendBundle:$entityName c WHERE c.archived = 0 ORDER BY c.id ASC";
            }elseif($filterParams) {
                $dql = $em->getRepository('BackendBundle:'.$entityName)->findProjectOrders($queryBuilder, $filterParams, $this->getJwt());
            }else {
                $dql = "SELECT c FROM BackendBundle:$entityName c ORDER BY c.id ASC";
            }


            $query = $em->createQuery($dql);


            $page = $request->query->getInt('page', 1);

            $paginator = $this->get('knp_paginator');
            $items_perPage = 10;
            $pagination = $paginator->paginate($query, $page, $items_perPage);
            $total_items_count = $pagination->getTotalItemCount();

//            $data = $this->getModifiedData($pagination->getItems());

            $data = array(
                "status" => "success",
                'code'  => 200,
                'total_items_count'   => $total_items_count,
                'page_actual' => $page,
                'items_per_page' => $items_perPage,
                'total_pages'   => ceil($total_items_count / $items_perPage),
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

    public function editAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        $json = $request->get('json', null);

        $params = json_decode($json);
        $upload = json_decode($request->get('upload', null));
        $sqlExecute = false;


        if ($this->isAuthenticated($token)  && $this->isAdmin($token)) {

            $em = $this->getDoctrine()->getManager();

            $id = $this->getEnitityId($params->id);

            if($id) {
                $sqlExecute = true;
            }

            $entityClassName = $this->entity->getEntityClassName();

            if ($sqlExecute) {

                $entity = $em->getRepository('BackendBundle:'.$entityClassName)->find($id);
                $this->prepareEntityData($entity, $params);
                if($upload && $upload != null) {
                    $this->updateAvatar($entity, $upload);
                }
            }
            if(count($this->error) > 0) {
                $sqlExecute = false;
            }
            if ($sqlExecute) {
                $em->persist($entity);
                $em->flush();


                if(count($this->getCollectionProperty($entity)) > 0) {
                    $collectionProperties = $this->getCollectionProperty($entity);
                    foreach ($collectionProperties as $prop) {
                        if(count($entity->{'spliced'.$prop}) > 0) {
                            foreach ($entity->{'spliced'.$prop} as $dEntity) {


                                if($entity->getEntityClassName() == 'Task' && $dEntity->getEntityClassName() == 'Job') {
                                    $entity->{'remove'.$prop}($dEntity);
                                    $em->persist($entity);
                                    $em->flush();
                                } else {
                                    $em->remove($dEntity);
                                    $em->flush();
                                }

                            }
                        }
                    }
                }
                if($params->userDeclearation && $params->userDeclearation->id != '0') {
                    $id = $this->getEnitityId($params->userDeclearation->id);
                    $userDeclearation = $em->getRepository('BackendBundle:UserDeclearation')->find($id);
                    $params->userDeclearation->employee = null;

                    $this->prepareEntityData($userDeclearation, $params->userDeclearation);
                    $em->persist($userDeclearation);
                    $em->flush();
                }


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

    public function updateEmployeeDocAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        $json = $request->get('json', null);

        $params = json_decode($json);
        $upload = json_decode($request->get('upload', null));
        $sqlExecute = false;


        if ($this->isAuthenticated($token)) {

            $em = $this->getDoctrine()->getManager();

            $id = $this->getEnitityId($params->id);

            if($id) {
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

    public function removeAction(Request $request, $id = null) {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $sqlExecute = false;

        if ($this->isAuthenticated($token)) {

            $em = $this->getDoctrine()->getManager();

            $id = $this->getEnitityId($params->id);

            if($id) {
                $sqlExecute = true;
            }
            $entityClassName = $this->entity->getEntityClassName();
            if ($sqlExecute) {

                $entity = $em->getRepository('BackendBundle:'.$entityClassName)->findOneBy(array(
                    "id" => $id
                ));

            }
            if ($sqlExecute) {
                $em->remove($entity);
                $em->flush();

//                $data = $this->getSingleModifiedData($entity);

                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'Deleted Successfully!!!',
                );
            } else {

                $data = array_merge($this->deleteError, array(
                    'data_error' => $this->error
                ));
            }

        } else {
            $data = array(
                "staus" => "error",
                'code'  => 400,
                'msg'   => 'Authorization not valid'
            );
        }
        return $helpers->json($data);
    }


    protected function getUniqueIdentifier($entityName)
    {


        switch ($entityName) {

            case 'Employee':
                $uniqueIdentifire = 'abnNo';
                break;
            case 'User':
                $uniqueIdentifire = 'eMail';
                break;
            case 'Client':
                $uniqueIdentifire = 'companyName';
                break;
            case 'Project':
                $uniqueIdentifire = 'projectName';
                break;
            case 'EmployeeCategory':
                $uniqueIdentifire = 'categoryName';
                break;
            case 'EmployeeOrderCategory':
                $uniqueIdentifire = 'categoryName';
                break;
            case 'SkillCompetencyList':
                $uniqueIdentifire = 'name';
                break;
            case 'Contact':
                $uniqueIdentifire = 'landPhone';
                break;
            case 'Order':
                $uniqueIdentifire = 'startDate';
                break;
            default:
                $uniqueIdentifire = null;

        }
        return $uniqueIdentifire;
    }

    public function getLoginProfile(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        $identity = $this->getIdentity($request);
        $userType = $identity->role;

        if ($this->isAuthenticated($token) && $userType == 'admin' || $userType == 'developer') {
            $em = $this->getDoctrine()->getManager();

            $entityName = ucwords($userType);

            $user = $em->getRepository('BackendBundle:User')->findOneBy(array(
                "id" => $identity->sub
            ));
            $data = $em->getRepository('BackendBundle:'.$entityName)->findOneBy(array(
                "user" => $user
            ));

//            $data = $this->getSingleModifiedData($profile);

//            if(!$data) {
//                $data = $this->accessError;
//            }

        } else {
            $data = $this->accessError;
        }

        return $helpers->json($data);
    }

    public function createUser($params, $userAvatar)
    {


        $user = new User();


        $createUser = true;

        if ($createUser) {
            $this->prepareUserData($user, $params);
        }

        $em = $this->getDoctrine()->getManager();

        $isset_user = $em->getRepository('BackendBundle:User')->findBy(array(
            'email' => $user->getEmail()
        ));

        if(count($this->error) > 0) {
            $createUser = false;
        }



        if (count($isset_user) == 0) {
            if ($createUser) {
                $em->persist($user);
                $em->flush();
                $auth = $this->doAuthorisation($user->getEmail(), $user->getPassword());
                if($userAvatar != null) {
                    $avatar = new UserAvatar();

                    $document = $this->addAvatar($user, $userAvatar);
                    $em->persist($document);
                    $em->flush();

                    $tmpId = $this->getEnitityId($userAvatar->id);
                    $tmpUpload = $em->getRepository('BackendBundle:Tmpimage')->find($tmpId);
                    $em->remove($tmpUpload);
                    $em->flush();
                    $fs= new Filesystem();
                    $fs->remove('tmp/'.$userAvatar->name);
                }



                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'New User Created Successfully!!!',
                    'user' => $user,
                    'token' => $auth
                );
            } else {
                $data = array_merge($this->registrationError, array(
                    'error_data' => $this->error
                ));
            }


        } else {
            $data = $this->registrationError;
        }

        return $data;
    }

    public function registerClient(Request $request)
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

            $isset_entity = 0;
            if($this->entity->{'get'.ucwords($uniqueIdentifire)}()!= null || $this->entity->{'get'.ucwords($uniqueIdentifire)}() != "") {
                $isset_entity = $em->getRepository('BackendBundle:'.$entityName)->findBy(array(
                    $uniqueIdentifire => $this->entity->{'get'.ucwords($uniqueIdentifire)}()
                ));
            }


            if (count($this->error) > 0) {
                $create = false;
            }


            if (count($isset_entity) == 0) {
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

    protected function doAuthorisation($email, $password, $getHash = false) {
        $helpers = $this->get(Helpers::class);

        if($email !=null && $password != null) {
            // Method for login


            $emailConstraint = new Assert\Email();

            $emailConstraint->message = "This email is not valid !!";
            $validate_email = $this->get("validator")->validate($email, $emailConstraint);

            if($email != null && count($validate_email) == 0 && $password != null) {

                $jwt_auth = $this->get(JwtAuth::class);

                if($getHash == null || $getHash == false) {
                    $login = $jwt_auth->getAuthToken($email, $password);
                } else {
                    $login = $jwt_auth->getAuthToken($email, $password, true);
                }

                return $login;

            } else {
                $data = array(
                    "staus" => "error",
                    'data' => 'Email Incorrect'
                );
            }


        }

        return $data;

    }
    protected function prepareUserData($user, $params)
    {
        $userProperties = $user->toArray();

        foreach ( $userProperties as $property => $value) {

            switch ($property) {

                case 'id':
                    break;
                case 'email':
                    (isset($params->{$property})) ? $this->validateUserEmail($user, $params->{$property}) : null;
                    break;
                case 'password':
                    (isset($params->{$property})) ? $this->hashedPassword($user, $params->{$property}) : null;
                    break;
                case 'userType':
                    (isset($params->{$property})) ? $this->setUserType($user, $params->{$property}, 'UserType') : null;
                    break;
                case 'dob':
                    (isset($params->{$property})) ? $this->processDob($user, $params->{$property}) : null;
                    break;
                case 'createdAt':
                    (isset($params->{$property})) ? $this->prepareCreatedAt($user): null;
                    break;
                default:
                    (isset($params->{$property})) ?
                        $user->{'set' . $property}($params->{$property}) :
                        null;

            }
        }
    }
    protected function prepareCreatedAt($user) {

        if($user->getCreatedAt() == null) {
            $createdAt = new \DateTime("now");

            return $user->setCreatedAt($createdAt);
        } else {
            return null;
        }

    }
    protected function processDob($user, $dob) {

        if($dob != null) {
            $formatedDob = $dob->year . '/' . $dob->month . '/' . $dob->day;
            $sql_dob = new \DateTime($formatedDob);

            return $user->setDob($sql_dob);
        } else {
            return null;
        }

    }
    protected function hashedPassword($user, $password) {

        if($password != null) {
            $pwd = hash('sha256', $password);
            $user->setPassword($pwd);
        }
    }
    public function validateUserEmail($user, $email)
    {
        $emailConstraint = new Assert\Email();

        $emailConstraint->message = "This email is not valid !!";

        $validate_email = $this->get("validator")->validate($email, $emailConstraint);


        if (count($validate_email) > 0) {
            return $this->error['email'] = "Please provide a valid email";
        }
        return $user->setEmail($email);
    }

    protected function addEmployeeDocument(Request $request) {
        $params = json_decode($request->get('json', null));
        $upload = json_decode($request->get('upload', null));
        $token = $request->get('authorisation', null);
        $helpers = $this->get(Helpers::class);
        $fs = new Filesystem();
        $create = true;

        if ($create) {
            $this->prepareEntityData($this->entity, $params);
        }



        $em = $this->getDoctrine()->getManager();

        $entityName = $this->entity->getEntityClassName();
//        $uniqueIdentifire = $this->getUniqueIdentifier($entityName);
//
//        if($uniqueIdentifire != null) {
//            $isset_entity = $em->getRepository('BackendBundle:'.$entityName)->findBy(array(
//                $uniqueIdentifire => $this->entity->{'get'.ucwords($uniqueIdentifire)}()
//            ));
//        } else {
//            $isset_entity = array();
//        }
//
//
//        if (count($this->error) > 0) {
//            $create = false;
//        }



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


        return $helpers->json($data);
    }

    protected function addEmployeeInductionDocument(Request $request) {
        $params = json_decode($request->get('json', null));
        $upload = json_decode($request->get('upload', null));
        $token = $request->get('authorisation', null);
        $helpers = $this->get(Helpers::class);
        $fs = new Filesystem();
        $create = true;


        $em = $this->getDoctrine()->getManager();

        $entityName = $this->entity->getEntityClassName();
//        $uniqueIdentifire = $this->getUniqueIdentifier($entityName);
//
//        if($uniqueIdentifire != null) {
//            $isset_entity = $em->getRepository('BackendBundle:'.$entityName)->findBy(array(
//                $uniqueIdentifire => $this->entity->{'get'.ucwords($uniqueIdentifire)}()
//            ));
//        } else {
//            $isset_entity = array();
//        }
//
//
//        if (count($this->error) > 0) {
//            $create = false;
//        }



        if ($create) {
            $this->prepareEntityData($this->entity, $params);
            $document = $this->addInductionDocument($upload);
            $this->entity->setEmployeeSkillDocument($document);

            $em->persist($this->entity);
            $em->flush();

            $tmpId = $this->getEnitityId($upload->id);
            $tmpUpload = $em->getRepository('BackendBundle:Tmpimage')->find($tmpId);
            $em->remove($tmpUpload);
            $em->flush();
            $fs->remove('tmp/'.$upload->name);



            $obj = new \stdClass();

            $obj->employeeId =  $this->entity->getEmployee()->getId();
            $obj->employeeName =  $this->entity->getEmployee()->getUser()->getFirstName(). " " . $this->entity->getEmployee()->getUser()->getLastName();
            $obj->inductionId =  $this->entity->getInduction()->getId();
            $obj->inductionName = $this->entity->getInduction()->getName();
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


        return $helpers->json($data);
    }

    protected function addDocument($entity, $upload) {
        $fs = new Filesystem();

        $fs->copy('tmp/'.$upload->name, 'documents/'.$upload->name);

        $employeeSkilldocument = new EmployeeSkillDocument();

        $employeeSkilldocument->setFileName($upload->name);
        $employeeSkilldocument->setStorageType($upload->ext);
        $employeeSkilldocument->setMime($upload->mime);
        $employeeSkilldocument->setSize($upload->size);
        $employeeSkilldocument->setEmployeeSkillCompetencyDocument($entity);

        return $employeeSkilldocument;


    }

    protected function addInductionDocument($upload) {
        $fs = new Filesystem();

        $fs->copy('tmp/'.$upload->name, 'documents/'.$upload->name);

        $employeeSkilldocument = new EmployeeSkillDocument();

        $employeeSkilldocument->setFileName($upload->name);
        $employeeSkilldocument->setStorageType($upload->ext);
        $employeeSkilldocument->setMime($upload->mime);
        $employeeSkilldocument->setSize($upload->size);

        return $employeeSkilldocument;


    }

    protected function addAvatar($entity, $upload) {
        $fs = new Filesystem();

        $fs->copy('tmp/'.$upload->name, 'documents/'.$upload->name);

        $avatar = new UserAvatar();

        $avatar->setFileName($upload->name);
        $avatar->setStorageType($upload->ext);
        $avatar->setMime($upload->mime);
        $avatar->setSize($upload->size);
        $avatar->setPath('/documents');
        $avatar->setUser($entity);
        $entity->setUserAvatar($avatar);

        return $avatar;


    }
    protected function updateAvatar($entity, $upload) {
        $fs = new Filesystem();

        $user = $entity->getUser();

        $fs->copy('tmp/'.$upload->name, 'documents/'.$upload->name);
        if($user->getUserAvatar()) {
            $fs->remove('documents/'.$user->getUserAvatar()->getFileName());
        } else {
            $userAvatar = new UserAvatar();
            $user->setUserAvatar($userAvatar);
        }


        $user->getUserAvatar()->setFileName($upload->name);
        $user->getUserAvatar()->setStorageType($upload->ext);
        $user->getUserAvatar()->setMime($upload->mime);
        $user->getUserAvatar()->setSize($upload->size);
        $user->getUserAvatar()->setPath('/documents');
        $user->getUserAvatar()->setUser($user);
        $entity->setUser($user);


        return $entity;


    }

    protected function updateEmployeeDocment($documentEntity, $upload) {
        $fs = new Filesystem();


        $fs->copy('tmp/'.$upload->name, 'documents/'.$upload->name);
        if($documentEntity->getFileName()) {
            $fs->remove('documents/'.$documentEntity->getFileName());
        } else {
            $documentEntity = new EmployeeSkillDocument();
        }


        $documentEntity->setFileName($upload->name);
        $documentEntity->setStorageType($upload->ext);
        $documentEntity->setMime($upload->mime);
        $documentEntity->setSize($upload->size);
        $documentEntity->setPath('/documents');



        return $documentEntity;


    }

    public function getAllSkillCompetencyDocuments(Request $request) {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        $json = $request->get('json', null);
        $params = json_decode($json);


        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $entity = $this->entity;

            $em = $this->getDoctrine()->getManager();

            $empId = $this->getEnitityId($params->id);

            $employee = $em->getRepository('BackendBundle:Employee')->find($empId);

            $employeeSkillDocuments = $em->getRepository('BackendBundle:EmployeeSkillCompetencyDocument')->findBy(array('employee' => $employee));

            $entityName = $this->entity->getEntityClassName();

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

    public function getContacts(Request $request) {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        $json = $request->get('json', null);
        $params = json_decode($json);



        if($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $em = $this->getDoctrine()->getManager();
            $id = $this->getEnitityId($params->id);
            $entityClassName = $this->entity->getEntityClassName();
            $entity = $em->getRepository('BackendBundle:'.$entityClassName)->find($id);

            $contactAllocs = $em->getRepository('BackendBundle:AllocatedContact')->findBy(array(strtolower($entityClassName) => $entity));

//            if($contactAllocs != null && count($contactAllocs) > 0) {
//                $contactArr = array();
//                foreach($contactAllocs as $contactAlloc) {
//                    $contact = $contactAlloc->getContact();
//                    $c['id'] = $contact->getId();
//                    $c['text'] = $contact->getEmargencyContact().'('.$contact->getLandPhone().')';
//                    $contactArr[] = $contact->getId();
//                }
//            }

            $data = array(
                'status' => "success",
                'code' => 200,
                'msg' => 'New User Created Successfully!!!',
                'data' => $contactAllocs,
            );
        } else {
            $data = $this->accessError;
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

    public function getCollectionProperty($entity) {
        $entityArr = $entity->toArray();
        $propertyArr = array();
        foreach ($entityArr as $key=>$property) {

            if($this->isCollectionType($property)){
                array_push($propertyArr, ucwords($key));
            }
        }

        return $propertyArr;
    }

    public function isCollectionType($property) {
        if (get_class($property) == "Doctrine\\ORM\\PersistentCollection" ||
            get_class($property) == ("Doctrine\\Common\\Collections\\ArrayCollection"))  {
            return true;
        }
    }




}