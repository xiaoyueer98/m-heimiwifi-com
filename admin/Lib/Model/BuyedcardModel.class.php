<?php

class BuyedcardModel   extends  Model{

public  function  getList($where="",$start=1,$end=10){

      return $this->where($where)->limit("{$start},{$end}")->select(); 
} 
public  function  getList1($where="",$start=1,$end=10){

      return $this->where($where)->limit("{$start},{$end}")->order("id desc")->select(); 
} 

public  function  getCount($where=""){
         
      $arr=$this->where($where)->select(); 
      return count($arr);
}

public  function  setStatus($arr,$sta,$stapre){
     
       foreach($arr as $v){
               $arr=$this->where("id=$v")->select(); 
               if($arr['0']['status']==$stapre){   
                     $re=$this->query("update r_buyedcard set status='$sta' where id=$v" );
               }
        }
       return $re; 
} 
public  function  updBuyedcard($arr){

        $re=$this->data($arr)->save();
        return $re;
}



}
