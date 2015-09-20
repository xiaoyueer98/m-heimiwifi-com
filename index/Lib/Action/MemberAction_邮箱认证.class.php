<?php

class   MemberAction  extends  Action{

    function  index() {
        if(isset($_GET['id']) && $_GET['id']!=""){
            $id=$_GET['id'];
        }else{
            $id=13;
        }
        $this->assign('id',$id);
        $this->display();
    }
    function   verify(){

        import("ORG.Util.ImageZ");
    } 
    function   reg() {

        $this->display();
    }
    function   regAjax() {
        //验证邮箱是否已经注册过

    }
    function   regAction(){
        $mOb=new MemberModel;
        $re=$mOb -> addMember($_POST);
        if($re){
            $this->assign("jumpUrl","index.php?m=Member&a=postEmail&email=".$_POST['email']);
            $this->assign("waitSeconds","3");
            $this->success();
        }else{
            $this->assign("jumpUrl","index.php?m=Member&a=reg");
            $this->assign("waitSeconds","3");
            $this->error();
        }

    }
    function   postEmail(){
        import("ORG.Util.Phpmailer");
        $mail =  new  PHPMailer();
        $mail->CharSet="UTF-8";    
        $mail->Encoding = "base64"	; 
        $mail->SMTPDebug=true ;  

        $mail->IsSMTP();               // 启用SMTP
        $mail->SMTPSecure = "ssl";
        $mail->Host = "smtp.qq.com";            //SMTP服务器
        $mail->Port = 465;
        $mail->SMTPAuth = true;                  //开启SMTP认证
        $mail->Username = "1551058861@qq.com";            // SMTP用户名
        $mail->Password = "sunyue19900924";                // SMTP密码
        $mail->From = "1551058861@qq.com";            //发件人地址
        $mail->FromName = "黑米科技";              //发件人

        $mail->AddAddress($_GET['email']);
        $address = $_GET['email'];
        $mail->AddReplyTo("1551058861@qq.com", "reply");    //回复地址
        $mail->WordWrap = 50;                    //设置每行字符长度

        $mail->IsHTML();                 // 是否HTML格式邮件
        $mail->Subject = "黑米帐号激活";          //邮件主题
        $mail->Body    = "192.168.2.103:10007/index.php?m=Member&a=activeNotice&email=".$address;   //邮件内容
        $mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //邮件正文不支持HTML的备用显示

        if($mail->Send()){

            header("location:/index.php?m=Member&a=active&email=".$address);

        }else{

            $this->assign("jumpUrl","index.php?m=Member&a=reg");
            $this->assign("waitSeconds","3");
            $this->error();

        }
    }
    function   active(){

        $this -> assign("email",$_GET['email']);
        $this -> display();
    }
    function   activeNotice(){
        if(!empty($_GET['email'])){

            $Mob = new MemberModel; 
            //$re=$Mob->query("update r_member set status='1' where email=\"{$_GET['email']}\" and status='0'");
            $re=$Mob->where("email=\"{$_GET['email']}\" and status='0'")->data(array('status'=>'1'))->save();
            //	var_dump($re);die();
            if($re){
                $this->assign("message","激活成功<br/><a href='/index.php?m=Member&a=login'>点击登录</a>");
                $this -> display();
            }else{
                $this->assign("message","已经激活<br/><a href='/index.php?m=Member&a=login'>点击登录</a>");
                $this -> display();
            }    

        }else{
            header("location:/index.php?m=Member&a=reg");				               
        } 


    }

    function   login() {

        $this->display();
    }
    function   loginAjax(){

        $email=$_GET['email'];
        $mOb=new MemberModel;
        $arr=$mOb -> getMember("email='".$email."'");
        if(isset($arr) && !empty($arr)){
            echo $arr[0]['password'].",".$arr[0]['id'];

        }else{
            echo "0";
        } 
    }
    function  setCookie(){

        setcookie("userinfo",serialize($_GET),time()+24*3600,"/");    


    }

    function  userInfo(){

        $this -> display();
    }
    function    code(){

        $this->display();
    }
    function    codeAjax(){

        $code=$_GET['code']; //$code='1234567890';
        $ob=M("codes");
        $arr=$ob->where("code='".$code."' and  status=1")->select();  //echo $ob->getLastSql();
        if($arr===null){//echo "1";
            echo "<script type='text/javascript'>alert('您输入的码不存在或已兑换过');window.history.back(-1);</script>";
        }else{
            //echo "<script type='text/javascript'>window.location='info?code=".$code."'</script>";
            echo "<script type='text/javascript'>
                function getRootPath(){
                    var strFullPath=window.document.location.href;
                    var pos=strFullPath.indexOf('&');
                    var prePath=strFullPath.substring(0,pos); 
                    return(prePath+'&a=info&code=".$code."');
                }
            str=getRootPath();  
            window.location=str; 
            </script>";
        }
    }


    function    info(){

        $code=$_GET['code'];
        $this->assign('code',$code);
        $this->display();
    }

    function    infoSave(){

        $_POST['address']=$_POST['add1']."".$_POST['add2']."".$_POST['add3']."".$_POST['add4'];
        unset($_POST['add1']);
        unset($_POST['add2']);
        unset($_POST['add3']);
        unset($_POST['add4']);
        $obOrder=M("order");  //var_dump($ob);die();
        $re=$obOrder->data($_POST)->add();  //var_dump($re);die();
        $obCode=M("codes");
        $sql="update r_codes set status='2'  where code='".trim($_POST['code'])."'";//echo $sql;die();
        $re1=$obCode->execute($sql);  //var_dump($re1);
        if($re && $re1){
            echo "<script type='text/javascript'>alert('订单已生成，很快就能送到喽！');window.location='index.php?m=Index&a=index&id=13'</script>";
        }else{
            echo "<script type='text/javascript'>alert('订单生成失败,请重新填写信息！');window.location='index.php?m=Member&a=info&code=+".$_POST['code']."'</script>";
        }

    }

    function   order(){

        $this->display();
    }

    function  address(){

        if($_COOKIE['userinfo']==null){
            header("location:?m=Member&a=login");
        }else{
            $this->display();
        }
    }





}
