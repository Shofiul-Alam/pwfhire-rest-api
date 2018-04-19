<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2/10/2017
 * Time: 3:56 AM
 */

namespace AppBundle\Controller\Staff;

use AppBundle\Services\Helpers;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\User;
use BackendBundle\Entity\Task;
use AppBundle\Services\JwtAuth;


class TaskController extends Controller {

    public function newAction(Request $request, $id = null) {
        $helpers = $this->get(Helpers::class);
        $jwt_auth = $this->get(JwtAuth::class);

        $token = $request->get('authorisation', null);
        $authCheck = $jwt_auth->checkToken($token);

        if($authCheck) {

            $identity = $jwt_auth->checkToken($token, true);
            $json = $request->get('json', null);

            if($json != null) {
                //create Task

                $params = json_decode($json);

                $createAt = new \DateTime("now");
                $updatedAt = new \DateTime("now");

                $user_id = ($identity->sub != null) ? $identity->sub : null;

                $title = (isset($params->title)) ? $params->title : null;
                $description = (isset($params->description)) ? $params->description : null;
                $status = (isset($params->status)) ? $params->status : null;


                if($user_id != null && $title != null) {
                    //create Task
                    $em = $this->getDoctrine()->getManager();
                    $user = $em->getRepository("BackendBundle:User")->findOneBy(array(
                       "id" => $user_id
                    ));
                if($id == null) {
                    $task = new Task();
                    $task->setUser($user);
                    $task->setTitle($title);
                    $task->setDescription($description);
                    $task->setStatus($status);
                    $task->setCreatedAt($createAt);
                    $task->setUpdatedAt($updatedAt);

                    $em->persist($task);
                    $em->flush();
                    $data = array(
                        "status" => "success",
                        "code" => 200,
                        "msg" => "Task is created",
                        "data" => $task
                    );


                } else {
                    $task = $em->getRepository('BackendBundle:Task')->findOneBy(array(
                        "id" => $id
                    ));

                    if(isset($identity->sub) && $identity->sub = $task->getUser()->getId()) {

                        $task->setUser($user);
                        $task->setTitle($title);
                        $task->setDescription($description);
                        $task->setStatus($status);
                        $task->setUpdatedAt($updatedAt);

                        $em->persist($task);
                        $em->flush();
                        $data = array(
                            "status" => "success",
                            "code" => 200,
                            "msg" => "Task is upadated",
                            "data" => $task
                        );
                    } else {
                        $data = array(
                            "status" => "error",
                            "code" => 400,
                            "msg" => "Task is not created. validation failiar."
                        );
                    }
                }


                } else {
                    $data = array(
                        "status" => "error",
                        "code" => 400,
                        "msg" => "Task is not created. validation failiar."
                    );
                }

            } else {
                $data = array(
                    "status" => "error",
                    "code" => 400,
                    "msg" => "Task is not created. Params failiar."
                );
            }


        } else {
            $data = array(
                "status" => "error",
                "code" => 400,
                "msg" => "Task is not created. Authentication Failiar."
            );
        }


        return $helpers->json($data);
    }

    public function tasksAction(Request$request) {
        $helpers = $this->get(Helpers::class);
        $jwt_auth = $this->get(JwtAuth::class);

        $token = $request->get('authorisation', null);
        $authCheck = $jwt_auth->checkToken($token);

        if($authCheck) {

            $identity = $jwt_auth->checkToken($token, true);
            $em = $this->getDoctrine()->getManager();

            $dql = "SELECT t FROM BackendBundle:Task t WHERE t.user = {$identity->sub} ORDER BY t.id DESC";
            $query = $em->createQuery($dql);

            $page = $request->query->getInt('page', 1);

            $paginator = $this->get('knp_paginator');
            $items_perPage = 10;
            $pagination = $paginator->paginate($query, $page, $items_perPage);
            $total_items_count = $pagination->getTotalItemCount();

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

    public function taskAction(Request $request, $id = null) {
        $helpers = $this->get(Helpers::class);
        $jwt_auth = $this->get(JwtAuth::class);

        $token = $request->get('authorisation', null);
        $authCheck = $jwt_auth->checkToken($token);

        if($authCheck) {

            $identity = $jwt_auth->checkToken($token, true);
            $em = $this->getDoctrine()->getManager();


            $task = $em->getRepository('BackendBundle:Task')->findOneBy(array(
               'id' => $id
            ));

            if($task & is_object($task) && $identity->sub == $task->getUser()->getId()) {
                $data = array(
                    "status" => "success",
                    'code'  => 200,
                    'data'   => $task
                );
            } else {
                $data = array(
                    "status" => "error",
                    'code'  => 400,
                    'msg'   => 'Task not found'
                );
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

    public function searchAction(Request $request, $search = null) {
        $helpers = $this->get(Helpers::class);
        $jwt_auth = $this->get(JwtAuth::class);

        $token = $request->get('authorisation', null);
        $authCheck = $jwt_auth->checkToken($token);

        if($authCheck) {

            $identity = $jwt_auth->checkToken($token, true);
            $em = $this->getDoctrine()->getManager();

            //Filter

            $filter = $request->get('filter', null);

            if(empty($filter)) {
                $filter = null;
            } elseif ($filter == 1) {
                $filter = 'new';
            } elseif ($filter == 2) {
                $filter = 'todo';
            } else {
                $filter = 'active';
            }

            //order
            $order = $request->get('order', null);
            if(empty($order) || $order == 2) {
                $order = 'DESC';
            } else {
                $order = 'ASC';
            }

            //search perform

            if($search != null) {
                $dql =  "SELECT t FROM BackendBundle:Task t"
                        . " WHERE t.user = $identity->sub AND"
                        . " (t.title LIKE :search OR t.description Like :search)";

            } else {
                $dql = "SELECT t FROM BackendBundle:Task t"
                    . " WHERE t.user = $identity->sub AND";


            }
            if($filter != null) {
                $dql .= " AND t.status = :filter";
            }
            //set Order

            $dql .=" ORDER BY t.id $order";

            $query = $em->createQuery($dql);

            if(!empty($filter)) {
                $query->setParameter('filter', "$filter");
            }

            if(!empty($search)) {
                $query->setParameter('search', "%$search%");
            }

            $tasks = $query->getResult();

            if(empty($tasks)) {
                $data = array(
                    "staus" => "error",
                    'code'  => 400,
                    'msg'   => 'Task Not found'
                );
            } else {
                $data = array(
                    "staus" => "success",
                    'code'  => 200,
                    'data'   => $tasks
                );
            }



        }else {
            $data = array(
                "staus" => "error",
                'code'  => 400,
                'msg'   => 'Authorization not valid'
            );
        }

        return $helpers->json($data);

    }

    public function removeAction(Request $request, $id = null) {
        $helpers = $this->get(Helpers::class);
        $jwt_auth = $this->get(JwtAuth::class);

        $token = $request->get('authorisation', null);
        $authCheck = $jwt_auth->checkToken($token);

        if($authCheck) {

            $identity = $jwt_auth->checkToken($token, true);
            $em = $this->getDoctrine()->getManager();


            $task = $em->getRepository('BackendBundle:Task')->findOneBy(array(
                'id' => $id
            ));

            if($task & is_object($task) && $identity->sub == $task->getUser()->getId()) {
                //Delete the object based on auth

                $em->remove($task);
                $em->flush();

                $data = array(
                    "staus" => "success",
                    'code'  => 200,
                    'data'   => $task
                );
            } else {
                $data = array(
                    "staus" => "error",
                    'code'  => 400,
                    'msg'   => 'Task not found'
                );
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


}