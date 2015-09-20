<?php
class   NewsModel   extends  Model{

                 function  addd($arr){
				        
						 /*foreach($arr as $k=>$v){
						        $key.=",".$k;
								$value.=",'".$v."'";
						 }
						 $key=substr($key,1);
						 $value=substr($value,1);
						 $this->query("insert into p_news($key) values($value)");
						 */
				         return  $this->data($arr)->add();
				 }
				 function  getNewsCount($where=""){
				 
				         $arr=$this->where($where)->select();
						 return  count($arr);
				 }
				 function  del($id){
				         
						 return  $this->where("id={$id}")->delete();
				 }
				 function  upd($arr){
				       
						 return $this->data($arr)->save();
				 
				 }
				 function getNewsList($start=0,$len=10,$where=""){
				         
						 return $this->where($where)->limit("{$start},{$len}")->select();
				 }
}
