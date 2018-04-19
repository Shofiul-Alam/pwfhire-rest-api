<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 7/11/2017
 * Time: 9:52 AM
 */

namespace AppBundle\Controller\Core;

use AppBundle\Controller\Base\Root;
use AppBundle\Services\JwtAuth;

class Auth extends Root {

    protected $simpleError = array();
    protected $loginError = array();
    protected $registrationError = array();
    protected $updateError = array();
    protected $accessError = array();
    protected $duplicateError = array();
    protected $deleteError = array();


    public function __construct()
    {
        $this->simpleError = array(
            'status' => "error",
            'code' => 404,
            'msg' => 'Something went wrong',
            'error_data' => '');

        $this->loginError = array (
            'status' => 'error',
            'code' => 404,
            'msg' => 'Email or Password is incorrect',
            'error_data' => ''
        );
        $this->registrationError = array (
            'status' => 'error',
            'code' => 404,
            'msg' => 'An user with same email address exist',
            'error_data' => ''
        );
        $this->updateError = array (
            'status' => 'error',
            'code' => 404,
            'msg' => 'Authentication Faild or Something Went Wrong',
            'error_data' => ''
        );
        $this->accessError = array (
            'status' => 'error',
            'code' => 404,
            'msg' => 'Unauthorized access. You do not have access permission',
            'error_data' => ''
        );
        $this->duplicateError = array (
            'status' => 'error',
            'code' => 404,
            'msg' => 'The same data is exist',
            'error_data' => ''
        );
        $this->deleteError = array (
            'status' => 'error',
            'code' => 404,
            'msg' => 'Data cannot be deleted',
            'error_data' => ''
        );

    }


    protected function isAuthenticated($token) {
        $jwt_auth = $this->get(JwtAuth::class);

        $authCheck = $jwt_auth->checkToken($token);

        return $authCheck;
    }

    protected function getIdentity($request) {
        $token = $request->get('authorisation', null);
        $jwt_auth = $this->get(JwtAuth::class);
        $identity = $jwt_auth->checkToken($token, true);

        return $identity;
    }

    protected function isAdmin($token) {
        $jwt_auth = $this->get(JwtAuth::class);
        $identity = $jwt_auth->checkToken($token, true);



        if($identity->role === 'admin' || $identity->role === 'developer') {
            return true;
        } else {
            return false;
        }

    }

    protected function isEmployee($token) {
        $jwt_auth = $this->get(JwtAuth::class);
        $identity = $jwt_auth->checkToken($token, true);

        if($this->getUserRole($identity) === 'employee') {
            return true;
        } else {
            return false;
        }
    }

    protected function isClient($token) {
        $jwt_auth = $this->get(JwtAuth::class);
        $identity = $jwt_auth->checkToken($token, true);

        if($this->getUserRole($identity) === 'client') {
            return true;
        } else {
            return false;
        }
    }

    protected function getUserRole($identity) {
        $jwt_auth = $this->get(JwtAuth::class);
        $role = $jwt_auth->getUserType($identity);

        return $role;

    }

    protected function prepareEntityData($entity, $params)
    {
        $entityProperties = $entity->toArray();

        foreach ( $entityProperties as $property => $value) {

            $propertyType = $this->getPropertyType($params->{$property}, $property, $value);

            switch ($propertyType) {
                case 'id';
                    break;
                case 'entity';
                    (isset($params->{$property})) ? $this->setEntity($entity, $params->{$property}, ucwords($property)) : null;
                    break;
                case 'userType';
                    (isset($params->{$property})) ? $this->setUserType($entity, $params->{$property}, ucwords($property)) : null;
                    break;
                case 'date';
                    (isset($params->{$property})) ? $this->processDate($entity, $params->{$property}, ucwords($property)) : null;
                    break;
                case 'time';
                    (isset($params->{$property})) ? $this->processTime($entity, $params->{$property}, ucwords($property)) : null;
                    break;
                case 'documents';
                    (isset($params->{$property})) ? $this->processDocument($entity, $params->{$property}, ucwords($property)) : null;
                    break;
                case 'Collection';
                    (isset($params->{$property})) ? $this->addToCollection($entity, $params->{$property}, $property) : null;
                    break;
                case 'default';
                if(isset($params->{$property}))  {
                    if(is_array($params->{$property})) {
                        IF(count($params->{$property}) > 0) {
                            $entity->{'add' . $property}($params->{$property});
                        }
                    } else {
                        $entity->{'set' . $property}($params->{$property});
                    }
                }
                default:
                    break;


            }

//            switch ($property) {
//
//                case 'id':
//                    break;
//                case 'user':
//                    (isset($params->{$property})) ? $this->setEntity($entity, $params->{$property}, 'User') : null;
//                    break;
//                case 'employeeCategory':
//                    (isset($params->{$property})) ? $this->setEntity($entity, $params->{$property}, 'EmployeeCategory') : null;
//                    break;
//                case 'employeeIdCard':
//                    (isset($params->{$property})) ? $this->setEntity($entity, $params->{$property}, 'EmployeeIdCard') : null;
//                    break;
//                case 'employeeOrderCategory':
//                    (isset($params->{$property})) ? $this->setEntity($entity, $params->{$property}, 'EmployeeOrderCategory') : null;
//                    break;
//                case 'employeeVisa':
//                    (isset($params->{$property})) ? $this->setEntity($entity, $params->{$property}, 'EmployeeVisa') : null;
//                    break;
//                case 'employeeWhiteCard':
//                    (isset($params->{$property})) ? $this->setEntity($entity, $params->{$property}, 'EmployeeWhiteCard') : null;
//                    break;
//                case 'client':
//                    (isset($params->{$property})) ? $this->setEntity($entity, $params->{$property}, 'Client') : null;
//                    break;
//                case 'dob':
//                    (isset($params->{$property})) ? $this->processDate($entity, $params->{$property}, 'dob') : null;
//                    break;
//                case 'invoiceDueDate':
//                    (isset($params->{$property})) ? $this->processDate($entity, $params->{$property}, "InvoiceDueDate") : null;
//                    break;
//                case 'userType':
//                    (isset($params->{$property})) ? $this->setUserType($entity, $params->{$property}, 'UserType') : null;
//                    break;
//                case 'employee':
//                    (isset($params->{$property})) ? $this->setEntity($entity, $params->{$property}, 'Employee') : null;
//                    break;
//                case 'skillCompetencyList':
//                    (isset($params->{$property})) ? $this->setEntity($entity, $params->{$property}, 'SkillCompetencyList') : null;
//                    break;
//                case 'employeeSkillDocument':
//                    (isset($params->{$property})) ? $this->processDocument($entity, $params->{$property}, 'EmployeeSkillDocument') : null;
//                    break;
//                default:
//                    if(isset($params->{$property}))  {
//                        if(is_array($params->{$property})) {
//                            IF(count($params->{$property}) > 0) {
//                                $entity->{'add' . $property}($params->{$property});
//                            }
//                        } else {
//                            $entity->{'set' . $property}($params->{$property});
//                        }
//                    }
//
//
//            }
        }
    }

    private function processDate($entity, $dob, $method) {

        if($dob != null && $dob->year != null) {
            $formatedDob = $dob->year . '/' . $dob->month . '/' . $dob->day;
            $sql_dob = new \DateTime($formatedDob);

            return $entity->{'set'.$method}($sql_dob);
        } else {
            return null;
        }

    }
    private function processTime($entity, $dob, $method) {
        if($dob != null && $dob->hour != null) {
            if(strlen($dob->hour) == 1) {
                $dob->hour = '0'.$dob->hour;
            }
            if(strlen($dob->minute) == 1) {
                $dob->minute = '0'.$dob->minute;
            }
            if(strlen($dob->seconds) == 1 || strlen($dob->second) == 1) {
                $dob->seconds = $dob->seconds? '0'.$dob->seconds : '0'.$dob->second;
            }
            $dateString = $dob->hour . ':' . $dob->minute. ':' . $dob->seconds?:$dob->second;
//            $sql_time = \DateTime::createFromFormat('H\h i\m s\s', $dateString)->format('H:i:s');
            $sql_time = new \DateTime($dateString);

            return $entity->{'set'.$method}($sql_time);
        } else {
            return null;
        }

    }

    protected function setEntity($entity, $childEntity, $childEntityName) {


        $getMethod = 'get'.$childEntityName;

        if(method_exists($entity, $getMethod) && $entity->$getMethod()) {
            $id = $entity->$getMethod()->getId();
            if($childEntity->id != '0' && $childEntity->id == $id) {
                $id = $this->getEnitityId($id);
            } else {
                $id = $this->getEnitityId($childEntity->id);
            }

        } else {
            if((string) $childEntity->id != '0') {
                $id = $this->getEnitityId($childEntity->id);
            } else {
                $id = false;
            }

        }

        $em = $this->getDoctrine()->getManager();
        $setMethod = 'set'.$childEntityName;

        if(!$id) {
            $entityPath = '\\BackendBundle\\Entity\\'.$childEntityName;
            $newChildEntity = new $entityPath();
            if($childEntity == 'User'){
                $entity->$setMethod($newChildEntity);
            } elseif($childEntityName == 'UserDeclearation') {
                $newEntity = new $entityPath();
                $this->prepareEntityData($newEntity, $childEntity);
                $entity->$setMethod($newEntity);
                $newEntity->setEmployee($entity);

            }

            else {
                $entity->$setMethod(null);
            }


//            $entity->$setMethod(null);
        }
        else {
            $entity->$setMethod(
                $em->getRepository('BackendBundle:'.$childEntityName)->find($id)
                );
        }
    }

    protected function addToCollection($entity, $collection, $property) {
            $em = $this->getDoctrine()->getManager();
            if(property_exists($entity, 'firstTime')) {
                $entity->firstTime = true;
            }

            if($collection != null && !is_array($collection)) {
                $arr = array();
                array_push($arr, $collection);
                $collection = $arr;
            }

        if(property_exists($entity, $property)) {

//            $all = $entity->{'getAll'.$property}();
            $mProperty = ucwords($property);

//            $entity->{'unset'.$mProperty}();

            foreach ($collection as $childEntity) {
                if($childEntity->id == '0' || $childEntity->id === null || $childEntity->id === "") {
                    if($mProperty == 'FieldsArr') {
                        $path = '\\BackendBundle\\Entity\\Field';
                    } else {
                        $path = '\\BackendBundle\\Entity\\'.$mProperty;
                    }

                    $newAlloc = new $path();
                    $this->prepareEntityData($newAlloc, $childEntity);
                    if(method_exists($newAlloc, 'set'.$entity->getEntityClassName())){
                        $newAlloc->{'set'.$entity->getEntityClassName()}($entity);
                    }

                    if(method_exists($entity, 'add'.$mProperty)) {
//                        $entity->{'add' . $mProperty}($newAlloc);
                        $entity->{'replace'.$mProperty}($newAlloc);
                    }
                } else {
                    if(method_exists($entity, 'add'.$mProperty)) {
                        $id = $this->getEnitityId($childEntity->id);
                        if($mProperty == 'FieldsArr') {
                            $existingEntity = $em->getRepository('BackendBundle:Field')->find($id);
                        } else {
                            $existingEntity = $em->getRepository('BackendBundle:'.$mProperty)->find($id);
                        }

                        if($existingEntity) {

                            if($entity->getEntityClassName() == 'Form') {
                                $form = new \stdClass();
                                $form->id = $entity->getId();
                                $childEntity->form =  $form;
                                $this->prepareEntityData($existingEntity, $childEntity);
                            }
                            $entity->{'replace'.$mProperty}($existingEntity);

                        }else {
                            $path = '\\BackendBundle\\Entity\\'.$mProperty;
                            $newChildEntity = new $path;
                            $newChildEntity->{'set'.$entity->getEntityClassName()}($entity);
                            $id = $this->getEnitityId($childEntity->id);
                            $entity2 = false;

                            if(class_exists('\\BackendBundle\\Entity\\'.substr($mProperty, 9))) {
                                $entity2 = $em->getRepository('BackendBundle:'.substr($mProperty, 9))->find($id);
                            }

                            if($entity2) {
                                $newChildEntity->{'set'.substr($mProperty, 9)}($entity2);
                            }
                            $entity->{'add' . $mProperty}($newChildEntity);


                        }

                    }
                }


            }
//            if(method_exists($entity, 'emptySpliced'.$mProperty)) {
//                $entity->{'emptySpliced'.$mProperty}();
//            }
        }


    }


    protected function setUserType($user, $userTypeEntity, $name) {


        $id = $this->getEnitityId($userTypeEntity);

        $em = $this->getDoctrine()->getManager();

        if(!$id) {
            if($userTypeEntity->type != null && ($userTypeEntity->type == 'employee' || $userTypeEntity->type == 'client'
                    || $userTypeEntity->type == 'user' ||$userTypeEntity->type == 'contact')) {

                switch ($userTypeEntity->type) {
                    case 'employee':
                        $userTypeEntity = $em->getRepository('BackendBundle:UserType')->findOneBy(array("type" => 'employee'));
                        break;
                    case 'client':
                        $userTypeEntity = $em->getRepository('BackendBundle:UserType')->findOneBy(array("type" => 'client'));
                        break;
                    case 'user':
                        $userTypeEntity = $em->getRepository('BackendBundle:UserType')->findOneBy(array("type" => 'user'));
                        break;
                    case 'contact':
                        $userTypeEntity = $em->getRepository('BackendBundle:UserType')->findOneBy(array("type" => 'contact'));
                        break;
                }
                $user->setUserType($userTypeEntity);
            } else {
                $this->error[] = 'Unauthorised Access';
            }
        } else {
            $user->setUserType(
                $em->getRepository('BackendBundle:UserType')->find($id)
            );
        }
    }

    protected function getEnitityId($encryptedId) {

        $jwt_auth = $this->get(JwtAuth::class);
        $id = $jwt_auth->decodeId($encryptedId);

        return $id;

    }




    protected function getModifiedData($items) {
        $jwt_auth = $this->get(JwtAuth::class);

        $array = array();

        foreach ($items as $key=>$item) {

            $encryptedId = $jwt_auth->encodeId($item->getId());

            $itemArr = $item->toArray();

            $itemArr['id'] = $encryptedId;

           array_push($array, $itemArr);


        }

        return $array;


    }

    protected function getSingleModifiedData($item) {
        $jwt_auth = $this->get(JwtAuth::class);


            $encryptedId = $item->getId();

            $itemArr = $item->toArray();

            $itemArr['id'] = $encryptedId;


        return $itemArr;


    }

    protected function isLegalEntity($entity, $identity) {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository("BackendBundle:User")->findOneBy(array(
            "id" => $identity->sub
        ));
        if($entity->getId() == $user->getId()) {
            return true;
        } else {
            return false;
        }
    }

    protected function isId($id) {
        return $id === 'id';
    }

    protected function isUserType($entity) {
        return is_object($entity) && property_exists($entity, 'type');
    }

    protected function isEntity($entity, $name) {
        return is_object($entity) && class_exists('\\BackendBundle\\Entity\\'.$name);
    }
    protected function isDate($date) {
        return is_object($date) && property_exists($date, 'year');
    }
    protected function isTime($date) {
        return is_object($date) && property_exists($date, 'hour');
    }
    protected function isDocument($document) {
        return is_object($document) && property_exists($document, 'mime') && property_exists($document, 'ext') ;
    }



    protected function getPropertyType($property, $name, $value) {
        if($this->isId($name)) {
            return 'id';
        } elseif (get_class($value) == "Doctrine\\ORM\\PersistentCollection" ||
                        get_class($value) == ("Doctrine\\Common\\Collections\\ArrayCollection"))  {
            return 'Collection';
        }
        elseif($this->isEntity($property, ucwords($name))) {
            return "entity";
        } elseif($this->isTime($property)) {
            return "time";
        }
        elseif($this->isDate($property)) {
            return "date";
        } elseif($this->isDocument($property)) {
            return 'document';
       } elseif($this->isUserType($property)) {
           return 'userType';
       } else {
           return 'default';
       }
    }


}
