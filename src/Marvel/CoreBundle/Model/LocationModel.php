<?php

namespace Marvel\CoreBundle\Model;

class LocationModel{

	protected $conn;

	public function __construct($em){
     	
    $this->conn = $em->getConnection();

  }

  public function listProvince($param){

    $sql = "SELECT * FROM mst_province";

    $stmt = $this->conn->prepare($sql);

    if($stmt->execute()){

      $res = $stmt->fetchAll(); 
      
      return $res;
    
    }else{
      return array();
    }   
  }

  public function listCity($param){

    $sql = "SELECT * FROM mst_city WHERE province_id = ".$param['provinceID'];

    $stmt = $this->conn->prepare($sql);

    if($stmt->execute()){

      $res = $stmt->fetchAll(); 
      
      return $res;
    
    }else{
      return array();
    }   
  }

  public function listArea($param){

    $sql = "SELECT * FROM mst_area WHERE city_id = ".$param['cityID'];

    $stmt = $this->conn->prepare($sql);

    if($stmt->execute()){

      $res = $stmt->fetchAll(); 
      
      return $res;
    
    }else{
      return array();
    }   
  }


}