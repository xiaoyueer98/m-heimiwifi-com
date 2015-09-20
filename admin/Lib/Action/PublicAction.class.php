<?php
class  PublicAction  extends  Action{

                           
                  function   login(){
				         
				         $this->display();
				  }
				  function   index(){
				  
				          $this->display();
				  }
				  function   main(){
				  
				          $this->display();
				  }
				  function   head(){
				  
				          $this->display();
				  }
                             
				  function   menu(){
				          $this->display();
				  }
				 
				  function    loginAction(){ 
				              $url="__ROOT__/admin.php?m=Public&a=login";
					       session_start();
				              //if($_POST['verify']==$_SESSION['verify']){
							        $mOb=M("user");
								 $user=$_POST['username'];
								 $pwd=$_POST['pwd'];
								 $arr=$mOb->where("username='{$user}'")->select();
								 if( is_array($arr) && !empty($arr)){
								       if($arr[0]['pwd']==$pwd){
								               //将登陆用户的信息存入cookie中
									      setcookie("success",serialize($arr),time()+24*3600,"/");
									     //setcookie("userPsd",$arr[0]['psd'],time()+24*3600,"/danei/thinkphp");
									       header("location:admin.php?m=Public&a=index"); 
									}else{
									       $this->assign("waitSecond",3);
								              $this->assign("jumpUrl",$url);
								              $this->error("密码不正确");  
									}
								 }else{
									$this->assign("waitSecond",3);
								       $this->assign("jumpUrl",$url);
								       $this->error("用户名不存在");    
									}
									
							 // }else{
							 //       $this->assign("waitSecond",3);
							//	    $this->assign("jumpUrl",$url);
							//	    $this->error("验证码错误");   
							//  }
							  
				  }
				   function   verify(){
				          import("ORG.Util.Image");
                          //require_once("/var/www/HeimiRoute/ThinkPHP/Lib/ORG/Util/ImageZ");
						  //Image::buildImageVerify();
						  
				  }
				  function   test(){
				                   $this->display();
				  }
				
				
}
