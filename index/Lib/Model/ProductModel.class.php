<?php
class  ProductModel extends  Model{

    protected  $tableName="product";
    function  getDetail($id){

        $arr=$this->where("id=$id")->select();
        return  $arr;
    }
    function  getList($start,$len,$where,$order="id  desc"){

        return $this->limit("{$start},{$len}")->order($order)->where($where)->select();
    }
    function  getCount($where){

        $arr=$this->field("count(*) as num")->where($where)->select();
        return $arr[0]['num'];
    }


}
