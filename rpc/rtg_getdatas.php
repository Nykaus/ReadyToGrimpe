<?php
header('Content-Type: application/json; charset=UTF-8');
$json = array();


include_once("../inc/bdd.inc");
include_once("../inc/auth.inc");
include_once("../inc/mesvoies.inc");

function getPiFromSi($siid)
{
	$r = array();
	$q = "select distinct pi_id from topo_pi_site where site_id=$siid";

	$d = $GLOBALS['Bdd']->fetch_all_array($q);
	if (!empty($d))
	{
		if (isset($d['pi_id']))
			return $d['pi_id'];

		for ($i=0;$i<sizeof($d);$i++)
		{
			$r[] = $d[$i]['pi_id'];
		}
	}
	return implode(',',$r);
}

function toJson($d,$isLeef=false)
{
	if (!empty($d))
	{

		if ($isLeef)
		{

			$leef=$d;
			if (!$isLeef)
				$leef = $d[0];
			$j = array();

			foreach ($leef as $k => $v)
			{
				switch($k)
				{
					default:
						$j[] = "\"".$k."\":\"".$v."\"";
						break;
					case "array_pi":
						$j[] = "\"".str_replace("array_","",$k)."\":[". getPiFromSi($v)."]";
						break;
					case "array_sc":
						if ($v)
						{
							$q = "select secteur_id, secteur_groupe, secteur_ordre, groupe_ordre
							from  topo_secteur 
							LEFT JOIN topo_secteur_groupe 
							ON topo_secteur.secteur_groupe = topo_secteur_groupe.groupe_name
							where secteur_id in (".$v.")
							order by groupe_ordre,secteur_ordre";
							$scO = $GLOBALS['Bdd']->fetch_all_array($q);
							$varray = array();
							for($scoi=0;$scoi<sizeof($scO);$scoi++)
							{
								$varray[] = $scO[$scoi]["secteur_id"];
							}
							$j[] = "\"".str_replace("array_","",$k)."\":[".implode(",",$varray)."]";
						}
						else
							$j[] = "\"".str_replace("array_","",$k)."\":[]";
						break;
					case "array_sp":
					case "array_w":
						$j[] = "\"".str_replace("array_","",$k)."\":[".$v."]";
						break;
					case "t":
						if ($v)
							$j[] = "\"".$k."\":".$v;
						else
							$j[] = "\"".$k."\":{}";
						break;
					case "comp":
						if (!preg_match("/^{.*}$/",$v) && !preg_match("/^\[.*\]$/",$v))
							$v = "\"".preg_replace('/"/','\\"',$v)."\"";

						if ($v)
							$j[] = "\"".$k."\":".$v;
						break;
					case "p":
						$j[] = "\"".$k."\":\"".preg_replace("/'/","’",preg_replace('/"/','\"',$v))."\"";
						break;
					case "descl":
						$j[] = "\"".$k."\":\"".preg_replace("/'/","’",preg_replace("/[\r\n]/","§",htmlspecialchars($v)))."\"";
						break;
				}
			}
			return "{".implode(',',$j)."}";
		}
		else
		{
			$j = array();
			for ($i=0;$i<sizeof($d);$i++)
			{
				$j[] = toJson($d[$i],true);
			}
			return "{multi:[".implode(',',$j)."]}";
		}

	}
}


$whereSite = "site_public > 0";
if (hasRight())
{
	$r = getUserRight();
	if (isset($r['SIRead']) && is_array($r['SIRead']) )
		$ids = $r['SIRead'];

	if (isset($r['SIWrite']) && is_array($r['SIWrite']) )
		$ids = $r['SIWrite'];

	if (isset($r['SIWrite']) && is_array($r['SIWrite']) && isset($r['SIRead']) && is_array($r['SIRead']))
		$ids = array_merge($r['SIRead'],$r['SIWrite']);

	if (isset($ids) && is_array($ids))
	{
		$whereSite = "(site_public > 0 or topo_site.site_id in ('".implode("','",$ids)."'))";
	}


	if (isset($r['admin']))
	{
		$whereSite = "1 = 1";
	}
}

//echo $whereSite;
$ids ="";
if(isset($_REQUEST["id"])){
if (is_array($_REQUEST["id"]))
	$ids = implode(",",$_REQUEST["id"]);
else
	$ids = $_REQUEST["id"];
}


if (isset($_REQUEST["type"]))
{
	switch($_REQUEST["type"])
	{
		case "w":
			if ($ids == "") {die("{}");}
			$q = "select `topo_voie`.`voie_id` as id, `topo_voie`.`depart_id` as sp, `topo_voie`.`voie_nom` as name
		, CONCAT( `topo_voie`.`voie_cotation_indice` , `topo_voie`.`voie_cotation_lettre` , `topo_voie`.`voie_cotation_ext`) as cot
		, `topo_voie`.`voie_dessin` as p, `topo_voie`.`voie_hauteur` as h, `topo_voie`.`voie_type_depart` as td, `topo_voie`.`voie_degaine` as nbd, `topo_voie`.`voie_description_courte` as descc, `topo_voie`.`voie_description_longue` as descl, `topo_voie`.`voie_type` as t, `topo_voie`.`voie_complements` as comp  
		, topo_site.site_id as si, topo_site.site_public as public
		from topo_voie , topo_depart, topo_secteur, topo_site
		where voie_id in (".$ids.") 
		and topo_voie.depart_id = topo_depart.depart_id
		and topo_depart.secteur_id = topo_secteur.secteur_id
		and topo_secteur.site_id = topo_site.site_id
		and ".$whereSite ."
		ORDER BY topo_voie.depart_id,voie_ordre,voie_cotation_indice";
			echo  toJson($Bdd->fetch_all_array($q));
			exit;
			break;
		case "sp":
			if ($ids == "") {die("{}");}
			$q = "select `topo_depart`.`depart_id` as id, `topo_depart`.`secteur_id` as sc
			, `topo_depart`.`depart_lat` as lat
			, `topo_depart`.`depart_lon` as lon
			, `topo_depart`.`depart_exposition` as e
			, avg(`topo_voie`.`voie_cotation_indice` ) as cot
			,GROUP_CONCAT(DISTINCT topo_voie.voie_id  ORDER BY topo_voie.depart_id,voie_ordre,voie_cotation_indice SEPARATOR ',') as array_w
			,depart_description_courte as descc
			,depart_description_longue as descl
			,`depart_complements` as comp
			, topo_site.site_id as si, topo_site.site_public as public				
			from topo_depart, topo_voie  , topo_secteur, topo_site
			where topo_depart.depart_id in (".$ids.")
			and topo_voie.depart_id = topo_depart.depart_id
			and topo_depart.secteur_id = topo_secteur.secteur_id
			and topo_secteur.site_id = topo_site.site_id
			and ".$whereSite ."
			group by id";
			$r = $Bdd->fetch_all_array($q);
			if (sizeof($r) > 0){ echo  toJson($r); }
			else
			{
				$q = "select `topo_depart`.`depart_id` as id, `topo_depart`.`secteur_id` as sc
				, `topo_depart`.`depart_lat` as lat
				, `topo_depart`.`depart_lon` as lon
				, `topo_depart`.`depart_exposition` as e
				, 'n' as cot
				, '' as array_w
				,depart_description_courte as descc
				,depart_description_longue as descl
				,`depart_complements` as comp
				, topo_site.site_id as si, topo_site.site_public as public				
				from topo_depart, topo_secteur, topo_site
				where topo_depart.depart_id in (".$ids.")
				and topo_depart.secteur_id = topo_secteur.secteur_id
				and topo_secteur.site_id = topo_site.site_id
				and ".$whereSite ;
				$r = toJson($Bdd->fetch_all_array($q));
				if ($r)
				{
					echo $r;
				}
				else
				{
					echo "{}";
				}
			}
			exit;
			break;
		case "sc":
			if ($ids == "") {die("{}");}
			$q = "select `topo_secteur`.`secteur_id` as id
			, `topo_secteur`.`site_id` as si, topo_site.site_public as public
			, `topo_secteur`.`secteur_nom` as name
			, `topo_secteur`.`secteur_photo` as pict
			, avg(`topo_voie`.`voie_cotation_indice` ) as cot
			,GROUP_CONCAT(DISTINCT topo_depart.depart_id ORDER BY topo_depart.depart_ordre,topo_depart.depart_lon + topo_depart.depart_lat  DESC SEPARATOR ',') as array_sp
			,GROUP_CONCAT(DISTINCT topo_voie.voie_id ORDER BY  topo_depart.depart_ordre,topo_depart.depart_lon + topo_depart.depart_lat,topo_voie.depart_id,voie_ordre,voie_cotation_indice DESC  SEPARATOR ',') as array_w
			,secteur_description_courte as descc
			,secteur_description_longue as descl
			,secteur_complements as comp		
 			,secteur_groupe as groupe
			from topo_secteur , topo_depart, topo_voie , topo_site 
			where topo_secteur.secteur_id in (".$ids.")
			and topo_secteur.secteur_id=topo_depart.secteur_id
			and topo_voie.depart_id = topo_depart.depart_id
			and topo_secteur.site_id = topo_site.site_id
			and ".$whereSite." 
			group by id";
			$r = toJson($Bdd->fetch_all_array($q));
			if ($r)
			{
				echo $r;
			}
			else
			{
				$q = "select `topo_secteur`.`secteur_id` as id
				, `topo_secteur`.`site_id` as si, topo_site.site_public as public
				, `topo_secteur`.`secteur_nom` as name
				,'d' as cot
				,'' as array_sp
				,'' as array_w
				,secteur_description_courte as descc
				,secteur_description_longue as descl
				,secteur_complements as comp	
				,secteur_groupe as groupe						
				from topo_secteur, topo_site
				where topo_secteur.secteur_id in (".$ids.")
				and topo_secteur.site_id = topo_site.site_id
				and ".$whereSite;
				$r = toJson($Bdd->fetch_all_array($q));
				if ($r)
				{
					echo $r;
				}
				else
				{
					echo "{}";
				}
			}
			exit;
			break;
		case "si":
			if ($ids == "") {die("{}");}

			$q = "select `topo_site`.`site_id` as id
			,`topo_site`.`site_nom` as name
			,GROUP_CONCAT(DISTINCT topo_secteur.secteur_id ORDER BY secteur_ordre  SEPARATOR ',') as array_sc
			,topo_site.site_id as array_pi
			,site_description_courte as descc
			,site_description_longue as descl
			,site_hauteur_min as hmin
			,site_hauteur_max as hmax
			,site_complements as comp
			,site_url_achat as urlachat
			, topo_site.site_id as si
			, topo_site.site_public as public
			, topo_site.site_type as type
			from topo_secteur , topo_site
			where topo_secteur.site_id = topo_site.site_id
			and topo_site.site_id in (".$ids.")
			and ".$whereSite."
			group by id";


			$r = toJson($Bdd->fetch_all_array($q));
			if ($r)
				echo $r;
			else
			{


				$q = "select `topo_site`.`site_id` as id
							,`topo_site`.`site_nom` as name
							,'' as array_sc
							,topo_site.site_id as array_pi
							,site_description_courte as descc
							,site_description_longue as descl
							,site_complements as comp
							,site_hauteur_min as hmin
							,site_hauteur_max as hmax
							,site_lat as lat
							,site_lon as lon							
							, topo_site.site_id as si, topo_site.site_public as public
							from topo_site
							where topo_site.site_id in (".$ids.") and ".$whereSite;

				echo toJson($Bdd->fetch_all_array($q));
			}
			exit;
			break;
		case "pi":
			if ($ids == "") {die("{}");}
			$q = "select topo_pi.pi_id as id
			,pi_type as type
			,pi_lat as lat
			,pi_lon as lon
			,pi_description_courte as descc
			,pi_description_longue as descl
			,GROUP_CONCAT(DISTINCT topo_pi_site.site_id SEPARATOR ',') as array_si
			,pi_complements as comp
			from topo_pi, topo_pi_site
			where topo_pi.pi_id in (".$ids.")
			and topo_pi.pi_id = topo_pi_site.pi_id
			group by topo_pi.pi_id";


			$r = toJson($Bdd->fetch_all_array($q));
			if ($r)
				echo $r;
			else
			{
				echo "{}";
			}
			exit;
			break;
		case "right":
			$r = "{}";
			$d = getUserRight();
			if (!empty($d))
			{
				if (is_array($d))
				{
					$j = array();
					foreach ($d as $k=>$v)
					{
						if (is_array($v))
							$j[] = $k.":['".implode("','",$v)."']";
					}
					$r = "{".implode(',',$j)."}";
				}
			}
			echo $r;
			break;
		case "listsi":
			$q = "select site_id,site_nom,site_public from topo_site where site_nom like '".$search."%' and ".$whereSite." order by site_nom";
			$a = $GLOBALS["Bdd"]->fetch_all_array($q);
			$html = "";
			for ($i=0;$i<sizeof($a);$i++)
			{
				$icon = "glyphicon-map-marker";
				if ($a[$i]['site_public'] == 0)
				{
					$icon = "glyphicon-lock";
				}

				$html .= "<a class=\"btn btn-list btn-default glyphicon ".$icon."\" onclick=\"".$_REQUEST["calljs"]."('".$a[$i]['site_id']."')\"> ".$a[$i]['site_nom']."</a>";
			}
			echo "{html:'".preg_replace("/'/","\\'",$html)."'}";

			break;
		case "listsc":
			$q = "select secteur_id,secteur_nom from topo_secteur where site_id = '".$_REQUEST['id']."' and ".$whereSite;
			$a = $GLOBALS["Bdd"]->fetch_all_array($q);
			$html = "";
			for ($i=0;$i<sizeof($a);$i++)
			{
				$html .= "<a class=\"btn btn-list btn-default glyphicon glyphicon-th-list\" onclick=\"".$_REQUEST["calljs"]."('".$a[$i]['secteur_id']."')\"> ".$a[$i]['secteur_nom']."</a>";
			}
			echo "{html:'".preg_replace("/'/","\\'",$html)."'}";

			break;
		case "listsp":
			$q = "select depart_id,depart_description_courte from topo_depart where secteur_id = '".$_REQUEST['id']."'";
			$a = $GLOBALS["Bdd"]->fetch_all_array($q);
			$html = "";
			for ($i=0;$i<sizeof($a);$i++)
			{
				$html .= "<a class=\"btn btn-list btn-default glyphicon glyphicon-th-list\" onclick=\"".$_REQUEST["calljs"]."('".$a[$i]['depart_id']."')\"> ".$a[$i]['depart_description_courte']."</a>";
			}
			echo "{html:'".preg_replace("/'/","\\'",$html)."'}";

			break;
		case "listw":
			$q = "select voie_id,voie_nom from topo_voie where depart_id = '".$_REQUEST['id']."'";
			$a = $GLOBALS["Bdd"]->fetch_all_array($q);
			$html = "";
			for ($i=0;$i<sizeof($a);$i++)
			{
				$html .= "<a class=\"btn btn-list btn-default glyphicon glyphicon-th-list\" onclick=\"".$_REQUEST["calljs"]."('".$a[$i]['voie_id']."')\"> ".$a[$i]['voie_nom']."</a>";
			}
			echo "{html:'".preg_replace("/'/","\\'",$html)."'}";

			break;
		case "mesvoies":
			if ($ids == "") {die("{}");}
			$j = toJson(getMesVoiesBySite($ids));
			if ($j == "") {die("{}");}
			echo $j;

			break;

	}
}

?>
