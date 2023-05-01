<?php

try {
	header('Content-Type: application/json; charset=UTF-8');
	include_once("../inc/bdd.inc");
	include_once("../inc/auth.inc");
	include_once("../inc/logs.inc");
	include_once("../inc/json.inc");
	include_once("../inc/comment.inc");
	include_once("../inc/mesvoies.inc");



// on recherche les droit en lecture de site
	$isAdmin = false;
	$allowSite;
	if (hasRight())
	{
		$r = getUserRight();
		if (isset($r['SIWrite']) && is_array($r['SIWrite']) )
		{
			$allowSites = $r['SIWrite'];
		}

		if (isset($r['admin']))
		{
			$isAdmin = true;
		}
	}




	function getSiidFromScid($id)
	{
		$q = "select topo_secteur.site_id as id from topo_secteur where topo_secteur.secteur_id=".$id."";
		$r = $GLOBALS['Bdd']->fetch_all_array($q);
		if (!empty($r))
		{
			return $r[0]['id'];
		}
		return "none";
	}
	function getSiidFromSpid($id)
	{
		$q = "select topo_secteur.site_id as id from topo_secteur, topo_depart where topo_depart.secteur_id = topo_secteur.secteur_id and topo_depart.depart_id =".$id;
		$r = $GLOBALS['Bdd']->fetch_all_array($q);
		if (!empty($r))
		{
			return $r[0]['id'];
		}
		return "none";
	}
	function getSiidFromWid($id)
	{
		$q = "select topo_secteur.site_id as id  from topo_secteur, topo_depart, topo_voie where  topo_voie.depart_id = topo_depart.depart_id and topo_depart.secteur_id = topo_secteur.secteur_id and topo_voie.voie_id =".$id;
		$r = $GLOBALS['Bdd']->fetch_all_array($q);
		if (!empty($r))
		{
			return $r[0]['id'];
		}
		return "none";
	}

	function array2sql($a)
	{
		foreach($a as $k=>$v)
		{
			switch($k)
			{
				case "voie_dessin":
					$u[] = $k."='".preg_replace('\{"items":\[(.*)\]\}',"\\1",$v)."'";
					break;
				case "voie_type":
					$u[] = $k."='".preg_replace("#'#",'’',rtg_json_encode($v))."'";
					break;
				default:
					$u[] = $k."='".preg_replace("#'#",'’',$v)."'";
					break;
			}
		}
		return 	implode($u,',');
	}
	$id="";
	switch($_REQUEST['action'])
	{

		case "piAddSite":
			if (!isset($pi_id)) { $id = $pi_id = $_REQUEST['pi_id']; }
			if (!isset($si_id)) { $si_id = $_REQUEST['addSi']; }
			if ($isAdmin || in_array($id,$allowSites))
			{
				// on dé lie
				$q = "insert into topo_pi_site(pi_id,site_id) values(".$pi_id.",".$si_id.")";
				$Bdd->query($q);
				addLog("add site","pi",$id);
			}
			break;
		case "piDelSite":
			if (!isset($pi_id)) { $id = $pi_id = $_REQUEST['pi_id']; }
			if (!isset($si_id)) { $si_id = $_REQUEST['delSi']; }
			if ($isAdmin || in_array($id,$allowSites))
			{
				$q = "delete from topo_pi_site where pi_id=".$id." and site_id=".$si_id;
				$Bdd->query($q);
				addLog("del","pi",$id);
			}
			break;
		case "piInsert":
			if ($isAdmin)
			{
				$q = "insert into topo_pi(pi_id) values(NULL)";
				$r = $Bdd->query($q);
				$id = $pi_id = $Bdd->last_id();
				addLog("add","pi",$id);
			}
		case "piUpdate":
			if (!isset($pi_id)) { $id = $pi_id = $_REQUEST['pi_id']; }
			if ($isAdmin || in_array($id,$allowSites))
			{
				$q = "update topo_pi set ".array2sql($_REQUEST['w'])." where pi_id=".$id;
				//echo $q;
				$Bdd->query($q);
				addLog("mod","pi",$id);
			}
			break;
		case "piDel":
			if (!isset($pi_id)  ) { $id = $pi_id = $_REQUEST['pi_id']; }
			if ($isAdmin || in_array($id,$allowSites))
			{
				// on dé lie
				$q = "delete from topo_pi_site where pi_id=".$id;
				$Bdd->query($q);
				// on supprime si il y a plus de liens
				$r = $Bdd->fetch_all_array("select * from topo_pi_site where pi_id=".$pi_id);
				if (empty($r))
				{
					$q = "delete from topo_pi where pi_id=".$id;
					$Bdd->query($q);
					addLog("del","pi",$id);
				}
			}
			break;
		case "piSiteUpdate":
			if (!isset($pi_id)) {$id = $pi_id = $_REQUEST['pi_id']; }
			if (!isset($si_id)) { $si_id = $_REQUEST['si_id']; }
			if ($pi_id && $si_id)
			{
				// on ajout le lien si il n'existe pas encore
				$r = $Bdd->fetch_all_array("select * from topo_pi_site where pi_id=".$pi_id." and site_id=".$si_id);
				if (empty($r))
				{
					$q = "insert into topo_pi_site(pi_id,site_id) values(".$pi_id.",".$si_id.")";
					$Bdd->query($q);
					addLog("mod site","pi",$id);
				}
			}
			break;
		case "siInsert":
			if ($isAdmin)
			{
				$q = "insert into topo_site(site_id) values(NULL)";
				$r = $Bdd->query($q);
				$id = $si_id = $Bdd->last_id();
				addLog("add","si",$id);
			}
		case "siUpdate":
			if (!isset($si_id)  || $id == "") { $id = $si_id = $_REQUEST['si_id']; }
			if ($isAdmin || in_array($id,$allowSites))
			{
				$q = "update topo_site set ".array2sql($_REQUEST['w'])." where site_id=".$id;
				$Bdd->query($q);
				addLog("mod","si",$id);
			}
			break;

		case "scInsert":
			//print_r($_REQUEST);
			// on initialise une nouvelle voie
			if ($isAdmin || in_array($_REQUEST['si_id'],$allowSites))
			{
				$q = "insert into topo_secteur(secteur_id,site_id) values(NULL,'".$_REQUEST['si_id']."')";
				$r = $Bdd->query($q);
				$id = $sc_id = $Bdd->last_id();
				addLog("add","sc",$id);
			}
		case "scUpdate":
			if (!isset($sc_id)  || $id == "") { $id = $sc_id = $_REQUEST['sc_id']; }
			if ($isAdmin || in_array(getSiidFromScid($id),$allowSites))
			{

				if (eregi("image/jpeg;base64",$_REQUEST['secteur_photo_F']) > 0)
				{
					$imgPath = dirname(__FILE__)."/../bddimg/sc/v.F.".$id.".jpg";
					$img = base64_decode(preg_replace("#^data:image/jpeg;base64,#","",$_REQUEST['secteur_photo_F']));
					$fp = fopen($imgPath, 'w');
					fwrite($fp, $img);
					fclose($fp);
				}
				if (preg_match_all("image/jpeg;base64",$_REQUEST['secteur_photo_W']) > 0)
				{
					$imgPath = dirname(__FILE__)."/../bddimg/sc/v.W.".$id.".jpg";
					$img = base64_decode(preg_replace("#^data:image/jpeg;base64,#","",$_REQUEST['secteur_photo_W']));
					$fp = fopen($imgPath, 'w');
					fwrite($fp, $img);
					fclose($fp);
				}

				$q = "update topo_secteur set ".array2sql($_REQUEST['w'])." where secteur_id=".$id;
				$Bdd->query($q);
				addLog("mod","sc",$id);
			}
			break;
		case "scDel":
			if ($isAdmin || in_array(getSiidFromScid($_REQUEST['sc_id']),$allowSites))
			{
				$q = "delete from topo_secteur where secteur_id=".$_REQUEST['sc_id'];
				$Bdd->query($q);
				addLog("del","sc",$id);
			}
			break;

		case "spInsert":
			if ($isAdmin || in_array(getSiidFromScid($_REQUEST['sc_id']),$allowSites))
			{
				// on initialise une nouvelle voie
				$q = "insert into topo_depart(depart_id,secteur_id) values(NULL,'".$_REQUEST['sc_id']."')";
				$r = $Bdd->query($q);
				$id = $sp_id = $Bdd->last_id();
				addLog("add","sp",$id);
			}
		case "spUpdate":
			if (!isset($id)  || $id == "") { $id = $sp_id = $_REQUEST['sp_id']; }
			if ($isAdmin || in_array(getSiidFromSpid($id),$allowSites))
			{
				$q = "update topo_depart set ".array2sql($_REQUEST['w'])." where depart_id=".$id;
				$Bdd->query($q);
				addLog("mod","sp",$id);
			}
			break;
		case "spDel":
			if ($isAdmin || in_array(getSiidFromSpid($_REQUEST['sp_id']),$allowSites))
			{
				$q = "delete from topo_depart where depart_id=".$_REQUEST['sp_id'];
				$Bdd->query($q);
				addLog("del","sp",$id);
			}
			break;


		case "wDel":
			if ($isAdmin || in_array(getSiidFromWid($_REQUEST['w_id']),$allowSites))
			{
				$q = "delete from topo_voie where voie_id=".$_REQUEST['w_id'];
				$Bdd->query($q);
				addLog("del","w",$id);
			}
			break;
		case "wInsert":
		case "wCopy":
			// on initialise une nouvelle voie
			if ($isAdmin || in_array(getSiidFromSpid($_REQUEST['sp_id']),$allowSites))
			{
				$q = "insert into topo_voie(voie_id,depart_id) values(NULL,'".$_REQUEST['sp_id']."')";
				$Bdd->query($q);
				$id = $w_id = $Bdd->last_id();
				addLog("add","w",$id);
			}
		case "wUpdate":
			if (!isset($w_id)) { $id = $w_id = $_REQUEST['w_id'];}
			if ($isAdmin || in_array(getSiidFromWid($id),$allowSites))
			{
				$q = "update topo_voie set ".array2sql($_REQUEST['w'])." where voie_id=".$id;
				$r = $Bdd->query($q);
				addLog("mod","w",$id);
			}
			break;


		case "comment":
			if (isAuthUser())
				$qui = $_SESSION['user'];
			else
				$qui = $_REQUEST['email'];
			addComments(rtg_json_encode($_REQUEST['datas']),$qui,$_REQUEST['elemType'],$_REQUEST['elemId']);
			break;
		case "commentPublicChange":
			if (isAuthUser())
			{
				PublicChangeComments($_REQUEST['id'],$_REQUEST['etat']);
			}
			break;
		case "commentDelete":
			if (isAuthUser())
			{
				DeleteComments($_REQUEST['id']);
			}
			break;

		case "updateMesVoies":
			if (isAuthUser()  && $_REQUEST['w_id'] > 0 && getAuthUserID() > 0)
			{
				$valide = 0;
				if (substr($_REQUEST['datas']['etat'],0,6) == "Echain")
				{
					$valide=1;
				}
				addMesVoies(getAuthUserID(),$_REQUEST['w_id'],$_REQUEST['datas']['quand'],$valide,rtg_json_encode($_REQUEST['datas']));
			}
			break;
		case "updateSecteurGroupe":
			if (isAuthUser() && $_REQUEST['si_id'] && $_REQUEST['groupe'] && is_array($_REQUEST['groupe']))
			{
				if ($isAdmin || in_array($id,$allowSites))
				{
					$q = "delete from topo_secteur_groupe where site_id='".$_REQUEST['si_id']."'";
					$Bdd->query($q);
					foreach($_REQUEST['groupe'] as $name=>$ordre)
					{
						if ($ordre > 0)
						{
							$q = "insert into topo_secteur_groupe(groupe_name,groupe_ordre,site_id) values('".$name."','".$ordre."','".$_REQUEST['si_id']."')";
							$Bdd->query($q);
						}
					}


				}
			}
			break;


		default:
			throw new Exception('Action non prise en compte : '.$_REQUEST['action']);
			break;
	}
}
catch(Exception $e)
{
	die("{Status:\"Error\", Action:\"".$_REQUEST['action']."\", Message: \"".str_replace("\"","'",$e->getMessage())."\"}");
}
echo "{Status:\"OK\",id:\"".$id."\", Action:\"".$_REQUEST['action']."\"}";

?>
