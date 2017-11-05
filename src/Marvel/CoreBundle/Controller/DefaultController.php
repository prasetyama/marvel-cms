<?php

namespace Marvel\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    
    public function dashboardAction(){
        return $this->render('MarvelAssetsBundle:Page:dashboard.html.twig');
    }
}
