<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 1/10/2017
 * Time: 10:16 PM
 */

namespace AppBundle\Services;

use Firebase\JWT\JWT;

class JwtAuth {

    public $manager;

    public $key;


    public function __construct($manager)
    {
        $this->manager = $manager;
        $this->key = "sxdet23445yklwerbkelswefbde";
    }

    public function login($email, $password, $getHash = null ) {

        $hashedPassword = hash('sha256', $password);

        $userRepo = $this->manager->getRepository('BackendBundle:User');

        $user = $userRepo->findOneBy(array('email' => $email, 'password' => $hashedPassword ));

        $login = false;
        if(is_object($user)) {
            $login = true;


        }

        if($login) {

            //Generate Token
            $token = array (
                "sub"   => $this->decodeId($user->getId()),
                "email" =>  $user->getEmail(),
                "name"  => $user->getFirstName(),
                "surname" => $user->getLastName(),
                'role' => $user->getUserType()->getType(),
                "iat" => time(),
                "exp" => time() + (7 * 24 * 60 * 60)
            );

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decode = JWT::decode($jwt, $this->key, array('HS256'));

            if($getHash == null) {
                $data =  $jwt;
            } else {
                $data =  $decode;
            }

//            $data = $jwt;


        } else {
            $data = array (
                'status' => 'Login Failed',
                'code' => 404,
                'msg' => 'Email or Password is incorrect',
                'error_data' => ''
            );
        }

        return $data;
    }

    public function checkToken($jwt, $getIdentity = false) {

        try {
            $decode = JWT::decode($jwt, $this->key, array('HS256'));
        } catch(\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e) {
            $auth = false;
        }

        if(isset($decode) && is_object($decode) && isset($decode->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }
        if($getIdentity == false) {
            return $auth;
        } else {
            return $decode;
        }



    }

    public function getUserType($identity) {

        $userRepo = $this->manager->getRepository('BackendBundle:User');

        $user = $userRepo->findOneBy(array('email' => $identity->email, 'id' => $identity->sub));

        return $user->getUserType()->getType();
    }

    public function decodeId($encryptedId) {

        try {
            $decode = JWT::decode($encryptedId, $this->key, array('HS256'));
        } catch(\UnexpectedValueException $e) {
            $decode = false;
        } catch (\DomainException $e) {
            $decode = false;
        }

        return $decode;
    }

    public function encodeId($id) {

        try {
            $encrypt = JWT::encode($id, $this->key, 'HS256');
        } catch(\UnexpectedValueException $e) {
            $encrypt = false;
        } catch (\DomainException $e) {
            $encrypt = false;
        }

        return $encrypt;

    }

    public function getAuthToken($email, $password, $getHash = null ) {


        $userRepo = $this->manager->getRepository('BackendBundle:User');

        $user = $userRepo->findOneBy(array('email' => $email, 'password' => $password ));

        $login = false;
        if(is_object($user)) {
            $login = true;


        }

        if($login) {

            //Generate Token
            $token = array (
                "sub"   => $this->decodeId($user->getId()),
                "email" =>  $user->getEmail(),
                "name"  => $user->getFirstName(),
                "surname" => $user->getLastName(),
                'role' => $user->getUserType()->getType(),
                "iat" => time(),
                "exp" => time() + (7 * 24 * 60 * 60)
            );

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decode = JWT::decode($jwt, $this->key, array('HS256'));

            if($getHash == null) {
                $data =  $jwt;
            } else {
                $data =  $decode;
            }

//            $data = $jwt;


        } else {
            $data = array (
                'status' => 'Login Failed',
                'code' => 404,
                'msg' => 'Email or Password is incorrect',
                'error_data' => ''
            );
        }

        return $data;
    }

}