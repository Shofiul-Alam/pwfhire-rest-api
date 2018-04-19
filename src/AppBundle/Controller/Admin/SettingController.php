<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/11/2017
 * Time: 8:15 PM
 */

namespace AppBundle\Controller\Admin;

use AppBundle\Services\Helpers;
use BackendBundle\Entity\Admin;
use BackendBundle\Entity\SkillCompetencyList;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\Employee;


class SettingController extends AAdmin
{
    private $error = array();

    public function addSkillCompetencyAction(Request $request) {
        $this->entity = new SkillCompetencyList();
        return parent::newAction($request);
    }
    public function skillCompetencyListAction (Request $request) {
        $token = $request->get('authorisation', null);
        $helpers = $this->get(Helpers::class);


        if($this->isAuthenticated($token) && $this->isAdmin($token)) {

            $em = $this->getDoctrine()->getEntityManager();

            $dql = "SELECT c FROM BackendBundle:SkillCompetencyList c ORDER BY c.id ASC";


            $query = $em->createQuery($dql);
            $result= $query->getResult();



            $data = array(
                "status" => "success",
                'code' => 200,
                'data' => $result

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
    public function editSkillCompetencyAction (Request $request) {
        $this->entity = new SkillCompetencyList();
        return parent::editAction($request);
    }


}