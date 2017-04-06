<?php
class MySimpleXMLElement extends SimpleXMLElement
{
    public function addProcessingInstruction($target, $data = NULL) {
        $node   = dom_import_simplexml($this);
        $pi     = $node->ownerDocument->createProcessingInstruction($target, $data);
        $result = $node->insertBefore($pi, $node->childNodes->item(0));
        return $this;
    }
}


if(isset($_GET["url"]) && $_GET["url"]!=""){
    header('Content-type: text/xml');
    //echo("<h1>Embed: ".$_GET["url"]."</h1>");
    
    $url = parse_url($_GET["url"]);
    $path = pathinfo($url["path"]);
    
    $instance=$url["host"];
    $user=preg_replace('/[^A-Za-z0-9]/', '', $path["dirname"]);
    $statusid=preg_replace('/[^A-Za-z0-9]/', '', $path["basename"]);
    
    //$atomUrl=$url["scheme"]."://".$instance."/users/".$user."/updates/".$statusid.".atom";
    
    

    // CURL $_GET["url"]
    $ch = curl_init(str_replace("https://", "http://", $_GET["url"]));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    $html = curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    
    $doc = new DOMDocument();
    libxml_use_internal_errors(true);
    $doc->loadHTML($html); // loads your HTML
    $xpath = new DOMXPath($doc);
    $atomUrl = $xpath->query("//link[@type='application/atom+xml']");
    $atomUrl = $atomUrl[0]->getAttribute("href");
    
    
    //echo("<ul><li>Instance : ".$instance."</li><li>User : ".$user."</li><li>Status ID : ".$statusid."</li><li>Atom url : <a href=\"".$atomUrl."\" target=\"_blank\">".$atomUrl."</li></ul>");
    
    
    // CURL $atomUrl
    $ch = curl_init(str_replace("https://", "http://", $atomUrl));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
    $curlxml = curl_exec($ch);
    curl_close($ch);
    fclose($fp);
    
    //print($xml);
    
    
    $xml  = simplexml_load_string($curlxml, 'MySimpleXMLElement');
    $xml->addProcessingInstruction('xml-stylesheet', 'type="text/xsl" href="style.xsl"');
    $xml->asXML('php://output');

}
?>