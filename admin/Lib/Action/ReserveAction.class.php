<?php
class  ReserveAction  extends  Action{

public  function  getlist(){
      $where = " 1";
      $page = 1;
      $size = 15;
      if(!empty($_GET)){
            if($_GET['page'] != ""){
                  
                  $page = $_GET['page'];
            }
            if($_GET['start_time'] != "" && $_GET['start_time'] != "开始日期"){
      
                  $where .= " and time >= '".$_GET['start_time']."'"; 
            }
            if($_GET['end_time'] != "" && $_GET['end_time'] != "截止日期"){
                  
                  $where .= " and time <= '".$_GET['end_time']." 23:59:59'";
            }
            if($_GET['uid'] != ""){
                
                  $where .= " and uid = {$_GET['uid']}";
            }
            if($_GET['size'] != ""){
                  
                  $size = $_GET['size'];
            }
      
      }
      $reOb = new ReserveModel;
      $num = $reOb->getCount($where);//var_dump($num);
      import("ORG.Util.Page1"); 
      $pageOb = new Page($page,$size,$num);//var_dump($pageOb);
      $start = $pageOb -> getStartStr(); 
      $reArr = $reOb -> getList($where,$start,$size);//var_dump($reArr);echo $reOb->getLastSql();
      $pageStr = $pageOb -> getPageStr1(1);
      $mOb=M("member");
      foreach($reArr as $k=>$v){
             $mArr=$mOb->where("id={$reArr[$k]['uid']}")->select();//var_dump($mArr);
             unset($reArr[$k]['uid']);
             $reArr[$k]['tel']=$mArr[0]['tel'];
      }
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

}
