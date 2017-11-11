<?php

namespace Marvel\DeveloperBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Marvel\CoreBundle\Util\HttpStatusHelper;
use Marvel\CoreBundle\Util\SimpleUpload;
use Marvel\CoreBundle\Controller\BaseController;
use Marvel\DeveloperBundle\Manager\DeveloperManager;

class DefaultController extends BaseController
{
    
    public function indexAction(){
        return $this->render('MarvelLoginBundle:Page:login.html.twig');
    }

    public function listDeveloperAction(){

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

    public function addAction(){
        return $this->render('MarvelDeveloperBundle:Page:proyek-developer-add.html.twig');
    }

    public function addDeveloperAction(){
        return $this->render('MarvelDeveloperBundle:Page:developer-add.html.twig');
    }

    public function editDeveloperAction(Int $id){
        
        $this->init();

        try {

            $act = new DeveloperManager($this->getDoctrine()->getManager());
            $res['data'] = $act->showDeveloperByID($id);

            return $this->render('MarvelDeveloperBundle:Page:developer-edit.html.twig',$res);

        } catch (\Exception $e) {
            return $this->errorResponse('Post developer failed, Please try again later', HttpStatusHelper::HTTP_PRECONDITION_FAILED);
        }
    }

    public function postDeveloperAction(Request $request){

        $this->init();

    	try {
            $post = $request->request->all();
            $file = $request->files->get('logo');

            $fileName = "";

            if(!is_null($file)){
                
                /* @var $targetDir \Marvel\CoreBundle */
                $targetDir = $this->container->getParameter('marvel.core.upload_logo_developer');
                
                $upload = new SimpleUpload();
                $upload = $upload->fileUpload($file,$post['developer_name'],$targetDir);
                $fileName = $upload;
            }

            $act = new DeveloperManager($this->getDoctrine()->getManager());
            $res = $act->insertDeveloper($post,$fileName);

            return  $this->successResponse(array('data' => $res));

        } catch (\Exception $e) {
            return $this->errorResponse('Post developer failed, Please try again later', HttpStatusHelper::HTTP_PRECONDITION_FAILED);
        }

    }

    public function updateDeveloperAction(Request $request){

        $this->init();

        try {
            $post = $request->request->all();
            $file = $request->files->get('logo');

            $fileName = "";

            if(!is_null($file)){
                
                /* @var $targetDir \Marvel\CoreBundle */
                $targetDir = $this->container->getParameter('marvel.core.upload_logo_developer');
                
                $upload = new SimpleUpload();
                $upload = $upload->fileUpload($file,$post['developer_name'],$targetDir);
                $fileName = $upload;
            }

            $act = new DeveloperManager($this->getDoctrine()->getManager());
            $res = $act->updateDeveloper($post,$fileName);

            return  $this->successResponse(array('data' => $res));

        } catch (\Exception $e) {
            return $this->errorResponse('Post developer failed, Please try again later', HttpStatusHelper::HTTP_PRECONDITION_FAILED);
        }

    }
}
