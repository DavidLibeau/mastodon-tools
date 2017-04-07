<?php
if(isset($_GET["url"]) && $_GET["url"]!=""){
    //echo("<h1>Embed: ".$_GET["url"]."</h1>");
	//var_dump(curl_version());
    
    /*$url = parse_url($_GET["url"]);
    $path = pathinfo($url["path"]);
    
    $instance=$url["host"];
    $user=preg_replace('/[^A-Za-z0-9]/', '', $path["dirname"]);
    $statusid=preg_replace('/[^A-Za-z0-9]/', '', $path["basename"]);*/
    
    //$atomUrl=$url["scheme"]."://".$instance."/users/".$user."/updates/".$statusid.".atom";
    
    
	
	// CURL $_GET["url"]
	$ch = curl_init(urldecode(str_replace("https://", "http://", $_GET["url"])));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_SSLVERSION, 3);
	curl_setopt($ch, CURLOPT_CAPATH, PATH_TO_CERT_DIR);
	curl_setopt($ch, CURLOPT_TIMEOUT, 0);
	curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
	//curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);
	curl_setopt($ch, CURLOPT_USERAGENT, "Embed");
	curl_setopt($ch, CURLOPT_AUTOREFERER, true);
	
	$content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
	
	$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	
	curl_close($ch);
	
	if($httpCode == 404) {
		echo("<h1>HTTP: ".$httpCode."</h1>");
	}else if($httpCode == 301){
		echo("<h1>HTTP: ".$httpCode."</h1>");
		echo("<h3>".$_GET["url"]." -> ".curl_getinfo($ch, CURLINFO_REDIRECT_URL)."</h3>");
		//var_dump($content);
	}else{
		//var_dump($content);
		$doc = new DOMDocument();
		libxml_use_internal_errors(true);
		$doc->loadHTML($content);
		$xpath = new DOMXPath($doc);
		$atomUrl = $xpath->query("//link[@type='application/atom+xml']");

		if($atomUrl->length){
			$atomUrl = $atomUrl[0]->getAttribute("href");
			// CURL $atomUrl
			$ch = curl_init(str_replace("https://", "http://", $atomUrl));
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
			$curlxml = curl_exec($ch);
			curl_close($ch);
			header('Content-type: text/xml');
			print(str_replace('<?xml version="1.0"?>', '<?xml version="1.0"?><?xml-stylesheet type="text/xsl" href="style.xsl"?>', $curlxml));
		}
	}
	
	/*var_dump($err);
	var_dump($errmsg);
	var_dump($header);
	var_dump($content);*/
		

}
?>
