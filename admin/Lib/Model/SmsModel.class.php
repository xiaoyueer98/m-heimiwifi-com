<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SmsModel
 *  短信发送接口类，请自行验证手机号码准确性，
 *  现在系统所使用的功能为 验证码登录，发货提醒，预约提醒，开放购买
 * @author peter
 */
class SmsModel extends Model {

    //找回密码
    //$phoneNumber 手机号码
    //$validateCode 验证码
    public function registerSendSms($phoneNumber, $validateCode) {
        import('ORG.Sms.SmsService');
        $message = str_replace('{@temp}', $validateCode, SmsService::$RECEVIED_CODE_TEMP);
        $result = $this->sendSMS($phoneNumber, $message);
        return $result;
    }

    //找回密码
    //$phoneNumber 手机号码
    //$validateCode 验证码
    public function forgetPasswordSendSms($phoneNumber, $validateCode) {
        import('ORG.Sms.SmsService');
        $message = str_replace('{@temp}', $validateCode, SmsService::$RECEVIED_CODE_TEMP);
        $result = $this->sendSMS($phoneNumber, $message);
        return $result;
    }

    // 快递发货短信
    //$phoneNumber 手机号码
    //$orderID 订单号
    //$productName 产品名称
    //$express 代表快递名称
    public function sendOutGoods($phoneNumber, $orderId, $productName = '黑米盒子', $express = '圆通快递') {
        import('ORG.Sms.SmsService');
        $message = SmsService::$DELIVERY_ADDRESS_TEMP;
        $message = str_replace('{@productName}', $productName, $message);
        $message = str_replace('{@express}', $express, $message);
        $message = str_replace('{@orderid}', $orderId, $message);
        $result = $this->sendSMS($phoneNumber, $message);
        return $result;
    }

    //预约成功接口
    //$phoneNumber 手机号码
    //$productName 产品名称
    public function appointment($phoneNumber, $productName = '黑米盒子') {
        import('ORG.Sms.SmsService');
        $message = SmsService::$APPOINTMENT_TEMP;
        $message = str_replace('{@productName}', $productName, $message);
        $result = $this->sendSMS($phoneNumber, $message);
        return $result;
    }

    //开发购买短信接口
    //$phoneNumber 手机号码
    //$productName 产品名称
    //time格式 为  xx月xx日xx时
    public function openBuy($phoneNumber, $productName, $time) {
        import('ORG.Sms.SmsService');
        $message = SmsService::$OPEN_BUY_TEMP;
        $message = str_replace('{@productName}', $productName, $message);
        $result = $this->sendSMS($phoneNumber, $message);
        return $result;
    }

    //发送短信，条用短信发送类
    private function sendSMS($phoneNumber, $message, $times = 0) {
        import('ORG.Sms.SmsService');
        if ($times > 3) {
            return -1;
        }
        $service = SmsService::getSMSChannel($phoneNumber);
        //选择模板，加上发送的验证码即可
        $result = $service->sendSMS($phoneNumber, $message);
        if ($result == '-1') {
            return $this->sendSMS($phoneNumber, $message, ++$times);
        } else {
            return $result;
        }
    }

}
