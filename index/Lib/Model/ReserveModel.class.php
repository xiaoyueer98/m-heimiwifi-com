<?php

class  reserveModel   extends  Model{


    function  addReserve($arr){

        $this->data($arr);
        return  $this->add();	
    } 
    function  getReserve($where=""){

        $this->where($where);
        return  $this->select();
    }  
    function  updReserve($arr){

        return  $this->data($arr)->save();
    }
    function  getReserveCount($where=""){

        $arr=$this->field("count(*) as num")->where($where)->select();
        return $arr[0]['num'];
    }

}
