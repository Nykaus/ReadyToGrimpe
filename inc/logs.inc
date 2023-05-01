<?php
include_once(dirname(__FILE__)."/config.inc");
include_once(dirname(__FILE__)."/bdd.inc");
include_once(dirname(__FILE__)."/auth.inc");




function getLogPathElem($elemType,$elemId,$path)
{
	switch ($elemType)
	{
		case "":
			return ">";
		default:
		case "pi":
		case "si":
			return ">".$elemType.$elemId.$path.">";
		case "sc":
			$q = "select site_id as id from topo_secteur where secteur_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
			return getLogPathElem("si",$r[0]['id'] ,">".$elemType.$elemId.$path);
		case "sp":
			$q = "select secteur_id as id from topo_depart where depart_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
			return getLogPathElem("sc",$r[0]['id'] ,">".$elemType.$elemId.$path);
		case "w":
			$q = "select depart_id as id from topo_voie where voie_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
			return getLogPathElem("sp",$r[0]['id'] ,">".$elemType.$elemId.$path);



	}
}

function getLogPathElemDisplayName($elemType,$elemId,$path)
{
	switch ($elemType)
	{
		default:
		case "pi":
			$q = "select pi_type, pi_description_courte from topo_pi where pi_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
			if (!isset($r[0]['pi_type']))
				return "";
			$disp = $GLOBALS['config']["PI"][$r[0]['pi_type']]["libelle"]." : ".$r[0]['pi_description_courte'];
			return " &gt; ".$disp." ".$path;

		case "si":
			$q = "select site_nom as disp from topo_site where site_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
			if (!isset($r[0]['disp']))
				return "";
			return " &gt; ".$r[0]['disp']." ".$path;
		case "sc":
			$q = "select secteur_nom as disp, site_id as id from topo_secteur where secteur_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
			return getLogPathElemDisplayName("si",$r[0]['id'] ," &gt; ".$r[0]['disp']." ".$path);
		case "sp":
			$q = "select depart_description_courte as disp, secteur_id as id from topo_depart where depart_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
			return getLogPathElemDisplayName("sc",$r[0]['id'] ," &gt; ".$r[0]['disp']." ".$path);
		case "w":
			$q = "select voie_nom as disp, depart_id as id from topo_voie where voie_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
			if ($r[0]['disp'])
				return getLogPathElemDisplayName("sp",$r[0]['id'] ," &gt; ".$r[0]['disp']." ".$path);
			else
				return getLogPathElemDisplayName("sp",$r[0]['id'] ,$path);
	}
}

$isPrivate = array();
function isPrivate($elemType,$path)
{
	$pathIds = explode(">",$path);
	if (sizeof($pathIds) < 1)
		return true;

	if (isset($isPrivate[$pathIds[1]]) )
	{
		return $isPrivate[$pathIds[1]];
	}

	switch ($elemType)
	{
		default:
		case "pi":
			$isPrivate[$pathIds[1]]	 = false;
			return false;
		case "si":
		case "sc":
		case "sp":
		case "w":
			$q = "select site_public from topo_site where site_id='".substr($pathIds[1],2)."'";

			$r = $GLOBALS['Bdd']->fetch_all_array($q);
			if (!isset($r[0]['site_public']))
			{
				$isPrivate[$pathIds[1]] = true;
				return true;
			}
			$isPrivate[$pathIds[1]] =($r[0]['site_public'] <= 0);
			return ($r[0]['site_public'] <= 0);
	}
}


function addLog($action,$elemType,$elemId)
{
	$qui = "ANNONYME";
	if (isset($_SESSION["user"]))
	{
		$qui = $_SESSION["user"];
	}

	$q = "select * from topo_log where log_qui='".$qui."' and log_element_type='".$elemType."' and log_element_id='".$elemId."' and log_date_derniere > (NOW() - INTERVAL 60 MINUTE)";

	$r = $GLOBALS['Bdd']->fetch_all_array($q);
	if (!empty($r))
	{
		$q = "update topo_log set log_date_derniere=NOW() where log_id = ".$r[0]['log_id'];
		$r = $GLOBALS['Bdd']->query($q);
	}
	else
	{
		$path = getLogPathElem($elemType,$elemId,"");
		$q = "insert into topo_log(log_qui,log_action,log_element_type,log_element_id,log_date,log_date_derniere,log_element_path) values('".$qui."','".$action."','".$elemType."','".$elemId."',NOW(),NOW(),'".$path."')";

		$r = $GLOBALS['Bdd']->query($q);
	}

}

function getLogs($elemType,$elemId)
{
	$path = getLogPathElem($elemType,$elemId,"");
	$q = "select DATE_ADD(log_date_derniere, INTERVAL 31 DAY) > NOW() as new, DATE_FORMAT(log_date_derniere,'%d-%m-%Y') as date,log_element_type,log_element_id,log_element_path,log_qui,log_action from topo_log where log_id in (select max(log_id) from topo_log where log_element_path like '".$path."%' or log_element_path = '".$path."' group by log_element_path) order by log_date_derniere desc limit 100";
	$r = $GLOBALS['Bdd']->fetch_all_array($q);
	return $r;
}

function getTableLogs($logs,$embeded=false)
{
	$action["add"] = "Création";
	$action["mod"] = "Mise à jour";
	$action["del"] = "Suppression";
	$action["mod site"] = "-";
	$target = "";
	if ($embeded)
	{
		$target=" target=\"RTG\"";
	}

	$allow['si'] = array();
	$allow['pi'] = array();
	if (hasRight())
	{
		$r = getUserRight();
		if (isset($r['SIWrite']) && is_array($r['SIWrite']) )
		{
			$allow['si'] = $r['SIWrite'];
		}
		if (isset($r['PIWrite']) && is_array($r['PIWrite']) )
		{
			$allow['pi'] = $r['SIWrite'];
		}
		$isAdmin = true;
	}
	$count=0;
	$fait = array();
	$target="";
	$table="";
	$comments ="";
	if ($embeded)
		$target=" target=\"_blank\"";

	for($i=0;$i<sizeof($logs);$i++)
	{
		$paths = explode(">",$logs[$i]["log_element_path"]);
		if (@preg_match("/^(si|pi)([[:digit:]]+)$/i",$paths[1],$rootElem))
		{
			$viewAllow = !isPrivate($logs[$i]["log_element_type"],$logs[$i]["log_element_path"]);
			//echo  $logs[$i]["log_element_path"]." ".(($viewAllow)?"true":"false")."<br/>";

			if (isset($isAdmin) || in_array($rootElem[2],$allow[$rootElem[1]]))
			{
				$qui = "<td class=\"hidden-xs hidden-sm hidden-md\">".$logs[$i]["log_qui"]."</td>";
				$viewAllow = true;
			}
			else
			{
				$qui = "<td></td><td></td>";
			}

			$name = getLogPathElemDisplayName($logs[$i]["log_element_type"],$logs[$i]["log_element_id"],"");
			if ($name && $count < $GLOBALS["config"]["Log"]["nbAff"] && !isset($fait[$name]) &&  $viewAllow)
			{
				$count++;
				$fait[$name] = true;
				$table .= "<tr><td class=\"hidden-xs\">".(($logs[$i]["new"])?"<b class=\"new\">Hot</b>":"")."</td><td>".$logs[$i]["date"]."</td><td>";


				$table .= "<div class=\"breadcrumb\">";
				$elsDisp = explode("&gt;",$name);
				$elsPath = explode(">",$logs[$i]["log_element_path"]);
				for ($xx=1;$xx<sizeof($elsDisp);$xx++)
					$table .= "<a $target href=\"".preg_replace("/^(si|sc|sp|w|pi)([0-9]*)/",'/\1/\2',$elsPath[$xx])."\">".$elsDisp[$xx]."</a>";
				if(isset($comments) && isset($comments[$i])){
					$table .= "<a $target href=\"".$comments[$i]->elemUrl."\"></a></div>";
				}

				/*<a $target href=\"http://".$GLOBALS["_SERVER"]["HTTP_HOST"]."".$GLOBALS['config']["BaseUrl"]."".$logs[$i]["log_element_type"]."/".$logs[$i]["log_element_id"]."\">".$name."</a>";*/

				$table .= "</td><td class=\"hidden-xs hidden-sm \">".$action[$logs[$i]["log_action"]]."</td>".$qui."</tr>";
			}
		}
	}
	if ($table)
		return "<table class=\"table\">".$table."</table>";
}



?>
