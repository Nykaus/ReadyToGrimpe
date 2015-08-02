<?php
include_once(dirname(__FILE__)."/config.inc");
include_once(dirname(__FILE__)."/bdd.inc");
include_once(dirname(__FILE__)."/auth.inc");





function getElemDisplayName($elemType,$elemId,$path)
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
			return getElemDisplayName("si",$r[0]['id'] ," &gt; ".$r[0]['disp']." ".$path);
		case "sp":
			$q = "select depart_description_courte as disp, secteur_id as id from topo_depart where depart_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
			return getElemDisplayName("sc",$r[0]['id'] ," &gt; ".$r[0]['disp']." ".$path);
		case "w":
			$q = "select voie_nom as disp, depart_id as id from topo_voie where voie_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
                        if ($r[0]['disp'])
			      return getElemDisplayName("sp",$r[0]['id'] ," &gt; ".$r[0]['disp']." ".$path);
                        else
			      return getElemDisplayName("sp",$r[0]['id'] ,$path);
	}
}

function addMesVoies($uid,$wid,$quand,$valide,$data)
{
            // déjà présent ?
            $q = "select *  from topo_mesvoies where voie_id='".$wid."' and utilisateur_id='".$uid."'";
	    $r = $GLOBALS['Bdd']->fetch_all_array($q);
	    
            $data = str_replace("'","’",$data);
            if (sizeof($r) <= 0)
            {
                   $q = "insert into topo_mesvoies(voie_id,utilisateur_id,mesvoies_derniere_tentatives,mesvoies_valide,mesvoies_nombre_tentative,mesvoies_datas) values('".$wid."','".$uid."','".substr($quand,6,4).substr($quand,3,2).substr($quand,0,2)."','".$valide."','1','".$data."')";
		   $r = $GLOBALS["Bdd"]->query($q);
            }
            else
            {
                   $data = $r[0]['mesvoies_datas']."§".$data;
                   if ($quand < $r[0]['mesvoies_derniere_tentatives'])
                       $quand = $r[0]['mesvoies_derniere_tentatives'];

                   if ($valide || $r[0]['mesvoies_valide'])
                       $valide = 1;
                  
                   $setQuand = "";
                   if ($quand)
                         $setQuand = "mesvoies_derniere_tentatives='".substr($quand,6,4).substr($quand,3,2).substr($quand,0,2)."' ,";

		   $q = "update topo_mesvoies set $setQuand mesvoies_valide='".$valide."'  ,mesvoies_nombre_tentative=mesvoies_nombre_tentative+1 , mesvoies_datas ='".str_replace("'","\'",$data)."'    where voie_id='".$wid."' and utilisateur_id='".$uid."'";
		   $r = $GLOBALS["Bdd"]->query($q);
            }
}

		


function getMesVoies($uid,$nb=10,$filter="")
{
 	    $sqlFilter="";

		if (isset($filter["date"]) && $filter["date"])
		{
			$d = explode("/",$filter["date_debut"]);
			$f = explode("/",$filter["date_fin"]);
			$sqlFilter .= " and mesvoies_derniere_tentatives >= '".$d[2].$d[1].$d[0]."' and mesvoies_derniere_tentatives <= '".$f[2].$f[1].$f[0]."' ";
		}
		if (isset($filter["libelle"]) && $filter["libelle"])
		{
			switch($filter["libelle_type"])
			{
				case "w":
					$sqlFilter .= " and (voie_nom like '%".$filter["libelle"]."%' or  voie_description_courte like '%".$filter["libelle"]."%' or voie_description_longue like '%".$filter["libelle"]."%')";
				break;
				case "sc":
					$sqlFilter .= " and (secteur_nom like '%".$filter["libelle"]."%' or secteur_description_courte like '%".$filter["libelle"]."%' or secteur_description_longue like '%".$filter["libelle"]."%')";
				break;
				case "si":
					$sqlFilter .= " and (site_nom like '%".$filter["libelle"]."%' or site_description_courte like '%".$filter["libelle"]."%' or site_description_longue like '%".$filter["libelle"]."%')";
				break;
			}
		}

		if (isset($filter["etat"]) && $filter["etat"])
		{
			$sqlFilter .= " and mesvoies_datas like '%".$filter["etat"]."%'";
		}

		if (isset($filter["wid"]) && $filter["wid"])
		{
			$sqlFilter .= " and topo_voie.voie_id = '".$filter["wid"]."'";
		}


            $q = "select *  from topo_mesvoies,topo_site ,topo_secteur, topo_depart, topo_voie where utilisateur_id='".$uid."' and topo_mesvoies.voie_id = topo_voie.voie_id and topo_voie.depart_id = topo_depart.depart_id and topo_depart.secteur_id =topo_secteur.secteur_id and topo_secteur.site_id = topo_site.site_id   ".$sqlFilter." order by mesvoies_derniere_tentatives desc limit ".$nb;


	    $r = $GLOBALS['Bdd']->fetch_all_array($q);
            return $r;
}



function getMesVoiesBySite($sid)
{
 	   $uid = getAuthUserId();

            $q = "select topo_mesvoies.voie_id as wid, mesvoies_valide as v,mesvoies_nombre_tentative as nb from topo_mesvoies,topo_site ,topo_secteur, topo_depart, topo_voie where utilisateur_id='".$uid."' and topo_mesvoies.voie_id = topo_voie.voie_id and topo_voie.depart_id = topo_depart.depart_id and topo_depart.secteur_id =topo_secteur.secteur_id and topo_secteur.site_id = topo_site.site_id and topo_site.site_id = '".$sid."'";

	    $r = $GLOBALS['Bdd']->fetch_all_array($q);
            return $r;
}


?>
