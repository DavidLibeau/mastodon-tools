<?php
/**
 * Plugin Name: Mastodon embed
 * Plugin URI: https://github.com/DavidLibeau/mastodon-widget
 * Description: A plugin to embed Mastodon statuses.
 * Version: 1.3
 * Author: David Libeau
 */

$CACHETIME=0; //24*60*60; // 1 day

function mastodon_embed_callback($atts=null, $content=null)
{
	extract($atts);
	
	if(isset($url) && $url!=""){
	
		$args = array(
			'sslverify'   => false
		); 

		$response = wp_remote_get($url,$args);
        set_transient( 'mastodon_status_', $response, $CACHETIME ); //cache curl response (we only need one responde to get atom url)
        
        $httpCode = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body( $response );
		var_dump($response);
        
        echo("<br/>");

		if($httpCode == 404) {
			return("<div class='mastodon-embed'>404</div>");
		}else if($httpCode == 301){
			return("<div class='mastodon-embed'>301</div>");
		}else{
			$doc = new DOMDocument();
			libxml_use_internal_errors(true);
			$doc->loadHTML($body);
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