<?php

class ReserveModel   extends  Model{

public  function  getList($where="",$start=1,$end=10){

      return $this->where($where)->limit("{$start},{$end}")->order("id desc")->select(); 
} 

public  function  getCount($where=""){
         
      $arr=$this->where($where)->select(); 
      return count($arr);
}



}
