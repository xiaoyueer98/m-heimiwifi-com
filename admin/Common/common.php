<?php

function   type($fid=0,$n=0,$fstr){
          $fstr.=">".$fid;
          $n++;
         //获取fid对应的分类
		  $ob=M("producttype");
		  $arr=$ob->where("fid={$fid}")->select();
          /*
		  echo"<pre>";
		  echo $n;
		  var_dump($arr);
		  echo"</pre>";
		  */
		  
		  for($i=1;$i<$n;$i++){
		         $kStr.="&nbsp;&nbsp;"; 
		  }
		  
		  foreach($arr as $v){
		               $id=$v['id'];
					   $newFstr=substr($fstr,2);
					   if($n==1){
		                       $str.="<option value='{$newFstr}>{$id}>'>{$v['name']}</option>";
		               }else{
		                       $str.="<option value='{$newFstr}>{$id}>'>{$kStr}{$v['name']}</option>";
					   }
					   //查找$id是否有子分类，找到它的子分类对应的数组
					   $sarr=$ob->where("fid={$id}")->select();
					   /*echo"<pre>";
		               var_dump($arr);
		               echo"</pre>";*/
					   if(!empty($sarr) && is_array($sarr)){
					             
								  $str1=type($id,$n,$fstr);
								  $str=$str.$str1;
								  
					   }
					   
		  }
		 
		  return   $str;
		
}
function    mark(){
            $mOb=M("mark");
		    $arr1=$mOb->select();//var_dump($arr1);
			foreach($arr1 as $v1){
			         
			         $str.="<option value=\"{$v1['id']}\">{$v1['name']}</option>";
			}
		
			return  $str;
}

function excelTime($days, $time=false){

if(is_numeric($days)){
//based on 1900-1-1
$jd = GregorianToJD(1, 1, 1970);
$gregorian = JDToGregorian($jd+intval($days)-25569);
$myDate = explode('/',$gregorian);
$myDateStr = str_pad($myDate[2],4,'0', STR_PAD_LEFT)
."-".str_pad($myDate[0],2,'0', STR_PAD_LEFT)
."-".str_pad($myDate[1],2,'0', STR_PAD_LEFT)
.($time?" 00:00:00":'');
return $myDateStr;
}
return $days;

}

