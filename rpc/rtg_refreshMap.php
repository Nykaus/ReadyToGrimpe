<?php 
header('Content-Type: application/json;  charset=UTF-8');
$json = array();
include_once("../inc/auth.inc");
include_once("../inc/bdd.inc");


// on recherche les droit en lecture de site
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
	$notId = "";
	$site_id = array();
	// les recoupements ...
	switch ($_REQUEST["zoomLevel"])
	{
		default:
			$ratiolat = pow(10, $_REQUEST["zoomLevel"]);
			$ratiolon = pow(10, $_REQUEST["zoomLevel"]);
		break;
		case 11:
		case 10:
			$ratiolat = pow(10, $_REQUEST["zoomLevel"]/($_REQUEST["zoomLevel"]*2));
			$ratiolon = pow(10, $_REQUEST["zoomLevel"]/($_REQUEST["zoomLevel"]*2));		
		break;
		case 9:
		case 8:
		case 7:
		case 6:
		case 5:
		case 4:
		case 3:
		case 2:
		case 1:
			$ratiolat = pow(10, $_REQUEST["zoomLevel"]/($_REQUEST["zoomLevel"]-9));
			$ratiolon = pow(10, $_REQUEST["zoomLevel"]/($_REQUEST["zoomLevel"]-9));
		break;

	}
	

        $q = "select * 
        from (select avg(site_lat) as lat 
        	,avg(site_lon) as lon 
        	,round(site_lat*".$ratiolat.") as latr 
        	,round(site_lon*".$ratiolon.") as lonr 
        	,GROUP_CONCAT(DISTINCT site_id SEPARATOR ',') as grpsi 
        	,count(*) as C 
        	from topo_site 
        	where site_lat > ".$_REQUEST["swLat"]."
			and site_lat < ".$_REQUEST["neLat"]."
			and site_lon > ".$_REQUEST["swLng"]."
			and site_lon < ".$_REQUEST["neLng"]."
			and site_lat != 0
			and site_lon != 0
		and ".$whereSite."        	
        	group by latr, lonr) as T 
        where C > 1 ";
	$group_sites = $Bdd->fetch_all_array($q);
	
	if (!isset($_REQUEST["printmode"]))
	{
		if (!empty($group_sites))
		{
			for($i=0;$i<count($group_sites);$i++)
			{
				$site_id = array_merge ( $site_id,explode(",",$group_sites[$i]['grpsi']));
				$json[] = "[\"si-Multi\",\"".$group_sites[$i]["grpsi"]."\",[".$group_sites[$i]["lat"].",".$group_sites[$i]["lon"]."],\"".$group_sites[$i]["minc"]."\",\"".$group_sites[$i]["avgc"]."\",\"".$group_sites[$i]["maxc"]."\",\"".$group_sites[$i]["C"]."\",\"".$group_sites[$i]["latr"]."\",\"".$group_sites[$i]["lonr"]."\",\"".$_REQUEST["zoomLevel"]."\"]";
			}
		}
		$notId = "";
		if (sizeof($site_id) > 0)
			$notId = " topo_site.site_id not in (".implode(",", $site_id).") and ";
	}

$zoomGpx = 15;
function getGpx($comp)
{
	if (preg_match ( "/\"name\":\"gpx\",\"value\":\"([^\"]*)\"/" , $comp , $matches))
	{
		return $matches[1];		
	}
}

// les sites avec des voies
	$q = "select topo_site.site_id as id, max(depart_lat) lat, max(depart_lon) lon, round(min(topo_voie.voie_cotation_indice)) minc, round(max(topo_voie.voie_cotation_indice)) maxc, round(avg(topo_voie.voie_cotation_indice)) avgc, site_type, site_complements
	from topo_site, topo_secteur, topo_depart, topo_voie
	where topo_secteur.site_id = topo_site.site_id
	and topo_voie.depart_id = topo_depart.depart_id
	and topo_depart.secteur_id = topo_secteur.secteur_id 
	and depart_lat > ".$_REQUEST["swLat"]."
	and depart_lat < ".$_REQUEST["neLat"]."
	and depart_lon > ".$_REQUEST["swLng"]."
	and depart_lon < ".$_REQUEST["neLng"]."
	and ".$notId.$whereSite."
	group by topo_site.site_id  order by lon,lat";
	$sites = $Bdd->fetch_all_array($q);

	
	if (!empty($sites))
	{
		for($i=0;$i<count($sites);$i++)
		{
			$site_id[] = $sites[$i]["id"];
			$json[] = "[\"si-".$sites[$i]["site_type"]."\",".$sites[$i]["id"].",[".$sites[$i]["lat"].",".$sites[$i]["lon"]."],\"".$sites[$i]["minc"]."\",\"".$sites[$i]["avgc"]."\",\"".$sites[$i]["maxc"]."\",\"".$ratiolat."\"]";

			if ($_REQUEST["zoomLevel"] > $zoomGpx)
			{
				//depart_complements, voie_complements, secteur_complements, site_complements
				$gpx = getGpx($sites[$i]["site_complements"]);
				if ($gpx)
					$json[] = "[\"gpx\",\"".$gpx."\",\"gpx/".$gpx.".gpx\"]";
			}


		}



		$q = "select topo_secteur.secteur_id as id, round(min(topo_voie.voie_cotation_indice)) minc, round(max(topo_voie.voie_cotation_indice)) maxc , round(avg(topo_voie.voie_cotation_indice)) avgc, secteur_complements 
		from topo_site, topo_secteur, topo_depart, topo_voie
		where topo_secteur.site_id in (".implode(",", $site_id).")
		and topo_voie.depart_id = topo_depart.depart_id
		and topo_depart.secteur_id = topo_secteur.secteur_id
		group by topo_secteur.secteur_id"; 


		$sc = $Bdd->fetch_all_array($q);
		if (!empty($sc))
		{
			for($i=0;$i<count($sc);$i++)
			{
				  $json[] = "[\"sc\",".$sc[$i]["id"].",[],\"".$sc[$i]["minc"]."\",\"".$sc[$i]["avgc"]."\",\"".$sc[$i]["maxc"]."\"]";
				if ($_REQUEST["zoomLevel"] > $zoomGpx)
				{
					//depart_complements, voie_complements, secteur_complements, site_complements
					$gpx = getGpx($sites[$i]["secteur_complements"]);
					if ($gpx)
						$json[] = "[\"gpx\",\"".$gpx."\",\"gpx/".$gpx.".gpx\"]";
				}				  
			}
		}





		$q = "select topo_depart.depart_id as id, depart_lat lat, depart_lon lon, round(min(topo_voie.voie_cotation_indice)) minc, round(max(topo_voie.voie_cotation_indice)) maxc , round(avg(topo_voie.voie_cotation_indice)) avgc, depart_complements
		from topo_site, topo_secteur, topo_depart, topo_voie
		where topo_secteur.site_id in (".implode(",", $site_id).")
		and topo_voie.depart_id = topo_depart.depart_id
		and topo_depart.secteur_id = topo_secteur.secteur_id
		group by topo_depart.depart_id  order by lon,lat"; 


		$sp_coor = array();
		$sp = $Bdd->fetch_all_array($q);
		if (!empty($sp))
		{
			for($i=0;$i<count($sp);$i++)
			{
				  $json[] = "[\"sp\",".$sp[$i]["id"].",[".$sp[$i]["lat"].",".$sp[$i]["lon"]."],\"".$sp[$i]["minc"]."\",\"".$sp[$i]["avgc"]."\",\"".$sp[$i]["maxc"]."\"]";
				if ($_REQUEST["zoomLevel"] > $zoomGpx)
				{
					//depart_complements, voie_complements, secteur_complements, site_complements
					$gpx = getGpx($sites[$i]["depart_complements"]);
					if ($gpx)
						$json[] = "[\"gpx\",\"".$gpx."\",\"gpx/".$gpx.".gpx\"]";
				}					  
			}
		}

	}
	
	
	$q = "select topo_pi.pi_id as id, pi_lat as lat, pi_lon as lon, pi_type as type, pi_description_courte as descc, pi_complements
	from topo_pi_site, topo_pi, topo_site
	where topo_pi.pi_id = topo_pi_site.pi_id
	and topo_pi_site.site_id = topo_site.site_id
	and pi_lat > ".$_REQUEST["swLat"]."
	and pi_lat < ".$_REQUEST["neLat"]."
	and pi_lon > ".$_REQUEST["swLng"]."
	and pi_lon < ".$_REQUEST["neLng"]."
	and ".$whereSite;
	
//	echo $q;
	
	$pis = $Bdd->fetch_all_array($q);
	if (!empty($pis))
	{
		for($i=0;$i<count($pis);$i++)
		{
			$json[] = "[\"pi\",".$pis[$i]["id"].",[".$pis[$i]["lat"].",".$pis[$i]["lon"]."],\"".$pis[$i]["type"]."\",\"".$pis[$i]["descc"]."\"]";
				if ($_REQUEST["zoomLevel"] > $zoomGpx)
				{
					//depart_complements, voie_complements, secteur_complements, site_complements
					$gpx = getGpx($pis[$i]["pi_complements"]);
					if ($gpx)
						$json[] = "[\"gpx\",\"".$gpx."\",\"gpx/".$gpx.".gpx\"]";
				}				
		}
	}	
	
	
	
	
	$notId = "";
	if (sizeof($site_id) > 0)
		$notId = " site_id not in (".implode(",", $site_id).") and ";
	// les sites sans voies
	$q = "select topo_site.site_id as id, site_lat lat, site_lon lon, '-' minc, '-' maxc, '-' avgc, site_type, site_complements
	from topo_site
	where site_lat > ".$_REQUEST["swLat"]."
	and site_lat < ".$_REQUEST["neLat"]."
	and site_lon > ".$_REQUEST["swLng"]."
	and site_lon < ".$_REQUEST["neLng"]."
	and site_lat != 0
	and site_lon != 0
	and ".$notId.$whereSite;
	
	$sites = $Bdd->fetch_all_array($q);

	$site_id = array();
	if (!empty($sites))
	{
		for($i=0;$i<count($sites);$i++)
		{
			$site_id[] = $sites[$i]["id"];
			$json[] = "[\"si-".$sites[$i]["site_type"]."\",".$sites[$i]["id"].",[".$sites[$i]["lat"].",".$sites[$i]["lon"]."],\"".$sites[$i]["minc"]."\",\"".$sites[$i]["avgc"]."\",\"".$sites[$i]["maxc"]."\"]";
			if ($_REQUEST["zoomLevel"] > $zoomGpx)
			{
				//depart_complements, voie_complements, secteur_complements, site_complements
				$gpx = getGpx($sites[$i]["site_complements"]);
				if ($gpx)
					$json[] = "[\"gpx\",\"".$gpx."\",\"gpx/".$gpx.".gpx\"]";
			}
		}
	}
	//echo $q;


      




echo "[".implode(",",$json)."]";
?>

