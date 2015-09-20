<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SmsModel
 *
 * @author peter
 */
class SmsModel extends Model {

    //注册发送验证码
    public function registerSendSms($phoneNumber, $validateCode) {
        $util = new UtilsModel();
        $message = "验证码：" . $validateCode . "，有效时间一小时，请您及时完成验证。";
        //获取发送短信信息
        $userData = $this->getSMSConfig($phoneNumber, $message);
        //获取URL信息
        $returnData = $util->postMethod(C('sms_url'), $userData);
        //返回信息
        return $returnData;
    }

     //组织发送短信的数据
    private function getSMSConfig($phoneNumber, $message) {
        $message = iconv("UTF-8", "GBK", $message);
        $userData = array(
            'corp_id' => C('sms_corp_id'),
            'corp_pwd' => C('sms_corp_pwd'),
            'corp_service' => C('sms_corp_service'),
            'mobile' => $phoneNumber,
            'msg_content' => $message,
        );
        return $userData;
    }

}
