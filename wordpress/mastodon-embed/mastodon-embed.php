<?php
/**
 * Plugin Name: Mastodon embed
 * Plugin URI: https://github.com/DavidLibeau/mastodon-widget
 * Description: A plugin to embed Mastodon statuses.
 * Version: 1.0
 * Author: David Libeau
 */


function mastodon_embed_callback($atts=null, $content=null)
{
	extract($atts);
	
	if(isset($url) && $url!=""){
	
		// CURL $_GET["url"]
		$ch = curl_init(urldecode(str_replace("https://", "http://", $url)));
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
			return("<div class='mastodon-embed'>404</div>");
		}else if($httpCode == 301){
			return("<div class='mastodon-embed'>301</div>");
		}else{
			$doc = new DOMDocument();
			libxml_use_internal_errors(true);
			$doc->loadHTML($content);
			$xpath = new DOMXPath($doc);
			$atomUrl = $xpath->query("//link[@type='application/atom+xml']");

			if($atomUrl->length){
				$embedUrl = str_replace(".atom", "/embed", $atomUrl[0]->getAttribute("href"));
				if(isset($height) && $height!=""){
					return('<div class="mastodon-embed"><iframe src="'. $embedUrl .'" style="overflow: hidden" frameborder="0" width="400" height="'.$height.'" scrolling="no"></iframe></div>');
				}else{
					return('<div class="mastodon-embed"><iframe src="'. $embedUrl .'" style="overflow: hidden" frameborder="0" width="400" height="150" scrolling="no"></iframe></div>');
				}
			}
		}
	
	}

	
}

add_shortcode("mastodon", "mastodon_embed_callback");