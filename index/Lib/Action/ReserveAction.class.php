<?php

class   ReserveAction  extends  Action{

    function  introduce() {

        $this->display();
    }
    function   reserve(){

        //var_dump($_COOKIE['userinfo']);die();
        if(!empty($_COOKIE['userinfo'])){
            //读出用户信息
            $userArr=unserialize($_COOKIE['userinfo']);//var_dump($userArr);die();
            //查看预定信息
            $rOb=new ReserveModel;
            $reserveNum=$rOb->getReserveCount();//var_dump($reserveNum);die();
            if($reserveNum<1000){

                $rArr=$rOb->getReserve("uid = {$userArr['uid']}");//echo $rOb->getLastSql();var_dump($rArr);die();
                if($rArr != null){

                    $this->assign("jumpUrl","index.php?m=Reserve&a=introduce");
                    $this->assign("waitSeconds","3");
                    $this->error();
                }else{
                    //得到验证码  
                    $yzm="123456";
                    //发送邮件
                    import("ORG.Util.Phpmailer");
                    $mail =  new  PHPMailer();
                    $mail->CharSet="UTF-8";    
                    $mail->Encoding = "base64"; 
                    // $mail->SMTPDebug=true ;  

                    $mail->IsSMTP();               // 启用SMTP
                    $mail->SMTPSecure = "ssl";
                    $mail->Host = "smtp.qq.com";            //SMTP服务器
                    $mail->Port = 465;
                    $mail->SMTPAuth = true;                  //开启SMTP认证
                    $mail->Username = "1551058861@qq.com";            // SMTP用户名
                    $mail->Password = "sunyue19900924";                // SMTP密码
                    $mail->From = "1551058861@qq.com";            //发件人地址
                    $mail->FromName = "黑米科技";              //发件人

                    $mail->AddAddress($userArr['email']);

                    $mail->AddReplyTo("1551058861@qq.com", "reply");    //回复地址
                    $mail->WordWrap = 50;                    //设置每行字符长度

                    $mail->IsHTML();                 // 是否HTML格式邮件
                    $mail->Subject = "黑米预定成功提示";          //邮件主题
                    $mail->Body    = "您已成功预定黑米手机，验证码为$yzm,我们会在正式发售前再次提醒您！";   //邮件内容
                    $mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //邮件正文不支持HTML的备用显示

                    if($mail->Send()){

                        $this->assign("jumpUrl","index.php?m=Reserve&a=introduce");
                        $this->assign("waitSeconds","3");
                        $this->success();

                    }else{

                        $this->assign("jumpUrl","index.php?m=Reserve&a=introduce");
                        $this->assign("waitSeconds","3");
                        $this->error();

                    }

                    //写进预定表
                    $arr=array('uid'=>$userArr['uid'],'yzm'=>$yzm,'time'=>date("Y-m-d H:i:s"));
                    $rOb->addReserve($arr);
                }        
            }else{

                //提示预定已满
            }    

        }else{

            header("location:/index.php?m=Member&a=login");
        }
    } 





}
