<?php
header("content-type:text/html;charset=utf-8");
class  PayAction extends  Action{
    //在类初始化方法中，引入相关类库    
    public function _initialize() {
        

        //wap端的
        vendor('AlipayWap.CorefunctionWap');
        vendor('AlipayWap.Md5functionWap');
        vendor('AlipayWap.NotifyWap');
        vendor('AlipayWap.SubmitWap');    
        vendor('AlipayWap.RsafunctionWap');    

    }
  

     //wap端的

       public function doalipay(){
           
        echo "暂停服务";die;
		//echo "<pre>";var_dump( $_SERVER );echo "</pre>"; 
		//返回格式
		$format = "xml";//必填，不需要修改//返回格式
		$v = "2.0";
		//这里我们通过TP的C函数把配置项参数读出，赋给$alipay_config；
		$alipay_config=C('alipay_config');  

		/**************************请求参数**************************/
		$payment_type = C('alipay_config.sign_type'); //支付类型 //必填，不能修改
		$notify_url = C('alipay.notify_urlWap'); //服务器异步通知页面路径
		$return_url = C('alipay.return_urlWap');//页面跳转同步通知页面路径
		$merchant_url = C('alipay.merchant_urlWap');//操作中断返回地址
		$seller_email = C('alipay.seller_email');//卖家支付宝帐户必填
		$out_trade_no = $_POST['trade_no']; //商户订单号 通过支付页面的表单进行传递，注意要唯一！
		$subject = $_POST['ordsubject'];  //订单名称 //必填 通过支付页面的表单进行传递
		$total_fee = $_POST['ordtotal_fee'];  //付款金额  //必填 通过支付页面的表单进行传递
		$body = $_POST['ordbody'];   //订单描述 通过支付页面的表单进行传递
		$show_url = $_POST['ordshow_url'];//商品展示地址 通过支付页面的表单进行传递
		$anti_phishing_key = "";//防钓鱼时间戳 //若要使用请调用类文件submit中的query_timestamp函数
		//$exter_invoke_ip = get_client_ip();//var_dump($exter_invoke_ip);die();   //客户端的IP地址 
		$exter_invoke_ip = $_SERVER["REMOTE_ADDR"];//var_dump($exter_invoke_ip);die();   //客户端的IP地址 
		/************************************************************/

		//对前台传过来的价钱数据做判断
		$orderId=$out_trade_no; 
		$oOb=M("order");
		$orderArr=$oOb->field("count(*) as num")->where("orderId='{$orderId}'")->select();
		$num=$orderArr[0]['num'];
		if($num==0){
		   $coOb = M("cardorder");
		   $arr = $coOb->where("orderId='{$orderId}'")->select();
		   //var_dump($arr);
		   $realPrice = $arr[0]['price'] - $arr[0]['discount'] - $arr[0]['yhqPrice'];
		   //var_dump($total_fee); var_dump($realPrice); die;
		   if($total_fee != $realPrice){
		       header("location:index.php?m=Member&a=payFail");
		   }
		}else{
		    
		   $arr=$oOb->where("orderId='{$orderId}'")->select();
		   $realPrice = $arr[0]['price'] - $arr[0]['discount'] - $arr[0]['yhq'];
		
		   if($total_fee != $realPrice){
		       header("location:index.php?m=Member&a=payFail");
		   }
		} 
		$call_back_url = $return_url;
		//请求业务参数详细
		$req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee><merchant_url>' . $merchant_url . '</merchant_url></direct_trade_create_req>';
		//echo "req_data";var_dump($req_data);echo "----------------------------------";
		//必填
		//构造要请求的参数数组，无需改动
		$para_token = array(
		        "service" => "alipay.wap.trade.create.direct",
		        "partner" => trim($alipay_config['partner']),
		        "sec_id"    => $payment_type,
		        "format"	=> $format,
		        "v"	=> $v,
		        "req_id"	=> $out_trade_no,
		        "req_data"	=> trim($req_data),
		        "_input_charset"    => trim(strtolower($alipay_config['input_charset']))
		        );
		//echo "<pre>";var_dump($para_token);echo "</pre>";die();
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);

		$html_text = $alipaySubmit->buildRequestHttp($para_token);
		//var_dump($html_text);die();
		//URLDECODE返回的信息
		$html_text = urldecode($html_text);
		//echo "<pre>";var_dump($html_text);echo "</pre>";die();
		//解析远程模拟提交后返回的信息
		$para_html_text = $alipaySubmit->parseResponse($html_text);
		//var_dump($para_html_text);die;
		//获取request_token
		$request_token = $para_html_text['request_token'];

		//var_dump($request_token);


		/**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/

		//业务详细
		$req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
		//必填

		$parameter = array(
		        "service" => "alipay.wap.auth.authAndExecute",
		        "partner" => trim($alipay_config['partner']),
		        "sec_id"    => $payment_type,                
		        "format"	=> $format,
		        "v"	=> $v,
		        "req_id"	=> $out_trade_no,
		        "req_data"	=> trim($req_data),
		        "_input_charset"    => trim(strtolower($alipay_config['input_charset']))
		        );
		//var_dump($parameter);die();
		$parameter1 = paraFilter($parameter); 
		$parameter2 = argSort($parameter);
		//建立请求
		$alipaySubmit = new AlipaySubmit($alipay_config);
		$mysign = $alipaySubmit -> buildRequestMysign($parameter2); 
		$parameter2['sign'] = $mysign;
		//echo "<pre>";var_dump($parameter2);echo "</pre>";die();
		$html_text = $alipaySubmit->buildRequestForm($parameter2,"get", "确认");   
		echo $html_text;   
	    }

	    function notifyurlWap(){
        /*
        $str= "111\n";   
        if(isset($_POST) && !empty($_POST)){
            foreach($_POST as $k=>$v){
                $str .= $k ."=>".$v."\n";
            }
        }
        file_put_contents("notifyurlWap.txt",$str,FILE_APPEND);
        */
		$alipay_config=C('alipay_config');
		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyNotify();
        //file_put_contents("notifyurlWap.txt",$verify_result,FILE_APPEND);
		if($verify_result) {
		    //验证成功
            //file_put_contents("notifyurlWap.txt","验证成功",FILE_APPEND);
		    $doc = new DOMDocument();
            //file_put_contents("notifyurlWap.txt","doc对象成功",FILE_APPEND);
            $notify_data = $_POST['notify_data'];
		    $doc->loadXML($notify_data);
            //file_put_contents("notifyurlWap.txt",unserialize($doc),FILE_APPEND);
		    if( ! empty($doc->getElementsByTagName( "notify" )->item(0)->nodeValue) ) {
		        //商户订单号
		        $out_trade_no = $doc->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;
		        //支付宝交易号
		        $trade_no = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
		        //交易状态
		        $trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;
		        $parameter = array(
		            "out_trade_no"     => $out_trade_no, //商户订单编号；
		            "trade_no"     => $trade_no,     //支付宝交易号；
		            "trade_status"     => $trade_status, //交易状态
		        );
		        //file_put_contents("/index/paylog.txt",$parameter,FILE_APPEND);
                //file_put_contents("notifyurlWap.txt",$out_trade_no,FILE_APPEND);
                //file_put_contents("notifyurlWap.txt",$trade_no,FILE_APPEND);
                //file_put_contents("notifyurlWap.txt",$trade_status,FILE_APPEND);
		        if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS')  {                           
		            if(!checkorderstatus($out_trade_no)){
		                orderhandle($parameter); 
		                    //进行订单处理，并传送从支付宝返回的参数；
		            }
		        }
		        echo "success";        //请不要修改或删除
		    }    
		}else {
		        //验证失败
		        echo "fail";
		}    
	    }

	    function returnurlWap(){

		$alipay_config = C('alipay_config');
		$alipayNotify = new AlipayNotify($alipay_config);//计算得出通知验证结果
		$verify_result = $alipayNotify->verifyReturn();
		//var_dump($_GET);
		if($verify_result) {
		    //验证成功
		    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表
		    $out_trade_no   = $_GET['out_trade_no'];      //商户订单号
		    $trade_no       = $_GET['trade_no'];          //支付宝交易号
		    $trade_status   = $_GET['result'];      //交易状态

		    $parameter = array(
		            "out_trade_no"     => $out_trade_no,      //商户订单编号；
		            "trade_no"     => $trade_no,          //支付宝交易号；
		            "trade_status"     => $trade_status,      //交易状态
		    );

		    //echo "<pre>";var_dump($parameter);echo "</pre>";die();
            
		    if($_GET['result'] == 'success' ) {
		        if(!checkorderstatus($out_trade_no)){
		            orderhandle($parameter); //进行订单处理，并传送从支付宝返回的参数；
		            //die();
		        }
		        $this->redirect(C('alipay.successpage'));//跳转到配置项中配置的支付成功页面；
		    }else {
		        //echo "trade_status=".$_GET['trade_status'];
		        $this->redirect(C('alipay.errorpage'));//跳转到配置项中配置的支付失败页面；
		    }
		}else {
		    //验证失败
		    //如要调试，请看alipay_notify.php页面的verifyReturn函数
		    echo "支付失败！";
		}
	    }

}




