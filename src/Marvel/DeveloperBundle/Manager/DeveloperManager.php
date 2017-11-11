<?php

namespace Marvel\DeveloperBundle\Manager;

class DeveloperManager{

	protected $conn;

	public function __construct($em){
     	
    $this->conn = $em->getConnection();

  }

  public function insertDeveloper(array $param = array(), String $fileName = ""){

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

  public function updateDeveloper(array $param = array(), String $fileName = ""){

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

}