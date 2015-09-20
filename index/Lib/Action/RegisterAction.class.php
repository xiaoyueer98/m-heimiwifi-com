<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Register
 * 该类主要完成了注册操作
 * @author peter
 */
class RegisterAction extends MainAction {

    //显示注册页面
    function index() {
        $this->display('register');
    }

    //提交用户资料
    function saveRegisterInformation() {
        $util = new UtilsModel();
        $userData = array('telephone' => $_POST['tel'], 'password' => $_POST['password'], 'confirmPwd' => $_POST['confirmPwd'], 'phoneCode' => $_POST['phoneCode']);
        //验证用户提交参数
        $validateResult = $util->validateParameteriIsNull($userData);
        //如果用户提交注册缺少必要的信息，则不允许注册，给出必要的提示信息，并将信息回填
        if (!$validateResult) {
            $this->errorSendRedict("缺少必填项", "register", $userData);
        } else {
            //验证用户提交信息是否合法
            $this->validateParameterIsValid($userData);
            //验证填写的注册码是否和Cookie中存在的是否一致，不一致则返回到注册页面
            $validateResult = true;
//                    $this->validateSMSCode($_POST['phoneCode']);
            if (!$validateResult) {
                $this->errorSendRedict("验证码错误", "register", $userData);
            } else {
                //验证用户手机号是否被注册过了
                $member = new MemberpcModel();
                $validateResult = $member->checkPhoneNumberExists($_POST['tel']);
                if (!$validateResult) {
                    $this->errorSendRedict("该手机号已被注册", "register", $userData);
                    exit();
                } else {
                    $validateResult = $member->saveMemberData($userData);
                    if (!$validateResult) {
                        $this->errorSendRedict("注册失败", "register", $userData);
                        exit();
                    } else {
                        //注册成功跳转到登录页面
//                        $this->successsendRedict("提示信息", "恭喜您，注册成功", "0", "?m=Index", "登陆页");
																								$this->redirect('Index/index');
                    }
                }
            }
        }
    }

    //
    /*
     * 发送一个验证码到手机，并将Cookie信息写入本地
     * 1 正常
     * 2 该手机号码已注册
     * 0 短信接口异常
     * -1 手机号码错误 
     */
    function sendRegisterSms() {
        $util = new UtilsModel();
        $tel = $_POST['tel'];
        if ($util->validateMobileNumber($tel)) {
            //判断该手机号是否已经注册过
            if ($util->checkPhoneNumberIsRegister($tel)) {
                echo 2;
																exit;
            }
            $sms = new SmsModel();
            $number = $util->getRandomNumber();
            $result = $sms->registerSendSms($tel, $number);
            if ($result == "0#1") {
                $util->writeCookie($number);
                echo "1";
            } else {
                echo "0";
            }
        } else {
            echo "-1";
        }
    }

    //验证参数是否合法
    public function validateParameterIsValid($userDate) {
        //  $userData = array('telephone' => $_POST['tel'], 'password' => $_POST['password'], 'configPwd' => $_POST['confirmPwd'], 'phoneCode' => $_POST['phoneCode']);
        $phoneNumber = $userDate['telephone'];
        $pwd = $userDate['password'];
        $configPwd = $userDate['confirmPwd'];
        $util = new UtilsModel();
        //判断手机号是否合法
        if (!$util->validateMobileNumber($phoneNumber)) {
            $this->errorSendRedict("您输入手机号码不正确,正确格式 例如：15012344032", "register", $userDate);
            exit();
        }
        //判断密码长度是否正确
        if (strlen($pwd) > 20 || strlen($pwd) < 6) {
            $this->errorSendRedict("您输入的密码长度不合法,合法长度应该是6-20位之间。", "register", $userDate);
            exit();
        }
        if ($pwd != $configPwd) {
            $this->errorSendRedict("您输入的密码和确认密码不一致,", "register", $userDate);
            exit();
        }
    }

    //验证短信验证码是否正确
    public function validateSMSCode($code) {
        if (empty($code) || !isset($code) || trim($code) == "" || $code == null || empty($_COOKIE['yzm']) || !isset($_COOKIE['yzm'])) {
            return false;
        } else {
            //将用户的手机验证码进行MD5加密后取前四位
            $code = substr(md5($code), 0, 4);
            $arrCode = explode(",", $_COOKIE['yzm']);
            for ($i = 0; $i < count($arrCode); $i++) {
                if ($arrCode[$i] == $code) {
                    return true;
                }
            }
            return false;
        }
    }

}
