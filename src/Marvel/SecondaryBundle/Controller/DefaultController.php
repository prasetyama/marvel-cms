<?php

namespace Marvel\SecondaryBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Marvel\CoreBundle\Controller\BaseController;
use Marvel\SecondaryBundle\Manager\SecondaryManager;
use Marvel\CoreBundle\Util\HttpStatusHelper;
use Marvel\CoreBundle\Util\SimpleUpload;

class DefaultController extends BaseController
{
    
    public function indexAction(){
        return $this->render('MarvelLoginBundle:Page:login.html.twig');
    }

    public function addAction(){
        return $this->render('MarvelSecondaryBundle:Page:secondary-add.html.twig');
    }

    public function addAgentAction(){
    	return $this->render('MarvelSecondaryBundle:Page:agent-add.html.twig');
    }

    public function listAgentAction(){
    	$this->init();

        try {

            $act = new DeveloperManager($this->getDoctrine()->getManager());
            $res['data'] = $act->showAllDeveloper();

            return $this->render('MarvelDeveloperBundle:Page:developer-list.html.twig',$res);

        } catch (\Exception $e) {
            return $this->errorResponse('Post developer failed, Please try again later', HttpStatusHelper::HTTP_PRECONDITION_FAILED);
        }
    	return $this->render('MarvelDeveloperBundle:Page:developer-list.html.twig');
    }

    public function addCompanyAction(){
    	return $this->render('MarvelSecondaryBundle:Page:company-add.html.twig');
    }

    public function postCompanyAction(Request $request){

        $this->init();

    	try {
            $post = $request->request->all();
            $file = $request->files->get('logo');

            $fileName = "";

            if(!is_null($file)){
                
                /* @var $targetDir \Marvel\CoreBundle */
                $targetDir = $this->container->getParameter('marvel.core.upload_logo_company');
                
                $upload = new SimpleUpload();
                $upload = $upload->fileUpload($file,$post['company_name'],$targetDir);
                $fileName = $upload;
            }

            $act = new SecondaryManager($this->getDoctrine()->getManager());
            $res = $act->insertCompany($post,$fileName);

            return  $this->successResponse(array('data' => $res));

        } catch (\Exception $e) {
            return $this->errorResponse('Post developer failed, Please try again later', HttpStatusHelper::HTTP_PRECONDITION_FAILED);
        }

    }

    public function listCompanyAction(){
    	$this->init();

        try {

            $act = new SecondaryManager($this->getDoctrine()->getManager());
            $res['data'] = $act->showAllCompany();

            return $this->render('MarvelDeveloperBundle:Page:developer-list.html.twig',$res);

        } catch (\Exception $e) {
            return $this->errorResponse('Post developer failed, Please try again later', HttpStatusHelper::HTTP_PRECONDITION_FAILED);
        }
    	return $this->render('MarvelDeveloperBundle:Page:developer-list.html.twig');
    }
}
