<?php

namespace Marvel\DeveloperBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    
    public function indexAction(){
        return $this->render('MarvelLoginBundle:Page:login.html.twig');
    }

    public function addAction(){
        return $this->render('MarvelAssetsBundle:Page:developer-add.html.twig');
    }
}
