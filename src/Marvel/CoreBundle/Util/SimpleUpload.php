<?php
namespace Marvel\CoreBundle\Util;

use Marvel\CoreBundle\Util\SlugHelper;

class SimpleUpload{

	protected function resizeImage($imagePath, $width, $height, $filterType = 0, $blur = 0, $bestFit = true, $cropZoom = false){
    
	    //The blur factor where > 1 is blurry, < 1 is sharp.
	    $imagick = new \Imagick($imagePath);
	 
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
	 
	    $imagick->writeImage($imagePath);

	    return true;
	}

	public function fileUpload($file, String $title, String $targetPath){
		
		$slug = new SlugHelper();
        $slug = $slug->makeSlug($title);

        $filename = uniqid()."-".$slug.".".$file->getClientOriginalExtension();
        $file->move($targetPath,$filename); 

        $resize = $this->resizeImage($targetPath."/".$filename,200,200);
        
        return $filename;
 		
	}
}