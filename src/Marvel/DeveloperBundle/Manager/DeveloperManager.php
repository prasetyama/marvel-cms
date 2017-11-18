<?php

namespace Marvel\DeveloperBundle\Manager;

use Marvel\CoreBundle\Util\SlugHelper;

class DeveloperManager{

	protected $conn;

	public function __construct($em){
     	
    $this->conn = $em->getConnection();

  }

  public function insertDeveloper($param = array() ,  $fileName = ""){

  	$sql = "INSERT INTO developer 
          		(developer_name, developer_address, developer_email, developer_phone, developer_mobilephone, developer_notes, developer_logo, status, post_date) 
          		VALUES ('".$param['developer_name']."', '".$param['address']."', '".$param['email']."', '".$param['phone']."', '".$param['mobile_phone']."', '".$param['notes']."', '".$fileName."', 1, NOW());";

    	$stmt = $this->conn->prepare($sql);
    	
    	if($stmt->execute()){

    		$res = (int) $this->conn->lastInsertId();	
    		return array('developerID' => $res);
    	
    	}else{
    		return array();
    	}	
  }

  public function updateDeveloper( $param = array(), $fileName = ""){

    $up['developer_name'] = $param['developer_name'];
    $up['developer_address'] = $param['address'];
    $up['developer_email'] = $param['email'];
    $up['developer_phone'] = $param['phone'];
    $up['developer_mobilephone'] = $param['mobile_phone'];
    $up['developer_notes'] = $param['notes'];
    
    if(!empty($fileName)){
      $up['developer_logo'] = $fileName;
    }

    foreach ($up as $key => $value) {
        $value = "'$value'";
        $updates[] = "$key = $value";
    }

    $implodeArray = implode(', ', $updates);

    $sql = "UPDATE developer SET $implodeArray, update_date = NOW() WHERE developer_id = ".$param['developer_id'];

    $stmt = $this->conn->prepare($sql);
    
    if($stmt->execute()){

      return array('developerID' => $param['developer_id']);
    
    }else{
      return array();
    }
  }

  public function showAllDeveloper(){

    $sql = "SELECT * FROM developer WHERE status = 1";

    $stmt = $this->conn->prepare($sql);

    if($stmt->execute()){

      $res = $stmt->fetchAll(); 
      
      return $res;
    
    }else{
      return array();
    }   
  }

  public function showDeveloperByID(Int $param){

    $sql = "SELECT * FROM developer WHERE developer_id =".$param;

    $stmt = $this->conn->prepare($sql);

    $stmt->execute();
    $count = $stmt->rowCount();

    if($count > 0){

      /*$res = $stmt->fetchAll(\PDO::FETCH_ASSOC)[0];
      $res['developer_id'] = (int) $res['developer_id'];
      $res['status'] = (int) $res['status'];*/

      $res = $stmt->fetchAll()[0]; 
      
      return $res;
    
    }else{
      return array();
    }   
  }

  public function insertProyekDeveloper(array $param = array(), $unit){

    $slug = new SlugHelper();
    $slug = $slug->makeSlug($param['projectName']);

    $in['property_type_id'] = $param['propertyType'];
    $in['area_id'] = $param['area'];
    $in['developer_id'] = $param['developerName'];
    $in['project_name'] = $param['projectName'];
    $in['project_start_price'] = str_replace('.','',$param['startPrice']);
    $in['project_end_price'] = str_replace('.','',$param['endPrice']);
    $in['project_title_tagline'] = $param['titleTagline'];
    $in['project_subtitle_tagline'] = $param['subtitleTagline'];
    $in['project_description_tagline'] = $param['titleTaglineDesc'];
    $in['project_description'] = $param['desc'];
    $in['project_address'] = $param['address'];
    $in['project_postcode'] = $param['postcode'];
    $in['project_facility'] = $param['facility'];
    $in['project_access'] = $param['access'];
    $in['project_total_unit'] = $param['unitTotalAll'];
    $in['project_unit'] = $unit;
    $in['project_slug'] = $slug;

    $valuex = array();
    $fieldx = array();

    foreach ($in as $key => $value) {
        $valuex[] = "'$value'";
        $fieldx[] = $key;
    }

    $implodeField = implode(', ', $fieldx);
    $implodeValue = implode(', ', $valuex);


    $sql = "INSERT INTO proyek_developer (".$implodeField.", status, post_date, post_by) VALUES (".$implodeValue.", 1, NOW(), 'admin');";

      $stmt = $this->conn->prepare($sql);
      
      if($stmt->execute()){

        $res = (int) $this->conn->lastInsertId(); 
        return array('projectID' => $res);
      
      }else{
        return array();
      } 
  }

  public function updateProyekDeveloper(array $param = array(), $unit){

    $slug = new SlugHelper();
    $slug = $slug->makeSlug($param['projectName']);

    $in['property_type_id'] = $param['propertyType'];
    $in['area_id'] = $param['area'];
    $in['developer_id'] = $param['developerName'];
    $in['project_name'] = $param['projectName'];
    $in['project_start_price'] = str_replace('.','',$param['startPrice']);
    $in['project_end_price'] = str_replace('.','',$param['endPrice']);
    $in['project_title_tagline'] = $param['titleTagline'];
    $in['project_subtitle_tagline'] = $param['subtitleTagline'];
    $in['project_description_tagline'] = $param['titleTaglineDesc'];
    $in['project_description'] = $param['desc'];
    $in['project_address'] = $param['address'];
    $in['project_postcode'] = $param['postcode'];
    $in['project_facility'] = $param['facility'];
    $in['project_access'] = $param['access'];
    $in['project_total_unit'] = $param['unitTotalAll'];
    $in['project_unit'] = $unit;
    $in['project_slug'] = $slug;

    foreach ($in as $key => $value) {
        $value = "'$value'";
        $updates[] = "$key = $value";
    }

    $implodeArray = implode(', ', $updates);

    $sql = "UPDATE proyek_developer SET $implodeArray, update_date = NOW(), update_by = 'admin' WHERE project_id = ".$param['project_id'];

    $stmt = $this->conn->prepare($sql);
    
    if($stmt->execute()){

      return array('projectID' => $param['project_id']);
    
    }else{
      return array();
    }
  }

  public function showAllProyekDeveloper(){

    $sql = "SELECT  proyek.project_id, proyek.project_name, proyek.project_start_price, 
                    proyek.project_end_price, proyek.project_total_unit, dev.developer_name,
                    type.property_type_name, area.area_name, city.city_name 

                    FROM proyek_developer proyek LEFT JOIN developer dev ON proyek.developer_id = dev.developer_id 
                    LEFT JOIN property_type type ON proyek.property_type_id = type.property_type_id
                    LEFT JOIN mst_area area ON area.area_id = proyek.area_id
                    LEFT JOIN mst_city city ON city.city_id = area.city_id
                    WHERE proyek.status = 1";

    $stmt = $this->conn->prepare($sql);

    if($stmt->execute()){

      $res = $stmt->fetchAll(); 
      
      return $res;
    
    }else{
      return array();
    }   
  }

  public function showProyekDeveloperByID(Int $param){

    $sql = "SELECT proyek.*, city.city_id, province.province_id 
            FROM proyek_developer proyek LEFT JOIN mst_area area ON proyek.area_id = area.area_id 
            LEFT JOIN mst_city city ON area.city_id = city.city_id
            LEFT JOIN mst_province province ON city.province_id = province.province_id
            WHERE proyek.project_id =".$param;

    $stmt = $this->conn->prepare($sql);

    $stmt->execute();
    $count = $stmt->rowCount();

    if($count > 0){

      $res = $stmt->fetchAll()[0];

      $res['project_unit'] = unserialize($res['project_unit']);
      
      return $res;
    
    }else{
      return array();
    }   
  }

  public function insertProyekGallery($projectID, $param, $table, $primary){

    $value = array();
    $field = array();

    foreach($param as $k => $v){
      $value[] = '('.$projectID.', "'.$v['imgName'].'", "sm_'.$v['imgName'].'", "md_'.$v['imgName'].'", "lg_'.$v['imgName'].'", "test", 0, 1, NOW(), "admin" )';
    }

    $implodeField = implode(', ', $field);
    $implodeValue = implode(', ', $value);


    $sql = "INSERT INTO ".$table." 
              (project_id, ori_img, sm_img, md_img, lg_img, caption, is_primary, status, post_date, post_by) 
              VALUES ".$implodeValue;

    $stmt = $this->conn->prepare($sql);
      
    if($stmt->execute()){

      if(!empty($primary)){
        $x = $this->conn->prepare("UPDATE ".$table." SET is_primary = 0 WHERE project_id = ".$projectID);
        $x->execute();

        $y = $this->conn->prepare("UPDATE ".$table." SET is_primary = 1 WHERE ori_img = '".$primary."' ");
        $y->execute();
      }

      return true;
    
    }else{
      return false;
    } 
  }

  public function showProyekGallery(Int $param, $table){

    $sql = "SELECT * FROM ".$table." WHERE status = 1 AND project_id =".$param;

    $stmt = $this->conn->prepare($sql);

    $stmt->execute();
    $count = $stmt->rowCount();

    if($count > 0){

      $res = $stmt->fetchAll();
      
      return $res;
    
    }else{
      return array();
    }   
  }

  public function deleteProyekGallery($param, $table){
    $sql = "DELETE FROM ".$table." WHERE ori_img = '".$param."' ";

    $stmt = $this->conn->prepare($sql);
    
    if($stmt->execute()){

      return true;
    
    }else{
      return false;
    }
  }

  public function updatePrimary($projectID, $table, $file){
    $x = $this->conn->prepare("UPDATE ".$table." SET is_primary = 0 WHERE project_id = ".$projectID);
    $x->execute();

    $y = $this->conn->prepare("UPDATE ".$table." SET is_primary = 1 WHERE ori_img = '".$file."' ");
    $y->execute();
  }

}