<?php

namespace Marvel\CoreBundle\Model;

class PropertyModel{

	protected $conn;

	public function __construct($em){
     	
    $this->conn = $em->getConnection();

  }

  public function listPropertyType($param){

    if($param['type'] == 'project'){
      $search = 'AND is_project = 1';
    }else{
      $search = 'AND is_listing = 1';
    }

    $sql = "SELECT * FROM property_type WHERE status = 1 ".$search;

    $stmt = $this->conn->prepare($sql);

    if($stmt->execute()){

      $res = $stmt->fetchAll(); 
      
      return $res;
    
    }else{
      return array();
    }   
  }


}