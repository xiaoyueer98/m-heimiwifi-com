<?php
return array(
	//'配置项'=>'配置值'
	'DEFAULT_MODULE'=>'Index',    //设置默认的控制器名称
	'DEFAULT_ACTION'=>'index',          //设置默认的方法名称
	'APP_DEBUG'=>true,               //开启调试模式
	'URL_MODEL'=> '3',  
    //'TMPL_L_DELIM'          => '{{',			// 模板引擎普通标签开始标记
    //'TMPL_R_DELIM'          => '}}',			// 模板引擎普通标签结束标记    
     /* 数据库设置 */
    'DB_TYPE' => 'mysql',     // 数据库类型
	'DB_HOST' => '127.0.0.1', // 服务器地址
	'DB_NAME' => 'Route',          // 数据库名
	'DB_USER' => 'root',      // 用户名
	'DB_PWD'  => '',          // 密码
	'DB_PREFIX'=> 'r_',
        
	'TOKEN_ON'=>false,
	'TOKEN_NAME'=>'__hash__',
	'TOKEN_TYPE'=>'md5',
	'DB_FIELDTYPE_CHECK'=>false,
     //模板引擎    
    'TMPL_ENGINE_TYPE'=>'Smarty',
	'TMPL_ENGINE_CONFIG'=>array(
		'caching'=>false,
		'template_dir'=>TMPL_PATH,
		'compile_dir'=>CACHE_PATH,
		'cache_dir'=>TEMP_PATH,
		'left_delimiter'=>"{{",
		'right_delimiter'=>"}}"
	),
	'TMPL_ACTION_ERROR'     => TMPL_PATH.'default/Public/error.html', // 默认错误跳转对应的模板文件
  	'TMPL_ACTION_SUCCESS'   => TMPL_PATH.'default/Public/success.html',

       //支付宝配置参数
      'alipay_config'=>array(
      'partner' =>'2088701622892694',   //这里是你在成功申请支付宝接口后获取到的PID；
      'key'=>'rmpb6i07jyl6eudgzyaiphedfcmtpmxu',//这里是你在成功申请支付宝接口后获取到的Key
      'sign_type'=>strtoupper('MD5'),
      'input_charset'=> strtolower('utf-8'),
      'cacert'=> getcwd().'\\cacert.pem',
      'transport'=> 'http',
      ),
     
      'apiurl' => 'https://mobapi.747.cn/3', 
      
      'alipay'   =>array(
       //这里是卖家的支付宝账号，也就是你申请接口时注册的支付宝账号
      'seller_email'=>'747@747.cn',
      //这里是异步通知页面url，提交到项目的Pay控制器的notifyurl方法；
      //'notify_url'=>'http://m.heimiwifi.com/index.php?m=Pay&a=notifyurl', 
      'notify_url'=>'http://m.heimiwifi.com/alipay_notifyurl.php', 
      //这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
      'return_url'=>'http://m.heimiwifi.com/index.php?m=Pay&a=returnurl',
      //支付成功跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参payed（已支付列表）


      //wap端的
      'notify_urlWap'=>'http://m.heimiwifi.com/alipay_notifyurlWap.php', 
      //这里是页面跳转通知url，提交到项目的Pay控制器的returnurl方法；
      'return_urlWap'=>'http://m.heimiwifi.com/alipay_returnurlWap.php',
      //merchant_url操作中断返回的页面
      'merchant_urlWap'=>'http://m.heimiwifi.com/index.php',


      'successpage'=>'/Member/myorder?ordtype=payed',   
      //支付失败跳转到的页面，我这里跳转到项目的User控制器，myorder方法，并传参unpay（未支付列表）
      'errorpage'=>'/index.php?m=Member&a=myorder&ordtype=unpay', 
      ),

      //财付通配置参数
      'tenpay_config'=>array(

      'partner' =>'1218375401',
      'key'=>'1c7fdfe02162656a44a0a56bc6eff6bd',
      ),
      
      
      'tenpay'  =>array(

      'notify_url'=>'http://m.heimiwifi.com/tenpay_notifyurl.php',
      'return_url'=>'http://m.heimiwifi.com/index.php?m=Payment&a=returnurl', 
      ),
      'IS_LOGIN' => array("controller"=>array("Member"),
                        "Member"=>array('loginhm','reghm','telAjaxhm','regYzmhm','regPwdhm','postLoginhm','regSavehm','loginAjax','loginSave','reg','regSave','telAjax','userCenter','forgetPwd1','forgetPwd2','forgetPwd3','pushMessage','getYzm','checkYzm','regYzm','postLogin','order_card','order_cardWap','order_cardwx','yhqForm','yhqFormWap','yhq','cardOrderInsert','cardBuyAjax','resetPwd','telAjax1','telAjax2','regPwd','postMethod','regProtocal','isWeiXin','myorder','paySuccess','payOk','payFail','changeOk','changeFail','verify','isHard','reserveAjax','simhardType')
       
    ),

    //短信接口配置文件
     'sms_corp_id' => '2e5c001',
     'sms_corp_service' => '1065505yd',
     'sms_corp_pwd' => 'tjhm018',
     'sms_url' => 'http://service2.baiwutong.com:8080/sms_send2.do',
    
);
?>
