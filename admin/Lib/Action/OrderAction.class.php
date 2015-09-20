<?php
header("Content-type:text/html;charset=utf-8");
class OrderAction extends  Action{
    /*
       function  lister(){
       $where = "1";

       if($_GET['start_time']!=""){

       $where.= " and createTime >= '".$_GET['start_time']."'"; 
       }
       if($_GET['end_time']!=""){

       $where.= " and createTime <= '".$_GET['end_time']." 23:59:59'";
       }
       if($_GET['code']!=""){

       $where.= " and code = '".$_GET['code']."'";
       }
       if($_GET['name']!=""){

       $where.= " and name = '".$_GET['name']."'";
       }
       if($_GET['status']!=""){

       $where.= " and status = '".$_GET['status']."'";
       }

       $orderOb = new OrderModel;
       $num = $orderOb->getCount($where);
       import("ORG.Util.Page");
       $pageOb = new Page(10,$num);
       $start = $pageOb -> getStartStr();
       $pageStr = $pageOb->getPageStr1(1);
       $orderArr = $orderOb->getList($where,$start);//echo getLastSql();
       $this -> assign("pageStr",$pageStr);
       $this -> assign("orderArr",$orderArr);
       $this -> assign("code",$_GET['code']);
       $this -> assign("name",$_GET['name']);
       $this -> assign("sta",$_GET['status']);
       $this -> assign("start_time",$_GET['start_time']);
       $this -> assign("end_time",$_GET['end_time']);

       $this -> display();
       }
     */
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

                $where .= " and title like '%".$_GET['title']."%'";
            }
            if($_GET['name'] != ""){

                $where .= " and name like '%".$_GET['name']."%'";
            }
            if($_GET['orderId'] != ""){

                $where .= " and orderId like  '%".$_GET['orderId']."%'";
            }
            if($_GET['tel'] != ""){

                $where .= " and tel like '%".$_GET['tel']."%'";
            }
            if($_GET['yhq'] != ""){
                $yOb = M("yhq");
                $yArr = $yOb -> where("code='{$_GET['yhq']}'")->select();
                $where .= " and yhqId  = ".$yArr[0]['id'];
            }
            if($_GET['sta'] != ""){

                $where .= " and status = '".$_GET['sta']."'";
            }
            if($_GET['size'] != ""){

                $size = $_GET['size'];
            }

        }
        $orderOb = new OrderModel;
        $num = $orderOb->getCount($where);
        import("ORG.Util.Page1"); 
        $pageOb = new Page($page,$size,$num);//var_dump($pageOb);
        $start = $pageOb -> getStartStr(); 
        $orderArr = $orderOb -> getList1($where,$start,$size);//var_dump($orderArr);echo $orderOb->getLastSql();
        $pageStr = $pageOb -> getPageStr1(1);
        $arr=array(
                'page' => $pageStr,
                'data' => $orderArr        
                );
        $data=json_encode($arr);
        echo $data;
        exit();
    }                        

    public  function  lister1(){

        $this->display();
    }                

    public  function  getlist2(){

        $where="status='3'"; 
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

                $where .= " and title like '%".$_GET['title']."%'";
            }
            if($_GET['name'] != ""){

                $where .= " and name like '%".$_GET['name']."%'";
            } 
            if($_GET['CCID'] != ""){

                $where .= " and CCID = '".$_GET['CCID']."'";
            } 
            if($_GET['orderId'] != ""){

                $where .= " and orderId like '%".$_GET['orderId']."%'";
            } 
            if($_GET['tel'] != ""){

                $where .= " and tel like '%".$_GET['tel']."%'";
            }
            if($_GET['sta'] != ""){

                $where .= " and status = '".$_GET['sta']."'";
            }
            if($_GET['size'] != ""){

                $size = $_GET['size'];
            }
        }      
        $orderOb = new OrderModel;
        $num = $orderOb->getCount($where);
        import("ORG.Util.Page1"); 
        $pageOb = new Page($page,$size,$num);//var_dump($pageOb);
        $start = $pageOb -> getStartStr(); 
        $orderArr = $orderOb -> getList1($where,$start,$size);//var_dump($orderArr);//echo getLastSql();
        $pageStr = $pageOb -> getPageStr1(1);
        $arr=array(
                'page' => $pageStr,
                'data' => $orderArr        
                );//var_dump($data);
        $data=json_encode($arr);
        echo $data;
        exit();
    }

    public  function  lister2(){

        $this->display();
    }
    public  function  getlist3(){

        $where="status='1'"; 
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

                $where .= " and title like '%".$_GET['title']."%'";
            }
            if($_GET['name'] != ""){

                $where .= " and name like '%".$_GET['name']."%'";
            }
            if($_GET['orderId'] != ""){

                $where .= " and orderId like '%".$_GET['orderId']."%'";
            }
            if($_GET['tel'] != ""){

                $where .= " and tel like '%".$_GET['tel']."%'";
            }
            if($_GET['sta'] != ""){

                $where .= " and status = '".$_GET['sta']."'";
            }
            if($_GET['size'] != ""){

                $size = $_GET['size'];
            }
        }      
        $orderOb = new OrderModel;
        $num = $orderOb->getCount($where);
        import("ORG.Util.Page1"); 
        $pageOb = new Page($page,$size,$num);//var_dump($pageOb);
        $start = $pageOb -> getStartStr(); 
        $orderArr = $orderOb -> getList1($where,$start,$size);//var_dump($orderArr);//echo getLastSql();
        $pageStr = $pageOb -> getPageStr1(1);
        $arr=array(
                'page' => $pageStr,
                'data' => $orderArr        
                );//var_dump($data);
        $data=json_encode($arr);
        echo $data;
        exit();
    }

    public  function  lister3(){

        $this->display();
    }
    public  function  getlist4(){

        $where=" 1 "; 
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

                $where .= " and title like '%".$_GET['title']."%'";
            }
            if($_GET['name'] != ""){

                $where .= " and name like '%".$_GET['name']."%'";
            }
            if($_GET['tel'] != ""){

                $where .= " and tel like '%".$_GET['tel']."%'";
            }
            if($_GET['orderId'] != ""){

                $where .= " and orderId like '%".$_GET['orderId']."%'";
            }
            if($_GET['yhq'] != ""){
                $yOb = M("yhq");
                $yArr=$yOb -> where("code='{$_GET['yhq']}'")->select();
                if(isset( $yArr) && !empty( $yArr)){
                    $yhqId = $yArr[0]['id'];

                }else{
                    $yhqId == 0;

                }

                $where .= " and yhqId = {$yhqId}";
            }
            if($_GET['sta'] != ""){

                $where .= " and status = '".$_GET['sta']."'";
            }
            if($_GET['size'] != ""){

                $size = $_GET['size'];
            }
        }      
        $orderOb = new OrderModel;
        $num = $orderOb->getCount($where);
        import("ORG.Util.Page1"); 
        $pageOb = new Page($page,$size,$num);//var_dump($pageOb);
        $start = $pageOb -> getStartStr(); 
        $orderArr = $orderOb -> getList1($where,$start,$size);//var_dump($orderArr);//echo getLastSql();
        foreach($orderArr as $k=>$v){
            $yOb = M("yhq");
            $yArr=$yOb -> where("id='{$v['yhqId']}'")->select();
            if(isset($yArr) && !empty($yArr)){
                $orderArr[$k]['yhqCode'] = $yArr[0]['code'];
            }else{
                $orderArr[$k]['yhqCode'] = 0;   
            }
            $orderArr[$k]['price'] = $orderArr[$k]['price'] - $orderArr[$k]['discount'] - $orderArr[$k]['yhq']; 
            if($orderArr[$k]['price'] <0 ){
                $orderArr[$k]['price'] = 0;
            }
        } 
        $pageStr = $pageOb -> getPageStr1(1);
        $arr=array(
                'page' => $pageStr,
                'data' => $orderArr        
                );//var_dump($data);
        $data=json_encode($arr);
        echo $data;
        exit();
    }

    public  function  lister4(){

        $this->display();
    }
    public  function  getlist5(){

        $where=" other!=''"; 
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

                $where .= " and title like '%".$_GET['title']."%'";
            }
            if($_GET['name'] != ""){

                $where .= " and name like '%".$_GET['name']."%'";
            }
            if($_GET['tel'] != ""){

                $where .= " and tel like '%".$_GET['tel']."%'";
            }
            if($_GET['orderId'] != ""){

                $where .= " and  orderId like '%".$_GET['orderId']."%'";
            }
            if($_GET['sta'] != ""){

                $where .= " and status = '".$_GET['sta']."'";
            }
            if($_GET['size'] != ""){

                $size = $_GET['size'];
            }
        }      
        $orderOb = new OrderModel;
        $num = $orderOb->getCount($where);
        import("ORG.Util.Page1"); 
        $pageOb = new Page($page,$size,$num);//var_dump($pageOb);
        $start = $pageOb -> getStartStr(); 
        $orderArr = $orderOb -> getList1($where,$start,$size);//var_dump($orderArr);//echo getLastSql();
        $pageStr = $pageOb -> getPageStr1(1);
        $arr=array(
                'page' => $pageStr,
                'data' => $orderArr        
                );//var_dump($data);
        $data=json_encode($arr);
        echo $data;
        exit();
    }

    public  function  lister5(){

        $this->display();
    }



    public  function  getSinggleOrder(){

        $id=$_GET['id'];
        if(isset($id) && $id!=""){

            $oOb=M("order");
            $arr=$oOb->where("id=$id")->select();
            if(isset($arr) && !empty($arr)){

                $data=json_encode($arr);
                echo $data;
                exit();
            }
        }
    }
    function  isHard(){

        $CCID = $_GET['CCID'];
        $shOb = M("simhard");
        $arr = $shOb->where("CCID='$CCID' and status='1'")->select();
        //$oOb = M("order");
        //$oArr = $oOb -> where("CCID='$CCID'") -> select();  
        if(!isset($arr) || empty($arr)){
            echo "0";
        }else{ 

            echo "1";    
            exit();  
        }
    }

    function postMethod($url, $post = null)
    {
        $context = array();

        if (is_array($post)){

            $context['http'] = array (

                    'method' => 'POST',
                    'content' => http_build_query($post, '', '&'),
                    );
        }

        return file_get_contents($url, false, stream_context_create($context));
    }
    function  okSave(){
        $id=$_GET['id']; //$id="64";
        $transfer=$_GET["transfer"];//$transfer="ssss";
        if($transfer == "1"){
            $transfer = "圆通快递";
        }elseif($transfer == "2"){
            $transfer = "申通快递";
        }
        $transferCode=$_GET["transferCode"];//$transferCode="678";
        $CCID=$_GET["CCID"];//$CCID="heimi0005";
        //$pArr=unserialize($_COOKIE['pArr']);
        //$pid=$pArr['pid'];
        if(isset($transfer) && !empty($transfer) && isset($transferCode) && !empty($transferCode) ) {
            $date=date("Y-m-d H:i:s");
            $oOb=M("order");
            $pArr = $oOb -> where("id=$id")->select();       
            $pid = $pArr[0]['pid'];
            //将设备状态更改为已发货
            $shOb = M("simhard");
            //判断设备里包含的是什么类型的套餐，确定截止日期存进simhard表里
            $arrM = array("1"=>"31","2"=>"28","3"=>"31","4"=>"30","5"=>"31","6"=>"30","7"=>"31","8"=>"31","9"=>"30","10"=>"31","11"=>"30","12"=>"31"); 
            $y = date("Y");
            $m = date("n");
            $pOb = M("product");
            $proArr = $pOb -> where("id=$pid") ->select();
            if($proArr[0]['type'] == "1"){

                $deadTime = $y."-".$m."-".$arrM[$m];

            }elseif($proArr[0]['type'] == "2"){
                $y = $y + 1;
                $m = $m -1 ;
                $deadTime = $y."-".$m."-".$arrM[$m]; 
            }elseif($proArr[0]['type'] == "3"){
                $deadTime = $y."-12-31";
            }

            $shOb -> query("update r_simhard set status=2,deadTime='{$deadTime}',type={$proArr[0]['type']},updated_at='{$date}'  where  CCID='{$CCID}'"); 
            //echo $shOb -> getLastSql();
            $arr=array("id"=>$id,"pid"=>$pid,"transfer"=>$transfer,"transferCode"=>$transferCode,"CCID"=>$CCID,"status"=>"3",'updated_at'=>$date);
            $re=$oOb->data($arr)->save(); //var_dump($re);echo $oOb->getLastSql();
            if($re!==false){
                $oArr = $oOb -> where("id=$id") -> select();
                $tel = $oArr[0]['tel'] ; 
                $orderId = $oArr[0]['orderId'] ;
                /*
                $content = "您好，您购买的商品已从北京发货，圆通速递单号".$transferCode."，请注意查收。如有问题请联系客服，感谢您的支持！"; 
                $content=iconv("UTF-8","GBK",$content);
                $userData = array(
                        'corp_id'=> "2e5c001",
                        'corp_pwd'=> "tjhm018",
                        'corp_service'=> "1065505yd",
                        'mobile' => $tel,
                        'msg_content' => $content,
                        );
                $ReturnData = $this -> postMethod("http://service2.baiwutong.com:8080/sms_send2.do",$userData);
                //echo $ReturnData;
                */
                $sms = new SmsModel();
                $ReturnData = $sms -> sendOutGoods($tel,$transferCode,$oArr[0]['title'],$transfer);
                //var_dump($ReturnData);die;
                if($ReturnData == "1"){

                    echo "1";
                }else{
                    echo "2";
                } 

                exit();
            }else{
                echo "0";
                exit();
            } 
        }

    }

    //保存备注信息
    function  otherSave(){

        $other = $_GET['other'];	
        $id = $_GET['id'];
        $type = $_GET['type'];
        $oOb = M("order");
        if($type==1){
            $arr = array("id"=>$id,"name"=>$other);
        }elseif($type==2){
            $arr = array("id"=>$id,"tel"=>$other);
        }elseif($type==3){
            $arr = array("id"=>$id,"other"=>$other);
        }
        $re = $oOb -> data($arr) -> save();
        //echo $oOb -> getLastSql();
        if($re!==false){
            echo "1";
            exit();
        }    
    }
    //绑定设备与用户帐号
    function bdshebei(){

        $id=$_GET['id']; 
        $uid=$_GET['uid'];
        $mOb= new MemberModel();
        $arr=array("CCID"=>$id,"id"=>$uid);  
        $re=$mOb->updMember($arr);
        if($re!==false){
            echo "1";
            exit();
        }   
    }
    //设置订单为已发货
    function  setyifahuo(){

        $str=$_GET['str'];
        $arr=explode(",",$str);
        if($arr!=null){
            $oOb=new OrderModel();
            $re=$oOb->setStatus($arr,"3","2");
            //var_dump($re);
            if($re!==null){
                echo "1";
                exit();
            }
        }
    }

    //根据地址得到地址的字符串
    function  selAddress(){

        $id=$_GET['addrId']; 
        if(isset($id) && $id!=""){  

            $ob=M("address");
            $arr=$ob->where("id=$id")->select();
            if($arr!=null){ 
                $pOb=M("province");
                $province=$pOb->where("provinceId=".$arr[0]['province'])->select();
                $cOb=M("city");
                $city=$cOb->where("cityId=".$arr[0]['city'])->select();
                $aOb=M("area");
                $area=$aOb->where("areaId=".$arr[0]['area'])->select();
                $addr=$province[0]['province'].$city[0]['city'].$area[0]['area'].$arr[0]['detail'];
                echo $addr; 
                exit(); 
            }
        }
    }
    function  selMailcode(){

        $id=$_GET['addrId']; 
        if(isset($id) && $id!=""){  

            $ob=M("address");
            $arr=$ob->where("id=$id")->select();
            if($arr!=null){ 

                echo $arr[0]['mailCode']; 
                exit(); 
            }
        }
    }
    //将快递名称写进数据库
    function  transferSave(){
        $id=$_GET['id'];//$id=4;
        $val=$_GET['val'];//$val="shun";
        $orderOb = new OrderModel();//var_dump($orderOb); 
        $arr=array("id"=>$id,"transfer"=>$val);
        $re=$orderOb->updOrder($arr); //var_dump($re);
        if($re!==false){
            echo "1";
            exit();
        }
    }
    function  transferPaySave(){
        $id=$_GET['id'];//$id=4;
        $val=$_GET['val'];//$val="shun";
        $orderOb = new OrderModel();//var_dump($orderOb); 
        $arr=array("id"=>$id,"transferPay"=>$val);
        $re=$orderOb->updOrder($arr); //var_dump($re);
        if($re!==false){
            echo "1";
            exit();
        }
    }

    //设置为已过期状态
    function  setTimeout(){

        $str=$_GET['str'];
        $arr=explode(",",$str);
        if($arr!=null){

            $date=date("Y-m-d H:i:s");
            $oOb=M("order");

            foreach($arr as $v){
                $re1=$oOb->query("update r_order set status=4,updated_at='{$date}'  where id={$v}");           
                //echo $oOb->getLastSql();
                $oArr=$oOb->where("id={$v}")->select();
                $yhqId=$oArr[0]['yhqId'];
                if($yhqId!=0){
                    $yOb=M("yhq");
                    $yOb->query("update r_yhq set status=1 where id={$yhqId}");
                }
            } //var_dump($re1);   
            if($re1!==null){
                echo "1";
                exit();
            }
        }
    }
    //导出功能
    public  function  export(){

        //$_GET['price']=1;
        //var_dump($_GET);
        $where=" 1";
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
            if($_GET['name'] != ""){

                $where .= " and name like '%". $_GET['name']."%'";
            }
            if($_GET['tel'] != ""){

                $where .= " and tel like '%". $_GET['tel']."%'";
            }
            if($_GET['orderId'] != ""){

                $where .= " and orderId like '%". $_GET['orderId']."%'";
            }
            if($_GET['yhq'] != ""){
                $yOb = M("yhq");
                $yArr=$yOb -> where("code='{$_GET['yhq']}'")->select();
                if(isset($yArr) && !empty($yArr)){
                    $yhqId = $yArr[0]['id'];
                }else{
                    $yhqId == 0;
                }

                $where .= " and yhqId = {$yhqId}";
            }

            if($_GET['status'] != ""){

                $where .= " and status =". $_GET['status'];
            }

        }

        $yOb=M("Order");
        $data=$yOb->field("id,orderId,name,tel,address_id,title,price,isDiscount,discount,yhq,yhqId,payType,status,CCID,transfer,transferCode,time,updated_at")->where($where)->order("id desc")->select();       
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
                $data[$k]['status'] = "已付款";
            }elseif($dv['status']==3){
                $data[$k]['status'] = "已发货";
            }elseif($dv['status']==4){
                $data[$k]['status'] = "已过期";
            }elseif($dv['status']==5){
                $data[$k]['status'] = "已关闭";
            }elseif($dv['status']==6){
                $data[$k]['status'] = "已取消";
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
            $data[$k]['price'] = $data[$k]['price'] - $data[$k]['discount'] - $data[$k]['yhq'];
           
            //将地址信息由码创成文字
            $addArr = $addOb -> where("id={$dv['address_id']}") -> select();
            // echo $addOb -> getLastSql();
            //var_dump($addArr);
            if($addArr!=null){ 
                $pOb = M("province");
                $province = $pOb->where("provinceId=".$addArr[0]['province'])->select();
                $cOb = M("city");
                $city = $cOb->where("cityId=".$addArr[0]['city'])->select();
                $aOb = M("area");
                $area = $aOb->where("areaId=".$addArr[0]['area'])->select();
                $addr = $province[0]['province'].$city[0]['city'].$area[0]['area'].$addArr[0]['detail'];
                $data[$k]['address_id'] = $addr;
                //	echo $addr;  
                $data[$k]['mailCode'] = $addArr[0]['mailCode'];
            }
            // var_dump($data);
        } 
        import("ORG.Util.MyExcel");	    
        $ob=new MyExcel();
        ob_end_clean(); 
        $ob->setTitle(array('ID','订单号', '用户名', '用户电话','收货地址','商品名称','交易价格','是否打折','折扣价格','优惠券金额','优惠券码','支付方式','订单状态','卡号','快递名称','快递编号','生成时间','修改时间','邮编'))->setFileName("订单")->dump($data, 2007);


    }   
    function  cancelCauseSave(){
        $id = $_GET['id'];
        $cause =  $_GET['cause'];
        $oOb = M("order");
        $date=date("Y-m-d H:i:s");
        $arr = array("id"=>$id,"cancelCause"=>$cause,"status"=>'6','updated_at'=>$date);
        $re = $oOb -> data($arr) ->save();
        if($re){
            echo "1";
            exit();
        }

    }
    function  getYhqCode(){
        $yhqId = $_GET['yhqId'];
        $yOb = M("yhq");
        $arr = $yOb -> where("id=$yhqId")->select();
        if(isset($arr) && !empty($arr)){
            echo $arr[0]['code'];
            exit();
        }else{
            echo "0";
            exit();
        }
    }
    function  issetTransferCode(){
        /*        $transferCode = $_GET['transferCode'];
                  $oOb = M("order");
                  $oArr = $oOb -> where("transferCode = '{$transferCode}'") ->select();
                  if(isset($oArr) && !empty($oArr)){

                  echo "0";
                  }
         */
    }
}
