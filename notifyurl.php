<?php
$str="";
foreach( $_POST as $k => $v){

    $str.=$k."=>".$v."\t\n";    
}

file_put_contents("b.txt",$str);



?>
