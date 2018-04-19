<?php

namespace AppBundle\Controller\Staff;


use AppBundle\Controller\Core\AEmployeeController;
use AppBundle\Services\JwtAuth;
use BackendBundle\Entity\Tmpimage;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Services\Helpers;
use Symfony\Component\Validator\Constraints as Assert;

class DefaultController extends AEmployeeController
{

    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    public function loginAction(Request $request) {
        $helpers = $this->get(Helpers::class);


        // Receiver json for Post
        $json = $request->get('json', null);

        //Array a developer login response
        $data = array(
            "staus" => "error",
            'data' => 'Send Json via post!!!'
        );

        if($json !=null) {
            // Method for login


            //Conversation array from request json obj in php
            $params = json_decode($json);

            $email = (isset($params->email)) ? $params->email : null;
            $password = (isset($params->password)) ? $params->password : null;
            $getHash = (isset($params->getHash)) ? $params->getHash : null;

            $emailConstraint = new Assert\Email();

            $emailConstraint->message = "This email is not valid !!";
            $validate_email = $this->get("validator")->validate($email, $emailConstraint);

            if($email != null && count($validate_email) == 0 && $password != null) {

                $jwt_auth = $this->get(JwtAuth::class);

                if($getHash == null || $getHash == false) {
                    $login = $jwt_auth->login($email, $password);
                } else {
                    $login = $jwt_auth->login($email, $password, true);
                }

                    return $this->json($login);

            } else {
                $data = array(
                    "staus" => "error",
                    'data' => 'Email Incorrect'
                );
            }


        }

        return $helpers->json($data);
    }

    public function apiAction() {

       $em = $this->getDoctrine()->getManager();
       $userRepo = $em->getRepository('BackendBundle:User');
       $allUser = $userRepo->findAll();
       $helpers = $this->get(Helpers::class);

       return $helpers->json($allUser);



    }



    public function uploadAction(Request $request){
        $helpers = $this->get(Helpers::class);

        $token = $request->get("authorisation", null);

        if($this->isAuthenticated($token)){
            $identity = $this->getIdentity($request);

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository("BackendBundle:User")->findOneBy(array(
                "id" => $identity->sub
            ));

            // upload file
            $file = $request->files->get("image");

            if(!empty($file) && $file != null){
                $ext = $file->guessExtension();

                if($ext == "jpeg" || $ext == "jpg" ||
                    $ext == "png" || $ext == "gif" || $ext == "pdf"){

                    $file_name = time().".".$ext;
                    $file_size = $file->getSize();
                    $mime = $file->getMimeType();
                    $file->move("tmp", $file_name);

                    $image = new Tmpimage();
                    $image->setName($file_name);
                    $image->setExt($ext);
                    $image->setMime($mime);
                    $image->setSize($file_size);
                    $image->setPath('tmp');

                    $em->persist($image);
                    $em->flush();

                    $data = array(
                        "status" => "success",
                        "code"	 => 200,
                        "msg"	 => "Image for user uploaded success !!",
                        "upload" => $image
                    );
                }else{
                    $data = array(
                        "status" => "error",
                        "code"	 => 400,
                        "msg"	 => "File not valid!!"
                    );
                }
            }else{
                $data = array(
                    "status" => "error",
                    "code"	 => 400,
                    "msg"	 => "Image not uploaded"
                );
            }

        }else{
            $data = array(
                "status" => "error",
                "code"	 => 400,
                "msg"	 => "Authorization not valid"
            );
        }

        return $helpers->json($data);
    }

}
