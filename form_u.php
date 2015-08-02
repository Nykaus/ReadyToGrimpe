<?php
include_once("inc/config.inc");
include_once("inc/auth.inc");
include_once("inc/bdd.inc");

if (!isAuthUser())
{
	exit();
}
$right = getUserRight();
if (!isset($right['admin']) && !isset($right['SIWrite']))
{
	exit;
}
// on recherche les droit en lecture de site
$whereSite = "site_public = 1";
if (hasRight())
{
	if (isset($right['SIWrite']) && is_array($right['SIWrite']) )
	{
		$whereSite = "(topo_site.site_id in ('".implode("','",$right['SIWrite'])."'))";
	}
	
	if (isset($right['admin']))
	{
		$whereSite = "1 = 1";
	}
}

$mapright["SIPdf"]  = "Télechargement du PDF";
$mapright["SIRead"]  = "Visibilité de site privé";
$mapright["SIWrite"] = "Gestionaire de site";
if (isset($right['admin']))
{
	$mapright["admin"]   = "Administrateur";
}

if (isset($_REQUEST["action"]))
{
	switch($_REQUEST["action"])
	{
		case "ADDUSER":
			if (!isset($right['admin']))
			{
				exit;
			}
			addUser($_REQUEST["email"]);
			$msg = "Utilisateur ajouté";
		break;			
		case "DELUSER":
			if (!isset($right['admin']))
			{
				exit;
			}
			delUser($_REQUEST["email"]);
			$msg = "Utilisateur supprimé";
		break;	
		case "GENLINK":
			 $msg = "Copier un des liens l'authentifications pour cet utilisateur :  <li><a href=\"".getAuthLink($_REQUEST["email"],365*3600*24)."\">Valable 1 an</a></li><li><a href=\"".getAuthLink($_REQUEST["email"],31*3600*24)."\">Valable 1 mois</a></li>";
		break;			
		case "ADDRIGHT":
			addUserRightByEmail($_REQUEST["email"],$_REQUEST["right"],$_REQUEST["ids"]);
			$msg = "Droit ajouté";
		break;			
		case "DELRIGHT":
			delUserRightByEmail($_REQUEST["email"],$_REQUEST["right"],$_REQUEST["ids"]);
			$msg = "Droit supprimé";
		break;	
	}
}





// lecture des sites disponibles
$q = "select `topo_site`.`site_id` as id
			,`topo_site`.`site_nom` as name
			from topo_site
			where ".$whereSite;
$r = $Bdd->fetch_all_array($q);

for ($i=0;$i<sizeof($r);$i++)
{
	$sites[$r[$i]['id']] = $r[$i]['name'];
}


// lecture des sites disponibles
$q = "select * 
			from topo_utilisateurs
			where utilisateur_email = '".$_REQUEST["email"]."'";
$r = $Bdd->fetch_all_array($q);
$User = $r[0];



function getSiteName($id)
{
	return $GLOBALS['sites'][$id];
}


?>
<script type="text/javascript">
function genLink(action)
{
		var r = rtg_getHtml('form_u.php?email=<?=$_REQUEST["email"]?>&action=GENLINK');
		$('#userDetail').html(r);
		return false;
}	
function delRight(right,ids)
{
		var r = rtg_getHtml('form_u.php?email=<?=$_REQUEST["email"]?>&action=DELRIGHT&right='+right+'&ids='+ids);
		$('#userDetail').html(r);
		return false;
}
function addRight()
{
		right = $( "#right option:selected").val();
		ids   = $( "#ids option:selected").val();
		var r = rtg_getHtml('form_u.php?email=<?=$_REQUEST["email"]?>&action=ADDRIGHT&right='+right+'&ids='+ids);
		$('#userDetail').html(r);
		return false;
}

function delUser()
{
		if (window.confirm("Etes vous sur de supprimer cet utilisateur ?"))
		{
			right = $( "#right option:selected").val();
			ids   = $( "#ids option:selected").val();
			var r = rtg_getHtml('form_u.php?email=<?=$_REQUEST["email"]?>&action=DELUSER');
			$('#userDetail').html(r);
		}
		return false;
}


</script>

<?php if (isset($msg)) { ?>
<div class="alert alert-success" role="alert">
  <?=$msg?>
</div>
<?php } ?>

<form class="form-horizontal" id="searchForm" role="form">
	
 <div class="form-group">
    <label class="col-sm-3" class="sr-only" >Nom</label>
    <div class="col-sm-9">
    	<?=$_REQUEST["email"]?>
    </div>
 </div>	
 <div class="form-group">
    <label class="col-sm-3" class="sr-only" >Dernière connexion</label>
    <div class="col-sm-9">
    	<?=$User["utilisateur_derniere_connexion"]?>
    </div>
 </div>	 
 <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="site_nom">Action</label>
    <div class="col-sm-9">
		<a class="btn btn-default glyphicon glyphicon-link" onclick="return genLink();" aria-hidden="true"> Générer un lien d'authentification</a>
		<?php if (isset($right['admin'])) { ?>
			<a class="btn btn-default glyphicon glyphicon-trash" onclick="return delUser();" aria-hidden="true"> Suppression de l'utilisateur</a>
		<?php } ?>
    </div>
 </div>	
 <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="site_nom">Droits</label>
    <div class="col-sm-9">
		<?php

			$d = getUserRightByEmail($_REQUEST["email"]);
			$c=0;
			if (is_array($d))
			{
				while (list($droit,$portees) = each($d))
				{
					// on test si sur cette porté, j'ai des droits
					$allow = false;
					reset($portees);
					while (list($x,$portee) = each($portees))
					{
						if (in_array($portee,$right['SIWrite']) || isAdmin())
							$allow = true;
					}
					
					if ($allow && isset($mapright[$droit]))
					{
						$c++;
						echo '<div class="row row'.($c%2).'"><div class="col-sm-5">'.$mapright[$droit].'</div>';
						echo '<div class="col-sm-7">';
						reset($portees);
						while (list($x,$portee) = each($portees))
						{
							//print_r($right['SIWrite']);
							//echo "#".array_search($portee,$right['SIWrite']);
							if (in_array($portee,$right['SIWrite']) || isAdmin())
							{
								switch($droit)
								{
									default:
									case "admin":
										echo '<div class="col-sm-9">-</div>';
									break;
									case "SIWrite":
									case "SIRead":
									case "SIPdf":
										echo '<div class="col-sm-9">'.getSiteName($portee).'</div>';
									break;
								}
								echo '<div class="col-sm-3"><a class="btn btn-default glyphicon glyphicon-trash btn-danger" onclick="return delRight(\''.$droit.'\',\''.$portee.'\');" aria-hidden="true"></a></div>';
							}
						}
						echo '</div></div>';
					}
				}
			}
			$c++;
			echo '<div class="row row'.($c%2).'">';
				echo '<div class="col-sm-5"><select name="right" id="right" class="form-control">';
					while (list($k,$v) = each($mapright))
					{
						echo "<option value='".$k."'>".$v."</option>";
					}
				echo '</select></div>';
				echo '<div class="col-sm-7">';
				echo '<div class="col-sm-9"><select name="ids" id="ids" class="form-control">';
					while (list($k,$v) = each($sites))
					{
						echo "<option value='".$k."'>".$v."</option>";
					}				
				echo '</select></div>';
				echo '<div class="col-sm-2"><a class="btn btn-default glyphicon glyphicon-plus btn-primary" onclick="return addRight();" aria-hidden="true"></a></div>';
			echo '</div></div>';
			
			
		?>
    </div>
 </div>		
	
	
</form>






