<?php
header("content-type:text/html;charset=utf-8");
//require_once("/ThinkPHP/Vender/Tenpay/RequestHandler.class.php");
class  PaymentAction  extends  Action{

    echo "暂停服务";die;
    public function _initialize() { 

        vendor('Tenpay.function');       
        import('ORG.Util.RequestHandler');
        import('ORG.Util.ResponseHandler');
        import('ORG.Util.ClientResponseHandler');
        import('ORG.Util.TenpayHttpClient');

    }

    public  function   dotenpay(){

        $tenpay_config=C('tenpay_config');
        $notify_url = C('tenpay.notify_url'); //服务器异步通知页面路径
        $return_url = C('tenpay.return_url');//页面跳转同步通知页面路径
        //echo $return_url;
        $partner = C('tenpay_config.partner');
        $key = C('tenpay_config.key');
        //var_dump($_POST);
        /* 获取提交的订单号 */
        $out_trade_no = $_REQUEST["trade_no"];
        /* 获取提交的商品名称 */
        $product_name = $_REQUEST["ordsubject"];
        /* 获取提交的商品价格 */
        $order_price = $_REQUEST["ordtotal_fee"];
        /* 获取提交的备注信息 */
        $remarkexplain = $_REQUEST["ordbody"];
        /* 支付方式 */
        $trade_mode=1;

        $strDate = date("Ymd");
        $strTime = date("His");

        /* 商品价格（包含运费），以分为单位 */
        $total_fee = $order_price*100;
        //$total_fee = 1;

        /* 商品名称 */
        $desc = "商品：".$product_name.",备注:".$remarkexplain;

        /* 创建支付请求对象 */
        $reqHandler = new RequestHandler();

        $reqHandler->init();
        $reqHandler->setKey($key);
        $reqHandler->setGateUrl("https://gw.tenpay.com/gateway/pay.htm");

        //----------------------------------------
        //设置支付参数 
        //----------------------------------------
        $reqHandler->setParameter("partner", $partner);
        $reqHandler->setParameter("out_trade_no", $out_trade_no);
        $reqHandler->setParameter("total_fee", $total_fee);  //总金额
        $reqHandler->setParameter("return_url", $return_url);
        $reqHandler->setParameter("notify_url", $notify_url);
        $reqHandler->setParameter("body", $desc);
        $reqHandler->setParameter("bank_type", "DEFAULT");  	  //银行类型，默认为财付通
        //用户ip
        $reqHandler->setParameter("spbill_create_ip", $_SERVER['REMOTE_ADDR']);//客户端IP
        $reqHandler->setParameter("fee_type", "1");               //币种
        $reqHandler->setParameter("subject",$desc);          //商品名称，（中介交易时必填）

        //系统可选参数
        $reqHandler->setParameter("sign_type", "MD5");  	 	  //签名方式，默认为MD5，可选RSA
        $reqHandler->setParameter("service_version", "1.0"); 	  //接口版本号
        $reqHandler->setParameter("input_charset", "utf-8");   	  //字符集
        $reqHandler->setParameter("sign_key_index", "1");    	  //密钥序号
        /*  
        //业务可选参数
        $reqHandler->setParameter("attach", "");             	  //附件数据，原样返回就可以了
        $reqHandler->setParameter("product_fee", "");        	  //商品费用
        $reqHandler->setParameter("transport_fee", "0");      	  //物流费用
        $reqHandler->setParameter("time_start", date("YmdHis"));  //订单生成时间
        $reqHandler->setParameter("time_expire", "");             //订单失效时间
        $reqHandler->setParameter("buyer_id", "");                //买方财付通帐号
        $reqHandler->setParameter("goods_tag", "");               //商品标记
        $reqHandler->setParameter("trade_mode",$trade_mode);              //交易模式（1.即时到帐模式，2.中介担保模式，3.后台选择（卖家进入支付中心列表选择））
        $reqHandler->setParameter("transport_desc","");              //物流说明
        $reqHandler->setParameter("trans_type","1");              //交易类型
        $reqHandler->setParameter("agentid","");                  //平台ID
        $reqHandler->setParameter("agent_type","");               //代理模式（0.无代理，1.表示卡易售模式，2.表示网店模式）
        $reqHandler->setParameter("seller_id",$partner);                //卖家的商户号

         */

        //对前台传过来的价钱数据做判断
        $orderId = $reqHandler->getParameter("out_trade_no"); 
        $total_fee = $reqHandler->getParameter("total_fee"); 
        $oOb=M("order");
        $orderArr=$oOb->field("count(*) as num")->where("orderId='{$orderId}'")->select();
        $num=$orderArr[0]['num'];
        if($num==0){
           $coOb = M("cardorder");
           $arr = $coOb->where("orderId='{$orderId}'")->select();
           //var_dump($arr);
           $realPrice = $arr[0]['price'] - $arr[0]['discount'] - $arr[0]['yhqPrice'];
           $realPrice = $realPrice*100;
           //var_dump($total_fee); var_dump($realPrice); die;
           if($total_fee != $realPrice){
               header("location:index.php?m=Member&a=payFail");
           }
        }else{
            
           $arr=$oOb->where("orderId='{$orderId}'")->select();
           $realPrice = $arr[0]['price'] - $arr[0]['discount'] - $arr[0]['yhq'];
           $realPrice = $realPrice*100;
           //var_dump($total_fee); var_dump($realPrice); die;
        
           if($total_fee != $realPrice){
               header("location:index.php?m=Member&a=payFail");
           }
        } 
        //请求的URL
        $reqUrl = $reqHandler->getRequestURL();

        //获取debug信息,建议把请求和debug信息写入日志，方便定位问题
        /**/
        $debugInfo = $reqHandler->getDebugInfo();
        //echo "<br/>" . $reqUrl . "<br/>";
        //echo "<br/>" . $debugInfo . "<br/>";

        header("location:".$reqUrl);



    }

    function  returnurl(){

        $partner = C('tenpay_config.partner');
        $key = C('tenpay_config.key');
        /* 创建支付应答对象 */
        $resHandler = new ResponseHandler();
        $resHandler->setKey($key);
        //var_dump($resHandler->setKey($key));var_dump($resHandler->parameters);  
        //判断签名
        if($resHandler->isTenpaySign()) {

            //通知id
            $notify_id = $resHandler->getParameter("notify_id");
            //商户订单号
            $out_trade_no = $resHandler->getParameter("out_trade_no");
            //财付通订单号
            $transaction_id = $resHandler->getParameter("transaction_id");
            //金额,以分为单位
            $total_fee = $resHandler->getParameter("total_fee");
            //如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
            $discount = $resHandler->getParameter("discount");
            //支付结果
            $trade_state = $resHandler->getParameter("trade_state");
            //交易模式,1即时到账
            $trade_mode = $resHandler->getParameter("trade_mode");


            if("1" == $trade_mode ) {
                if( "0" == $trade_state){ 


                    //echo "<br/>" . "即时到帐支付成功" . "<br/>";
                if(!checkorderstatus($out_trade_no)){
                    orderhandle($resHandler->parameters);
                }
                    header("location:index.php?m=Member&a=payOk");

                } else {
                    //当做不成功处理
                    //echo "<br/>" . "即时到帐支付失败" . "<br/>";
                    header("location:index.php?m=Member&a=payFail");
                }
            }elseif( "2" == $trade_mode  ) {
                if( "0" == $trade_state) {



                    echo "<br/>" . "中介担保支付成功" . "<br/>";

                } else {
                    //当做不成功处理
                    echo "<br/>" . "中介担保支付失败" . "<br/>";
                }
            }

        } else {
            echo "<br/>" . "认证签名失败" . "<br/>";
            echo $resHandler->getDebugInfo() . "<br>";
        }
    }

    function   notifyurl(){

        $partner = C('tenpay_config.partner');
        $key = C('tenpay_config.key');
        /* 创建支付应答对象 */
        $resHandler = new ResponseHandler();
        $resHandler->setKey($key);

        //判断签名
        if($resHandler->isTenpaySign()) {

            //通知id
            $notify_id = $resHandler->getParameter("notify_id");

            //通过通知ID查询，确保通知来至财付通
            //创建查询请求
            $queryReq = new RequestHandler();
            $queryReq->init();
            $queryReq->setKey($key);
            $queryReq->setGateUrl("https://gw.tenpay.com/gateway/simpleverifynotifyid.xml");
            $queryReq->setParameter("partner", $partner);
            $queryReq->setParameter("notify_id", $notify_id);

            //通信对象
            $httpClient = new TenpayHttpClient();
            $httpClient->setTimeOut(5);
            //设置请求内容
            $httpClient->setReqContent($queryReq->getRequestURL());

            //后台调用
            if($httpClient->call()) {
                //设置结果参数
                $queryRes = new ClientResponseHandler();
                $queryRes->setContent($httpClient->getResContent());
                $queryRes->setKey($key);

                if($resHandler->getParameter("trade_mode") == "1"){
                    //判断签名及结果（即时到帐）
                    //只有签名正确,retcode为0，trade_state为0才是支付成功
                    if($queryRes->isTenpaySign() && $queryRes->getParameter("retcode") == "0" && $resHandler->getParameter("trade_state") == "0") {
                        log_result("即时到帐验签ID成功");
                        //取结果参数做业务处理
                        $out_trade_no = $resHandler->getParameter("out_trade_no");
                        //财付通订单号
                        $transaction_id = $resHandler->getParameter("transaction_id");
                        //金额,以分为单位
                        $total_fee = $resHandler->getParameter("total_fee");
                        //如果有使用折扣券，discount有值，total_fee+discount=原请求的total_fee
                        $discount = $resHandler->getParameter("discount");
                        if(!checkorderstatus($out_trade_no)){
                            orderhandle($resHandler->parameters);
                        }

                        //------------------------------
                        //处理业务开始
                        //------------------------------

                        //处理数据库逻辑
                        //注意交易单不要重复处理
                        //注意判断返回金额

                        //------------------------------
                        //处理业务完毕
                        //------------------------------
                        log_result("即时到帐后台回调成功");
                        echo "success";

                    } else {
                        //错误时，返回结果可能没有签名，写日志trade_state、retcode、retmsg看失败详情。
                        //echo "验证签名失败 或 业务错误信息:trade_state=" . $resHandler->getParameter("trade_state") . ",retcode=" . $queryRes->                         getParameter("retcode"). ",retmsg=" . $queryRes->getParameter("retmsg") . "<br/>" ;
                        log_result("即时到帐后台回调失败");
                        echo "fail";
                    }
                }elseif ($resHandler->getParameter("trade_mode") == "2")

                {
                    //判断签名及结果（中介担保）
                    //只有签名正确,retcode为0，trade_state为0才是支付成功
                    if($queryRes->isTenpaySign() && $queryRes->getParameter("retcode") == "0" ) 
                    {
                        log_result("中介担保验签ID成功");
                        //取结果参数做业务处理
                        $out_trade_no = $resHandler->getParameter("out_trade_no");
                        //财付通订单号
                        $transaction_id = $resHandler->getParameter("transaction_id");


                        //------------------------------
                        //处理业务开始
                        //------------------------------

                        //处理数据库逻辑
                        //注意交易单不要重复处理
                        //注意判断返回金额

                        log_result("中介担保后台回调，trade_state=".$resHandler->getParameter("trade_state"));
                        switch ($resHandler->getParameter("trade_state")) {
                            case "0":	//付款成功

                                break;
                            case "1":	//交易创建

                                break;
                            case "2":	//收获地址填写完毕

                                break;
                            case "4":	//卖家发货成功

                                break;
                            case "5":	//买家收货确认，交易成功

                                break;
                            case "6":	//交易关闭，未完成超时关闭

                                break;
                            case "7":	//修改交易价格成功

                                break;
                            case "8":	//买家发起退款

                                break;
                            case "9":	//退款成功

                                break;
                            case "10":	//退款关闭			

                                break;
                            default:
                                //nothing to do
                                break;
                        }


                        //------------------------------
                        //处理业务完毕
                        //------------------------------
                        echo "success";
                    } else

                    {
                        //错误时，返回结果可能没有签名，写日志trade_state、retcode、retmsg看失败详情。
                        //echo "验证签名失败 或 业务错误信息:trade_state=" . $resHandler->getParameter("trade_state") . ",retcode=" . $queryRes->             										       getParameter("retcode"). ",retmsg=" . $queryRes->getParameter("retmsg") . "<br/>" ;
                        log_result("中介担保后台回调失败");
                        echo "fail";
                    }
                }



                //获取查询的debug信息,建议把请求、应答内容、debug信息，通信返回码写入日志，方便定位问题
                /*
                   echo "<br>------------------------------------------------------<br>";
                   echo "http res:" . $httpClient->getResponseCode() . "," . $httpClient->getErrInfo() . "<br>";
                   echo "query req:" . htmlentities($queryReq->getRequestURL(), ENT_NOQUOTES, "GB2312") . "<br><br>";
                   echo "query res:" . htmlentities($queryRes->getContent(), ENT_NOQUOTES, "GB2312") . "<br><br>";
                   echo "query reqdebug:" . $queryReq->getDebugInfo() . "<br><br>" ;
                   echo "query resdebug:" . $queryRes->getDebugInfo() . "<br><br>";
                 */
            }else
            {
                //通信失败
                echo "fail";
                //后台调用通信失败,写日志，方便定位问题
                echo "<br>call err:" . $httpClient->getResponseCode() ."," . $httpClient->getErrInfo() . "<br>";
            } 


        }else{
            echo "<br/>" . "认证签名失败" . "<br/>";
            echo $resHandler->getDebugInfo() . "<br>";
        }


    }



}
