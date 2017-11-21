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

    public function listDeveloperAction(Request $request){

        $this->init();

        //try {

            $act = new DeveloperManager($this->getDoctrine()->getManager());
            $res['data'] = $act->showAllDeveloper();

            if(!empty($_GET['type'])){
                if($_GET['type'] == 'bridge'){
                    return  $this->successResponse(array('data' => $act->showAllDeveloper()));
                }
            }

            return $this->render('MarvelDeveloperBundle:Page:developer-list.html.twig',$res);

        //} catch (\Exception $e) {
          //  return $this->errorResponse('Post developer failed, Please try again later', HttpStatusHelper::HTTP_PRECONDITION_FAILED);
        //}

    }

    public function addAction(){

        $res['token'] = date('ymdhis').str_replace(' ', '', microtime()).uniqid();
        return $this->render('MarvelDeveloperBundle:Page:proyek-developer-add.html.twig',$res);
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

    /* PROYEK DEVELOPER */

    public function listProyekDeveloperAction(Request $request){

        $this->init();

        try {

            $act = new DeveloperManager($this->getDoctrine()->getManager());
            $res['data'] = $act->showAllProyekDeveloper();

            return $this->render('MarvelDeveloperBundle:Page:proyek-developer-list.html.twig',$res);

        } catch (\Exception $e) {
            return $this->errorResponse('Post developer failed, Please try again later', HttpStatusHelper::HTTP_PRECONDITION_FAILED);
        }

    }

    public function postProyekDeveloperAction(Request $request){

        $this->init();

        try {
            $post = $request->request->all();

            $unit = '';
            if(count($post['unitName']) > 0){
                $dt['unit'] = array();

                for($i=0;$i<count($post['unitName']);$i++){
                    $dt['unit'][] = array(
                                        'unitName' => $post['unitName'][$i],
                                        'unitTotal' => $post['unitTotal'][$i],
                                        'unitPrice' => ($post['unitPrice'][$i] != 0) ? str_replace('.','',$post['unitPrice'][$i]) : '',
                                        'unitType' => $post['unitType'][$i],
                                        'unitLT' => $post['unitLT'][$i],
                                        'unitLB' => $post['unitLB'][$i],
                                        'unitKT' => $post['unitKT'][$i],
                                        'unitKM' => $post['unitKM'][$i],
                                        'unitSpesifikasi' => $post['unitSpesifikasi'][$i]
                                    );
                    
                }

                $unit = serialize($dt);
            }

            $act = new DeveloperManager($this->getDoctrine()->getManager());
            $res = $act->insertProyekDeveloper($post,$unit);

            if($res > 0){

                /* @var $targetDir \Marvel\CoreBundle */
                $coreDir = $this->container->getParameter('marvel.core.proyekImg');
                $tmpDir = $this->container->getParameter('marvel.core.tmp');
                
                $upload = new SimpleUpload();

                $gallery = $upload->dynamicUpload($coreDir,$res['projectID'],'gallery',$tmpDir.$post['token_gallery']);
                $denah = $upload->dynamicUpload($coreDir,$res['projectID'],'denah',$tmpDir.$post['token_denah']);

                $resGallery = $act->insertProyekGallery($res['projectID'],$gallery,'proyek_gallery',$post['gallerySetPrimary']);
                $resDenah = $act->insertProyekGallery($res['projectID'],$denah,'proyek_gallery_denah',$post['denahSetPrimary']);              

            }


            return  $this->successResponse(array('data' => $res));

        } catch (\Exception $e) {
            return $this->errorResponse('Post developer failed, Please try again later', HttpStatusHelper::HTTP_PRECONDITION_FAILED);
        }

    }

    public function editProyekDeveloperAction(Int $id){
        
        $this->init();

        try {

            $res['token'] = date('ymdhis').str_replace(' ', '', microtime()).uniqid();

            $act = new DeveloperManager($this->getDoctrine()->getManager());
            $res['data'] = $act->showProyekDeveloperByID($id);

            $res['proyekGallery']['initialPreview'] =  [];
            $res['proyekGallery']['initialPreviewConfig'] =  [];

            $res['proyekDenah']['initialPreview'] =  [];
            $res['proyekDenah']['initialPreviewConfig'] =  [];
            
            $proyekGallery = $act->showProyekGallery($id,'proyek_gallery');
            $proyekDenah = $act->showProyekGallery($id,'proyek_gallery_denah');

            
            if(!empty($proyekGallery)){
                foreach($proyekGallery as $key => $val){
                    $url = '/upload/delete?token='.$res['data']['project_id'].'&type=proyekGallery&data='.$val['ori_img'].'&id='.$res['data']['project_id'];
                    $p1[$key] = '<img style="height:160px" src="http://localhost:2000/uploads/proyek/'.$res['data']['project_id'].'/gallery/'.$val['ori_img'].'" class="file-preview-image">';
                    $p2[$key] = ['caption' => "", 'width' => '120px', 'url' => $url, 'key' => $val['ori_img']];
                }

                $res['proyekGallery']['initialPreview'] =  $p1;
                $res['proyekGallery']['initialPreviewConfig'] =  $p2;
            }

            if(!empty($proyekDenah)){
                foreach($proyekDenah as $key => $val){
                    $urlDenah = '/upload/delete?token='.$res['data']['project_id'].'&type=proyekDenah&data='.$val['ori_img'].'&id='.$res['data']['project_id'];
                    $p1Denah[$key] = '<img style="height:160px" src="http://localhost:2000/uploads/proyek/'.$res['data']['project_id'].'/denah/'.$val['ori_img'].'" class="file-preview-image">';
                    $p2Denah[$key] = ['caption' => "", 'width' => '120px', 'url' => $urlDenah, 'key' => $val['ori_img']];
                }

                $res['proyekDenah']['initialPreview'] =  $p1Denah;
                $res['proyekDenah']['initialPreviewConfig'] =  $p2Denah;
            }

            /*$res['proyekGallery'] =  [
                'initialPreview' => $p1, 
                'initialPreviewConfig' => $p2,
                'uploadUrl' => '/upload/post?type=gallery&token='.$res['token'],
                'uploadAsync' => false,
                'showUpload' => false, // hide upload button
                'showRemove' => false, // hide remove button
                'minFileCount' => 1,
                'maxFileCount' => 5,
                'allowedFileExtensions' => ['jpg', 'png','gif'],

            ];*/

            return $this->render('MarvelDeveloperBundle:Page:proyek-developer-edit.html.twig',$res);

        } catch (\Exception $e) {
            return $this->errorResponse('Post developer failed, Please try again later', HttpStatusHelper::HTTP_PRECONDITION_FAILED);
        }
    }

    public function updateProyekDeveloperAction(Request $request){

        $this->init();

        try {
            $post = $request->request->all();

            $unit = '';
            if(count($post['unitName']) > 0){
                $dt['unit'] = array();

                for($i=0;$i<count($post['unitName']);$i++){
                    $dt['unit'][] = array(
                                        'unitName' => $post['unitName'][$i],
                                        'unitTotal' => $post['unitTotal'][$i],
                                        'unitPrice' => ($post['unitPrice'][$i] != 0) ? str_replace('.','',$post['unitPrice'][$i]) : '',
                                        'unitType' => $post['unitType'][$i],
                                        'unitLT' => $post['unitLT'][$i],
                                        'unitLB' => $post['unitLB'][$i],
                                        'unitKT' => $post['unitKT'][$i],
                                        'unitKM' => $post['unitKM'][$i],
                                        'unitSpesifikasi' => $post['unitSpesifikasi'][$i]
                                    );
                    
                }

                $unit = serialize($dt);
            }

            $act = new DeveloperManager($this->getDoctrine()->getManager());
            $res = $act->updateProyekDeveloper($post,$unit);

            if($res > 0){

                /* @var $targetDir \Marvel\CoreBundle */
                $coreDir = $this->container->getParameter('marvel.core.proyekImg');
                $tmpDir = $this->container->getParameter('marvel.core.tmp');
                
                $upload = new SimpleUpload();

                $gallery = $upload->dynamicUpload($coreDir,$res['projectID'],'gallery',$tmpDir.$post['token_gallery']);
                $denah = $upload->dynamicUpload($coreDir,$res['projectID'],'denah',$tmpDir.$post['token_denah']);

                if(!empty($post['gallerySetPrimary'])){

                    if(!empty($gallery)){
                        $resGallery = $act->insertProyekGallery($res['projectID'],$gallery,'proyek_gallery',$post['gallerySetPrimary']);
                    }else{
                        $act->updatePrimary($res['projectID'],'proyek_gallery',$post['gallerySetPrimary']);    
                    }   
                }else{
                    if(!empty($gallery)){
                        $resGallery = $act->insertProyekGallery($res['projectID'],$gallery,'proyek_gallery',$post['gallerySetPrimary']);
                    }    
                }

                if(!empty($post['denahSetPrimary'])){

                    if(!empty($denah)){ 
                        $resDenah = $act->insertProyekGallery($res['projectID'],$denah,'proyek_gallery_denah',$post['denahSetPrimary']);  
                    }else{
                        $act->updatePrimary($res['projectID'],'proyek_gallery_denah',$post['denahSetPrimary']);   
                    }  
                }else{
                    if(!empty($denah)){ 
                        $resDenah = $act->insertProyekGallery($res['projectID'],$denah,'proyek_gallery_denah',$post['denahSetPrimary']);  
                    }    
                }          

            }


            return  $this->successResponse(array('data' => $res));

        } catch (\Exception $e) {
            return $this->errorResponse('Post developer failed, Please try again later', HttpStatusHelper::HTTP_PRECONDITION_FAILED);
        }

    }
}
