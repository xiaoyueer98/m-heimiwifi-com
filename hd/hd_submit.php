<?php

$code = $_GET['code'];
$tel = $_GET['tel'];

//根据用户输入黑米号，查询现有流量

$ch = curl_init();
// 设置URL和相应的选项
curl_setopt($ch, CURLOPT_URL, "http://api.heimiwifi.com/wifi/query/?tel=".$tel);
curl_setopt($ch, CURLOPT_HEADER, 0);
// curl_setopt ( $ch, CURLOPT_POST, 1 );
curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
// curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
// 抓取URL并把它传递给浏览器
$response = curl_exec($ch);
// 关闭cURL资源，并且释放系统资源
curl_close($ch);

$result = json_decode($response);

echo $result['data']['total'];

