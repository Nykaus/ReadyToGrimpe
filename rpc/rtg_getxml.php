<?php
@header('Content-Type: application/xml; charset=UTF-8');
$xml= array();
include_once(dirname(__FILE__)."/../inc/bdd.inc");
include_once(dirname(__FILE__)."/../inc/auth.inc");
include_once(dirname(__FILE__)."/../inc/json.inc");


function arrayToXML($x,$balise)
{
	//print_r($x);
	$j = array();
	if (is_array($x))
	{
		foreach( $x as $k=>$v)
		{
			$node = $k;
			$attr = "";
			if (is_int($k))
			{

				$node = $balise;
				$attr = " id=\"".$k."\"";
			}
			if ($node == "Bdd"
				|| $node == "KeySign"
				|| $node == "GeoportailKey"
				|| $node == "Auth"
				|| $node == "Right"
				|| substr($node,0,1) == "_"
			)
			{
				continue;
			}

			if (is_string($v) || is_int($v))
			{
				$j[] = "<".$node.$attr.">".$v."</".$node.">";
			}
			else if(is_array($v))
			{
				$j[] =  "<".$node.$attr.">".arrayToXML($v,$k)."</".$node.">";
			}
			else if(is_object($v))
			{
				if (isset($v->name) && isset($v->value))
				{
					$j[] = "<".$v->name.$attr.">".$v->value."</".$v->name.">";
				}
				else
				{
					$j[] = arrayToXML(get_object_vars($v),$k);
				}
			}
			else
			{
				$j[] = "<".$k." />";
				//echo $k.":\n";
				//print_r($v);
			}
		}
	}
	return implode("\n",$j);
}
function jsonToXML($json,$balise)
{
	$x = rtg_json_decode($json);
	if (is_object($x))
		$x = get_object_vars($x);

	$r = arrayToXML($x,$balise);
	if ($r != "")
	{
		//echo $r;
		return "<".$balise.">".$r."</".$balise.">";
	}
}

function toXml($d,$balise,$isLeef=false)
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
						$j[] = "<".$k.">".htmlentities($v,ENT_XML1)."</".$k.">";
						break;
					case "array_pi":
						if ($balise == "SI" && $v != "")
						{
							$q = "select topo_pi.pi_id as id
							,pi_type as type
							,pi_description_courte as descc
							,pi_description_longue as descl
							,pi_complements as comp
							,pi_lat as lat
							,pi_lon as lon
							from topo_pi
							where topo_pi.pi_id in (select distinct pi_id from topo_pi_site where site_id = ".$v.")";

							$j[] = toXml($GLOBALS['Bdd']->fetch_all_array($q),"PI");
						}
						break;
					case "array_si"; // on fait rien :) 
						break;
					case "array_sc":
						if ($balise == "SI" && $v != "")
						{
							$grpxml = array();
							$q = "select `topo_secteur`.`secteur_id` as id
							, `topo_secteur`.`site_id` as si, topo_site.site_public as public
							, `topo_secteur`.`secteur_nom` as name
							, `topo_secteur`.`secteur_photo` as pict
							, avg(`topo_depart`.`depart_lat`) as lat
							, avg(`topo_depart`.`depart_lon`) as lon
							, avg(`topo_voie`.`voie_cotation_indice` ) as cot
							,GROUP_CONCAT(DISTINCT topo_depart.depart_id ORDER BY topo_depart.depart_ordre,topo_depart.depart_lon + topo_depart.depart_lat  DESC SEPARATOR ',') as array_sp
							,secteur_description_courte as descc
							,secteur_description_longue as descl
							,secteur_complements as comp
							,secteur_groupe as groupe
							from topo_secteur , topo_depart, topo_voie , topo_site 
							where topo_secteur.secteur_id in (select distinct secteur_id from topo_secteur where site_id = ".$v.")
							and topo_secteur.secteur_id=topo_depart.secteur_id
							and topo_voie.depart_id = topo_depart.depart_id
							and topo_secteur.site_id = topo_site.site_id
							group by id
							order by topo_secteur.secteur_ordre";

							$_t = $GLOBALS['Bdd']->fetch_all_array($q);
							for ($scii=0;$scii<sizeof($_t);$scii++)
							{
								if ($_t[$scii]["groupe"])
								{
									$grpxml[$_t[$scii]["groupe"]][] = "<SC>".toXml($_t[$scii],"SC",true)."</SC>";
								}
								else
								{
									$j[] = "<SC>".toXml($_t[$scii],"SC",true)."</SC>";
								}
							}

							$q2 = "select `topo_secteur`.`secteur_id` as id
							, `topo_secteur`.`site_id` as si, topo_site.site_public as public
							, `topo_secteur`.`secteur_nom` as name
							,secteur_description_courte as descc
							,secteur_description_longue as descl
							,secteur_complements as comp
							,secteur_groupe as groupe			
							from topo_secteur, topo_site 
							where topo_secteur.secteur_id in (select distinct secteur_id from topo_secteur where site_id = ".$v.")
							and topo_secteur.secteur_id not in (select distinct secteur_id from topo_depart)
							group by id";
							$_t = $GLOBALS['Bdd']->fetch_all_array($q2);

							for ($scii=0;$scii<sizeof($_t);$scii++)
							{
								if ($_t[$scii]["groupe"])
								{
									$grpxml[$_t[$scii]["groupe"]][] = "<SC>".toXml($_t[$scii],"SC",true)."</SC>";
								}
								else
								{
									$j[] = "<SC>".toXml($_t[$scii],"SC",true)."</SC>";
								}
							}
							reset($grpxml);
							foreach($grpxml as $gname=>$gxml)
							{
								$j[] = "<GRP><name>".$gname."</name>".implode("",$gxml)."</GRP>";
							}

						}
						break;
					case "array_sp":
						if ($balise == "SC" && $v != "")
						{
							$q = "select `topo_depart`.`depart_id` as id, `topo_depart`.`secteur_id` as sc
							, `topo_depart`.`depart_lat` as lat
							, `topo_depart`.`depart_lon` as lon
							, `topo_depart`.`depart_exposition` as e
							, `topo_depart`.`depart_ordre` as ordre
							, avg(`topo_voie`.`voie_cotation_indice` ) as cot
							,GROUP_CONCAT(DISTINCT topo_voie.voie_id  ORDER BY voie_ordre,voie_cotation_indice SEPARATOR ',') as array_w
							,depart_description_courte as descc
							,depart_description_longue as descl
							,`depart_complements` as comp
							, topo_site.site_id as si, topo_site.site_public as public				
							from topo_depart, topo_voie  , topo_secteur, topo_site
							where topo_depart.depart_id in (".$v.")
							and topo_voie.depart_id = topo_depart.depart_id
							and topo_depart.secteur_id = topo_secteur.secteur_id
							and topo_secteur.site_id = topo_site.site_id
							group by id
							order by topo_depart.depart_ordre,topo_depart.depart_lon + topo_depart.depart_lat  DESC";

							$j[] = toXml($GLOBALS['Bdd']->fetch_all_array($q),"SP");
						}
						break;
					case "array_w":
						if ($balise == "SP" && $v != "")
						{
							$q = "select `topo_voie`.`voie_id` as id, `topo_voie`.`depart_id` as sp, `topo_voie`.`voie_nom` as name
							, CONCAT( `topo_voie`.`voie_cotation_indice` , `topo_voie`.`voie_cotation_lettre` , `topo_voie`.`voie_cotation_ext`) as cot
							, `topo_voie`.`voie_cotation_indice` as iCot
							, `topo_voie`.`voie_dessin` as p, `topo_voie`.`voie_hauteur` as h, `topo_voie`.`voie_type_depart` as td, `topo_voie`.`voie_degaine` as nbd, `topo_voie`.`voie_description_courte` as descc, `topo_voie`.`voie_description_longue` as descl, `topo_voie`.`voie_type` as t, `topo_voie`.`voie_complements` as comp  
							, topo_site.site_id as si, topo_site.site_public as public
							from topo_voie , topo_depart, topo_secteur, topo_site
							where voie_id in (".$v.") 
							and topo_voie.depart_id = topo_depart.depart_id
							and topo_depart.secteur_id = topo_secteur.secteur_id
							and topo_secteur.site_id = topo_site.site_id
							ORDER BY topo_voie.depart_id,voie_ordre,voie_cotation_indice";

							$j[] = toXml($GLOBALS['Bdd']->fetch_all_array($q),"W");
						}
						break;
						break;
					case "t":
					case "comp":
						$j[] = jsonToXML($v,$k);
						break;
					case "commentaires_data":
						$j[] = jsonToXML($v,$k);
						break;
					case "p":
						break;
					case "descl":
						$j[] = "<".$k.">".preg_replace("#'#","’",preg_replace("[\r\n]","§",htmlspecialchars($v)))."</".$k.">";
						break;
				}
			}
			return implode("\n",$j);
		}
		else
		{
			$j = array();
			for ($i=0;$i<sizeof($d);$i++)
			{
				$xml = toXML($d[$i],$balise,true);
				// on ajoute le whenchange et commentaires
				if ($balise != "CHANGE" && $balise != "COMMENTS")
				{
					$q = "select log_action as action, log_date as date  from topo_log where log_element_type = '".strtolower($balise)."' and log_element_id = '".$d[$i]['id']."' limit 1";
					$xml .= toXml($GLOBALS['Bdd']->fetch_all_array($q),"CHANGE");

					$q = "select  commentaires_qui,	commentaires_data, commentaires_date  from topo_commentaires where commentaires_element_type = '".strtolower($balise)."' and commentaires_element_id = '".$d[$i]['id']."' and commentaires_public = 1";
					$xml .= "<COMMENTS>".toXml($GLOBALS['Bdd']->fetch_all_array($q),"COMMENT")."</COMMENTS>";
				}
				$j[] = $xml;
			}
			return "<".$balise.">".implode("</".$balise."><".$balise.">",$j)."</".$balise.">";
		}

	}
}


$whereSite = " and site_public > 0";
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
		$whereSite = " and (site_public > 0 or topo_site.site_id in ('".implode("','",$ids)."'))";
	}


	if (isset($r['admin']))
	{
		$whereSite = " and 1 = 1";
	}
}

//echo $whereSite;

if (isset($_REQUEST["id"]))
{
	if (is_array($_REQUEST["id"]))
		$ids = implode(",",$_REQUEST["id"]);
	else
		$ids = $_REQUEST["id"];
	$whereSite .= " and `topo_site`.`site_id` in (".$ids.")";
}




$q = "select `topo_site`.`site_id` as id
,`topo_site`.`site_nom` as name
,`topo_site`.`site_id` as array_sc
,`topo_site`.`site_id` as array_pi
,site_description_courte as descc
,site_description_longue as descl
,site_hauteur_min as hmin
,site_hauteur_max as hmax
,site_complements as comp
,site_lat as lat
,site_lon as lon
,topo_site.site_id as si
,topo_site.site_public as public
,site_type
from topo_site
where 1=1
".$whereSite." 
group by id";

$xmlconfig = arrayToXML($config,"CONFIG");
$xmlsearch = arrayToXML($_REQUEST,"SEARCH");

echo "<RTG>".$xmlconfig.$xmlsearch.toXml($Bdd->fetch_all_array($q),"SI")."</RTG>";


?>
