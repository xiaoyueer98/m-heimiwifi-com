<?php
header("content-type:text/html;charset=utf-8");

// 定义ThinkPHP框架路径(相对于入口文件)
define('THINK_PATH', './ThinkPHP');

//定义项目名称和路径
define('APP_NAME', 'myapp');
define('APP_PATH', './admin');

// 加载框架入口文件 
require(THINK_PATH."/ThinkPHP.php");

$a=strtolower($_GET['a']);
$m=strtolower($_GET['m']);
if(empty($_COOKIE['success'])  && $a!="login"  && $a!="verify" &&$a!="loginAction"){
                     
              header("location:admin.php?m=Public&a=login");   
		     //这样跳转当在index页面时跳转到login页面时，左侧menu还在，只是右面变成login页面，url还是index 
			 /*echo  "<script type=\"text/javascript\">top.location.URL(\"/danei/thinkphp/admin.php/public/login\")</script>";*/
           //  echo  "<script type=\"text/javascript\">location.replace(\"/danei/thinkphp/admin.php/public/login\")</script>";
		     //top为当前页面的上一级目录
}


//实例化一个网站应用实例
App::run();
?>

 
