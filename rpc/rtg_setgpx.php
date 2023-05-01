<?php 
header('Content-Type: application/json; charset=UTF-8');
include_once("../inc/bdd.inc");

//print_r($_POST);

if (isset($_POST['gpx']) && isset($_POST['id']))
{
	$fileName = $_POST['id'].".gpx";
	$gpxPath = dirname(__FILE__)."/../gpx/".$fileName;
	

	$gpx = base64_decode(preg_replace("#^data:application/octet-stream;base64,#","",$_POST['gpx']));
	$fp = fopen($gpxPath, 'w');
	fwrite($fp, $gpx);
	fclose($fp);
	
	echo "{Status:\"OK\",id:\"".$_POST['id']."\", Action:\"SetGpx : $fileName\"}";
}
else
{
	echo "{Status:\"KO\",id:\"".$_POST['id']."\", Action:\"SetGpx\"}";
}





?>
