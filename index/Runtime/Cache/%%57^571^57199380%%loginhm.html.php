<?php /* Smarty version 2.6.13, created on 2015-07-17 17:37:09
         compiled from default/Member/loginhm.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0" />
<meta content="yes" name="apple-mobile-web-app-capable" />   
<meta content="telephone=no" name="format-detection" />
<meta content="black" name="apple-mobile-web-app-status-bar-style" /> 
<title>黑米WiFi官网</title>
<link href="__ROOT__/index/Tpl/css/sty.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="__ROOT__/index/Tpl/images/logo.png" type="image/x-icon">

</head>
<script type="text/javascript" src="__ROOT__/index/Tpl/js/jquery.js"></script>
<script type="text/javascript">

$(document).ready(function(){

var tip1="请输入您的手机号";
$("#tel").val(tip1);
$("#tel").focus(function(){

      tel=$("#tel").val(); 
      if(tel==tip1){ 
            $("#tel").val("");
            $("#tel").css("color","#61605e");
            tel=$("#tel").val();
      }
})
$("#tel").blur(function(){
      tel=$("#tel").val();

      if(tel==""){
            re1=false;
            $("#tel").css("color","#d7d5d4");
            $("#tel").val(tip1);
            tel=$("#tel").val(); 
      }else{
            re1=true;
      }
})
var tip2="请输入密码";

$("#pwdtxt").val(tip2);
$("#pwdtxt").focus(function(){
	 $("#pwd").css("color","#61605e");
      $("#pwdtxt").css("display","none");
      $("#pwd").css("display","block");
      $("#pwd").focus();
     
})
$("#pwd").blur(function(){
      
      var pwd=$("#pwd").val();
      if(pwd==""){
    	  $("#pwd").css("color","#d7d5d4");
            $("#pwd").css("display","none");   
            $("#pwdtxt").css("display","block");   
            re2=false;
        
      }else{
            re2=true;
      }
})

})
function  funSub(){
      if(re1 && re2){
            //$("form")[0].submit();
            var tel=$("#tel").val();
            var pwd=$("#pwd").val();
            $.ajax({

                  url:"?m=Member&a=postLoginhm",
                  data:"tel="+tel+"&pwd="+pwd+"&referer="+encodeURIComponent("<?php echo $this->_tpl_vars['referer']; ?>
"),
                 // data:"tel="+tel+"&pwd="+pwd, 
                  type:"POST",
                  dataType:"text",
                  success:function(re){//sys_alert(re);
                       //alert("<?php echo $this->_tpl_vars['action']; ?>
"); 
                       if($.trim(re) == "1"){
                            $.ajax({ 
                                  url:"?m=Member&a=getRefererAction",
                                  dataType:"text",
                                   success:function(re){//alert(re);
                                        if($.trim(re) == "myWifi"){
                                            location.href="index.php?m=Member&a=myWifi";
                                        }else if($.trim(re) == "orderSearch"){
                                            location.href="index.php?m=Member&a=orderSearch";
                                        }else if($.trim(re) == "cardLog"){
                                            location.href="index.php?m=Member&a=cardLog";
                                        }else{

                                            location.href="<?php echo $this->_tpl_vars['referer']; ?>
";
                                        }
                                    }
                            })
                       }else if($.trim(re) == "2"){
                            
                            location.href="index.php?m=Index&a=index";     
                       
                       }else{     
                            
                            sys_alert(re); 
                       }
                       
                  }
           })  
     }

}

 
</script>	

<body>
<div id="main-login">
    
<div class="menu_bg " style="height:2.9em;position:relative;">
     <div style="position:absolute;text-align:center;margin:0;width:100%;height:100%;color:#626262;">   
           <span class="sTlh" style="line-height:2.9em;display:block;width:100%">登录</span>
      </div>
      <div class="menu_width " style="position:absolute;left:0;top:0" >
           <span class="spanlh" onclick="javascript:history.go(-1);"   >
                 <img width="100%" src="__ROOT__/index/Tpl/images/back.gif" />
           </span>
      </div>
     <!--
      <?php if ($this->_tpl_vars['is_login_tmp']): ?>
	      <div style="float: right;vertical-align: middle;margin-right: 30px"><a href="index.php?m=Member&a=userCenter"><img src="__ROOT__/index/Tpl/images/me.gif"></img></a></div>
        <?php else: ?>
        <?php endif; ?>
-->
</div>      
        
<center class="login">
<div class="title1">欢迎登录黑米WiFi账户</div>
<!--<div class="title2">(WiFi免费通用户可以直接登录)</div>-->
<input type="text" name="tel" id="tel" value="" /><br/>
<input type="text"  id="pwdtxt" value=""/><br/>
<input type="password" name="pwd" id="pwd" value="" style="display:none;margin-top:-0.91em;"/><br/>
<input type="hidden"  value="<?php echo $this->_tpl_vars['referer']; ?>
" name="referer"/><br/>
<img src="__ROOT__/index/Tpl/images/lijidenglu.gif" style="width:82%" id="loginSub" onclick="funSub();"><br/>
<p id="lm"><a href="?m=Member&a=reghm" id="a1">立即注册</a><a href="?m=Member&a=forgetPwd1">忘记密码</a></p>
</center>
</div>
<div style="display:none;">
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F6cd12d7ef0cc86ffeb9308a405b813cb' type='text/javascript'%3E%3C/script%3E"));
</script>
</div>
</body>
</html>