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
use BackendBundle\Entity\UserType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Client;
use AppBundle\Controller\UserController;

class ClientController extends AEmployeeController
{
    private $error = array();

    public function newAction(Request $request)
    {

        $createdAt = new \DateTime("now");
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);

        $userParmas = $params->user;
        $data = $this->CreateUser($userParmas);

        if($data['status'] === 'success' && $data['user'] != null) {
            $this->entity = new Client();
            $user = $data['user'];
            $this->entity->setUser($user);

            $request->authorisation = $data['token'];

            return parent::registerClient($request, $user);
        } else {
            return $helpers->json($this->registrationError);
        }



    }

    public function editAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);
        $json = $request->get('json', null);

        $params = json_decode($json);
        $upload = json_decode($request->get('upload', null));
        $sqlExecute = false;

        $this->entity = new Client();

        if ($this->isAuthenticated($token)  && $this->isClient($token)) {

            $em = $this->getDoctrine()->getManager();

            $id = $this->getEnitityId($params->id);
            $userId = $this->getEnitityId($params->user->id);
            $identity = $this->getIdentity($request);

            if($id && $userId== $identity->sub) {
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








}