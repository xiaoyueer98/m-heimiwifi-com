<?php /* Smarty version 2.6.13, created on 2015-07-17 17:37:16
         compiled from default/Member/reghm.html */ ?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0">  
<meta content="yes" name="apple-mobile-web-app-capable" />   
<meta content="telephone=no" name="format-detection" />
<title>黑米WiFi官网</title>
<link href="__ROOT__/index/Tpl/css/sty.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="__ROOT__/index/Tpl/images/logo.png" type="image/x-icon">
</head>

<script type="text/javascript" src="__ROOT__/index/Tpl/js/jquery.js"></script>
<script type="text/javascript">
var re1=false;  
var re2=false; 
var tel;
var tip1="请输入您的手机号";
      //$("#tel").blur(function(){
      function funTel(){      
            tel=$("#tel").val();
            reg = /^\d{11}$/;

            if(tel==""){
                  re1=false;
                  $("#tel").css("color","#d7d5d4");
                  $("#tel").val(tip1);
                  tel=$("#tel").val(); 

            }else if(!reg.test(tel)){
                  re1=false;
                  sys_alert("号码不符合规则");

            }else{
                  $.ajax({
	                      url:"index.php?m=Member&a=telAjaxhm",
	                      data:"tel="+tel,
	                      dataType:"text",
	                      type:'GET',
	                      success:function(re){
                              if($.trim(re)=="1"){
			                  
                                    if($("#ckbox")[0].checked==true && $("#regBtn1").click()){
                                            
                                          $("form")[0].submit();
                              
                                    }
                              }else{
                                    sys_alert(re);   
                              }
								 
                       }
                        
                 })

           }

      } 


$(document).ready(function(){
      
      if($("#tel").val()=="")
            $("#tel").val(tip1);

      $("#tel").focus(function(){
    	  $("#tel").css("color","#61605e");
            tel=$("#tel").val(); 
            if(tel==tip1){ 
                  $("#tel").val("");
                  tel=$("#tel").val();
            }
      })

})
</script>
<body>
<div id="main_order">
<!--  	   
<div class="menu_bg ">
      <div class="menu_width "><span onclick="javascript:history.go(-1);"  style="float:left;text-decoration:none;font-size:30px;margin-left:5%;"><img src="__ROOT__/index/Tpl/images/back.gif"></span>
      <span style="margin-left:0px;">注册</span>
      <?php if ($this->_tpl_vars['is_login_tmp']): ?>
	      <div style="float: right;vertical-align: middle;margin-right: 30px"><a href="index.php?m=Member&a=userCenter"><img src="__ROOT__/index/Tpl/images/me.gif"></img></a></div>
        <?php else: ?>
        <?php endif; ?>
      </div>
-->
<div class="menu_bg" style="height:2.9em;position:relative;">
      <div style="position:absolute;text-align:center;margin:0;width:100%;height:100%;color:#626262;">
                <span class="sTlh" style="line-height:2.9em;display:block;width:100%">注册</span>
          </div>
      <div class="menu_width " style="position:absolute;left:0;top:0" >
          <span class="spanlh" onClick="javascript:history.go(-1);" >
              <img width="100%" src="__ROOT__/index/Tpl/images/back.gif">
          </span>
      </div>
         
</div>      
<div class="reg_div">
      <div class="reg_tip">请输入您的手机号</div>
      <form action="index.php?m=Member&a=regYzmhm" method="post">
      <div class="reg_tel"><input type="text" value="<?php echo $this->_tpl_vars['tel']; ?>
" id="tel" name="tel" onBlur="funTel();"></div> 
      <div class="reg_tip2"><input type="checkbox" checked="checked" id="ckbox">已阅读并同意<a href="index.php?m=Member&a=regProtocal">用户服务协议</a></div>
      <div class="reg_btn"><img  style="width:90%" src="__ROOT__/index/Tpl/images/nextjh.gif" id="regBtn1"></div> 
      </form>
</div>

</div>
<div style="display:none;">
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F6cd12d7ef0cc86ffeb9308a405b813cb' type='text/javascript'%3E%3C/script%3E"));
</script>
</div>
<body>
</html>