<?php
namespace Marvel\CoreBundle\Util;

use Cocur\Slugify\Slugify;

class SlugHelper{

	public function makeSlug($data){
		
		$slugify = new Slugify();
		return $slugify->slugify($data);
	}
}