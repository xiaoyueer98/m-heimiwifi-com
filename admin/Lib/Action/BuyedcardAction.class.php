<?php
class  BuyedcardAction  extends  Action{

public  function  getlist(){
      $where = "where";
      $page = 1;
      $size = 15;
      if((!isset($GET['start_time']) || empty($_GET['start_time'])) && (!isset($GET['start_time']) || empty($_GET['end_time']))){
             //$monthnow=date("Y-m-d h:i:s");
             //$monthnow="2014-04-02 10:10:10";
             //$where .=" and beginTime<='{$monthnow}' and endTime>='{$monthnow}'";
             
      }
      if(!empty($_GET)){
            if($_GET['page'] != ""){
                  
                  $page = $_GET['page'];
            }
            if($_GET['start_time'] != "" && $_GET['start_time'] != "开始日期"){
      
                  $where .= " and beginTime >= '".$_GET['start_time']."'"; 
            }
            if($_GET['end_time'] != "" && $_GET['end_time'] != "截止日期"){
                  
                  $where .= " and beginTime <= '".$_GET['end_time']." 23:59:59'";
            }
            if($_GET['CCID'] != ""){
                
                  $where .= " and CCID = '".$_GET['CCID']."'";
            }
            if($_GET['status'] != ""){
                
                  $where .= " and status = ".$_GET['status']."'";
            }
            if($_GET['size'] != ""){
                  
                  $size = $_GET['size'];
            }
      
      }
   
      $buyedOb = new BuyedcardModel;
      $num = $buyedOb->getCount($where);
      import("ORG.Util.Page1"); 
      $pageOb = new Page($page,$size,$num);//var_dump($pageOb);
      $start = $pageOb -> getStartStr(); 
      //$buyedArr = $buyedOb -> getList($where,$start,$size);//var_dump($orderArr);echo $corderOb->getLastSql();
      $buyedArr = $buyedOb->query("select CCID,sum(data) as totalpeie from r_buyedcard  $where  group by CCID ");
      //echo $buyedOb->getLastSql();die();
      $pageStr = $pageOb -> getPageStr1(1);
      
      $arr=array(
         'page' => $pageStr,
         'data' => $buyedArr        
      );//var_dump($data);
      $data=json_encode($arr);
      echo $data;

}                        

public  function  lister1(){

      $this->display();
}                

//充值
function  setchongzhi(){
      
      $str=$_GET['str'];
      $arr=explode(",",$str);
      if($arr!=null){
            $oOb=new CardorderModel();
            $re=$oOb->setStatus($arr,"3","2");
            $date=date("Y-m-d H:i:s");
            $coOb=M("cardorder");
            foreach($arr as $v){
                  $re1=$coOb->query("update r_cardorder set updateTime='{$date}' where id={$v}");
            }      
            if($re!==null){
                  echo "1";
            }
      }
}

public  function  getlist2(){
      $where = " status != 1";
      $shOb = M("simhard");
      $shArr = $shOb -> query("select * from r_simhard    where {$where} ");        
      //var_dump($shArr);die;
      foreach($shArr as $k=>$v){
            $coOb = M("cardorder");
            $coArr = $coOb->where("CCID = '{$shArr[$k]['CCID']}' and status=2")->select();
            if(isset($coArr) && !empty($coArr)){
                foreach($coArr as $cov){
                    $shArr[$k]['leftOrder'] .= ",".$cov['title']; 
                }
                $shArr[$k]['leftOrder'] = substr($shArr[$k]['leftOrder'],1);
            }else{
                $shArr[$k]['leftOrder'] = "无";
            }
            $content=file_get_contents("http://api.heimiwifi.com/wifi/query/?tel=".$shArr[$k]['CCID']);
            ob_end_clean();
            $re=json_decode($content);
            $re1=get_object_vars($re);
            $data=get_object_vars($re1['data']);
            $shArr[$k]['left'] = $data['left'];

      }
      //var_dump($shArr);die;
      $page = 1;
      $size = 15;
      if(!empty($_GET)){
            foreach($shArr as $k=>$v){
            $op =  true;
            if($_GET['page'] != ""){
                  
                  $page = $_GET['page'];
            }
            if($_GET['start_time'] != "" && $_GET['start_time'] != "开始日期"){
     
                  if ($shArr[$k]['deadTime'] >= $_GET['start_time']){
                        $op = true && $op;
                  }else{
                        $op = false && $op;
                  } 
                  //$op = " and {$shArr[$k]['deadTime']} >= {$_GET['start_time']}"; 
            }
            if($_GET['end_time'] != "" && $_GET['end_time'] != "截止日期"){
                  //$op .= "  and {$shArr[$k]['deadTime']} <= {$_GET['end_time']}"." 23:59:59";
                  if ($shArr[$k]['deadTime'] <= $_GET['end_time']." 23:59:59'"){
                       $op = true && $op;
                            
                  }else{
                    
                        $op = false && $op;
                  }
            }
            if($_GET['CCID'] != ""){
                  //$op .= "  and {$shArr[$k]['CCID']} == {$_GET['CCID']}";
                  if($shArr[$k]['CCID'] == $_GET['CCID']){
                        $op = true && $op; 
                  }else{
                  
                        $op = false && $op;
                  }
            }
            if($_GET['type'] != ""){
                  //$op .= " and {$shArr[$k]['type']} == {$_GET['type']}";  
                  if($shArr[$k]['type'] == $_GET['type']){
                        //$shArrSel[] = $shArr[$k]; 
                        $op = true && $op;
                  }else{
                      
                        $op = false && $op;
                  }
            }
            if($_GET['size'] != ""){
                  
                  $size = $_GET['size'];
            }
            if($_GET['sta'] != ""){
                 
                  if($_GET['sta'] == 2){
                        //$op .= "  and {$shArr[$k]['status']} == 2 or {$shArr[$k]['status']}==3";
                        if($shArr[$k]['status'] == 2 or  $shArr[$k]['status']==3){
                              //$shArrSel[] = $shArr[$k]; 
                              $op = true && $op;
                        }else{
                          
                              $op = false && $op;
                        }
                  }else{
                        //$op .= " and {$shArr[$k]['status']} == {$_GET['sta']}";
                        if($shArr[$k]['status'] == $_GET['sta']){
                          //    $shArrSel[] = $shArr[$k]; 
                             $op = true && $op; 
                        }else{
                           
                             $op = false && $op;
                        }
                  }
            }
      
            //$_GET['left'] = 1; 
            if($_GET['left'] != ""){
                 if(strpos($shArr[$k]['left'],"KB") !== false){
                       //$op .= " and {$_GET['left']}*1024 >= {$shArr[$k]['left']}"; 
                       if($_GET['left']*1024 >= $shArr[$k]['left']){
                               // $shArrSel[] = $shArr[$k]; 
                              $op = true && $op; 
                       }else{
                             
                              $op = false && $op;
                       }
                 }elseif(strpos($shArr[$k]['left'],"GB") !== false){
                       //$op .= " and {$_GET['left']}/1024 >= {$shArr[$k]['left']}";
                       if($_GET['left']/1024 >= $shArr[$k]['left']){ 
                         //       $shArrSel[] = $shArr[$k]; 
                              $op = true && $op;  
                       }else{
                              
                              $op = false && $op;
                       } 
                 }else{
                       //$op .= " and {$_GET['left']} >= {$shArr[$k]['left']}";
                       if($_GET['left'] >= $shArr[$k]['left']){ 
                         //       $shArrSel[] = $shArr[$k]; 
                              $op = true && $op;
                       }else{
                             
                              $op = false && $op;
                       }
                 }
            }
            //echo "条件".$op;
            //var_dump($op);

            if($op){
                $shArrSel[] = $shArr[$k]; 
            }
            }
      }
      /*
      if(!isset($shArrSel) || empty($shArrSel)){
            $shArrSel = $shArr;        
      }
      */
      $num = count($shArrSel);
      //echo $num;
      import("ORG.Util.Page1"); 
      $pageOb = new Page($page,$size,$num);//var_dump($pageOb);
      $start = $pageOb -> getStartStr(); 
      $pageStr = $pageOb -> getPageStr1(1);
      //echo "<pre>";
      //var_dump($shArrSel);
      //echo "</pre>";
      $i = $start;
      while(count($shArrNew) < $size ){
            //if($shArrSel[$i] != null){
                  $shArrNew[] = $shArrSel[$i];
            //}
            $i ++ ;
      }
      foreach($shArrNew as $k => $v){
            if($v == null){
                $shArrNew[$k] = "";
            }

      }
      $arr=array(
         'page' => $pageStr,
         'data' => $shArrNew        
      );
      //echo "<pre>";
      //var_dump($arr);
      //echo "</pre>";
      $data=json_encode($arr);
      echo $data;
}
/*
public  function  getlist2(){
      $where = " status != 1 ";
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
            if($_GET['CCID'] != ""){
                
                  $where .= " and CCID = '".$_GET['CCID']."'";
            }
            if($_GET['type'] != ""){
                
                  $where .= " and type = '".$_GET['type']."'";
            }
            if($_GET['size'] != ""){
                  
                  $size = $_GET['size'];
            }
            if($_GET['sta'] != ""){
                  if($_GET['sta'] == 2){
                       $where .= " and (status = 2 or status=3)";
                  }else{
                       $where .= " and status = ".$_GET['sta'];
                  }
            }
      
      }
   
      $shOb = M("simhard");
      $shNum = $shOb ->field("count(*) as num")-> where($where) -> select();
      //echo $shOb -> getLastSql();
      $num = $shNum[0]['num'];
      import("ORG.Util.Page1"); 
      $pageOb = new Page($page,$size,$num);//var_dump($pageOb);
      $start = $pageOb -> getStartStr(); 
      $shArr = $shOb -> query("select * from r_simhard    where {$where}  limit {$start},{$size}");        
      //var_dump($orderArr);
      //echo $shOb -> getLastSql();
      $pageStr = $pageOb -> getPageStr1(1);
      foreach($shArr as $k=>$v){
            $coOb = M("cardorder");
            $coArr = $coOb->where("CCID = '{$shArr[$k]['CCID']}' and status=2")->select();
            if(isset($coArr) && !empty($coArr)){
                foreach($coArr as $cov){
                    $shArr[$k]['leftOrder'] .= ",".$cov['title']; 
                }
                $shArr[$k]['leftOrder'] = substr($shArr[$k]['leftOrder'],1);
            }else{
                $shArr[$k]['leftOrder'] = "无";
            }
            $content=file_get_contents("http://api.heimiwifi.com/wifi/query/?tel=".$shArr[$k]['CCID']);
            ob_end_clean();
            $re=json_decode($content);
            $re1=get_object_vars($re);
            $data=get_object_vars($re1['data']);
            $shArr[$k]['left'] = $data['left'];
            //$_GET['left'] = 1; 
            if($_GET['left'] != ""){
                 if(strpos($shArr[$k]['left'],"KB") !== false){
                 
                       if($_GET['left']*1024 < $shArr[$k]['left']){
                            foreach($shArr[$k] as $k1=>$v1){ 
                                unset($shArr[$k][$k1]);
                            }
                       }
                 }elseif(strpos($shArr[$k]['left'],"G") !== false){
                      
                       if($_GET['left']/1024 < $shArr[$k]['left']){ 
                            foreach($shArr[$k] as $k1=>$v1){ 
                                unset($shArr[$k][$k1]);
                            }
                       } 
                 }else{
                       if($_GET['left'] < $shArr[$k]['left']){ 
                            foreach($shArr[$k] as $k1=>$v1){ 
                                unset($shArr[$k][$k1]);
                            }   
                       }
                 }
            }

      }//var_dump($corderArr);
      $arr=array(
         'page' => $pageStr,
         'data' => $shArr        
      );
      //echo "<pre>";
      //var_dump($arr);
      //echo "</pre>";
      $data=json_encode($arr);
      echo $data;

} 
*/
function  lister2(){

      $this->display();
}
public  function  getlist_flow(){
      //var_dump($_GET);
      $where = "where 1";
      $page = 1;
      $size = 15;
      if(!empty($_GET)){
            if($_GET['page'] != ""){
                  
                  $page = $_GET['page'];
            }
           if($_GET['start_time'] != "" && $_GET['start_time'] != "开始日期"){
      
                  $where .= " and f.ctime >= '".$_GET['start_time']."'"; 
            }
            if($_GET['end_time'] != "" && $_GET['end_time'] != "截止日期"){
                  
                  $where .= " and f.ctime <= '".$_GET['end_time']." 23:59:59'";
            }
            if($_GET['CCID'] != ""){
                
                  $where .= " and f.CCID= '".$_GET['CCID']."'";
              }
            if($_GET['flow'] != ""){
                
                  $where .= " and f.flow < ".$_GET['flow'];
            }
          
            if($_GET['size'] != ""){
                  
                  $size = $_GET['size'];
            }
      
      }
      $reOb=M("flow");//$reOb = new SimhardModel;
      
      $num=$reOb->query("select count(*) as num from r_flows f ".$where);
      //echo $reOb -> getLastSql();die;
      import("ORG.Util.Page1"); 
      $pageOb = new Page($page,$size,$num[0]['num']);
      //var_dump($pageOb);
      $start = $pageOb -> getStartStr(); 
      $reArr=$reOb->query("select f.id,f.ccid,f.flow,f.ctime  from r_flows f ".$where."  limit $start,$size");
      //echo $reOb -> getLastSql();die;
      $pageStr = $pageOb -> getPageStr1(1);
      //var_dump($pageStr);
      $arr=array(
         'page' => $pageStr,
         'data' => $reArr        
      );
      
      $data=json_encode($arr);
      echo $data;

}                        
public function  lister_flow(){

      $this->display();
}

function  import(){

      
     $dir=$_FILES['file-name'];
     $date = date("Y-m-d H:i:s");
     if($_FILES['file-name']['error'] == '0'){
           $type=substr($_FILES['file-name']['type'],-5);  //var_dump($type);die();
           if($type == 'xlsx' || $type=='/kset' || $type=='excel'  || $type=='sheet' ){  
                 if($_FILES['file-name']['size'] <= 2000000 ){ 
                       $filePath=$_FILES['file-name']['tmp_name']; 
                       //var_dump($filePath);die;
                       import("ORG.Util.MyExcel");
                       $excel = new MyExcel();//var_dump($excel);die();
                       $excel->loadExcel($filePath);
                       $columnTitle = $excel->findColumnTitle();
                       //var_dump($columnTitle);die;
                       $strTitle = md5(trim(implode(',', $columnTitle)));
                       //$standard=md5("电话号码,ICCID,IP,产品型号,生产日期,WiFi-SSID,WiFi密码,备注，NULL");
                       $standard=md5("上网号,配额大小,已用流量,剩余流量");
                       //var_dump($standard);die;
                       if ($standard != $strTitle) {
                             echo "<script language='javascript'>alert('请选择正确的模板！');window.history.back(-1);</script>";
                       }else{

                            $data = $excel->findAll();
                            //echo "<pre>";
                            //var_dump($data);die();
                            //echo "</pre>";
                           //echo "<pre>";var_dump($data);echo "</pre>";die();
                            $shOb=M("flows");
                            if (count($data)<=1) {
                                  echo "<script language='javascript'>alert('无数据！');window.history.back(-1);</script>";
                            }else{
                                  $dataStr = "";
                                  
                                  for($i=2;$i<=count($data);$i++){
                                       
                                        $dataStr.=",('".$data[$i]['A']."','".$data[$i]['B']."','".$data[$i]['C']."','".$data[$i]['D']."','".$date."')";
                                       
                                      }
                                  $dataStr1=substr($dataStr,1); 
				     // var_dump($dataStr1===false);die; 	
                                  if($dataStr1 === false){
                                         echo "<script language='javascript'>alert('数据为空或重复导入！');window.history.back(-1);</script>";
                                  }else{
                                         $reIn=$shOb->query("insert into r_flows (CCID,peie,used,flow,ctime) values".$dataStr1,array());
					     if($reIn!==false)
                                             echo "<script language='javascript'>alert('导入成功！');window.history.back(-1);</script>";
                                              }
				      }
   
                      }
                                          
                }else{
                      echo "<script language='javascript'>alert('文件过大！');window.history.back(-1);</script>";
                }
          }else{
                echo "<script language='javascript'>alert('文件类型不正确！');window.history.back(-1);</script>";
          }
    }else{
          echo "<script language='javascript'>alert('文件上传失败！');window.history.back(-1);</script>";
    }
}
function  import1(){

      
     $dir=$_FILES['file-name1'];
     $date = date("Y-m-d H:i:s");
     if($_FILES['file-name1']['error'] == '0'){
           $type=substr($_FILES['file-name1']['type'],-5);  //var_dump($type);die();
           if($type == 'xlsx' || $type=='/kset' || $type=='excel'  || $type=='sheet' ){  
                 if($_FILES['file-name1']['size'] <= 2000000 ){ 
                       $filePath=$_FILES['file-name1']['tmp_name']; 
                       //var_dump($filePath);die;
                       import("ORG.Util.MyExcel");
                       $excel = new MyExcel();//var_dump($excel);die();
                       $excel->loadExcel($filePath);
                       $columnTitle = $excel->findColumnTitle();
                       //var_dump($columnTitle);die;
                       $strTitle = md5(trim(implode(',', $columnTitle)));
                       //$standard=md5("电话号码,ICCID,IP,产品型号,生产日期,WiFi-SSID,WiFi密码,备注，NULL");
                       $standard=md5("上网号");
                       //var_dump($standard);die;
                       if ($standard != $strTitle) {
                             echo "<script language='javascript'>alert('请选择正确的模板！');window.history.back(-1);</script>";
                       }else{

                            $data = $excel->findAll();
                            //echo "<pre>";
                            //var_dump($data);die();
                            //echo "</pre>";
                           //echo "<pre>";var_dump($data);echo "</pre>";die();
                            $shOb=M("flows");
                            if (count($data)<=1) {
                                  echo "<script language='javascript'>alert('无数据！');window.history.back(-1);</script>";
                            }else{
                                  
                                  
                                  for($i=2;$i<=count($data);$i++){
                                       
                                        $reIn = $shOb -> query("update r_flows set flow=0 where  CCID=".$data[$i]['A']);
                                        
                                      }
                                  if($reIn!==false)
                                        echo "<script language='javascript'>alert('导入成功！');window.history.back(-1);</script>";
                                       }
                                  
					    
				    
                      }
                                          
                }else{
                      echo "<script language='javascript'>alert('文件过大！');window.history.back(-1);</script>";
                }
          }else{
                echo "<script language='javascript'>alert('文件类型不正确！');window.history.back(-1);</script>";
          }
    }else{
          echo "<script language='javascript'>alert('文件上传失败！');window.history.back(-1);</script>";
    }
}



}

