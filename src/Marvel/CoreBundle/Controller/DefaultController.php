<?php

namespace Marvel\CoreBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Marvel\CoreBundle\Util\SimpleUpload;
use Marvel\CoreBundle\Controller\BaseController;
use Marvel\CoreBundle\Model\LocationModel;
use Marvel\CoreBundle\Model\PropertyModel;
use Marvel\DeveloperBundle\Manager\DeveloperManager;

class DefaultController extends BaseController
{
    
    public function dashboardAction(){
        return $this->render('MarvelAssetsBundle:Page:dashboard.html.twig');
    }

    public function uploadAction(){

    	$this->init();

    	$p1 = $p2 = [];
		
		/*if (empty($_FILES['image']['name'])) {
		    echo '{}';
		    return;
		}*/

		$folderTMP	= $this->container->getParameter('marvel.core.tmp');
		$targetDir = $folderTMP.$_GET['token'].'/';
		
		if (!is_dir($targetDir)) {
			@mkdir($targetDir, 0777);
		}

		$upload = new SimpleUpload();
        $res = $upload->fileUploadV2($_FILES,$targetDir,$_GET['token'],$_GET['type']);
        
        if(is_array($res)){
		    return new JsonResponse($res);
		    //return new JsonResponse(array());
        }else{
        	return new JsonResponse(array());
        }

    }

    public function deleteImgAction(){

        $this->init();

        $type = $_GET['type'];
        $token = $_GET['token'];
        $img = $_GET['data'];
        $id = @$_GET['id'];
        $data = $_GET['data'];

        $upload = new SimpleUpload();

        switch($type){
            case 'tmp':
                $folder  = $this->container->getParameter('marvel.core.tmp');
                $inject = $upload->deleteImg($folder.$token.'/',$img);
                $res = true;
            break;
            case 'proyekGallery':
                $folder  = $this->container->getParameter('marvel.core.proyekImg');
                $inject = $upload->deleteImg($folder.$id.'/'.'gallery/',$img);
                $act = new DeveloperManager($this->getDoctrine()->getManager());
                $res = $act->deleteProyekGallery($data,'proyek_gallery');
            break;
            case 'proyekDenah':
                $folder  = $this->container->getParameter('marvel.core.proyekImg');
                $inject = $upload->deleteImg($folder.$id.'/'.'denah/',$img);
                $act = new DeveloperManager($this->getDoctrine()->getManager());
                $res = $act->deleteProyekGallery($data,'proyek_gallery_denah');
            break;
        }

        return new JsonResponse(true);

    }

    public function listProvinceAction(Request $request){

    	$this->init();

    	try {
            
            $get = $request->query->all();

            $act = new LocationModel($this->getDoctrine()->getManager());
            $res = $act->listProvince($get);

            return  $this->successResponse(array('data' => $res));

        } catch (\Exception $e) {
            return $this->errorResponse('Post developer failed, Please try again later', HttpStatusHelper::HTTP_PRECONDITION_FAILED);
        }
    }

    public function listCityAction(Request $request){

    	$this->init();

    	try {
            
            $get = $request->query->all();

            $act = new LocationModel($this->getDoctrine()->getManager());
            $res = $act->listCity($get);

            return  $this->successResponse(array('data' => $res));

        } catch (\Exception $e) {
            return $this->errorResponse('Post developer failed, Please try again later', HttpStatusHelper::HTTP_PRECONDITION_FAILED);
        }
    }

    public function listAreaAction(Request $request){

    	$this->init();

    	try {
            
            $get = $request->query->all();

            $act = new LocationModel($this->getDoctrine()->getManager());
            $res = $act->listArea($get);

            return  $this->successResponse(array('data' => $res));

        } catch (\Exception $e) {
            return $this->errorResponse('Post developer failed, Please try again later', HttpStatusHelper::HTTP_PRECONDITION_FAILED);
        }
    }

    public function listPropertyTypeAction(Request $request){

    	$this->init();

    	try {
            
            $get = $request->query->all();

            $act = new PropertyModel($this->getDoctrine()->getManager());
            $res = $act->listPropertyType($get);

            return  $this->successResponse(array('data' => $res));

        } catch (\Exception $e) {
            return $this->errorResponse('Post developer failed, Please try again later', HttpStatusHelper::HTTP_PRECONDITION_FAILED);
        }
    }


}
