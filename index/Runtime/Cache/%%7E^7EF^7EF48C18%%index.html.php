<?php /* Smarty version 2.6.13, created on 2015-03-09 09:48:42
         compiled from default/Index/index.html */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0">

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<!-- <meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=0"> -->
<meta content="yes" name="apple-mobile-web-app-capable" />   
<meta content="telephone=no" name="format-detection" />
<title>黑米WiFi官网</title>
<link href="__ROOT__/index/Tpl/css/sty.css" rel="stylesheet" type="text/css" />
<link rel="icon" href="__ROOT__/index/Tpl/images/logo.png" type="image/x-icon">
</head>
<script type="text/javascript" src="__ROOT__/index/Tpl/js/jquery.js"></script>
<script type="text/javascript">

$("document").ready(function(){
      
     /*$("#data").click(function(){
           fun1();
     })   */
    
     $("#data_tu").click(function(){
    	 fun1();
     })  
     function fun1(){  
           $.ajax({
                url:"?m=Member&a=isLogin",
                success:function(re){
                     if($.trim(re)!=="1"){
                           location.href="index.php?m=Member&a=loginhm";
                     }
                }
           })    
		   //需要判断是否绑定，如果绑定查询详细信息，如果末绑定，转到绑定页面  
           $.ajax({
                url:"?m=Member&a=isBd",
                success:function(re){
                	//sys_alert(re);
                     if($.trim(re)==="0"){
                    	  location.href="index.php?m=Member&a=dataWbd";
                     }else{
                          location.href="index.php?m=Member&a=data";
                     }
                           
                }
          })    

     }       
       
    
})    

var knext = 1; 
var t = 0;
function  fun1(){
   
    clearInterval(t);
    
    $(".img_lun").each(function(k){
        
        //alert($(this).css("display"));
        if($(this).css("display") == "block"){
           
            knext = k + 1;
            var n = $(".img_lun").length;
            if(knext == n){
                knext = 0;
            }
        }
        //alert(knext);  
    })
    $(".img_lun").css("display","none");
    $(".img_lun"+knext).css("display","block");
    //alert(knext);    
    t=setInterval('fun1()',6000);

}
</script>
</style>
<body onload="fun1();t=setInterval('fun1()',6000);">
<div id="main">
    
<div class="menu_bg ">
    <div class="menu_width  menu_width_shouye clear">
        <div  style="width:9.6em;height:3.57em;float: left;">
        <img src="<?php echo $this->_tpl_vars['sArr']['logo']; ?>
" id="shouyetitle" width="100%" />
        </div>
            <?php if ($this->_tpl_vars['is_login_tmp']): ?>
            <div style="float: right;vertical-align: middle;margin-right: 1.25em;width:1.75em;height:3.58em;"  class="xxtop_userCenter">
                <a href="index.php?m=Member&a=userCenter"><img width="100%" src="__ROOT__/index/Tpl/images/me.gif" /></a>
            </div>     
            <?php else: ?>
            <ul class="top_login_reg clear" >
                  <li><a href="?m=Member&a=loginhm">登录</a></li>
                  <li><a href="?m=Member&a=reghm">注册</a></li>
            </ul>
            <?php endif; ?>
      </div>
</div>      
	
<!-- banner图片-->
<div class="banner">
      <!--<a href="/index.php?m=Product&a=detail_buy">
            <img src=<?php echo $this->_tpl_vars['sArr']['indexBanner']; ?>
 border="0"3>
      </a>-->
      <img src="__ROOT__/index/Tpl/images/banner.jpg"  class="img_lun img_lun0" style="display:none;"/>    
      <a href="http://api.heimiwifi.com/hd/index.html"><img src="__ROOT__/index/Tpl/images/banner-shujia.jpg"   class="img_lun img_lun1"/></a>     
</div>
   
<!-- 小图开始-->	
<center>	
<div class="content clear">
    	<div class="lefts"> 
           <!--<a href="index.php?m=Product&a=detail_buy"> -->
           <a href="index.php">
              <img src=<?php echo $this->_tpl_vars['sArr']['icon1']; ?>
 border="0"/> 
           </a> 
        </div>
        <div class="middles"> 

           <a href="index.php">
              <img src=<?php echo $this->_tpl_vars['sArr']['icon2']; ?>
 border="0"/>
           </a>   
        </div>
        <div class="rights"> 
           <a href="http://bbs.heimiwifi.com/forum.php?mod=forumdisplay&fid=2&page=1">
              <img src=<?php echo $this->_tpl_vars['sArr']['icon3']; ?>
  border="0"/>
           </a> 
        </div>
	
	
</div>
</center>
<center>
<div class="content2 clear">
<center>
    	<div class="left2"> 
           <a href="index.php?m=Member&a=userCenter"> 
              <img src=<?php echo $this->_tpl_vars['sArr']['icon4']; ?>
 border="0"/> 
           </a> 
        </div>
        <div class="middle2">
           <a href="http://bbs.heimiwifi.com/forum.php?mod=viewthread&tid=17"> 
              <img src=<?php echo $this->_tpl_vars['sArr']['icon5']; ?>
  border="0"/>
           </a>
        </div>
        <div class="right2"> 
           <a href="http://bbs.heimiwifi.com/forum.php?mod=viewthread&tid=71">
              <img src=<?php echo $this->_tpl_vars['sArr']['icon6']; ?>
 border="0" />
           </a>
        </div>
</div>
</center>
	

<!-- 小图结束-->
<!--图片1-->
<div class="tu1">
  
      <img src=<?php echo $this->_tpl_vars['sArr']['recommend1']; ?>
 border="0"/>
  
</div>
<!--图片1end-->
<!--图片2-->
<div class="tu2">
   
      <img src=<?php echo $this->_tpl_vars['sArr']['recommend2']; ?>
 border="0"/>
 
</div>
<!--图片2end-->
<!--图片3-->
<div class="tu3">
   
      <img src=<?php echo $this->_tpl_vars['sArr']['recommend3']; ?>
 border="0"/>
   
</div>
<!--图片3end-->
<!--图片4-->
<div class="tu4">
   
      <img src=<?php echo $this->_tpl_vars['sArr']['recommend4']; ?>
 border="0"/>
  
</div>
<!--图片4end-->

<!--图片5-->
<!--
<div class="tu5">

   <a href="/index.php?m=Product&a=card_list">
      <img src=__ROOT__/index/Tpl/images/bannerx3.jpg border="0"/>
   </a>
</div>
-->
<!--图片5end-->

<div class="zzc"></div>
<!-- <div class="order_upd" id="orderupd"  style="position:fixed;left:10%;top:20%;">
      <div>
            <div class="tip">请输入要绑定的设备号</div>
      </div>
      <div class="upd_info">
            <input type="text" value="" id="updIn">
      </div>
      <div class="btn">
            <input type="button" value="立即绑定" id="bdupd">
            <input type="button" value="取消" id="cancel">
      </div>
</div> -->
<!--尾部 -->
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "default/Public/footer.html", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
<!--尾部end-->

</div>
<div style="display:none;">
<script type="text/javascript">
var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F6cd12d7ef0cc86ffeb9308a405b813cb' type='text/javascript'%3E%3C/script%3E"));
</script>
</div>
</body>
</html>