<?php

namespace Marvel\LoginBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    
    public function indexAction(){
        return $this->render('MarvelLoginBundle:Page:login.html.twig');
    }
}
