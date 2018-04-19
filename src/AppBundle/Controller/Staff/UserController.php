<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 1/10/2017
 * Time: 11:58 PM
 */

namespace AppBundle\Controller\Staff;


use AppBundle\Services\Helpers;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\User;
use AppBundle\Controller\Core\AController;

class UserController extends AController
{

    private $error = array();

    public function newAction(Request $request)
    {

        $createdAt = new \DateTime("now");
        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);


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
                $data = $this->getSingleModifiedData($user);
                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'New User Created Successfully!!!',
                    'user' => $data
                );
            } else {
                $data = array_merge($this->registrationError, array(
                    'error_data' => $this->error
                ));
            }


        } else {
            $data = $this->registrationError;
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
                $data = $this->getSingleModifiedData($user);
                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'New User Created Successfully!!!',
                    'user' => $data
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

    public function editAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $token = $request->get('authorisation', null);

        if ($this->isAuthenticated($token)) {
            $em = $this->getDoctrine()->getManager();

            $identity = $this->getIdentity($request);

            $user = $em->getRepository('BackendBundle:User')->findOneBy(array(
                "id" => $identity->sub
            ));

            $json = $request->get('json', null);
            $params = json_decode($json);


            $sqlExecute = true;


            if ($sqlExecute) {
                $this->prepareUserData($user, $params);
            }
            if(count($this->error) > 0) {
                $sqlExecute = false;
            }
            if ($sqlExecute) {
                $em->persist($user);
                $em->flush();

                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'User Updated Successfully!!!',
                    'user' => $user
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

    public function profileAction(Request $request)
    {
        return $this->getLoginProfile($request);
    }


    public function getUserJsonRequest()
    {

    }





}