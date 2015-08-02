<?php
include_once("inc/config.inc");
include_once("inc/auth.inc");
include_once("inc/bdd.inc");

if (!isadmin())
	die("t'es pas admin mon gars");

if (isset($_REQUEST["email"]) && isset($_REQUEST["ids"]))
{
         $url =	  $url = $GLOBALS["config"]["Auth"]["BaseUrl"]."?";
         
	 $arrayData["action"] = "ADDRIGHT";
	 $arrayData["email"] = $_REQUEST["email"];
 	 $arrayData["right"] = "SIRead";
 	 $arrayData["ids"] = $_REQUEST["ids"];
	 $arrayData["valid"] = date("YmdHis", mktime(date("H"),date("i"), date("s"),date("m"),date("d"),date("Y")+1));
	 $arrayData["goto"] = $baseurl."si/".$_REQUEST["ids"];
	 $salt = $_REQUEST["email"];
      	 $arrayData["sign"]  = getSign($arrayData,$_REQUEST["email"]); 
	 
	 
	 reset($arrayData);
	 while(list($k,$v) = each($arrayData))
	 {
		$url .= $k."=".urlencode($v)."&";
	 }
	 echo '<a target="RTG" href="'.$url.'" target="_blank">'.$url.'</a><hr/>';
}

?>








<form action="?" method="POST">
<input type="text" name="email" value="">
<input type="hidden" name="right" value="SIRead">
<select name="ids">
	<option value="23">Vergisson</option>
	<option value="9">Solutré</option>
</select>
<input type="submit" name="action" value="Donne moi le lien !">
</form>



