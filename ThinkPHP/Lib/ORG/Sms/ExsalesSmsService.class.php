<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ExsalesSmsService
 * 短信实现类
 * @author peter
 */
class ExsalesSmsService extends SmsService {

    //获取短信配置信息
    public function getSmsConfig($mobileNumber, $message) {
        $userData = array("customerName" => C('sms_exsales_user'),
            "password" => C('sms_exsales_pwd'),
            "subAccount" => "",
            "jobName" => "",
            "mobilelist" => $mobileNumber,
            "message" => $message
        );
        return $userData;
    }

    //发送短消息
    public function sendSMS($mobileNumber, $message) {
        $userData = $this->getSmsConfig($mobileNumber, $message);
        $client = new SoapClient(C('sms_exsales_url'));
        $result = '';
        try {
            $result = $client->__soapCall("SmsService", array("SmsService" => $userData), NULL, NULL);
        } catch (Exception $ex) {
            $result = $ex->getMessage();
        }
        //将错误码转成唯一
        return $this->wrongNumberChange($result);
    }

    //处理返回值转换成统一格式
    public function wrongNumberChange($result) {
        $json = json_decode($result->SmsServiceResult);
        // $remoteID = $json->TaskId;
        $message = $json->Message;
        if ($message == "请求被正常接收") {
            return $this->SEND_SUCCESS;
        } else {
            return $this->SEND_FAILED;
        }
    }

}
