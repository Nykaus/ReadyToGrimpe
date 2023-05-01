<?php 
header('Content-Type: application/json; charset=UTF-8');
include_once("../inc/bdd.inc");

//print_r($_POST);

if (isset($_POST['img']))
{
	$h = "";
	if (isset($_POST['h']))
		$h = $_POST['h'].".";
	$fileName = $_POST['type']."/".$h.$_POST['id'].".jpg";
	$imgPath = dirname(__FILE__)."/../bddimg/".$fileName;
	
	$img = base64_decode(preg_replace("#^data:image/[^;]*;base64,#","",$_POST['img']));
	
	$fp = fopen($imgPath, 'w');
	fwrite($fp, $img);
	fclose($fp);
}
if (isset($_POST['path']))
{
	$fileName = $_POST['type']."/".$_POST['id'].".json";
	$imgPath = dirname(__FILE__)."/../bddimg/".$fileName;
	$fp = fopen($imgPath, 'w');
	fwrite($fp, $_POST['path']);
	fclose($fp);
}

echo "{Status:\"OK\",id:\"".$_POST['id']."\", Action:\"SetImage : $fileName\"}";


?>
