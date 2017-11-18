<?php
namespace Marvel\CoreBundle\Util;

use Cocur\Slugify\Slugify;

class SlugHelper{

	public function makeSlug(String $data){
		
		$slugify = new Slugify();
		return $slugify->slugify($data);
	}
}