<?php

class  memberModel   extends  Model{


    function  addMember($arr){

        $this->data($arr);
        return  $this->add();	
    } 
    function  getMember($where=""){

        $this->where($where);
        return  $this->select();
    }  
    function  updMember($arr){

        return  $this->data($arr)->save();
    }

}
