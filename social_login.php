<!doctype html>
<?php
session_start();
include_once("inc/config.inc");
include_once("inc/auth.inc");




// on gere le retour
if (isset($_REQUEST['goto']))
	$_SESSION['goto'] = $_REQUEST['goto'];

if (isset($_REQUEST['action']))
	$_SESSION['action'] = $_REQUEST['action'];



// on charge simpleSAMLPhp
require_once('/home/climbingoo/www/simplesaml/lib/_autoload.php');

switch (@$_SESSION['action'])
{
	case "Facebook":

		$as = new SimpleSAML_Auth_Simple("Facebook");
		// si on est pas authentifier, on lance l'authentification
		if (!$as->isAuthenticated())
			$as->requireAuth();
		if (isset($as) && $as->isAuthenticated()) 
		{

			$att = $as->getAttributes();
			/*while (list($k,$v) = each($att))
			{
				if (sizeof($v) == 1)
				{
					$_SESSION[$k] = $v[0];
				}
				else
				{
					$_SESSION[$k] = $v;
				}
			}*/

			if (isset($att["facebook_cn"]))
			{
				$_SESSION['user'] = str_replace(" ",".",$att["facebook_cn"][0]."@facebook.com");
				
				$q = "select * from topo_utilisateurs where utilisateur_email='".$_SESSION["user"]."'";
				$a = $GLOBALS["Bdd"]->fetch_all_array($q);
				if (sizeof($a) <= 0)
				{
					addUser($_SESSION["user"]);
				}
				$q = "update topo_utilisateurs set utilisateur_derniere_connexion=NOW() where utilisateur_email='".$inData["email"]."'";
				$r = $GLOBALS["Bdd"]->query($q);
			}
		}
		
	break;

	case "Google":

		$as = new SimpleSAML_Auth_Simple("Google");
		// si on est pas authentifier, on lance l'authentification
		if (!$as->isAuthenticated())
			$as->requireAuth();
		if (isset($as) && $as->isAuthenticated()) 
		{

					
			$att = $as->getAttributes();


			/*while (list($k,$v) = each($att))
			{
				if (sizeof($v) == 1)
				{
					$_SESSION[$k] = $v[0];
				}
				else
				{
					$_SESSION[$k] = $v;
				}
			}*/



			if (isset($att["google_email"]))
			{
				$_SESSION['user'] = $att["google_email"][0];
				

				$q = "select * from topo_utilisateurs where utilisateur_email='".$_SESSION["user"]."'";
				$a = $GLOBALS["Bdd"]->fetch_all_array($q);
				if (sizeof($a) <= 0)
				{
					addUser($_SESSION["user"]);
				}
				$q = "update topo_utilisateurs set utilisateur_derniere_connexion=NOW() where utilisateur_email='".$inData["email"]."'";
				$r = $GLOBALS["Bdd"]->query($q);
			}
		}
		
	break;


	case "windowslive":

		$as = new SimpleSAML_Auth_Simple("windowslive");
		// si on est pas authentifier, on lance l'authentification
		if (!$as->isAuthenticated())
			$as->requireAuth();
		if (isset($as) && $as->isAuthenticated()) 
		{

					
			$att = $as->getAttributes();


			/*while (list($k,$v) = each($att))
			{
				if (sizeof($v) == 1)
				{
					$_SESSION[$k] = $v[0];
				}
				else
				{
					$_SESSION[$k] = $v;

				}
			}*/



			if (isset($att["windowslive_emails.account"]))
			{
				$_SESSION['user'] = $att["windowslive_emails.account"][0];
				

				$q = "select * from topo_utilisateurs where utilisateur_email='".$_SESSION["user"]."'";
				$a = $GLOBALS["Bdd"]->fetch_all_array($q);
				if (sizeof($a) <= 0)
				{
					addUser($_SESSION["user"]);
				}
				$q = "update topo_utilisateurs set utilisateur_derniere_connexion=NOW() where utilisateur_email='".$inData["email"]."'";
				$r = $GLOBALS["Bdd"]->query($q);
			}
		}
		
	break;


	case "Twitter":

		$as = new SimpleSAML_Auth_Simple("Twitter");
		// si on est pas authentifier, on lance l'authentification
		if (!$as->isAuthenticated())
			$as->requireAuth();
		if (isset($as) && $as->isAuthenticated()) 
		{

					
			$att = $as->getAttributes();


			/*while (list($k,$v) = each($att))
			{
				if (sizeof($v) == 1)
				{
					$_SESSION[$k] = $v[0];
				}
				else
				{
					$_SESSION[$k] = $v;
				}
			}*/



			if (isset($att["twitter_screen_n_realm"]))
			{
				$_SESSION['user'] = $att["twitter_screen_n_realm"][0];
				

				$q = "select * from topo_utilisateurs where utilisateur_email='".$_SESSION["user"]."'";
				$a = $GLOBALS["Bdd"]->fetch_all_array($q);
				if (sizeof($a) <= 0)
				{
					addUser($_SESSION["user"]);
				}
				$q = "update topo_utilisateurs set utilisateur_derniere_connexion=NOW() where utilisateur_email='".$inData["email"]."'";
				$r = $GLOBALS["Bdd"]->query($q);
			}
		}
		
	break;

}



session_commit();


	if (isset($_SESSION['goto']))
	{
		$_SESSION['goto'] = str_replace("logout.php","index.php",$_SESSION['goto']);
		header("location: ".$_SESSION['goto']);
	}
	else
	{
		header("location: index.php");
	}


?>
