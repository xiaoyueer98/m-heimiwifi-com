
<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * 
 * 通用的帮助类
 *
 * @author peter
 */
class UtilsModel {

    //验证用户参数是否为空
    public function validateParameteriIsNull($userData) {
        foreach ($userData as $value) {
            if (trim($value) == "" || trim($value) == NULL) {
                return false;
            }
        }
        return true;
    }

    //验证是否是一个有效的电话号码
    public function validateMobileNumber($number) {
        if (preg_match("/^1{1}[0-9]{10}$/", trim($number))) {
            return true;
        }
        return false;
    }
				
				public function checkPhoneNumberIsRegister($tel){
								if(strlen($tel) > 10){
												$sql = "select id from __TABLE__ where usertel='{$tel}'";
												$rmbd = M('memberbd');
												$r=$rmbd->query($sql);
												return $r[0]['id'];
								}
								return 0;
				}

    //验证短信验证码是否正确
    public function writeCookie($validateCode) {
        $yzm = $validateCode;
        //加密写入cookie
        if (empty($_COOKIE['yzm']) || !isset($_COOKIE['yzm'])) {
            //echo $yzm ;
            setcookie("yzm", substr(md5($yzm), 0, 4), time() + 3600, "/");
        } else {
            setcookie("yzm", $_COOKIE['yzm'] . "," . substr(md5($yzm), 0, 4), time() + 3600, "/");
        }
    }

    //发送HTTP请求
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

    //获取一个1000-9999之内的随机数
    public function getRandomNumber() {
        return rand(1000, 9999);
    }

}
