<?php /* Smarty version 2.6.13, created on 2015-03-09 09:48:42
         compiled from default/Public/footer.html */ ?>
<style>
.footer-reg-login-div{float:left;text-align: center;font-size:30px; height:150px;}
.menu_width_shouye img{float:left;}
.top_userCenter{float:right;font-size:30px;padding-top:0;list-style:none;}

<!--
a:link { text-decoration: none;color: #909090}
a:active { text-decoration:blink}
a:hover { text-decoration:underline;color: #909090} 
a:visited { text-decoration: none;color: #909090}
-－> 
</style>
<center>
<!--尾部-->
 
<?php if ($this->_tpl_vars['is_login_tmp']): ?>
<div class="footer-reg-login" style="width:80%;" id="foot2">
	<div><a href="#" onclick="loginOut();" style="width:33.1%; float:left; text-align:center;color:#909090;font-size:1.3em;">退出登录</a></div>
    <div><a href="index.php?m=Index&a=index" style="width:33.1%; float:left; text-align:center;font-size:1.3em;">回到首页</a></div> 
    <div><a href="javascript:scroll(0,0)" style="width:33.1%; float:left; text-align:center;font-size:1.3em;">回到顶部</a></div> 
</div>
<?php else: ?>

<div class="footer-reg-login" style="width:60%;" id="foot1">
	<div><a href="index.php?m=Index&a=index" style="width:49.5%; float:left; text-align:center;font-size:1.3em;">回到首页</a></div> 
    <div><a href="javascript:scroll(0,0)" style="width:49.5%; float:left; text-align:center;font-size:1.3em;">回到顶部</a></div> 
</div>
<?php endif; ?>
</center>
<!--尾部end-->

<script type="text/javascript" src="__ROOT__/index/Tpl/js/jquery.js"></script>
<script type="text/javascript">
$(document).ready(function(){

$.ajax({
      url:"?m=Member&a=isLogin",
      success:function(re){

             if($.trim(re)==="1"){
                  
                    $("#foot1").css("display","none");
                    $("#foot2").css("display","block");
                    $(".top_login_reg").css("display","none");
                    $(".top_userCenter").css("display","block");
                       //在每页加上<span class="user"><img src="/index/Tpl/images/user.conf"></span>后,默认为none;加上$(".user img").css("display","block");
                       
             }else{
                    $("#foot1").css("display","block");
                    $("#foot2").css("display","none");
                    $(".top_login_reg").css("display","block");
                    $(".top_userCenter").css("display","none");
                       //在每页加上<span class="user"><img src="/index/Tpl/images/user.conf"></span>后加上$(".user img").css("display","none");
                   
            }
       }

})

$("#loginOut").mouseover(function(){
  
     $(this).css("cursor","pointer"); 
})
      
})
function loginOut(){
$.ajax({
      url:"?m=Member&a=loginOuthm",
      success:function(re){
             //sys_alert(re);
             if($.trim(re)==="0"){
                     
                     sys_alert("退出成功！");
                     
                     $("#queding").click(function(){
                           location.reload();
                        })
             }else{
                     sys_alert("退出失败！");
              }
       }

})
}
</script>