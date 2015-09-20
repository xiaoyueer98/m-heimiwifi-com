<?php

/*
  //判断当前的短信通道列表，并获取可用状态，如果都可用，则选择默认的，
  //如果没有设置默认的通道，则随机选一种，并记录到缓存中，如果该手机号重复获取，则切换到，另一个通道
  //用户输入号码后，将缓存清空，如果两分钟不输入手机号，则该号码过期
  //获取当前手机号，是否使用过短信通道
  //如果使用过短信通道
 */

/**
 * Description of SmsService
 * 短信接口类，各厂家短信接口类需要实现该方法
 *    //判断当前用户是否用其他短信通道发送过短信
  //如果已经发过短信，则切换其他短信商
 * @author peter
 */
abstract class SmsService {

    //发送成功状态
    public $SEND_SUCCESS = '1';
    //发送失败状态
    public $SEND_FAILED = '-1';
    //欠费状态
    public $SEND_ARREARS = '0';
    //接受验证码模板
    public static $RECEVIED_CODE_TEMP = "验证码：{@temp}，有效时间一小时，请您及时完成验证。如非本人操作，请忽略本短信。";
    //发货提示模板
    public static $DELIVERY_ADDRESS_TEMP = "您购买的{@productName}已发货，{@express}快递单号{@orderid}，请注意查收。如有问题，请联系客服。";
    //预约模板
    public static $APPOINTMENT_TEMP = "恭喜！您已成功预约{@productName}。开放购买时间，我们会以短信形式提前通知到您。感谢您的支持！";
    //开发购买模板//{@year}将于{@month}月{@day}日{@hour}
    public static $OPEN_BUY_TEMP = "您好，{@productName}将于{@time}对预约用户开放购买，请关注黑米官网活动信息。感谢您的支持！";

    //获取短信配置
    public abstract function getSmsConfig($mobileNumber, $message);

    //发送短信到指定的联系人
    public abstract function sendSMS($mobileNumber, $message);

    //统一转换格式
    //1：短信发送成功，2，发送失败，3，短信欠费
    public abstract function wrongNumberChange($result);

    //验证手机号
    public function validataMobileNumber() {
        
    }

    //为当前手机号选择一个合适的短信通道
    //判断当前用户是否用其他短信通道发送过短信
    //如果已经发过短信，则切换其他短信商
    public static function getSMSChannel($telphone) {
        import('ORG.Sms.BaiwuSmsService');
        import('ORG.Sms.ExsalesSmsService');
        $smsChannel = '';
        if (S($telphone) != '') {
            $sms = S($telphone);
            $smsChannel = self::getSMSService($sms);
        } else {
            $defaultSms = C('default_sms');
            if ($defaultSms == '') {
                $smsChannel = self::getSMSService('');
            } else {
                $smsChannel = $defaultSms;
            }
        }
        //保存该手机号使用的通道60秒，过期释放
        S($telphone, $smsChannel, 180);
        return new $smsChannel;
    }

    public function getSMSService($sms) {
        $smsList = C('sms_list');
        $arr = explode(",", $smsList);
        for ($i = 0; $i < count($arr); $i++) {
            if ($arr[$i] != $sms) {
                return $arr[$i];
            }
        }
        return $sms;
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

}
