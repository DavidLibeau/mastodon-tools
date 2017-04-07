<?php
if(isset($_GET["url"]) && $_GET["url"]!=""){
	$url = $_GET["url"];
}

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,$url);
curl_setopt($ch, CURLOPT_USERAGENT, 'Opera/9.23 (Windows NT 5.1; U; en)');
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);

## Below two option will enable the HTTPS option.
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  2);
$result = curl_exec($ch);
var_dump($result);
?>