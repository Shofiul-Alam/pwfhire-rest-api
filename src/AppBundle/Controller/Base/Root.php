<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 18/11/2017
 * Time: 8:09 PM
 */

namespace AppBundle\Controller\Base;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Root extends Controller {
    protected $imageTypes = array(
        'image/jpeg'            => 'jpeg',
        'image/jpg'             => 'jpg',
        'image/gif'             => 'gif',
        'image/xpm'             => 'xpm',
        'image/gd'              => 'gd',
        'image/gd2'             => 'gd2',
        'image/wbmp'            => 'bmp',
        'image/bmp'             => 'bmp',
        'image/x-ms-bmp'        => 'bmp',
        'image/x-windows-bmp'   => 'bmp',
        'image/png'             => 'png',
    );

    protected $documentTypes = array (
        'application/pdf' => 'pdf',
    );

    protected function processDocument($entity, $params, $childEntityName) {

    }

    protected function isValidFile($file) {
        $ext = $file->guessExtension();
        if(in_array($ext, $this->imageTypes) ||  in_array($ext, $this->documentTypes)){
            return true;
        } else {
            return false;
        }
    }
}