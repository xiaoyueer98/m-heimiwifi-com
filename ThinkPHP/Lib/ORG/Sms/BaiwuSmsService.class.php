<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of BaiwuSmsService
 *
 * @author peter
 */
class BaiwuSmsService extends SmsService {

    //初始化短信配置
    public function getSmsConfig($mobileNumber, $message) {
        $userData = array(
            'corp_id' => C('sms_corp_id'),
            'corp_pwd' => C('sms_corp_pwd'),
            'corp_service' => C('sms_corp_service'),
            'mobile' => $mobileNumber,
            'msg_content' => $message,
        );
        return $userData;
    }

    //发送一条短信
    public function sendSMS($mobileNumber, $message) {
        $message = iconv("UTF-8", "GBK", $message);
        $userData = $this->getSmsConfig($mobileNumber, $message);
        $result = '';
        //增加手机号和短信息内容
        try {
            $result = $this->postMethod(C('sms_url'), $userData);
        } catch (Exception $ex) {
            $result = $ex->getMessage();
        }
        return $this->wrongNumberChange($result);
    }

    //转换错误码值成唯一
    public function wrongNumberChange($result) {
        //baiwu接口返回值判断
        if ($result == '0#1') {
            return $this->SEND_SUCCESS;
        } else if ($result == '100') {
            return $this->SEND_ARREARS;
        } else {
            return $this->SEND_FAILED;
        }
    }

}
