<?php
$dirname = dirname(__FILE__);
include_once($dirname."/../inc/config.inc");
include_once($dirname."/../inc/auth.inc");
include_once($dirname."/../inc/bdd.inc");
include_once($dirname."/../inc/PiwikTracker.php");
$piwikTracker = new PiwikTracker(1);


if (isset($_REQUEST["action"]))
{
	$r[] = "Action:'".$_REQUEST["action"]."'";
	switch($_REQUEST["action"])
	{
		case "PERMLOGIN":
			if (isAuthUser())
			{
				$piwikTracker->doTrackEvent('Authentification','Rester connecté');
				setAuthCookie();
				echo '<div class="alert alert-success" role="alert">Action éffectuée</div>';
				exit;
			}
		break;
		case "AUTH":
			if (isValidAuthLink($_GET))
			{
				$r[] = "r:'true'";
				$piwikTracker->doTrackEvent('Authentification','Login','success');
			}
			else
			{
				$r[] = "r:'false'";
				$piwikTracker->doTrackEvent('Authentification','Login','error');
			}			
		break;
		case "SENDLINK":
			if (sendAuthLink($_REQUEST["email"]))
			{
				$r[] = "r:'true'";
				$piwikTracker->doTrackEvent('Authentification','Envoie email','success');
			}
			else
			{
				$r[] = "r:'false'";
				$piwikTracker->doTrackEvent('Authentification','Envoie email','error');
			}
		break;	
		
		case "GENLINK":
			$r[] = "r:'".getAuthLink($_REQUEST["email"])."'";
		break;			
		case "LOGOUT":
			unAuth();
			delAuthCookie();
			$r[] = "r:'true'";
			$piwikTracker->doTrackEvent('Authentification','Logout','success');
		break;		
		case "ADDRIGHT":
			if (isValidAuthLink($_GET))
			{
				$piwikTracker->doTrackEvent('Authentification','Login','success');
			}
			if (isValidAddRightLink($_GET))
			{
				$r[] = "r:'true'";
				$piwikTracker->doTrackEvent('Authentification','Ajout de droits','success');
			}
			else
			{
				$r[] = "r:'false'";
				$piwikTracker->doTrackEvent('Authentification','Ajout de droits','error');
			}		
		break;			
		case "DELRIGHT":
			delUserRight($_REQUEST["right"],$_REQUEST["ids"]);
			$piwikTracker->doTrackEvent('Authentification','Suppression de droits','success');
			$r[] = "r:'true'";
		break;	
		case "GENRIGHT":
			$a = getAddRightLink($_REQUEST["right"],$_REQUEST["ids"]);
			$r[] = "r:'".getAuthLink($a)."'";
		break;
		
		
		case "LISTUSER":
			if (isAuthUser())
			{
				$r[] = "html:'".@ereg_replace("'","\\'",getListUsers(@$_REQUEST["search"],@$_REQUEST["calljs"]))."'";
			}			
		break;
		
		
	}
}
if (isAuthUser())
{
	$r[] = "Auth:'true'";
	$r[] = "UserName:'".getAuthUserName()."'";
	$piwikTracker->setUserId(getAuthUserId()." - ".getAuthUserName());
}
else
{
	$r[] = "Auth:'false'";
}

echo "{".implode(',',$r)."}";
?>
