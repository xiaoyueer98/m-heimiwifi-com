<?php
class  ProductModel  extends  Model{

              function getProductCount($where=""){
			             
						 $arr=$this->where($where)->select();
						 return count($arr);
			  }
			  function getProductList($start,$len,$where=""){
			             return  $this->where($where)->limit("$start,$len")->select();
			             
			  }
			  function  del($id){
			             
						 return  $this->where("id={$id}")->delete();
			  }
			  function   update($arr){
			             $this->data($arr);
						 $re=$this->save();
						 return $re;
			  }
              function  setStatus($id,$stapre,$sta){
                         $ob = M("product");
                         $re = $ob->query("update r_product set status=$sta where status=$stapre and id=$id");
                         return $re;
              }
}
