<?php
namespace Marvel\CoreBundle\Util;

use Marvel\CoreBundle\Util\SlugHelper;

class SimpleUpload{

	protected function resizeImage($imagePath, $fileNameOri, $fileNameTo, $width, $height, $filterType = 0, $blur = 0, $bestFit = true, $cropZoom = false){
    
	    //The blur factor where > 1 is blurry, < 1 is sharp.
	    $imagick = new \Imagick($imagePath.$fileNameOri);
	 
	    $imagick->resizeImage($width, $height, $filterType, $blur, $bestFit);
	 
	    $cropWidth = $imagick->getImageWidth();
	    $cropHeight = $imagick->getImageHeight();
	 
	    if ($cropZoom) {
	        $newWidth = $cropWidth / 2;
	        $newHeight = $cropHeight / 2;
	 
	        $imagick->cropimage(
	            $newWidth,
	            $newHeight,
	            ($cropWidth - $newWidth) / 2,
	            ($cropHeight - $newHeight) / 2
	        );
	 
	        $imagick->scaleimage(
	            $imagick->getImageWidth() * 4,
	            $imagick->getImageHeight() * 4
	        );
	    }
	 
	    $imagick->writeImage($imagePath.$fileNameTo);

	    return true;
	}

	public function fileUpload($file, $title, $targetPath){
		
		$slug = new SlugHelper();
        $slug = $slug->makeSlug($title);

        $filename = uniqid()."-".$slug.".".$file->getClientOriginalExtension();
        $file->move($targetPath,$filename); 

        $resize = $this->resizeImage($targetPath."/",$filename,$filename,200,200);
        
        return $filename;
 		
	}

	public function fileUploadV2($file,$targetDir,$token,$type){

		$image = 1;
		$finishName = '';

		foreach($file[$type]['tmp_name'] as $key => $tmp_name ){
			//$fileName = date('ymdhis').str_replace(' ', '', microtime());
			//$fileName = uniqid().md5(microtime());
			$fileName = uniqid();
		    $file_size =$file[$type]['size'][$key];
		    $file_tmp =$file[$type]['tmp_name'][$key];
		    $file_type=$file[$type]['type'][$key];
			
			$file_ext=explode('.',$file[$type]['name'][$key])	;
			$file_ext=end($file_ext);
			$finishName = $fileName.'.'.$file_ext;
			
			if(move_uploaded_file($file_tmp,$targetDir.$finishName)){

				$this->resizeImage($targetDir,$finishName,'sm_'.$finishName,200,200);
				$this->resizeImage($targetDir,$finishName,'md_'.$finishName,600,600);
				$this->resizeImage($targetDir,$finishName,'lg_'.$finishName,800,800);

				if($type == 'proyekGallery'){
					$url = '/upload/delete?token='.$token.'&type=tmp&data='.$finishName;
				}else{
					$url = '/upload/delete?token='.$token.'&type=tmp&data='.$finishName;
				}

			    $p1[$key] = "<img style='height:160px' src='/uploads/tmp/".$token."/".$finishName."' class='file-preview-image'>";
			    $p2[$key] = ['caption' => "", 'width' => '120px', 'url' => $url, 'key' => $finishName];
			    
			}
		}

		return array('initialPreview' => $p1, 'initialPreviewConfig' => $p2, 'append' => true);

	}

	public function dynamicUpload($codeDir, $id, $dirName, $tmpDir){

		if (!is_dir($codeDir.$id)) {
			@mkdir($codeDir.$id, 0777);
		}

		if (!is_dir($codeDir.$id.'/'.$dirName)) {
			@mkdir($codeDir.$id.'/'.$dirName, 0777);
		}

		$arr = array();

		if(is_dir($tmpDir) && count(scandir($tmpDir)) > 2){
			$file = scandir($tmpDir);
			foreach($file as $value){
				
				if(!in_array($value,array(".",".."))){
					@copy($tmpDir.'/'.$value, $codeDir.$id.'/'.$dirName.'/'.$value);
					$gl['imgName'] = $value;
				}

				if((!preg_match('[lg|md|sm]', $value)) && (!in_array($value,array(".","..")))) {
					$arr[] = $gl;
				
				}	
				
			}

			$this->destroyDir($tmpDir);
		}

		return $arr;

	}

	public function deleteImg($folder,$img){

		$file = $folder.$img;
		$fileSm = $folder.'/sm_'.$img;
		$fileMd = $folder.'/md_'.$img;
		$fileLg = $folder.'/lg_'.$img;
		
		@unlink($file);
		@unlink($fileSm);
		@unlink($fileMd);
		@unlink($fileLg);
	}

	public function destroyDir($dir, $virtual = false){
        $ds = DIRECTORY_SEPARATOR;
        $dir = $virtual ? realpath($dir) : $dir;
        $dir = substr($dir, -1) == $ds ? substr($dir, 0, -1) : $dir;
        if(is_dir($dir) && $handle = opendir($dir)){
            while (false!==($file = readdir($handle))){
                 if($file == '.' || $file == '..'){
					continue;
				}elseif(is_dir($dir.$ds.$file)){
                    destroyDir($dir.$ds.$file);
                }else{
                    unlink($dir.$ds.$file);
                }
            }
                        
			closedir($handle);
            rmdir($dir);
			return true;
		}else{
			return false;
		}
	}
}