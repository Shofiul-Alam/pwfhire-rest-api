<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 9/11/2017
 * Time: 10:59 AM
 */

namespace BackendBundle\Entity;

use Firebase\JWT\JWT;


class AEntity {

    private $encryptedId = '';


    /**
     * @return string
     */
    public function getEncryptedId(): string
    {
        return $this->encryptedId;
    }

    /**
     * @param string $encryptedId
     */
    public function setEncryptedId(string $encryptedId)
    {
        $this->encryptedId = $encryptedId;
    }


    public static function getEntityClassName() {
        $fullClassName = get_called_class();

        return substr($fullClassName, strrpos($fullClassName, '\\')+1);
    }

    public function encodeId($id) {

        try {
            $encrypt = JWT::encode($id, 'sxdet23445yklwerbkelswefbde', 'HS256');
        } catch(\UnexpectedValueException $e) {
            $encrypt = false;
        } catch (\DomainException $e) {
            $encrypt = false;
        }

        return $encrypt;

    }





}