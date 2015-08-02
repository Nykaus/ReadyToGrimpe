<?php
function rtg_xslt($xml,$xsl)
{
	$result="";
	if ($xsl and $xml)
	{

		   $xslDoc = new DOMDocument();
   		   $xslDoc->loadXML($xsl);

		   $xmlDoc = new DOMDocument();
		   $xmlDoc->loadXML($xml);

		   $proc = new XSLTProcessor();
		   $proc->importStylesheet($xslDoc);
		   return  $proc->transformToXML($xmlDoc);
	}

}
?>


