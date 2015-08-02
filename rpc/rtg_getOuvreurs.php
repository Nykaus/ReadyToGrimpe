<?php
ob_start();
include_once("../inc/config.inc");
include_once("../inc/bdd.inc");
include("rtg_getxml.php");
$xml = ob_get_contents();

$xsl = implode("",file("../xsl/ouvreurs.xsl.xml"));

		   $xslDoc = new DOMDocument();
   		   $xslDoc->loadXML($xsl);

		   $xmlDoc = new DOMDocument();
		   $xmlDoc->loadXML($xml);

		   $proc = new XSLTProcessor();
		   $proc->importStylesheet($xslDoc);
		   $html = $proc->transformToXML($xmlDoc);


$html = @str_replace('<?xml version="1.0" encoding="UTF-8"?>',"",trim($html));
ob_end_clean();

echo $html;

?>

