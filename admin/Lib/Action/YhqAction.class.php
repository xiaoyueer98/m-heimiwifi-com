<?php
class  YhqAction  extends  Action{

public  function  getlist(){
      $where = " (status=0 or status=1 or status=2)";
      $page = 1;
      $size = 15;
      if(!empty($_GET)){
            if($_GET['page'] != ""){
                  
                  $page = $_GET['page'];
            }
            if($_GET['start_time'] != "" && $_GET['start_time'] != "开始日期"){
      
                  $where .= " and deadTime >= '".$_GET['start_time']."'"; 
            }
            if($_GET['end_time'] != "" && $_GET['end_time'] != "截止日期"){
                  
                  $where .= " and deadTime <= '".$_GET['end_time']." 23:59:59'";
            }
            if($_GET['code'] != ""){
                
                  $where .= " and code = '".$_GET['code']."'";
            }
            if($_GET['type'] != ""){
                
                  $where .= " and type =". $_GET['type'];
            }
            if($_GET['inPrice'] != ""){
                
                  $where .= " and inPrice =". $_GET['inPrice'];
            }
            if($_GET['status'] != ""){
                
                  $where .= " and status =". $_GET['status'];
            }
            if($_GET['size'] != ""){
                  
                  $size = $_GET['size'];
            }
      
      }
      $reOb = new YhqModel;
      $num = $reOb->getCount($where);//var_dump($num);
      import("ORG.Util.Page1"); 
      $pageOb = new Page($page,$size,$num);//var_dump($pageOb);
      $start = $pageOb -> getStartStr(); 
      $reArr = $reOb -> getList($where,$start,$size);//var_dump($reArr);echo $reOb->getLastSql();
      $pageStr = $pageOb -> getPageStr1(1);
     
      //var_dump($reArr); 
      $arr=array(
         'page' => $pageStr,
         'data' => $reArr        
      );//var_dump($data);
      $data=json_encode($arr);
      echo $data;

}                        

public  function  lister1(){

      $this->display();
} 


 //导出功能
public  function  export(){

      //$_GET['price']=1;
      //var_dump($_GET);
      $where=" 1";
      if(!empty($_GET)){
            
            if($_GET['start_time'] != "" && $_GET['start_time'] != "开始日期"){
      
                  $where .= " and createTime >= '".$_GET['start_time']."'"; 
            }
            if($_GET['end_time'] != "" && $_GET['end_time'] != "截止日期"){
                  
                  $where .= " and createTime <= '".$_GET['end_time']." 23:59:59'";
            }
            if($_GET['code'] != ""){
                
                  $where .= " and code = '".$_GET['code']."'";
            }
            if($_GET['type'] != ""){
                
                  $where .= " and type =". $_GET['type'];
            }
            if($_GET['inPrice'] != ""){
                
                  $where .= " and inPrice =". $_GET['inPrice'];
            }
            if($_GET['status'] != ""){
                
                  $where .= " and status =". $_GET['status'];
            }
          
      }

     $yOb=M("yhq");
     $data=$yOb->where($where)->order("id desc")->select();       
     //echo "<pre>";var_dump($data); echo "</pre>";
     //echo $yOb->getLastSql();die();	      
     import("ORG.Util.MyExcel");	    
     $ob=new MyExcel();
     ob_end_clean(); 
     $ob->setTitle(array('ID', '优惠券码', '优惠券价钱', '类型', '状态', '生成时间', '修改时间'))->setFileName("优惠券")->dump($data, 2007);
			 			      		
		    
}   
//生成优惠券并写进数据库
public  function  createYhqAjax(){
      if(!empty($_GET)){ 	   
	      $e=0;  //$_GET['num']=2;$_GET['inPrice']=100;$_GET['deadTime']="2014-10-10"; $_GET['type']='A3';
	      $yOb=M("yhq");
	      $sql="insert into r_yhq(code,inPrice,deadTime,type,status,createTime,updateTime) values";
	      $sql1="";
	      while($e<$_GET['num']){ 
		    $str="ab2c3d4e5f6g7h8jkmnopqrstu9vwxy0z";
            $s="";
            while(strlen($s)<8){
                 
                $i=mt_rand(0,32);
                $s .= $str[$i];
            
            }
		    $date=date('Y-m-d H:i:s');
		    $deadTime=$_GET['deadTime']." 23:59:59";  
		    $type=$_GET['type'];
		    $status=0;     
		    $inPrice=$_GET['inPrice'];
		    $sql1.=",('".$s."',".$inPrice.",'".$deadTime."','".$type."',".$status.",'".$date."','".$date."')";
		     $e++;
	       }
	      $sql1=substr($sql1,1);
	      //echo $sql.$sql1; 
	      $re=$yOb->query($sql.$sql1);
	      if($re!==false){
		    echo "1";
	      }   
      
     } 
 }

//置已发布
function  zhiyifabu(){
      
      $str=$_GET['str'];
      $arr=explode(",",$str);
      if($arr!=null){
            
            $date=date("Y-m-d H:i:s");
            $yOb=M("yhq");
            foreach($arr as $v){
                  $re1=$yOb->query("update r_yhq set updateTime='{$date}',status=1 where id={$v} and status=0");
             }     
           echo "1";
           
      }
}   
//置已发布
function  delYhq(){
      
      $str=$_GET['str'];
      $arr=explode(",",$str);
      if($arr!=null){
            
            $date=date("Y-m-d H:i:s");
            $yOb=M("yhq");
            foreach($arr as $v){
                  $re1=$yOb->query("update r_yhq set updateTime='{$date}',status=3 where id={$v}");
             }     
           echo "1";
           
      }
}   

}
