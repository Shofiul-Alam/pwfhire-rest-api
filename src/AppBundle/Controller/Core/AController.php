<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/11/2017
 * Time: 1:42 PM
 */

namespace AppBundle\Controller\Core;


use AppBundle\Services\Helpers;
use AppBundle\Services\JwtAuth;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\User;



class AController extends Auth {

    protected $entity;

    private $error = array();

    public function newAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
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

            $isset_entity = $em->getRepository('BackendBundle:'.$entityName)->findBy(array(
                $uniqueIdentifire => $this->entity->{'get'.ucwords($uniqueIdentifire)}()
            ));

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

    public function listAction(Request$request) {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);


        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $entity = $this->entity;

            $em = $this->getDoctrine()->getManager();

            $entityName = $this->entity->getEntityClassName();

            $dql = "SELECT c FROM BackendBundle:$entityName c ORDER BY c.id ASC";
            $query = $em->createQuery($dql);

            $page = $request->query->getInt('page', 1);

            $paginator = $this->get('knp_paginator');
            $items_perPage = 10;
            $pagination = $paginator->paginate($query, $page, $items_perPage);
            $total_items_count = $pagination->getTotalItemCount();

//            $items = array();
//
//
//            foreach ($pagination->getItems() as $entity) {
//                $empArr = $entity->getEmployee()->toArray();
//
//                foreach ($empArr as $emp) {
//                    $entity->removeEmployee($emp);
//                }
//                $items[] = $entity;
//            }



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

//                $data = $this->getSingleModifiedData($entity);

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

//                $empArr = $entity->getEmployee()->toArray();
//
//                foreach ($empArr as $emp) {
//                    $entity->removeEmployee($emp);
//                    $emp->setEmployeeCategory(null);
//                    $entity->addEmployee($emp);
//                }

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

                default:
                    $uniqueIdentifire = 'eMail';

            }
        return $uniqueIdentifire;
    }

    public function getLoginProfile(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        $identity = $this->getIdentity($request);
        $userType = $identity->role;

        if ($this->isAuthenticated($token)) {
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

    public function createUser($params)
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

    public function registerClient(Request $request, $user)
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

    protected function addEmployeeSkillDocument(Request $request) {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);


        if($this->isAuthenticated($token)) {
            $create = true;

            $file = $request->files->get('document', null);

            if($this->isValidFile($file)) {

            }

            if ($create) {
                $this->prepareEntityData($this->entity, $params);
            }



        } else {
            return $helpers->json($this->accessError);
        }


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

    protected function addTimeSheet($entity, $upload) {
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
        if(is_object($user->getUserAvatar())) {
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


}
