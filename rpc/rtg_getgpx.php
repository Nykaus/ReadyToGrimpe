<?php
ob_start();
include_once("../inc/config.inc");
include_once("../inc/bdd.inc");
include("rtg_getxml.php");
$xml = ob_get_contents();

$xsl = implode("",file("../xsl/gpx.xsl.xml"));

		   $xslDoc = new DOMDocument();
   		   $xslDoc->loadXML($xsl);

		   $xmlDoc = new DOMDocument();
		   $xmlDoc->loadXML($xml);

		   $proc = new XSLTProcessor();
		   $proc->importStylesheet($xslDoc);
		   $gpx= $proc->transformToXML($xmlDoc);


ob_end_clean();
//echo $xml;
echo str_replace("<gpx>",'<gpx xmlns="http://www.topografix.com/GPX/1/1" creator="ReadyToGrimpe" version="1.1" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.topografix.com/GPX/1/1 http://www.topografix.com/GPX/1/1/gpx.xsd">',$gpx);

?>

