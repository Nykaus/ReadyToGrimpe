<?php
header('Content-Type: application/json; charset=UTF-8');
include_once("inc/config.inc");
// configuration
$templates['/@facebook.com$/i']  = "Facebook";
$templates['/@gmail.com$/i']  = "Google";
$templates['/@twitter.com$/i']  = "Twitter";
$templates['/@(live|msn|hotmail|outlook).com$/i']  = "windowslive";




if (isset($_REQUEST['userId']))
{		
        $goto = $config["BaseDNSHttp"];
	if (isset($_REQUEST['location']))
		$goto = $_REQUEST['location'];

		while (list($template,$action) = each($templates))
		{
			if (preg_match($template,$_REQUEST['userId']))
			{
				die("{type:\"redirect\", redirect:\"". "social_login.php?action=".$action."&goto=".urldecode($goto) ."\"}");
			}
		}
}
die("{type:\"local\"}");
?>
