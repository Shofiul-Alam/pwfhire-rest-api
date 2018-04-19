<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/11/2017
 * Time: 8:15 PM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Services\Helpers;
use BackendBundle\Entity\Job;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;


class ManageJobController extends AAdmin
{
    private $error = array();


    public function newAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $this->entity = new Job();
        return parent::newAction($request);

    }

    public function listAction(Request $request) {

        $x = 1;

        $this->entity = new Job();

        return parent::listAction($request);
    }


    public function editAction(Request $request)
    {
        $this->entity = new Job();
        return parent::editAction($request);
    }

    public function archiveJobAction(Request $request)
    {

        $helpers = $this->get(Helpers::class);
        $json = $request->get('json', null);
        $params = json_decode($json);
        $token = $request->get('authorisation', null);

        if($this->isAuthenticated($token) && $this->isAdmin($token)) {
            $id = $this->getEnitityId($params->id);

            $em = $this->getDoctrine()->getManager();
            $job = $em->getRepository('BackendBundle:Job')->find($id);

            if($job) {
                $job->setArchived($params->archived);
                $em->persist($job);
                $em->flush();
                $data = array(
                    'status' => "success",
                    'code' => 200,
                    'msg' => 'Job is Successfully Archived!!!',
                    'job' => $job
                );
            } else {
                $data = $this->error;
            }


        } else {
            $data = $this->accessError;
        }

        return $helpers->json($data);
    }


}