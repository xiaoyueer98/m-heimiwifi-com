<?php
header("content-type:text/html;charset=utf-8");

//读取数据库获取产品分类拼接字符串
function   getCatList(){

              $pTypeOb=M("producttype");
			  $arr=$pTypeOb->where("fid=0")->select();
			  foreach($arr as $v){
			             // $fstr=">".$v['id'];	
			              $typeStr.="<h2> <a target=\"_blank\" href=\"/danei/thinkphp/index.php/product/lister?typeid=>{$v['id']}\"> {$v['name']}</a></h2>";						  
			   			  $sArr=$pTypeOb->where("fid={$v['id']}")->select();
						  foreach($sArr as $sv){
						            $typeStr.="<div class=\"h2_cat\" onmouseover=\"this.className='h2_cat active_cat'\" onmouseout=\"this.className='h2_cat'\"><h3><div class=\"xianzhi\"><a target=\"_blank\" href=\"/danei/thinkphp/index.php/product/lister?typeid=>{$v['id']}>{$sv['id']}>\">{$sv['name']}</a> <span class=\"des\">- </span>
									</div>
									</h3>
									<div class=\"h3_cat\">
									<div class=\"shadow\">
									<div class=\"shadow_border\">
									<ul>";					  
						 // $newFstr=$fstr.">".$sv['id'];	
						  $tArr=$pTypeOb->where("fid={$sv['id']}")->select();
                          foreach($tArr as $tv){
						            $typeStr.="
									        <li><a target=\"_blank\" href=\"/danei/thinkphp/index.php/product/lister?typeid=>{$v['id']}>{$sv['id']}>{$tv['id']}>\">{$tv['name']}</a></li>";	
						  }
                          $typeStr.="				
									</ul></div></div></div></div>";					
						}
			  }
			  
			  
			   return  $typeStr;
}

 //得到头部下方的商品分类名称字符串
function  typeStr($typeid){

        	 if(empty($typeid)){
						   $typeStr="&nbsp;&nbsp;所有产品";
			 }else{   
						   $tidArr=explode(">",$typeid);
						   for($key=1;$key<count($tidArr)-1;$key++){
								   $ptOb=M("producttype");
								   $typeArr=$ptOb->where("id={$tidArr[$key]}")->select();//echo $ptOb->getLastSql();
								   $typeidStr.=">".$tidArr[$key].">";
								   $typeStr.="&nbsp;&nbsp;>&nbsp;&nbsp;<a target=\"_blank\" href=\"?typeid={$typeidStr}\">".$typeArr[0]['name']."</a>";
						   }
			 }

             return  $typeStr;

}
//生成缓存文件函数
function   typeList($cacheDir){
             if(is_file($cacheDir)){//缓存文件存在
			               //判断是否过期
						   $dir=fopen($cacheDir,"r+");
						   $birthTime=fgets($dir);//读取完后指针停留在第二行开头
						   $now=time();
						   if($now-$birthTime>30*24*3600){
						            rewind($dir); //让指针回到头部
									$content=getCatList();
				                    $content=$birthTime."
				                    ".$content; 
									$re=fwrite($dir,$content);
				                    fclose($dir);
									return str_replace("birthTime","",$content);
									    
						   }else{  
						            $content=fread($dir,filesize($cacheDir));
									fclose($dir);
									return $content;
						   }
						   
			 }else{//缓存文件不存在
			       
				   //生成缓存文件
			       $content=getCatList();//调用函数得到分类字符串
				   $birthTime=time();//获取缓存文件的生成时间
				   $content=$birthTime."
				   ".$content;  //”\r“是文本的换行符，拼接成字符串
				   $dir=fopen($cacheDir,"w");
				   $re=fwrite($dir,$content);//成功返回写入字符数，失败返回false
				   fclose($dir);
				   return  str_replace("$birthTime","",$content);
			 }
}


//支付宝相关

function checkorderstatus($orderId){
    $oOb=M("order");
    $cOb=M('cardorder');
    $orderArr=$oOb->field("count(*) as num")->where("orderId='{$orderId}'")->select();
    $num=$orderArr[0]['num'];
    if($num==0){
         $ordstatus=$cOb->where("orderId='".$orderId."'")->getField('status');
    }else{
         $ordstatus=$oOb->where("orderId='".$orderId."'")->getField('status');
    }     
    if($ordstatus==1){
        return false;
    }else{
        return true;    
    }
 }
 //处理订单函数
 //更新订单状态，写入订单支付后返回的数据
 function orderhandle($parameter){
 /*
    $ordid=$parameter['out_trade_no'];
    $data['payment_trade_no']      =$parameter['trade_no'];
    $data['payment_trade_status']  =$parameter['trade_status'];
    $data['payment_notify_id']     =$parameter['notify_id'];
    $data['payment_notify_time']   =$parameter['notify_time'];
    $data['payment_buyer_email']   =$parameter['buyer_email'];
    $data['ordstatus']             =1;
    $Ord=M('Orderlist');
    $Ord->where('ordid='.$ordid)->save($data);
*/
    $orderId=$parameter['out_trade_no']; 
    $oOb=M("order");
    $orderArr=$oOb->field("count(*) as num")->where("orderId='{$orderId}'")->select();
    $num=$orderArr[0]['num'];
    //echo "<pre>";var_dump($orderArr);echo "</pre>";
    //echo $num."<br>";
    //var_dump($num==0);echo "<br>";
    $date=date("Y-m-d H:i:s");
    if($num==0){
           $coOb=M("cardorder");
           $arr=$coOb->where("orderId='{$orderId}'")->select();
           //echo "<pre>";var_dump($arr);echo "</pre>";
           $pid=$arr[0]['pid'];
           $coOb->query("update r_cardorder set status=2,updateTime='{$date}' where orderId='{$orderId}'");
           //echo $coOb->getLastSql();
           $cOb=M("card");
           $cOb->query("update r_card set store=store-1 where id={$pid}");
           $cArr=$cOb->where("id={$pid}")->select();
           $buyedOb=M("buyedcard");
           /*
           if($cArr[0]['validTime']=="0"){
                 $type=1;
           }else{
                 $type=2;
           }
           */
           $type = $cArr[0]['type'];
           //$shOb=M("simhard");
           //$shArr=$shOb->where("CCID='{$arr[0]['CCID']}'")->select();
           $buyedArr=array("CCID"=>$arr[0]['CCID'],"type"=>$type,"beginTime"=>$cArr[0]['beginTime'],"endTime"=>$cArr[0]['endTime'],"data"=>$cArr[0]['data']);
           $re2=$buyedOb->data($buyedArr)->add();
           /*$yhqId=$arr[0]['yhqId'];
           if($yhqId!=0){
                 $yOb=M("yhq");
                 $yOb->query("update r_yhq set status=2,updateTime='{$date}'  where id={$yhqId}");
           }*/

    }else{
           $arr=$oOb->where("orderId='{$orderId}'")->select();
           //var_dump($arr);
           $pid=$arr[0]['pid'];
           $oOb->query("update r_order set status=2 ,updated_at='{$date}'  where orderId='{$orderId}'");
           $ob=M("product");
           $ob->query("update r_product set store=store-1 where id={$pid}");
    }
 } 
?>


