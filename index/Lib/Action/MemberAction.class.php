<?php

ob_end_clean();

class MemberAction extends Action {

    function phpinfo() {
        //echo phpinfo();
    }

    function index() {
        /*
          $content=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token=ACCESS_TOKEN&openid=o-1IBt0XOZzw3YR0PpqfgFcHY4xI&lang=zh_CN");
          ob_end_clean();
          $re=json_decode($content);
          var_dump($re);
          $re1=get_object_vars($re);
          $data=get_object_vars($re1['data']);
          var_dump($data);
          echo $data['total'].",".$data['left'].",".$data['expiry'];
         */
    }

    function verify() {

        import('ORG.Util.Image');
        Image::buildImageVerify();
    }

    function reg() {
        $this->display();
    }
    function reghm() {
        $this->display();
    }

    function regYzm() {
        //$yzm=$_COOKIE['yzm'];
        //$this->assign("test",$yzm); 
        $tel = $_POST['tel'];
        if ($tel != "") {
            $this->assign("tel", $tel);
            $this->display();
        } else {
            header("location:?m=Member&a=reg");
        }
    }
				
    function regYzmhm() {
        //$yzm=$_COOKIE['yzm'];
        //$this->assign("test",$yzm); 
        $tel = $_POST['tel'];
        if ($tel != "") {
            $this->assign("tel", $tel);
            $this->display();
        } else {
            header("location:?m=Member&a=reghm");
        }
    }

    function regSave() {

        $userData = array(
            'telephone' => $_POST['tel'],
            'password' => $_POST['password'],
        );
        //var_dump($userData);
        $apiurl = C('apiurl');
        $ReturnData = $this->postMethod($apiurl . '/Wuser/register/izeus201201', $userData);
        //var_dump($ReturnData);
        //echo "<br>";

        $ReturnData = json_decode($ReturnData, true);
        //var_dump($ReturnData); 
        $code = $ReturnData['code'];
        $detail = $ReturnData['detail'];
        //echo $code;
        if ($code == "500" or $code == "404") {

            header("location:index.php?m=Member&a=reg");
        } elseif ($code == "200") {
            /* 设置用户登录状态 */
            $ReturnData = $this->postMethod($apiurl . '/Wuser/login/izeus201201', $userData);
            $ReturnData = json_decode($ReturnData, true);
            $uid = $ReturnData['data'][0]['uId'];
            $mOb = M("memberbd");
            $re = $mOb->data(array("uid" => $uid))->add();
            if ($re) {
                $arr = array("uid" => $uid, "tel" => $_POST['tel'], "CCID" => "");
                $re1 = setcookie("userinfo", serialize($arr), time() + 24 * 3600, "/");

                if ($re1) {
                    $this->assign("jumpUrl", "index.php?m=Index&a=index");
                    $this->assign("waitSeconds", "3");
                    $this->success();
                } else {

                    $this->assign("jumpUrl", "index.php?m=Member&a=login");
                    $this->assign("waitSeconds", "3");
                    $this->success();
                }
            } else {
//                $this->assign("jumpUrl","index.php?m=Member&a=reg");
                $this->assign("jumpUrl", "index.php");
                $this->assign("waitSeconds", "3");
                $this->error();
            }
        }
    }
				//获取UID
    private function getUid() {
        //2013-01-01 00:00:00 (timestamp-microtime)
        $startTime = 1356969600000;
        $preBit = '0' . decbin(intval(microtime(1) * 1000) - $startTime);
        $partitionNumber = '0000000000';
        $counter = decbin(rand(0, 4096));
        return strval(bindec($preBit . $partitionNumber . $counter));
    }
//				是否已注册
				private function checkUsertel($tel){
								$mm = M('memberbd');
								$sql = "select id from __TABLE__ where usertel='{$tel}'";
								$r=$mm->query($sql);
								return count($r);
				}
    function regSavehm() {
								ob_start();
        $userData = array(
            'telephone' => $_POST['tel'],
            'password' => $_POST['password'],
        );
								$usertel = trim($_POST['tel']);
								$pwd = $this->getpwd(trim($_POST['password']));
								$uid = $this->getUid();
								$have = $this->checkUsertel($usertel);
								if($have > 0 ){
//												echo '已注册过。';
												header("location:index.php?m=Index&a=index");
												exit;
								}else{
												$ctime = date('Y-m-d H:i:s');
												$mm = M('memberbd');
												$sql = "insert into __TABLE__ set uid='{$uid}',usertel='{$usertel}',pwd='{$pwd}',ctime='{$ctime}'";
												$r=$mm->execute($sql);
												
												$arr = array("uid" => $uid, "tel" => $usertel, "CCID" => "");
												setcookie("userinfo", serialize($arr), time() + 24 * 3600 * 30, "/");
												$this->assign("jumpUrl", "index.php?m=Index&a=index");
												$this->assign("waitSeconds", "3");
												$this->success();
								}
								ob_end_flush();
    }

    /*
      function	telAjax(){
      //$_GET['tel']="12345678900";
      if(isset($_GET) && !empty($_GET)){

      $tel=$_GET['tel'];
      $mOb=M("member");
      $arr=$mOb->where("tel='$tel'")->select();
      if(isset($arr) && !empty($arr)){
      ob_end_clean();
      echo "0";
      }
      }
      } */

    /*
     * 注册获取验证码
     */

    function telAjaxhm() {
								ob_start();
        $phone = trim($_GET['tel']);
								$mm = M('memberbd');
								$sql = "select id from __TABLE__ where usertel='{$phone}'";
								$r = $mm->query($sql);
								$have = count($r);
								if($have > 0){
												echo "该号码已注册，请直接登录";
												exit;
								}else{
												$yzm = $this->sendRegisterSms($phone);
												if($yzm == 0){
//																短信接口问题；
																echo '短信服务商服务忙,请稍后重试';
																exit;
												}
            //echo $yzm ;
            $md5Yzm = md5(trim($yzm));
            $yzm = substr($md5Yzm, 0, 4);
            $yzm_old = $_COOKIE['yzm'];
            if (isset($yzm_old) && !empty($yzm_old)) {

                $yzmStr = $yzm_old . "," . $yzm;
            } else {
                $yzmStr = $yzm;
            }
            setcookie("yzm", $yzmStr, time() + 3600, "/");
            //echo json_encode(array("error" => 0, "code" => "ok"));
            echo "1";
								}  
								ob_end_flush();
        return;
    }
				/*
     * 发送一个验证码到手机，并将Cookie信息写入本地
     * 1 正常
     * 2 该手机号码已注册
     * 0 短信接口异常
     * -1 手机号码错误 
     */
    private function sendRegisterSms($tel) {
        $util = new UtilsModel();  
								$sms = new SmsModel();
								$number = $util->getRandomNumber();
								$result = $sms->registerSendSms($tel, $number);
								if ($result == "0#1") {
												return $number;
								} else {
												return "0";
								}
    }
				
    function telAjax() {
        $phone = $_GET['tel'];
        //获取接口数据，reset：1注册；2：找回密码
        $userData = array(
            'telephone' => $phone,
            'reset' => 1);
        $apiurl = C('apiurl');
        $ReturnData = $this->postMethod($apiurl . '/Wuser/get_code/izeus201201', $userData);
        $ReturnData = json_decode($ReturnData, true);
        //var_dump($ReturnData);die();
        $code = $ReturnData['code']; //响应状态
        //echo $code;
        $detail = $ReturnData['detail']; //消息
        //echo $detail;
        if ($code != '200') {
            //echo json_encode(array("error" => 1, "msg" => $detail));
            if ($detail == "号码已注册，请尝试重置密码。") {
                echo "该号码已注册，请直接登录";
            } else {
                echo $detail;
            }
        } else {

            $yzm = $ReturnData['data'][0]['verify_code'];
            //echo $yzm ;
            $md5Yzm = md5(trim($yzm));
            $yzm = substr($md5Yzm, 0, 4);
            $yzm_old = $_COOKIE['yzm'];
            if (isset($yzm_old) && !empty($yzm_old)) {

                $yzmStr = $yzm_old . "," . $yzm;
            } else {
                $yzmStr = $yzm;
            }
            setcookie("yzm", $yzmStr, time() + 3600, "/");
            //echo json_encode(array("error" => 0, "code" => "ok"));
            echo "1";
        }

        return;
    }

    /* 找回密码第一步获取验证码 */

    function telAjax1() {
        $phone = $_GET['tel'];
        //获取接口数据，reset：1注册；2：找回密码
        $userData = array(
            'telephone' => $phone,
            'reset' => 2);
        $apiurl = C('apiurl');
        $ReturnData = $this->postMethod($apiurl . '/Wuser/get_code/izeus201201', $userData);
        $ReturnData = json_decode($ReturnData, true);
        //var_dump($ReturnData);
        $code = $ReturnData['code']; //响应状态
        //echo $code;
        $detail = $ReturnData['detail']; //消息
        //echo $detail;
        if ($code != '200') {
            //echo json_encode(array("error" => 1, "msg" => $detail));
            echo $detail;
        } else {

            $yzm = $ReturnData['data'][0]['verify_code'];
            //echo $yzm ;
            setcookie("yzm", $yzm, time() + 3600, "/");
            echo "1";
        }
    }

    function regPwd() {

        $tel = $_POST['tel'];
        if ($tel != "") {
            $this->assign("tel", $tel);
            $this->assign("referer", $_SERVER['HTTP_REFERER']);
            $this->display();
        } else {
            header("location:?m=Member&a=regYzm");
        }
    }
    function regPwdhm() {
        $tel = $_POST['tel'];
        if ($tel != "") {
            $this->assign("tel", $tel);
            $this->assign("referer", $_SERVER['HTTP_REFERER']);
            $this->display();
        } else {
            header("location:?m=Member&a=regYzmhm");
        }
    }

    function login() {
        ob_end_clean();
        // if(!empty($_SERVER["HTTP_REFERER"])){
        //   $referer_arr = explode("?",$_SERVER["HTTP_REFERER"]);
        //   $referer = "?".$referer_arr[1];
        //  }
        // $referer = !isset($referer) || $referer == "m=Member&a=login" ?"": $referer;
        $this->assign("referer", $_SERVER["HTTP_REFERER"]);
        $this->display();
    }
    function loginhm() {
        ob_end_clean();
        // if(!empty($_SERVER["HTTP_REFERER"])){
        //   $referer_arr = explode("?",$_SERVER["HTTP_REFERER"]);
        //   $referer = "?".$referer_arr[1];
        //  }
        // $referer = !isset($referer) || $referer == "m=Member&a=login" ?"": $referer;
        $this->assign("referer", $_SERVER["HTTP_REFERER"]);
        $this->display();
    }

    /*
      function   loginAjax(){

      $tel=$_GET['tel'];
      $mOb=new MemberModel;
      $arr=$mOb -> getMember("tel='".$tel."'");
      if(isset($arr) && !empty($arr)){
      echo $arr[0]['password'].",".$arr[0]['id'];

      }else{
      echo "0";
      }
      }
     */

    function loginOut() {
        $userInfo = unserialize(stripcslashes($_COOKIE['userinfo']));
        $uid = $userInfo['uid'];
        $userData = array(
            "session_id" => $uid
        );
        $apiurl = C('apiurl');
        $ReturnData = $this->postMethod($apiurl . '/user/logout/izeus201201', $userData);

        $ReturnData = json_decode($ReturnData, true);
        //var_dump($ReturnData);die;

        $code = $ReturnData['code'];
        $detail = $ReturnData['detail'];
        if ($code == "500" or $code == "404") {
            ob_end_clean();
            echo $detail;
        } elseif ($code == "200") {
            setcookie("userinfo", serialize($_GET), time() - 1, "/");
            setcookie("orderId", serialize($_GET), time() - 1, "/");
            setcookie("addressId", serialize($_GET), time() - 1, "/");
            setcookie("payMethodId", serialize($_GET), time() - 1, "/");
            ob_end_clean();
            echo "0";
        }
    }
    function loginOuthm() {
								setcookie("userinfo", serialize($_GET), time() - 1, "/");
								setcookie("orderId", serialize($_GET), time() - 1, "/");
								setcookie("addressId", serialize($_GET), time() - 1, "/");
								setcookie("payMethodId", serialize($_GET), time() - 1, "/");
        echo "0";
    }

    function postMethod($url, $post = null) {
        $context = array();

        if (is_array($post)) {

            $context['http'] = array(
                'method' => 'POST',
                'content' => http_build_query($post, '', '&'),
            );
        }

        return file_get_contents($url, false, stream_context_create($context));
    }

    function postLogin() {

        $userData = array(
            'telephone' => $_POST['tel'],
            'password' => $_POST['pwd'],
        );
        /* 这句是为了测试 */
        /*
          $userinfo=array("uid"=>"109635820687720448","tel"=>"15201273050","CCID"=>"");
          setcookie("userinfo",serialize($userinfo),time()+24*3600,"/");
          die; */
        /* 测试end */
        //var_dump($userData);
        $apiurl = C('apiurl');
        $ReturnData = $this->postMethod($apiurl . '/Wuser/login/izeus201201', $userData);

        $ReturnData = json_decode($ReturnData, true);
        //var_dump($ReturnData);

        $code = $ReturnData['code'];
        $detail = $ReturnData['detail'];
        if ($code == "500" or $code == "404") {
            ob_end_clean();
            echo $detail;
        } elseif ($code == "200") {
            $uid = $ReturnData['data'][0]['uId'];
            $mOb = M("memberbd");
            $arr = $mOb->where("uid={$uid}")->select();
            if (isset($arr) && !empty($arr)) {

                $CCID = $arr[0]['CCID'];
            } else {
                $mArr = array('uid' => $uid);
                $mOb->data($mArr)->add();
                $CCID = "";
            }
            $userinfo = array("uid" => $uid, "CCID" => $CCID, "tel" => $_POST['tel']);
            setcookie("userinfo", serialize($userinfo), time() + 24 * 3600, "/");
            $referer = urldecode($_POST['referer']);
            //var_dump( $referer);
            if ($referer == "") {

                ob_end_clean();
                echo "2";
                exit();
            } elseif (!strpos($referer, "reg") && !strpos($referer, "regSave") && !strpos($referer, "regYzm") && !strpos($referer, "regPwd") && !strpos($referer, "login") && !strpos($referer, "postLogin") && !strpos($referer, "forgetPwd1") && !strpos($referer, "forgetPwd2") && !strpos($referer, "forgetPwd3") && !strpos($referer, "pwdSave")) {
                ob_end_clean();
                echo "1";

                exit();
            } else {
                ob_end_clean();
                echo "2";
                exit();
            }
        } else {

            ob_end_clean();
            echo "未知错误";
            exit();
        }
        exit();
    }
				private function getpwd($pwd){
								return strtolower(hash('sha256',$pwd));
				}
    function postLoginhm() {
        $userData = array(
            'telephone' => trim($_POST['tel']),
            'password' => $_POST['pwd'],
        );
//        $userData = array(
//            'telephone' => 13520047593,
//            'password' => 111111,
//        );
        /* 这句是为了测试 */
        /*
          $userinfo=array("uid"=>"109635820687720448","tel"=>"15201273050","CCID"=>"");
          setcookie("userinfo",serialize($userinfo),time()+24*3600,"/");
          die; */
        /* 测试end */
        //var_dump($userData);
								$usertel = $userData['telephone'];
								$pwd = $this->getpwd($userData['password']);
								$mOb = M("memberbd");
								$sql = "select uid,CCID,pwd from __TABLE__ where usertel='{$usertel}'";
								$r = $mOb->query($sql);
								$have = 0;
								if(count($r) > 0){
												$pwd1 = strtolower($r[0]['pwd']);
												if($pwd == $pwd1){
																$have = 1;
												}else{
																$have = 0;
																echo "密码输入错误";
																exit();
												}
								}
//								echo $have;
//								var_dump($r);exit;
        if ($have == "1") {
            $uid = $r[0]['uid'];
												$CCID = $r[0]['CCID']; 
            $userinfo = array("uid" => $uid, "CCID" => $CCID, "tel" => $_POST['tel']);
            setcookie("userinfo", serialize($userinfo), time() + 24 * 3600*30, "/");
            $referer = urldecode($_POST['referer']);
            //var_dump( $referer);
            if ($referer == "") {

                ob_end_clean();
                echo "2";
                exit();
            } elseif (!strpos($referer, "reg") && !strpos($referer, "regSave") && !strpos($referer, "regYzm") && !strpos($referer, "regPwd") && !strpos($referer, "login") && !strpos($referer, "postLogin") && !strpos($referer, "forgetPwd1") && !strpos($referer, "forgetPwd2") && !strpos($referer, "forgetPwd3") && !strpos($referer, "pwdSave")) {
                ob_end_clean();
                echo "1";

                exit();
            } else {
                ob_end_clean();
                echo "2";
                exit();
            }
        } else {

            ob_end_clean();
            echo "无此用户";
            exit();
        }
        exit();
    }

    function getRefererAction() {
        $action = $_COOKIE['action'];
        echo $action;
    }

    function set_Cookie() {
        $uid = $_GET['uid'];
        $mOb = M("memberbd");
        $arr = $mOb->where("uid={$uid}")->select();
        $CCID = $arr[0]['CCID'];
        $_GET['CCID'] = $CCID;
        setcookie("userinfo", serialize($_GET), time() + 24 * 3600, "/");
        ob_end_clean();
        echo "1";
    }

    function forgetPwd1() {

        $this->display();
    }

    function pushMessage() {
        if (empty($_COOKIE['yzm'])) {
            $userData = array(
                'telephone' => $_GET['tel'],
                'reset' => 2);
            $apiurl = C('apiurl');
            $ReturnData = $this->postMethod($apiurl . '/user/get_code/izeus201201', $userData);
            $ReturnData = json_decode($ReturnData, true);
            $code = $ReturnData['code']; //响应状态
            $detail = $ReturnData['detail']; //消息
            if ($code != '200')
                echo json_encode(array("error" => 1, "msg" => $detail));
            else {
                setcookie("yzm", true, time() + 120, "/");
                echo json_encode(array("error" => 0, "code" => $ReturnData['data']['verify_code']));
            }
        }
        echo json_encode(array("error" => 0, "msg" => "ok"));
        return;
    }

    /*
      验证验证码
     */

    function getYzm() {

        echo $_COOKIE['yzm'];
    }

    function checkYzm() {
        $yzm1 = $_GET['yzm'];
        $md5Yzm = md5(trim($yzm1));
        $yzm = substr($md5Yzm, 0, 4);
        $yzmStr = $_COOKIE['yzm'];
        $yzmArr = explode(",", $yzmStr);
        foreach ($yzmArr as $v) {
            if ($v == $yzm) {
                echo "1";
                return;
            }
        }
    }

    /*
      检验重置的密码
     */

    function resetPwd() {

        $tel = $_POST['tel'];
        $code = $_COOKIE['yzm'];
        $newpwd = $_POST['repwd'];
        $userData = array(
            'telephone' => $tel,
            'verify_code' => $code,
            'password' => $newpwd
        );
        //var_dump($userData);
        $apiurl = C('apiurl');
        $ReturnData = $this->postMethod($apiurl . '/Wuser/reset_password/izeus201201', $userData);
        //var_dump($ReturnData);	
        $ReturnData = json_decode($ReturnData, true);
        //var_dump($ReturnData);die;	
        $code = $ReturnData['code']; //响应状态
        $detail = $ReturnData['detail']; //消息
        if ($code != '200') {
            //echo json_encode(array("error"=>1,"msg"=>$detail));
            echo $detail;
        } else {
            //echo json_encode(array("error"=>0,"msg"=>"ok"));
            echo "1";
        }
        exit();
    }

    function forgetPwd2() {
        $tel = $_POST['tel'];
        if ($tel != "") {
            $this->assign("tel", $tel);
            $this->display();
        } else {
            header("location:?m=Member&a=forgetPwd1");
        }
    }

    function forgetPwd3() {

        $tel = $_POST['tel'];
        if ($tel != "") {
            $this->assign("tel", $tel);
            $this->display();
        } else {
            header("location:?m=Member&a=forgetPwd2");
        }
    }

//找回密码的密码保存     
    function pwdSave() {
        $this->assign("jumpUrl", "index.php?m=Member&a=login");
        $this->assign("waitSeconds", "3");
        $this->success();
    }

    function userCenter() {
        $userInfo = unserialize(stripcslashes($_COOKIE['userinfo']));
        $tel = $userInfo['tel'];
        $this->assign("tel", $tel);
        $this->display();
    }

//微信注册设备号获取验证码接口

    function telAjax2() {
        $phone = $_GET['tel'];
        //获取接口数据，reset：1注册；2：找回密码
        $userData = array(
            'telephone' => $phone,
        );
        $apiurl = C('apiurl');
        $ReturnData = $this->postMethod($apiurl . '/Wuser/get_code/izeus201201', $userData);
        $ReturnData = json_decode($ReturnData, true);
        //var_dump($ReturnData);die();
        $code = $ReturnData['code']; //响应状态
        //echo $code;
        $detail = $ReturnData['detail']; //消息
        //echo $detail;
        if ($code != '200') {
            //echo json_encode(array("error" => 1, "msg" => $detail));
            //if($detail == "号码已注册，请尝试重置密码。"){
            // echo "该号码已注册，请直接登录";
            //}else{
            echo $detail;
            //}     
        } else {

//        $yzm = $ReturnData['data'][0]['verify_code'];
//        //echo $yzm ;
//        setcookie("yzm",$yzm, time() + 3600, "/");
//        //echo json_encode(array("error" => 0, "code" => "ok"));
//        echo "1";
            $yzm = $ReturnData['data'][0]['verify_code'];
            //echo $yzm ;
            $md5Yzm = md5(trim($yzm));
            $yzm = substr($md5Yzm, 0, 4);
            $yzm_old = $_COOKIE['yzm'];
            if (isset($yzm_old) && !empty($yzm_old)) {

                $yzmStr = $yzm_old . "," . $yzm;
            } else {
                $yzmStr = $yzm;
            }
            setcookie("yzm", $yzmStr, time() + 3600, "/");
            //echo json_encode(array("error" => 0, "code" => "ok"));
            echo "1";
        }

        return;
    }

    function myWifi() {
        $userInfo = unserialize(stripcslashes($_COOKIE['userinfo']));
        $uid = $userInfo['uid']; //var_dump($uid);
        $mOb = M("memberbd");
        $mArr = $mOb->where("uid='$uid'")->select();
        $shOb = M("simhard");
        if ($mArr[0]['CCID'] != "") {
            $sta = 1;  //已绑定状态
            $this->assign("CCID", $mArr[0]['CCID']);
            $this->assign("sta", $sta);
            //ssid、pwd
            $shArr = $shOb->where("CCID='{$mArr[0]['CCID']}'")->select();
            if (isset($shArr) && !empty($shArr)) {
                $bOb = M("batch");
                $bArr = $bOb->where("id={$shArr[0]['simBatch']}")->select();

                $this->assign("ssid", $bArr[0]['ssid']);
                $this->assign("pwd", $bArr[0]['password']);
            }
        } else {

            $sta = 2; //未绑定
            $this->assign("sta", $sta);
        }
        $this->display();
    }

    function orderSearch() {
        $userInfo = unserialize(stripcslashes($_COOKIE['userinfo']));
        $uid = $userInfo['uid'];
        $oOb = M("order");
        $orderArr = $oOb->where("uid='$uid' and status < 6 ")->order('time desc')->select();
        //找到未完成订单的数量
        $order1 = $oOb->field("count(*) as num")->where("uid='$uid' and status=1")->select();
        $num = $order1[0]['num'];
        $this->assign("num", $num);
        //根据产品id,查询产品相关信息,由于设置订单不会太多，所以循环查询一下
        foreach ($orderArr as &$order) {
            $pid = $order['pid'];
            $oObj = M("product");
            $product = $oObj->where("id=$pid ")->select();
            $order['title'] = $product[0]['title'];
            $order['content'] = $product[0]['content'];
            //$order['price']=$product[0]['price'];
            $order['store'] = $product[0]['store'];
            $order['type'] = $product[0]['type'];
												$u = '';
												if($product[0]['pctype'] == 2){
																$u = 'http://www.heimiwifi.com/m/';
												}
												$order['thumbimg'] = $u.$product[0]['thumbimg'];
            //echo $order['time'];
            //$order['time']=date('Y年m月d日 H:i',time($order['time']));
            //echo $order['time']; die; 
            $order['payType'] = $order['payType'] == 1 ? '?m=Pay&a=doalipay' : '?m=Payment&a=dotenpay';
            if ($order['status'] < 4) {
                $order['price'] = intval($order['price'] - $order['discount'] - $order['yhq']);
            }
        }
        // print_r($orderArr);
        if (isset($orderArr) && !empty($orderArr)) {
            $this->assign("orderArr", $orderArr);
        }
        $this->display();
    }

    function cardLog() {

        $userInfo = unserialize(stripcslashes($_COOKIE['userinfo']));
        //var_dump($userInfo);
        $uid = $userInfo['uid'];
        $CCID = $userInfo['CCID'];

        $coOb = M("cardorder");
        $coArr = $coOb->where("CCID='{$CCID}' and (status='3' or status='2')")->order("id desc")->select();
        //var_dump($coArr);
        if (isset($coArr) && !empty($coArr)) {

            $this->assign("cardLog", $coArr);
        }
        $this->display();
    }

    function dataWbd() {

        $userInfo = unserialize(stripcslashes($_COOKIE['userinfo']));
        $CCID = $userInfo['CCID']; //echo $CCID;die();
        if ($CCID == "") {
            $this->assign("tel", $userInfo['tel']);
            $this->display();
        }
    }

    function data() {
        $userInfo = unserialize(stripcslashes($_COOKIE['userinfo']));
        $CCID = $userInfo['CCID']; //echo $CCID;die();
        if ($CCID != "") {
            $this->assign("usertel", $userInfo['tel']);
            $this->assign("CCID", $CCID);
            $this->display();
        }
    }

    function daili() {

        $content = file_get_contents("http://api.heimiwifi.com/wifi/query/?tel=" . $_GET['tel']);
        ob_end_clean();
        $re = json_decode($content);
        $re1 = get_object_vars($re);
        $data = get_object_vars($re1['data']);
        //var_dump($data);
        echo $data['type'] . "," . $data['left'] . "," . $data['expiry'];
        //echo $data['total'];
    }

    function userInfo() {

        $userInfo = unserialize(stripcslashes($_COOKIE['userinfo']));
        //var_dump($userInfo);
        $uid = $userInfo['uid'];
        $mOb = new MemberModel;
        $arr = $mOb->getMember("id=$uid");
        if ($arr == null) {
            header("location:?m=Member&a=login");
        } else {
            //var_dump($arr[0]);
            $this->assign("userInfo", $arr[0]);
            $this->display();
        }
    }

    function isWeiXin() {

        if (strpos($_SERVER["HTTP_USER_AGENT"], ”MicroMessenger”)){
        echo "1";
    } else {
        echo "0";
    }
}

function order() {
    $yhqPrice = 0;
    if ($_POST['yhqPrice'] != "") {
        $yhqPrice = $_POST['yhqPrice'];
    }
    $this->assign("yhqPrice", $yhqPrice);
    $yhq = "00000000";
    if ($_POST['yhq'] != "") {
        $yhq = $_POST['yhq'];
    }
    $this->assign("yhq", $yhq);
    $payType = 1;
    if ($_POST['payType'] != "") {

        $payType = $_POST['payType'];
    }
    $this->assign("payType", $payType);

    $orderId = mt_rand("100000", "999999");
    $orderId = $orderId . time();
    $this->assign("orderId", $orderId);
    $userInfo = unserialize(stripcslashes($_COOKIE['userinfo']));
    $uid = $userInfo['uid'];
    //得到地址信息
    $addrOb = M("address");
    $addrArr = $addrOb->where("uid='$uid'")->order("id desc")->limit(1)->select();
    if (isset($addrArr) && !empty($addrArr)) {
        //$addr=$addrArr[0]['province'].$addrArr[0]['city'].$addrArr[0]['area'].$addrArr[0]['detail'];
        $proOb = M("province");
        $proArr = $proOb->where("provinceId={$addrArr[0]['province']}")->select();
        $province = $proArr[0]['province'];
        $citOb = M("city");
        $citArr = $citOb->where("cityId={$addrArr[0]['city']}")->select();
        //echo $citOb->getLastSql();
        $city = $citArr[0]['city'];
        $arOb = M("area");
        $arArr = $arOb->where("areaId={$addrArr[0]['area']}")->select();
        $area = $arArr[0]['area'];
        $addr = $province . $city . $area . $addrArr[0]['detail'];
        //var_dump($addr);
        $this->assign("addr", $addr);
        $this->assign("addrId", $addrArr[0]['id']);
        $this->assign("name", $addrArr[0]['name']);
        $this->assign("tel", $addrArr[0]['tel']);
    }
    $proInfo = unserialize(stripcslashes($_COOKIE['pArr']));
    $pid = $proInfo['pid']; //var_dump($proInfo);die(); 
    $num = $proInfo['num'];
    $mOb = new ProductModel;
    $pArr = $mOb->getDetail($pid);
    $sum = $pArr[0]['price'] * $num;
    $titArr = explode("+", $pArr[0]['title']);
    //var_dump($titArr);
    $data = $titArr[1];
    //var_dump($data);
    $this->assign("pArr", $pArr[0]);
    $this->assign("num", $num);
    $this->assign("data", $data);
    $this->assign("sum", $sum);
    $this->display();
}

function productBuyAjax() {
    $orderId = $_POST['k'];
    $pid = $_POST['p'];
    $ob = M("product");
    $re = $ob->query("update r_product set store=store-1 where id={$pid}");
    $oOb = M("order");
    $re1 = $oOb->query("update r_order set status=2 where orderId='{$orderId}'");
    if ($re !== false && $re1 !== false) {
        ob_end_clean();
        echo "1";
    } else {
        ob_end_clean();
        echo "0";
    }
}

function productBuySuccess() {

    $orderId = $_GET['k'];
    $pid = $_GET['p'];
    $ob = M("product");
    $re = $ob->query("update r_product set store=store-1 where id={$pid}");
    $oOb = M("order");
    $date = date("Y-m-d H:i:s");
    $re1 = $oOb->query("update r_order set status=2,updated_at='{$date}'  where orderId='{$orderId}' ");
    if ($re !== false && $re1 !== false) {

        $this->display("Member:changeOk");
    } else {
        $this->display("Member:changeFail");
    }
}

function orderSave() {//var_dump($_GET);die();
    $date = date("Y-m-d H:i:s");
    $yhq = $_GET['yhq'];
    if ($yhq == "00000000") {
        $yhqId = 0;
    } else {
        $yOb = M("yhq");
        $yArr = $yOb->where("code='$yhq' and status=1 and deadTime>'$date'")->select();
        $yhqId = $yArr[0]['id'];
    }
    $_GET['yhqId'] = $yhqId;
    $_GET['yhq'] = $_GET['yhqPrice'];
    unset($_GET['yhqPrice']);
    setcookie("orderId", $_GET['orderId'], time() + 24 * 3600, "/");
    $userinfo = unserialize(stripcslashes($_COOKIE['userinfo']));
    $uid = $userinfo['uid'];
    $proInfo = unserialize(stripcslashes($_COOKIE['pArr']));
    $pid = $proInfo['pid'];
    $num = $proInfo['num'];
    $addrOb = M("address");
    $addrArr = $addrOb->where("uid='$uid'")->order("id desc")->limit(1)->select();
    $addressId = $addrArr[0]['id'];
    $time = date("Y-m-d H:i:s");
    $_GET['uid'] = $uid;
    $_GET['pid'] = $pid;
    $_GET['num'] = $num;
    $_GET['address_id'] = $addressId;
    $_GET['time'] = $time;
    $_GET['updated_at'] = $time;
    $_GET['status'] = 1;

    $orderOb = M("order");
    $orderId = $_GET['orderId'];
    $oArr = $orderOb->where("orderId='{$orderId}'")->select();
    if (!isset($oArr) || empty($oArr)) {
        $re = $orderOb->data($_GET)->add(); //echo $orderOb->getLastSql();
        if ($re) {
            ob_end_clean();
            echo "1";
        }
    }
}

function orderOk() {

    $userinfo = unserialize(stripcslashes($_COOKIE['userinfo']));
    $uid = $userinfo['uid'];
    $mob = M("order");
    $arr = $mob->where("uid={$uid}")->order("id desc")->limit(1)->select();
    if ($arr == null) {
        header("location:?m=Member&a=address");
    } else {
        $this->assign("orderId", $arr[0]['id']);
        $this->assign("sum", $_GET['sum']);
        $this->display();
    }
}

function threeAjax() {

    $fid = $_GET['fid'];
    $cOb = M("city");
    $cArr = $cOb->where("father=$fid")->select();
    if ($cArr != null) {
        foreach ($cArr as $v) {
            ob_end_clean();
            echo "<option value=" . $v['cityID'] . ">" . $v['city'] . "</option>";
        }
    }
}

function threeAjaxArea() {

    $fid = $_GET['fid'];
    $cOb = M("area");
    $cArr = $cOb->where("father=$fid")->select();
    if ($cArr != null) {
        foreach ($cArr as $v) {

            ob_end_clean();
            echo "<option value=" . $v['areaID'] . ">" . $v['area'] . "</option>";
        }
    }
}

function addressAdd() {

    $pOb = M("province");
    $pArr = $pOb->select(); //]var_dump($pArr);die();
    $this->assign("pArr", $pArr);
    $this->display("Member:address");
}

/*
  function  addressAddSave(){

  $userinfo=unserialize(stripcslashes($_COOKIE['userinfo']));
  $uid=$userinfo['uid'];
  $_POST['uid']=$uid;
  $addOb=M("address");
  $re=$addOb->data($_POST)->add();
  if($re){
  $this->assign("jumpUrl","?m=Member&a=address");
  $this->assign("waitSeconds","3");
  $this->success();
  }else{
  $this->assign("jumpUrl","?m=Member&a=addressAdd");
  $this->assign("waitSeconds","3");
  $this->error();
  }
  } */

function addressAddSave() {

    $userinfo = unserialize(stripcslashes($_COOKIE['userinfo']));
    if (empty($userinfo) || !is_array($userinfo)) {
        echo json_encode(array("error" => 1, "msg" => "请您登录后操作！"));
        exit();
    }
    $addressinfo = array();
    $addressinfo['name'] = trim($_POST['name']);
    $addressinfo['tel'] = intval($_POST['tel']);
    $addressinfo['province'] = $_POST['province'];
    $addressinfo['city'] = $_POST['city'];
    $addressinfo['area'] = $_POST['area'];
    $addressinfo['detail'] = $_POST['detail'];
    $addressinfo['mailCode'] = $_POST['mailcode'];
    $addressinfo['uid'] = $userinfo['uid'];
    if (empty($addressinfo['name']) || empty($addressinfo['tel']) || empty($addressinfo['province']) || empty($addressinfo['city']) || empty($addressinfo['area']) || empty($addressinfo['detail']) || empty($addressinfo['mailCode']) || empty($addressinfo['uid'])) {
        echo json_encode(array("error" => 1, "msg" => "您提交的数据不合法，请刷新页面重试！"));
        exit();
    }

    $addOb = M("address");
    $uid = $userinfo['uid'];
    $addrUid = $addOb->where("uid = '{$uid}'")->select();
    if (isset($addrUid) && !empty($addrUid)) {
        echo json_encode(array("error" => 1, "msg" => "您已请填写过地址信息，不能重复填写！"));
        exit();
    }
    $re = $addOb->data($addressinfo)->add();
    //$sql= $addOb -> getLastSql();
    //echo json_encode(array("error"=>0,"msg"=>$sql));
    if ($re) {
        echo json_encode(array("error" => 0, "msg" => "您提交的数据不合法，请刷新页面重试！"));
        exit();
    } else {
        echo json_encode(array("error" => 1, "msg" => "添加失败！"));
        exit();
    }
}

function addressSetcookie() {

    setcookie("addressId", serialize($_GET), time() + 24 * 3600, "/");
    //setcookie("userinfo",serialize($_GET),time()+24*3600,"/");               
}

function addressUpd() {
    $id = $_GET['id'];
    $addrOb = M("address");
    $arr = $addrOb->where("id={$id}")->select(); //var_dump($arr);echo "<br>";
    $pOb = M("province");
    $pArr = $pOb->select(); //var_dump($pArr);die();
    //var_dump($arr[0]['province']);
    $aOb = M("area");
    if ($arr[0]['province'] == "110000") {
        $cArr = array(array("cityID" => "110000", "city" => "北京市"));
        $aArr = $aOb->where("father=110100 or father=110200")->select();
    } elseif ($arr[0]['province'] == "120000") {
        $cArr = array(array("cityID" => "120000", "city" => "天津市"));
        $aArr = $aOb->where("father=120100 or father=120200")->select();
    } elseif ($arr[0]['province'] == "310000") {
        $cArr = array(array("cityID" => "310000", "city" => "上海市"));
        $aArr = $aOb->where("father=310100 or father=310200")->select();
    } elseif ($arr[0]['province'] == "500000") {
        $cArr = array(array("cityID" => "500000", "city" => "重庆市"));
        $aArr = $aOb->where("father=500100 or father=500200")->select();
    } else {
        $cOb = M("city");
        $cArr = $cOb->where("father={$arr[0]['province']}")->select();

        $aArr = $aOb->where("father={$arr[0]['city']}")->select();
    }
    $this->assign("pArr", $pArr);
    $this->assign("cArr", $cArr);
    $this->assign("aArr", $aArr);
    $this->assign("addrArr", $arr[0]);
    $this->display();
}
/*
 * $selfid:5:预约地址id；
 */
public function getAddr($uid,$selfid){
				$addr = M("address");
				$r=$addr->where("uid='{$uid}' and selfid={$selfid}")->select();
//				var_dump($r);
				return $r;
//				echo count($r);
//				var_dump($r);
}
function addressYuyue() {
    $id = $_GET['id'];
    $addrOb = M("address");
    $arr = $addrOb->where("id={$id}")->select(); //var_dump($arr);echo "<br>";
    $pOb = M("province");
    $pArr = $pOb->select(); //var_dump($pArr);die();
    //var_dump($arr[0]['province']);
    $aOb = M("area");
    if ($arr[0]['province'] == "110000") {
        $cArr = array(array("cityID" => "110000", "city" => "北京市"));
        $aArr = $aOb->where("father=110100 or father=110200")->select();
    } elseif ($arr[0]['province'] == "120000") {
        $cArr = array(array("cityID" => "120000", "city" => "天津市"));
        $aArr = $aOb->where("father=120100 or father=120200")->select();
    } elseif ($arr[0]['province'] == "310000") {
        $cArr = array(array("cityID" => "310000", "city" => "上海市"));
        $aArr = $aOb->where("father=310100 or father=310200")->select();
    } elseif ($arr[0]['province'] == "500000") {
        $cArr = array(array("cityID" => "500000", "city" => "重庆市"));
        $aArr = $aOb->where("father=500100 or father=500200")->select();
    } else {
        $cOb = M("city");
        $cArr = $cOb->where("father={$arr[0]['province']}")->select();

        $aArr = $aOb->where("father={$arr[0]['city']}")->select();
    }
    $this->assign("pArr", $pArr);
    $this->assign("cArr", $cArr);
    $this->assign("aArr", $aArr);
    $this->assign("addrArr", $arr[0]);
    $this->display();
}

/*
 * 判断是否已存在此用户
 */

private function judgeHave($uid) {
    $n = 0;
    $addrOb = M("address");
    $uid = mysql_escape_string($uid);
    $sql = 'select id from __TABLE__ where uid="' . $uid . '"';
    $r = $addrOb->query($sql);
    if (isset($r[0])) {
        $n = $r[0]['id'];
    }
    return $n;
//    var_dump($r);
}

function addressUpdSave() {
    //var_dump($_POST); 
    $addrOb = M("address");
    $userinfo = unserialize(stripcslashes($_COOKIE['userinfo']));
    $uid = $userinfo['uid'];
    $_POST['uid'] = $uid;
//    var_dump($_POST);
//    $re=$addrOb->data($_POST)->add();
//    if($this->judgeHave($uid) > 0){
////        这段是判断是否有记录了，有了就更改，后来发现bug，如果买了一个，又预约一个且预约地址修改了，就完蛋了，所以之差新记录。
//        $name = mysql_escape_string($_POST['name']);
//        $tel = mysql_escape_string($_POST['tel']);
//        $province = mysql_escape_string($_POST['province']);
//        $city = mysql_escape_string($_POST['city']);
//        $area = mysql_escape_string($_POST['area']);
//        $detail = mysql_escape_string($_POST['detail']);
//        $mailCode = mysql_escape_string($_POST['mailCode']);
//        $sql="update __TABLE__ set `name`='{$name}',tel='{$tel}',province='{$province}',city='{$city}',area='{$area}',detail='{$detail}',mailCode='{$mailCode}' where uid='{$uid}'";
//        $addrOb->execute($sql);
//    }else{
    $re = $addrOb->data($_POST)->add();
//    }
//    exit;
    if ($re !== false) {
        $this->assign("jumpUrl", "?m=Member&a=order");
        $this->assign("waitSeconds", "3");
        $this->success();
    } else {
        $this->assign("jumpUrl", "?m=Member&a=addressUpd&id=" . $_GET['id']);
        $this->assign("waitSeconds", "3");
        $this->error();
    }
}
function  addressYySave(){
    //var_dump($_POST); 
				$addrid = -1;
    $addrOb=M("address");
    $userinfo=unserialize(stripcslashes($_COOKIE['userinfo']));
    $uid=$userinfo['uid'];
    $_POST['uid'] = $uid;
//    var_dump($_POST);
//    $re=$addrOb->data($_POST)->add();
				$addr = $this->getAddr($uid,	5);
        $name = mysql_escape_string($_POST['name']);
        $tel = mysql_escape_string($_POST['tel']);
        $province = mysql_escape_string($_POST['province']);
        $city = mysql_escape_string($_POST['city']);
        $area = mysql_escape_string($_POST['area']);
        $detail = mysql_escape_string($_POST['detail']);
        $mailCode = mysql_escape_string($_POST['mailCode']);
    if(count($addr) > 0){
//        selfid=5,是预约专用字段；
								$addrid = $addr[0]['id'];
        $sql="update __TABLE__ set `name`='{$name}',tel='{$tel}',province='{$province}',city='{$city}',area='{$area}',detail='{$detail}',mailCode='{$mailCode}' where uid='{$uid}' and selfid=5";
        $re = $addrOb->execute($sql);
    }else{
        $sql="insert into __TABLE__ set `name`='{$name}',tel='{$tel}',province='{$province}',city='{$city}',area='{$area}',detail='{$detail}',mailCode='{$mailCode}',uid='{$uid}',selfid=5";
        $re = $addrOb->execute($sql);
    }
//    exit;
    if($re !== false){
								$rs = M('reserve');
								$ctime = date('Y-m-d H:i:s');
								$pid = $_POST['pid'];
								$batch = $_POST['batch'];
								if($addrid == -1){
												$addr = $this->getAddr($uid,	5);
												$addrid = $addr[0]['id'];
								}
								$sql = "insert into __TABLE__ set uid='{$uid}',pid='{$pid}',batch='{$batch}',createTime='{$ctime}',updateTime='{$ctime}',address_id='{$addrid}'";
								$rs->execute($sql);
//								echo $rs->getLastSql();
//								exit;
        $this->assign("jumpUrl","?m=Member&a=yyok");
        $this->assign("waitSeconds","3");
        $this->success();
    }else{
        $this->assign("jumpUrl","?m=Member&a=addressYuyue&id=".$_GET['id']);
        $this->assign("waitSeconds","3");
        $this->error();
    }
}
public function yyok(){
//				echo 'yyok';
				$this->display();
}
function paySetcookie() {

    setcookie("payMethodId", serialize($_GET), time() + 24 * 3600, "/");
    $pid = unserialize(stripcslashes($_COOKIE['pArr']));
    ob_end_clean();
    echo $pid['type'];
}

function isBd() {

    $userinfo = unserialize(stripcslashes($_COOKIE['userinfo']));
    $uid = $userinfo['uid'];
    $tel = $userinfo['tel'];
    $mOb = M("memberbd");
    $mArr = $mOb->where("uid='$uid'")->select();
    if ($mArr[0]['CCID'] != "") {
        ob_end_clean();
        echo $tel . "," . $mArr[0]['CCID'];
    } else {
        ob_end_clean();
        echo "0";
    }
}

function isHard() {

    $add = $_GET['add'];
    //$add= '10090010050';
    $shOb = M("simhard");
    $arr = $shOb->where("CCID='$add'")->select();
    //ar_dump(isset($arr));
    if (!isset($arr) || empty($arr)) {

        echo "0";
    } else {
        echo "1";
    }
}

/*
  function  bdMember(){

  $CCID=$_GET['CCID'];
  $userinfo=unserialize(stripcslashes($_COOKIE['userinfo']));
  $uid=$userinfo['uid'];
  //var_dump($userinfo);
  $mOb=M("memberbd");
  $re=$mOb->query("update r_memberbd set CCID='$CCID' where uid='$uid'");
  //echo $mOb->getLastSql();
  //var_dump($re);
  if($re!==false){
  $userinfo['CCID']=$CCID;
  setcookie("userinfo",serialize($userinfo),time()+24*3600,"/");
  ob_end_clean();
  echo "1";
  }
  }
 */

function bdMember() {

    $CCID = $_GET['CCID'];
    $shOb = M("simhard");
    $arr = $shOb->where("CCID='$CCID'")->select();

    if (!isset($arr) || empty($arr)) {
        ob_end_clean();
        echo 0;
        exit();
    }
    $userinfo = unserialize(stripcslashes($_COOKIE['userinfo']));
    $uid = $userinfo['uid'];
    //var_dump($userinfo);
    $mOb = M("memberbd");
    $re = $mOb->query("update r_memberbd set CCID='$CCID' where uid='$uid'");
    //echo $mOb->getLastSql();
    //var_dump($re);
    if ($re !== false) {
        $userinfo['CCID'] = $CCID;
        setcookie("userinfo", serialize($userinfo), time() + 24 * 3600, "/");
        ob_end_clean();
        echo 1;
        exit();
    } else {
        echo 0;
        exit();
    }
}

function updMemHard() {

    $CCID = $_GET['CCID'];
    $userinfo = unserialize(stripcslashes($_COOKIE['userinfo']));
    $uid = $userinfo['uid'];
    $mOb = M("memberbd");
    $re = $mOb->query("update r_memberbd set CCID='$CCID' where uid='$uid'");
    if ($re !== false) {

        $userinfo['CCID'] = $CCID;
        setcookie("userinfo", serialize($userinfo), time() + 24 * 3600, "/");
        ob_end_clean();
        echo "1";
    }
}

function order_card() {
	echo '暂停充值服务';
	exit;
    $yhqPrice = 0;
    if ($_POST['yhqPrice'] != "") {

        $yhqPrice = $_POST['yhqPrice'];
    }
    $this->assign("yhqPrice", $yhqPrice);
    $yhq = "00000000";
    if ($_POST['yhq'] != "") {

        $yhq = $_POST['yhq'];
    }
    $this->assign("yhq", $yhq);
    $CCID = "";
    if ($_POST['CCID'] != "") {

        $CCID = $_POST['CCID'];
    }
    $this->assign("CCID", $CCID);
    $payType = 1;
    if ($_POST['payType'] != "") {

        $payType = $_POST['payType'];
    }
    $this->assign("payType", $payType);
    $referer = $_SERVER['HTTP_REFERER'];
    $this->assign("referer", $referer);
    $orderId = mt_rand(100000, 999999);
    $time = time();
    $orderId = $orderId . $time;
    $pArr = unserialize(stripcslashes($_COOKIE['pArr']));
    $pid = $pArr['pid'];
    $pcOb = M("card");
    $arr = $pcOb->where("id=$pid")->select();
    $this->assign("session", $_SESSION["sess_" . time()]);
    $this->assign("pArr", $arr[0]);
    $this->assign("orderId", $orderId);
    $this->display();
}

function order_cardwx() {
    echo "充值服务暂停";die;
    $wxid = $_GET['wxid'];
    $mm = M('memberbd');
    $wxsql = 'select CCID from __TABLE__ where weixinid="' . $wxid . '"';
    $wxr = $mm->query($wxsql);
//    echo $mm->getLastSql();
//    var_dump($wxr);
    if (isset($wxr[0])) {
        $wxr1 = $wxr[0];
        //$this->assign("ccid", $wxr1['CCID']);
        $this->assign("ccid","");
    }
    $yhqPrice = 0;
    if ($_POST['yhqPrice'] != "") {

        $yhqPrice = $_POST['yhqPrice'];
    }
    $this->assign("yhqPrice", $yhqPrice);
    $yhq = "00000000";
    if ($_POST['yhq'] != "") {

        $yhq = $_POST['yhq'];
    }
    $this->assign("yhq", $yhq);
    $CCID = "";
    if ($_POST['CCID'] != "") {

        $CCID = $_POST['CCID'];
    }
    $this->assign("CCID", $CCID);
    $payType = 1;
    if ($_POST['payType'] != "") {

        $payType = $_POST['payType'];
    }
    $this->assign("payType", $payType);
    $referer = $_SERVER['HTTP_REFERER'];
    $this->assign("referer", $referer);
    $orderId = mt_rand(100000, 999999);
    $time = time();
    $orderId = $orderId . $time;
    $pArr = unserialize(stripcslashes($_COOKIE['pArr']));
    $pid = $pArr['pid'];
    $pcOb = M("card");
    $arr = $pcOb->where("id=$pid")->select();
    $this->assign("session", $_SESSION["sess_" . time()]);
    $this->assign("pArr", $arr[0]);
    $this->assign("orderId", $orderId);
    $this->assign("wxid", $wxid);
    $this->display();
}

function order_cardWap() {

    $yhqPrice = 0;
    if ($_POST['yhqPrice'] != "") {

        $yhqPrice = $_POST['yhqPrice'];
    }
    $this->assign("yhqPrice", $yhqPrice);
    $yhq = "00000000";
    if ($_POST['yhq'] != "") {

        $yhq = $_POST['yhq'];
    }
    $this->assign("yhq", $yhq);
    $CCID = "";
    if ($_POST['CCID'] != "") {

        $CCID = $_POST['CCID'];
    }
    $this->assign("CCID", $CCID);
    $payType = 1;
    if ($_POST['payType'] != "") {

        $payType = $_POST['payType'];
    }
    $this->assign("payType", $payType);
    $referer = $_SERVER['HTTP_REFERER'];
    $this->assign("referer", $referer);
    $orderId = mt_rand(100000, 999999);
    $time = time();
    $orderId = $orderId . $time;
    $pArr = unserialize(stripcslashes($_COOKIE['pArr']));
    $pid = $pArr['pid'];
    $pcOb = M("card");
    $arr = $pcOb->where("id=$pid")->select();
    $this->assign("session", $_SESSION["sess_" . time()]);
    $this->assign("pArr", $arr[0]);
    $this->assign("orderId", $orderId);
    $this->display();
}

function simhardType() {
    $CCID = trim($_POST['CCID']);
    $pArr = unserialize($_COOKIE['pArr']);
    $pid = trim($pArr['pid']);
    //$CCID = '10090010050/';
    if ($CCID != "") {
        $shOb = M("simhard");
        $shArr = $shOb->where("CCID='{$CCID}'")->select();
        if (isset($shArr) && !empty($shArr)) {
            $type = $shArr[0]['type'];
            $deadTime = $shArr[0]['deadTime'];
            $now = date("Y-m-d");
            //得到剩余流量
            $content = file_get_contents("http://api.heimiwifi.com/wifi/query/?tel=" . $CCID);
            ob_end_clean();
            $re = json_decode($content);
            $re1 = get_object_vars($re);
            $data = get_object_vars($re1['data']);
            //var_dump($data);
            $left = $data['left'];
            //$left =0 ;
            $arrM = array("1" => "31", "2" => "28", "3" => "31", "4" => "30", "5" => "31", "6" => "30", "7" => "31", "8" => "31", "9" => "30", "10" => "31", "11" => "30", "12" => "31");
            $y = date("Y");
            $m = date("n");
            $monthEnd = $y . "-" . $m . "-" . "01";  //截止日期当月月第一天 
            if ($pid != "") {
                $cOb = M("card");
                $cArr = $cOb->where("id=$pid")->select();
                if (isset($cArr) && !empty($cArr)) {
                    $pType = $cArr[0]['type'];
                } else {
                    echo json_encode(array("error" => "4", "msg" => "您所购买的充值卡不存在，请重新进行购买。"));
                    return;
                }
            } else {
                echo json_encode(array("error" => "5", "msg" => "您所购买的充值卡不存在，请重新进行购买。"));
                return;
            }
            echo "0";
            /*
              //如果是年卡用户且流量没有用完则不能购买
              if($left > 0 && $deadTime > $now && $type == 3){

              echo json_encode(array("error"=>"1","msg"=>"不好意思,您的包年流量用完之后，才能进行叠加充值。"));
              return;

              }elseif( $type == 2 && $pType == 2){
              if($left > 0 && $deadTime > $now){
              echo json_encode(array("error"=>"2","msg"=>"不好意思，您当前设备号的流量类型暂不支持叠加此流量包。"));
              return;
              }elseif($now < $monthEnd){

              echo json_encode(array("error"=>"3","msg"=>"不好意思，您当前设备号的流量类型暂不支持叠加此流量包。"));
              return;
              }else{
              echo "0";
              }
              }else{

              echo "0";
              } */
        } else {
            echo json_encode(array("error" => "6", "msg" => "设备号不存在"));
            return;
        }
    } else {
        echo json_encode(array("error" => "7", "msg" => "设备号不能为空"));
        return;
    }
}

function yhqForm() {
    $referer = $_SERVER['HTTP_REFERER'];
    $this->assign("referer", $referer);
    if (isset($_GET['CCID'])) {
        $CCID = $_GET['CCID'];
    }
    $payType = $_GET['payType'];
    $this->assign("CCID", $CCID);
    $this->assign("payType", $payType);
    $this->display();
}

function yhqFormWap() {
    $referer = $_SERVER['HTTP_REFERER'];
    $this->assign("referer", $referer);
    if (isset($_GET['CCID'])) {
        $CCID = $_GET['CCID'];
    }
    $payType = $_GET['payType'];
    $this->assign("CCID", $CCID);
    $this->assign("payType", $payType);
    $this->display();
}

function cardOrderInsert() {
    echo "充值服务暂停";die;
    //$referer=$_GET['referer'];
    //echo $bo=strpos($referer,"?m=Product");
    //if($bo!==false){
    //unset($_GET['referer']);  
    $date = date("Y-m-d H:i:s");
    $yhq = $_GET['yhq'];
    if ($yhq == "00000000") {
        $yhqId = 0;
    } else {
        $yOb = M("yhq");
        $yArr = $yOb->where("code='$yhq' and status=1 and deadTime>'$date'")->select();
        if(isset($yArr) && !empty($yArr)){
            $yhqId = $yArr[0]['id'];
            $yOb -> query("update r_yhq set status=2,updateTime='{$date}' where id={$yhqId}");
        }else{
            $yhqId = 0;
        }
    }
    $_GET['yhqId'] = $yhqId;

    setcookie("orderId", $_GET['orderId'], time() + 24 * 3600, "/");
    $pArr = unserialize(stripcslashes($_COOKIE['pArr']));
    $pid = $pArr['pid'];
    $cOb = M("card");
    $cArr = $cOb->where("id={$pid}")->select();
    $_GET['title'] = $cArr[0]['title'];
    $num = $pArr['num'];
    $userinfo = unserialize(stripcslashes($_COOKIE['userinfo']));
    $uid = $userinfo['uid'];
    if ($uid == null) {
        $uid = 0;
    }

    $status = 1;
    $date = date("Y-m-d H:i:s");
    $_GET['pid'] = $pid;
    $_GET['num'] = $num;
    $_GET['uid'] = $uid;
    $_GET['status'] = $status;
    $_GET['time'] = $date;
    $coOb = M("cardorder");
    $orderId = $_GET['orderId'];
    $coArr = $coOb->where("orderId='{$orderId}'")->select();
    if (!isset($coArr) || empty($coArr)) {
        $re = $coOb->data($_GET)->add();
        //echo $coOb->getLastSql();
        if ($re) {
            ob_end_clean();
            echo "1";
        }
    }
}

function cardBuyAjax() {


    $date = date("Y-m-d H:i:s");
    $orderId = $_POST['k'];
    $pid = $_POST['p'];
    $cOb = M("card");
    $re = $cOb->query("update r_card set store=store-1 where id={$pid}");
    $coOb = M("cardorder");
    $re1 = $coOb->query("update r_cardorder set status=2,updateTime='{$date}' where orderId='{$orderId}'");
    $cArr = $cOb->where("id={$pid}")->select();
    /*
      if($cArr[0]['validTime']=="0"){
      $type=1;
      }else{
      $type=2;
      }
     */
    $type = $cArr[0]['type'];
    $coArr = $coOb->where("orderId='{$orderId}'")->select();
    $yhqId = $coArr[0]['yhqId'];
    if ($yhqId != 0) {
        $yOb = M("yhq");
        $yOb->query("update r_yhq set status=2,updateTime='{$date}'  where id={$yhqId}");
    }
    $CCID = $coArr[0]['CCID'];
    //$shOb=M("simhard");
    //$shArr=$shOb->where("CCID='{$CCID}'")->select();
    $buyedOb = M("buyedcard");
    $buyedArr = array("CCID" => $CCID, "type" => $type, "beginTime" => $cArr[0]['beginTime'], "endTime" => $cArr[0]['endTime'], "data" => $cArr[0]['data']);
    $re2 = $buyedOb->data($buyedArr)->add();
    if ($re !== false && $re1 !== false) {

        ob_end_clean();
        echo "1";
    } else {
        ob_end_clean();
        echo "0";
    }
}

function changeOk() {
    $this->display();
}

function changeFail() {
    $this->display();
}

function myorder() {

    $ordtype = $_GET['ordtype'];
    if ($ordtype == "payed") {//echo "pay";
        header("location:?m=Member&a=paySuccess");
    } else if ($ordtype == "unpay") {
        header("location:?m=Member&a=payFail");
    }
}

function paySuccess() {

    $this->display("Member:payOk");
}

function payFail() {
    $this->display("Member:payFail");
}

function yhq() {
    $yhq = $_GET['yhq'];
    $yOb = M("yhq");
    $date = date("Y-m-d H:i:s");
    $yArr = $yOb->where("status=1 && code='{$yhq}' && deadTime>'{$date}'")->select();
    //echo $yOb->getLastSql();
    if (isset($yArr) && !empty($yArr)) {
        ob_end_clean();
        echo $yArr[0]['inPrice'];
    } else {
        ob_end_clean();
        echo "0";
    }
}

function yhqStatus() {

    $yhq = $_GET['yhq'];
    $yOb = M("yhq");
    $date = date("Y-m-d H:i:s");
    $re = $yOb->query("update r_yhq set status=2,updateTime='{$date}' where code='$yhq'");
}

function receiveInfo() {

    $pOb = M("province");
    $pArr = $pOb->select();
    //var_dump($pArr);die();
    $this->assign("pArr", $pArr);
    $this->display();
}

function isLogin() {

    $userinfo = unserialize(stripcslashes($_COOKIE['userinfo']));
    if (isset($userinfo) && !empty($userinfo)) {
        ob_end_clean();
        echo "1";
    }
}

function isBuy() {

    $userinfo = unserialize(stripcslashes($_COOKIE['userinfo']));
    $uid = $userinfo['uid'];
    $oOb = M("order");
    $oArr = $oOb->where("  status='2' or uid='$uid' and  status='3'")->select();
    if (isset($oArr) && !empty($oArr)) {
        ob_end_clean();
        echo "1";
    }
}

function reserveAjax() {
    session_start();
    $verify = $_SESSION['verify'];
    if ($verify == md5($_GET['verify'])) {
        ob_end_clean();
        echo "1";
    }
}

function receiveSave() {
    session_start();
    $verify = $_SESSION['verify'];
    // if($verify==$_POST['verify']){
    $userinfo = unserialize(stripcslashes($_COOKIE['userinfo']));
    $uid = $userinfo['uid'];
    $_POST['uid'] = $uid;
    //$reOb=M("reserve");
    $date = date("Y-n-d H:i:s");
    //$batch=1;  //预约批次
    //$arr=array("uid"=>$_POST['uid'],"createTime"=>$date,"batch"=>$batch);
    //$re=$reOb->data($arr)->add();
    $addrOb = M("address");
    $addrArr = array("name" => "{$_POST['name']}", "tel" => "{$_POST['tel']}", "mail" => "{$_POST['mail']}", "province" => "{$_POST['province']}", "city" => "{$_POST['city']}", "area" => "{$_POST['area']}", "detail" => "{$_POST['detail']}", "mailCode" => "{$_POST['mailCode']}", "uid" => $uid);
    $re1 = $addrOb->data($addrArr)->add();
    if ($re1) {

        $this->assign("jumpUrl", "?m=Member&a=order");
        $this->assign("waitSeconds", "3");
        $this->success();
    } else {
        $this->assign("jumpUrl", "?m=Product&a=detail_buy&id=1");
        $this->assign("waitSeconds", "3");
        $this->error();
    }
    //}else{
    //    header("location:?m=Member&a=reserveInfo");  
    // }
}

function isAddress() {


    $userinfo = unserialize(stripcslashes($_COOKIE['userinfo']));
    $uid = $userinfo['uid'];
    $adOb = M("address");
    $adArr = $adOb->where("uid='$uid'")->select();
    if (isset($adArr) && !empty($adArr)) {
        echo "1";
    }
}

//取消订单
function orderCancel() {
    $id = $_GET['id'];
    $yhq = $_GET['yhq'];
    //print_r($_GET);die;
    $yOb = M("order");
    $date = date("Y-m-d H:i:s");
    $re = $yOb->query("update r_order set status=5,updated_at='{$date}' where id='$id'");
    //如果优惠券已经使用
    if (!empty($yhq)) {
        $yObj = M("yhq");
        $yObj->query("update r_yhq set status=1 where id='$yhq'");
    }
    echo "1";
}

//支付订单
function orderPay() {
    //print_r($_GET);
    $orderId = $_GET['id'];
    //查询订单信息
    $objOrder = M("order");
    $orderInfo = $objOrder->where("orderId=$orderId ")->select();
    //print_r($orderInfo); die;

    $this->assign("oArr", $orderInfo[0]);
    $this->assign("orderId", $orderId);
    //优惠券价格
    $yhqPrice = $orderInfo[0]['yhq'];
    $this->assign("yhqPrice", $yhqPrice);

    $yhq = $orderInfo[0]['yhqId'];
    $this->assign("yhq", $yhq);
    //ccid	
    $CCID = $orderInfo[0]['CCID'];
    $this->assign("CCID", $CCID);
    //支付类型
    $this->assign("payType", 1);
    $pid = $orderInfo[0]['pid'];
    $aid = $orderInfo[0]['address_id'];
    //查询产品信息
    $objProduct = M("product");
    $productInfo = $objProduct->where("id=$pid ")->select();
    //print_r($productInfo);
    $this->assign("pArr", $productInfo[0]);
    // $this->assign("price",$productInfo[0]);
    //查询地址信息
    $objAddress = M("address");
    $addressInfo = $objAddress->where("id=$aid ")->select();
    //print_r($addressInfo);
    if (isset($addressInfo) && !empty($addressInfo)) {
        //$addr=$addrArr[0]['province'].$addrArr[0]['city'].$addrArr[0]['area'].$addrArr[0]['detail'];
        $proOb = M("province");
        $proArr = $proOb->where("provinceId={$addressInfo[0]['province']}")->select();
        $province = $proArr[0]['province'];
        $citOb = M("city");
        $citArr = $citOb->where("cityId={$addressInfo[0]['city']}")->select();
        $city = $proArr[0]['city'];
        $arOb = M("area");
        $arArr = $arOb->where("areaId={$addressInfo[0]['area']}")->select();
        $area = $arArr[0]['area'];
        $addr = $province . $city . $area . $addressInfo[0]['detail'];
        $this->assign("addr", $addr);
        $this->assign("addrId", $addressInfo[0]['id']);
        $this->assign("name", $addressInfo[0]['name']);
        $this->assign("tel", $addressInfo[0]['tel']);
    }
    $sum = intval($orderInfo[0]['price'] - $orderInfo[0]['discount'] - $yhqPrice);
    $this->assign("sum", $addressInfo[0]['tel']);
    $this->display();
}

}
