<?php
class  CardorderAction  extends  Action{

public  function  getlist(){
      $where = "status=2";
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
            if($_GET['title'] != ""){
                
                  $where .= " and title = '".$_GET['title']."'";
            }
            if($_GET['size'] != ""){
                  
                  $size = $_GET['size'];
            }
      
      }
   
      $corderOb = new CardorderModel;
      $num = $corderOb->getCount($where);
      import("ORG.Util.Page1"); 
      $pageOb = new Page($page,$size,$num);//var_dump($pageOb);
      $start = $pageOb -> getStartStr(); 
      $corderArr = $corderOb -> getList($where,$start,$size);//var_dump($orderArr);echo $corderOb->getLastSql();
      $pageStr = $pageOb -> getPageStr1(1);
      foreach($corderArr as $k=>$v){
            $cOb = M("card");
            $cArr = $cOb->where("id={$corderArr[$k]['pid']}")->select();
            $corderArr[$k]['beginTime'] = $cArr[0]['beginTime'];
            $corderArr[$k]['endTime'] = $cArr[0]['endTime'];
            $shOb = M("simhard");
            $shArr = $shOb->where("CCID='{$corderArr[$k]['CCID']}'")->select();//var_dump($shArr);
           // echo $shOb -> getLastSql();
            $corderArr[$k]['type'] = $shArr[0]['type'];
            //var_dump($shArr[0]['type']);
            $yOb = M("yhq");
            $yArr = $yOb -> where("id={$corderArr[$k]['yhqId']}") ->select();
            if(isset($yArr) && !empty($yArr)){
                $corderArr[$k]['yhqCode'] = $yArr[0]['code'];
            }else{
                $corderArr[$k]['yhqCode'] = 0;
            }
            
      }
      //var_dump($corderArr);
      $arr=array(
         'page' => $pageStr,
         'data' => $corderArr        
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
                  $coArr = $coOb -> where("id={$v}") -> select();
                  $CCID = $coArr[0]['CCID'];
                  $pid = $coArr[0]['pid'];
                  $cOb = M("card");
                  $cArr = $cOb -> where("id={$pid}") ->select();
                  $type = $cArr[0]['type'];
                  $shOb = M("simhard");
                  $shArr = $shOb -> where("CCID={$CCID}") -> select();
                  $shType = $shArr[0]['type'];
                  $arrM = array("1"=>"31","2"=>"28","3"=>"31","4"=>"30","5"=>"31","6"=>"30","7"=>"31","8"=>"31","9"=>"30","10"=>"31","11"=>"30","12"=>"31"); 
                  $y = date("Y");
                  $y1 = $y + 1; 
                  $m = date("n");
                  $m1 = $m-1;
                  $deadTime = $y1."-".$m1."-".$arrM[$m1];  //次年相同时间
                  $monthEnd = $y."-".$m."-".$arrM[$m];//月末
                  //if($type == 3){
                        $shOb -> query("update r_simhard  set type=1,deadTime='".$cArr[0]['endTime']."' ,updated_at='{$date}' where  CCID='{$CCID}'");
                   //echo $shOb -> getLastSql(); 
                  //}
                  /*
                  if($type == "2"){
                       $shOb -> query("update r_simhard  set type=2,deadTime='{$deadTime}' where CCID='{$CCID}'");
                  }elseif($type=="1" and $shType=="2"){
                       $deadTime1 = $y."-"."$m"."-".$arr[$m];
                       $now = date("Y-m-d");
                       //得到剩余流量
                       $content = file_get_contents("http://api.heimiwifi.com/wifi/query/?tel=".$CCID);
                       ob_end_clean();
                       $re=json_decode($content);
                       $re1=get_object_vars($re);
                       $data=get_object_vars($re1['data']);
                       //var_dump($data);
                       $left = $data['left'];

                       if($shArr[0]['deadTime'] < $now or ($shArr[0]['deadTime'] == $monthEnd and $left=0)){
                           $shOb -> query("update r_simhard  set type=1,deadTime='{$monthEnd}' where  CCID='{$CCID}'");
                       }      
                  }elseif($type=="1" and $shType=="1"){
                      $shOb -> query("update r_simhard  set type=1,deadTime='{$monthEnd}' where CCID='{$CCID}'");   
                  }elseif($type=="1" and $shType=="3"){
                      $shOb -> query("update r_simhard  set type=1,deadTime='{$monthEnd}' where CCID='{$CCID}'");
                  }
                  */
            }      
            if($re!==null){
                  echo "1";
            }
      }
}

public  function  getlist2(){
      $where = "status=3";
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
            if($_GET['title'] != ""){
                
                  $where .= " and title = '".$_GET['title']."'";
            }
            if($_GET['CCID'] != ""){
                
                  $where .= " and CCID = '".$_GET['CCID']."'";
            }
            if($_GET['size'] != ""){
                  
                  $size = $_GET['size'];
            }
      
      }
   
      $corderOb = new CardorderModel;
      $num = $corderOb->getCount($where);
      import("ORG.Util.Page1"); 
      $pageOb = new Page($page,$size,$num);//var_dump($pageOb);
      $start = $pageOb -> getStartStr(); 
      $corderArr = $corderOb -> getList1($where,$start,$size);//var_dump($orderArr);//echo getLastSql();
      $pageStr = $pageOb -> getPageStr1(1);
      foreach($corderArr as $k=>$v){
            $cOb = M("card");
            $cArr = $cOb->where("id={$corderArr[$k]['pid']}")->select();
            $corderArr[$k]['beginTime'] = $cArr[0]['beginTime'];
            $corderArr[$k]['endTime'] = $cArr[0]['endTime'];
            $shOb=M("simhard");
            $shArr=$shOb->where("CCID='{$corderArr[$k]['CCID']}'")->select();//var_dump($shArr);
            $corderArr[$k]['type'] = $shArr[0]['type'];
            //var_dump($shArr[0]['type']);
            $yOb = M("yhq");
            $yArr = $yOb -> where("id={$corderArr[$k]['yhqId']}") ->select();
            if(isset($yArr) && !empty($yArr)){
                $corderArr[$k]['yhqCode'] = $yArr[0]['code'];
            }else{
                $corderArr[$k]['yhqCode'] = 0;
            }
      }//var_dump($corderArr);
      $arr=array(
         'page' => $pageStr,
         'data' => $corderArr        
      );//var_dump($data);
      $data=json_encode($arr);
      echo $data;

} 
function  lister2(){

      $this->display();
}
    //导出功能
    public  function  export(){

        $where=" status=3";
        if(!empty($_GET)){

            if($_GET['start_time'] != "" && $_GET['start_time'] != "开始日期"){

                $where .= " and updated_at >= '".$_GET['start_time']."'"; 
            }
            if($_GET['end_time'] != "" && $_GET['end_time'] != "截止日期"){

                $where .= " and updated_at <= '".$_GET['end_time']." 23:59:59'";
            }
            if($_GET['title'] != ""){

                $where .= " and title like '%".$_GET['title']."%'";
            }
            if($_GET['CCID'] != ""){

                $where .= " and CCID like '%". $_GET['CCID']."%'";
            }

        }

        $yOb=M("cardorder");
        $data=$yOb->field("id,orderId,uid,pid,title,price,isDiscount,discount,yhqPrice,yhqId,payType,status,CCID,beginTime,endTime,time,updateTime,num")->where($where)->order("id desc")->select();       
        //echo "<pre>";var_dump($data); echo "</pre>";
        //echo $yOb->getLastSql();die();	
        $addOb=M("address");
            foreach($data as $k=>$dv){
            //通过优惠券id得到优惠券码
            $yOb = M("yhq");
            $yId = $dv['yhqId'];
            $yArr=$yOb -> where("id={$yId}")->select();
            $data[$k]['yhqId'] = $yArr[0]['code'];
            //将状态翻译成文字
            if($dv['status']==1){
                $data[$k]['status'] = "未付款";
            }elseif($dv['status']==2){
                $data[$k]['status'] = "待充值";
            }elseif($dv['status']==3){
                $data[$k]['status'] = "已充值";
            }
            //将支付方式翻译成文字
            if($dv['payType']==1){
                $data[$k]['payType'] = "支付宝";
            }elseif($dv['payType']==2){
                $data[$k]['payType'] = "财付通";
            } 

            //将是否打折翻译成文字
            if($dv['isDiscount']==1){
                $data[$k]['isDiscount'] = "打折";
            }elseif($dv['isDiscount']==0){
                $data[$k]['isDiscount'] = "不打折";
            }
            //将订单id加上单引号，以免excel由于用科学计数法将后面改成0
            $data[$k]['orderId'] = "'".$data[$k]['orderId']."'";

            //计算出交易价格
            $data[$k]['price'] = $data[$k]['price'] - $data[$k]['discount'] - $data[$k]['yhqPrice'];
           
        } 
        import("ORG.Util.MyExcel");	    
        $ob=new MyExcel();
        ob_end_clean(); 
        $ob->setTitle(array('ID','订单号', '用户ID', '商品ID','商品名称','交易价格','是否打折','折扣价格','优惠券金额','优惠券码','支付方式','订单状态','卡号','套餐开始时间','套餐截止时间','订单生成时间','订单修改时间','购买数量'))->setFileName("订单")->dump($data, 2007);


    }   

}

