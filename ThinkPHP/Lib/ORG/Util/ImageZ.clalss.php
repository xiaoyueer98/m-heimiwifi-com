<?php
ob_end_clean();
header("content-type:image/png");
$im=imagecreatetruecolor(120,50);
$col=imagecolorallocate($im,100,100,100);
imagefill($im,0,0,$col);
$str="1234567890qwertyuiopasdfghjklzxcvbnm";

$s="";
for($i=0;strlen($s)<4;$i++){
   
    $k=mt_rand(0,strlen($str)-1);
    if(strpos($s,$str[$k])===false){
			$color[$i]=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
			$path="/var/www/heimiBootstrap/ThinkPHP/Vendor/fonts/".mt_rand(0,16).".ttf";
			imagefttext($im,20,mt_rand(-15,15),15*$i+10,30,$color[$i],$path,$str[$k]);
			$s.=$str[$k];
	}else{
	        continue;
	} 
}
$coll=imagecolorallocate($im,mt_rand(0,255),mt_rand(0,255),mt_rand(0,255));
for($i=0;$i<300;$i++){	 
     imagesetpixel($im,mt_rand(0,300),mt_rand(0,100),$coll);	 
}
imagepng($im);
session_start();
$_SESSION['verify']=$s;

echo  $s;
