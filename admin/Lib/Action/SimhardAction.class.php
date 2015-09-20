<?php
class  SimhardAction  extends  Action{

public  function  getlist(){
      $where = "where 1";
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
            if($_GET['tel'] != ""){
                
                  $where .= " and tel = '{$_GET['tel']}'";
            }
            if($_GET['CCID'] != ""){
                
                  $where .= " and CCID = '{$_GET['CCID']}'";
            }
            if($_GET['size'] != ""){
                  
                  $size = $_GET['size'];
            }
      
      }
      $reOb=M();//$reOb = new SimhardModel;
      $num=$reOb->query("select count(*) as num from r_simhard join r_batch  b on simBatch=b.id ".$where);
      //var_dump($num);
      import("ORG.Util.Page1"); 
      $pageOb = new Page($page,$size,$num[0]['num']);
      //var_dump($pageOb);
      $start = $pageOb -> getStartStr(); 
      $reArr=$reOb->query("select s.id sid,CCID,IP,simBatch,createTime,b.ssid,b.password,other,status,s.deadTime,s.type from r_simhard  s  join r_batch  b on simBatch=b.id ".$where." limit $start,$size");//$reArr = $reOb -> getList($where,$start,$size);var_dump($reArr);
      $pageStr = $pageOb -> getPageStr1(1);
      //var_dump($pageStr);
      $arr=array(
         'page' => $pageStr,
         'data' => $reArr        
      );
      //var_dump($arr);die;
      $data=json_encode($arr);
      echo $data;

}                        

public  function  lister1(){

      $this->display();
} 

//导入
public  function  import(){

     $dir=$_FILES['file-name'];
     
     /*
     echo "<pre>";
     var_dump($dir);
     echo "</pre>";die();
     */
     if($_FILES['file-name']['error'] == '0'){
           $type=substr($_FILES['file-name']['type'],-5); // var_dump($type);die();
           if($type == 'xlsx' || $type='/kset' || $type='excel'  ){ 
                 if($_FILES['file-name']['size'] <= 2000000 ){
                       $filePath=$_FILES['file-name']['tmp_name']; 
                       //var_dump($filePath);
                       import("ORG.Util.MyExcel");
                       $excel = new MyExcel();//var_dump($excel);die();
                       $excel->loadExcel($filePath);
                       $columnTitle = $excel->findColumnTitle();
                       $strTitle = md5(trim(implode(',', $columnTitle)));
                       //$standard=md5("电话号码,ICCID,IP,产品型号,生产日期,WiFi-SSID,WiFi密码,备注");
                       $standard=md5("HeimiNum,IP,ICCID,PhoneNum,SSID,PASSWORD");
                       if ($standard != $strTitle) {
                             echo "<script language='javascript'>alert('请选择正确的模板！');window.history.back(-1);</script>";
                       }else{

                            $data = $excel->findAll();
                            //echo "<pre>";
                            //var_dump($data);die();
                            //echo "</pre>";
                            //echo "<pre>";var_dump($data);echo "</pre>";die();
                            if (count($data)<=1) {
                                  echo "<script language='javascript'>alert('无数据！');window.history.back(-1);</script>";
                            }else{
                                  $dataStr = "";
                                  $date = date("Y-m-d H:i:s");
                                  $arr = array("type"=>1,"createTime"=>$date,"ssid"=>$data[2]['E'],"password"=>$data[2]['F']);
                                  //$arr=array("type"=>$data[2]['D'],"createTime"=>$data[2]['E'],"ssid"=>$data[2]['F'],"password"=>$data[2]['G'],"other"=>$data[2]['H'] );
                                  $bOb=M("batch");
                                  $reB=$bOb->data($arr)->add();
                                  //echo $bOb->getLastSql();die();
                                  $simBatch=mysql_insert_id();
                                  for($i=2;$i<=count($data);$i++){
                                                 
                                        $dataStr.=",('".$data[$i]['A']."','".ip2long($data[$i]['B'])."','".$data[$i]['C']."','".$data[$i]['D']."','".$simBatch."',1)";
                                                
                                       }
                                  $dataStr1=substr($dataStr,1); //var_dump($dataStr1);die();    
                                  $shOb=M("simhard");
                                  $reIn=$shOb->query("insert into r_simhard (CCID,IP,ICCID,tel,simBatch,status) values".$dataStr1,array());
                                  if($reIn!==false && $reB!==false)
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
//导入需要修改的设备号

public  function  import1(){

     $dir=$_FILES['file-name1'];
     
     /*
     echo "<pre>";
     var_dump($dir);
     echo "</pre>";die();
     */
     if($_FILES['file-name1']['error'] == '0'){
           $type=substr($_FILES['file-name1']['type'],-5); // var_dump($type);die();
           if($type == 'xlsx' || $type='/kset' || $type='excel' || $type='sheet' ){ 
                 if($_FILES['file-name1']['size'] <= 2000000 ){
                       $filePath=$_FILES['file-name1']['tmp_name']; 
                       //var_dump($filePath);
                       import("ORG.Util.MyExcel");
                       $excel = new MyExcel();//var_dump($excel);die();
                       $excel->loadExcel($filePath);
                       $columnTitle = $excel->findColumnTitle();
                       $strTitle = md5(trim(implode(',', $columnTitle)));
                       //$standard=md5("电话号码,ICCID,IP,产品型号,生产日期,WiFi-SSID,WiFi密码,备注");
                       $standard=md5("设备号,卡状态,配额类型,截止日期");
                       if ($standard != $strTitle) {
                             echo "<script language='javascript'>alert('请选择正确的模板！');window.history.back(-1);</script>";
                       }else{

                            $data = $excel->findAll();
                            
                            //var_dump($data);exit();
                            //echo "<pre>";
                            //var_dump($data);die();
                            //echo "</pre>";
                            //echo "<pre>";var_dump($data);echo "</pre>";die();
                            if (count($data)<=1) {
                                  echo "<script language='javascript'>alert('无数据！');window.history.back(-1);</script>";
                            }else{
                                  $dataStr = "";
                                  
                                  for($i=2;$i<=count($data);$i++){
                                           
                                        //$dataStr.=",('".$data[$i]['A']."','".ip2long($data[$i]['B'])."','".$data[$i]['C']."','".$data[$i]['D']."','".$simBatch."',1)";
                                        $shOb=M("simhard");
                                        $type = stripcslashes($data[$i]['C']);
                                        $deadTime = excelTime($data[$i]['D']);  //将时间日期字段做一下格式转换
                                        //$deadTime = stripcslashes('2014-12-31');
                                        $status = stripcslashes($data[$i]['B']);
                                        if(stripcslashes($status) == ""){
                                              $status=1;  
                                              }
                                        $CCID = $data[$i]['A'];
                                        $date = date("Y-m-d H:i:s");
                                        $reIn=$shOb->query("update r_simhard set deadTime='{$deadTime}',type='{$type}',status='{$status}',updated_at='{$date}'  where CCID='{$CCID}'");
                                       }
                              //$dataStr1=substr($dataStr,1); //var_dump($dataStr1);die();    
                              
                              if($reIn!==false )
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
