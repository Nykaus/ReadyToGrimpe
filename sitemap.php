<?php
ob_start();
@header('Content-Type: application/xml; charset=UTF-8');
include_once("inc/config.inc");
include_once("inc/auth.inc");
include_once("inc/xslt.inc");
$sitebase = "http://".$_SERVER["HTTP_HOST"].$config["BaseUrl"];
ob_end_clean();
ob_start();
include("rpc/rtg_getxml.php");
$xml = ob_get_contents();
ob_end_clean();
ob_start();
$xml = @ereg_replace("</RTG>","<SITEBASE>".$sitebase."</SITEBASE></RTG>",$xml);
$xsl = implode("",file("xsl/sitemap.xsl.xml"));
ob_end_clean();
echo @ereg_replace('<urlset>','<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">',trim(rtg_xslt($xml,$xsl)));
?>
