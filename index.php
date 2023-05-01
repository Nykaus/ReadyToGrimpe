<?php
include_once("inc/auth.inc");
isValidCookie();



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

$q = "select site_id,site_nom,site_public,site_type from topo_site where ".$whereSite." order by site_nom";
$a = $GLOBALS["Bdd"]->fetch_all_array($q);


$liste_sites_menu= array();
for ($i=0;$i<sizeof($a);$i++)
{
	$icon = "fa-map-marker";
	if ($a[$i]['site_public'] == 0)
	{
		$icon = "fa-unlock-alt";
	}


	$liste_secteurs = array();

	$q = "select  secteur_groupe from topo_secteur where site_id=".$a[$i]['site_id']." and secteur_groupe !='' order by secteur_ordre";
	$b = $GLOBALS["Bdd"]->fetch_all_array($q);
	for ($j=0;$j<sizeof($b);$j++)
	{
		$liste_secteurs_groupe = array();
		$q = "select secteur_id,secteur_nom from topo_secteur where site_id=".$a[$i]['site_id']." and secteur_groupe='".$b[$j]['secteur_groupe']."' order by secteur_ordre";

		$c = $GLOBALS["Bdd"]->fetch_all_array($q);
		$scid = $c[0]['secteur_id'];
		for ($k=0;$k<sizeof($c);$k++)
		{
			$liste_secteurs_groupe[] = "<a class=\"glyphicon fa fa-folder-o\" href=\"/sc/".$c[$k]['secteur_id']."/".$c[$k]['secteur_nom']."\" onclick=\"mmenuapi.close(); rtg_viewSCDetails(".$c[$k]['secteur_id'].");return false;\"> ".$c[$k]['secteur_nom']."</a>";
		}
		$liste_secteurs[] = "<a class=\"glyphicon fa fa-folder-o\" href=\"/sc/".$scid."/".$b[$j]['secteur_groupe']."\" onclick=\"mmenuapi.close(); rtg_viewSCDetails(".$scid.");return false;\"> ".$b[$j]['secteur_groupe']."</a><ul><li>".implode("</li><li>",$liste_secteurs_groupe)."</li></ul>";
	}



	$q = "select secteur_id,secteur_nom from topo_secteur where site_id=".$a[$i]['site_id']." and secteur_groupe='' order by secteur_ordre";
	$b = $GLOBALS["Bdd"]->fetch_all_array($q);
	for ($j=0;$j<sizeof($b);$j++)
	{
		$liste_secteurs[] = "<a class=\"glyphicon fa fa-folder-o\" href=\"/sc/".$b[$j]['secteur_id']."/".$b[$j]['secteur_nom']."\" onclick=\"mmenuapi.close(); rtg_viewSCDetails(".$b[$j]['secteur_id'].");return false;\"> ".$b[$j]['secteur_nom']."</a>";
	}


	if ($a[$i]['site_type'] == "Block")
		$a[$i]['site_type'] = "Bloc";

	$liste_sites_menu[$a[$i]['site_type']][] = "<a class=\"glyphicon fa ".$icon."\" href=\"/si/".$a[$i]['site_id']."/".$a[$i]['site_nom']."\" onclick=\"mmenuapi.close();rtg_viewSIDetails(".$a[$i]['site_id']."); return false;\"> ".$a[$i]['site_nom']."</a>".((sizeof($liste_secteurs) > 0)?("<ul><li>".implode("</li><li>",$liste_secteurs)."</li></ul>"):"");


}


?>
<!DOCTYPE html>
<html>
<head>
	<link rel="shortcut icon" type="image/png" href="/favicon.png" />
	<title>Ready To Grimpe - Topos d'escalades numériques gratuits</title>
	<base href="<?=$config["BaseUrl"]?>"/>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta charset="utf-8" />
	<meta content="width=device-width initial-scale=1.0 minimum-scale=1.0 maximum-scale=1.0 user-scalable=no" name="viewport" />



	<meta name="description" content="Mise à disposition gratuitement de topo d'escalade numérique. Avec la possibilité de commenter les sites, secteurs et voies." />
	<meta name="keywords" content="topo, escalade, numérique, gratuit, ReadyToGrimpe" />



	<script src="<?=$config["BaseDNSHttp"]?><?=$config["BaseUrl"]?>piwik/piwik.js" type="text/javascript"></script>
	<script type="text/javascript">
		var initHash = '<?=@preg_replace('#/#','-',substr($_SERVER['REQUEST_URI'],1))?>';
		if (typeof Piwik != "undefined") {
			var piwikTracker = Piwik.getTracker('<?=$config["BaseUrl"]?>piwik/piwik.php',1);
			piwikTracker.enableLinkTracking();
			<?php if (isAuthUser()) { ?>
			piwikTracker.setUserId('<?=getAuthUserId()." - ".getAuthUserName() ?>');
			<?php } ?>
		}
		else
		{
			var piwikTracker = {trackPageView: function(){}, trackEvent: function() {}}
		}
	</script>
	<!-- google map -->
	<script src="https://maps.google.com/maps/api/js?sensor=true" type="text/javascript"></script>
	<script src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<script src="https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<!-- Include all compiled plugins (below), or include individual files as needed -->
	<script type="text/javascript" src="<?=$config["BaseUrl"]?>js/bin/showdown.js"></script>
	<!-- editeur de topo betacreator -->
	<script type="text/javascript" src="<?=$config["BaseUrl"]?>js/bin/betacreator.js"></script>
	<script type="text/javascript" src="<?=$config["BaseUrl"]?>js/RTG/rtg_datas_init.php"></script>
	<script type="text/javascript" src="<?=$config["BaseUrl"]?>js/RTG/rtg_nav.js"></script>
	<script type="text/javascript" src="<?=$config["BaseUrl"]?>js/RTG/rtg_rpc.js"></script>
	<link href="css/betacreator.css" rel="stylesheet" type="text/css" />
	<!-- Bootstrap core CSS -->
	<script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.1/js/bootstrap.min.js"></script>
	<link  href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet" media="screen">
	<link  href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">


	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7/html5shiv.js"></script>
	<script src="http://cdnjs.cloudflare.com/ajax/libs/respond.js/1.3.0/respond.js"></script>
	<![endif]-->
	<link  href="<?=$config["BaseUrl"]?>css/rtg2.css.php" rel="stylesheet">


	<!-- Include jQuery.mmenu .css files -->
	<link type="text/css" href="mmenu/css/jquery.mmenu.all.css" rel="stylesheet" />
	<!--<link type="text/css" href="mmenu/css/extensions/jquery.mmenu.iconbar.css" rel="stylesheet" />-->
	<link type="text/css" href="http://maxcdn.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet" />

	<!-- Include jQuery and the jQuery.mmenu .js files -->
	<script type="text/javascript" src="mmenu/js/jquery.mmenu.min.all.js"></script>





	<!-- Fire the plugin onDocumentReady -->
	<script type="text/javascript">
		var mmenuapi;
		$(document).ready(function() {
			rtg_Init();
			$("#menu").menu({
				"extensions": [
					"theme-rtg"
				],
				"counters": true,
				"header": {
					"title": "ReadyToGrimpe",
					"add": true,
					"update": true
				},
				"searchfield": {
					"placeholder": "Recherche",
					"noResults": "Pas de résultat",
					"add": true,
					"search": true
				}
			});

			mmenuapi = $("#menu").data( "mmenu" );

		});

	</script>

</head>
<body>

<!-- The page -->
<div class="page">
	<div class="header">
		<a href="#menu" id="bmenu">&#160;</a>
		<span id="titleNav">
        <span id="rtg_nav_root"><div style="position:absolute;top:2px;"><svg width="45" height="50" xmlns="http://www.w3.org/2000/svg" xmlns:svg="http://www.w3.org/2000/svg">
 <!-- Created with SVG-edit - http://svg-edit.googlecode.com/ -->
 <g>
   <path fill="#000000" stroke="#000000" stroke-dasharray="null" stroke-linejoin="null" stroke-linecap="null" d="m33.45538,6.34011c-1.84232,-2.14868 -0.86911,-1.258 -4.13094,-2.08062l-21.11045,0l0,0l7.73499,0l14.50281,0c0.94004,0 1.84186,0.40988 2.50694,1.13921c0.66477,0.72983 1.0383,1.71911 1.0383,2.75116l0,9.72563l0,0l0,5.83541l0,0c0,2.14819 0.25516,3.56111 -3.54524,3.89016l-3.56673,0l-3.56674,0l-3.9639,5.28548l-2.96026,-5.43963l-5.57021,-0.01037l5.73818,0l2.66764,5.42927l3.80002,-5.26475c-1.9581,0 6.74096,-0.09674 9.04386,-0.10611l1.99585,-1.48071l0,-8.63231l-0.15353,-2.7969l0.01919,-0.80441l0.01919,-0.80439l0.01919,-0.80439l0.00952,-0.40221l0.00967,-0.40219z" fill-opacity="0" id="svg_7"/>
   <path fill="#EE0000" stroke="#000000" stroke-dasharray="null" stroke-linejoin="null" stroke-linecap="null" d="m3.17589,8.07017l0,0c0,-2.14868 1.58719,-3.89037 3.54527,-3.89037l1.61157,0l0,0c2.57833,0 12.52594,0.16452 15.10427,0.16452c-0.68489,7.78052 -11.80958,15.39647 -12.49432,23.17711l-4.22152,0c-1.95808,0 -3.54527,-1.74197 -3.54527,-3.89016l0,0l0,-5.83547l0,0l0,-9.72563l0,0z" id="svg_6"/>
   <path id="svg_1" d="m28.39002,18.229c-0.09273,-0.81782 -0.32622,-1.56479 -0.62535,-2.15943c-0.71031,0.07071 -1.46848,-0.06043 -2.19008,-0.44332l-1.62264,-1.42581c-0.50441,-0.39604 -1.67552,-4.39584 -2.33864,-3.97908c-1.54319,0.96737 -0.34372,5.23123 0.23934,6.01583c0.26073,0.35207 -0.20194,0.51524 -0.2769,1.30695c-0.07495,0.79171 -0.12609,0.56692 -1.02443,0.91832c-0.89832,0.3514 -2.34688,-0.2147 -2.2348,1.35398c0.11208,1.56868 4.10053,1.70914 4.56768,1.03419c-0.2178,0.80725 -0.58262,2.89789 -0.0612,3.17455c0.86906,0.46112 2.1896,-0.49254 2.94787,-2.13366c0.17042,-0.36884 0.29589,-0.74246 0.37823,-1.10381c0.22198,0.51753 0.52173,0.83609 0.9412,0.84492c0.93577,0.0213 1.51729,-1.50203 1.29973,-3.40365zm-2.55703,-3.16201c1.46233,0.77592 3.1442,0.33294 3.75492,-0.98883s-0.07973,-3.02454 -1.54206,-3.80046c-1.46233,-0.77592 -3.14431,-0.333 -3.75541,0.9896s0.08021,3.02377 1.54255,3.79969z" stroke="#000000" fill="#AAAAAA"/>
 </g>
</svg></div><a href="/" Onclick="rtg_reInit();  return false;" >Ready&thinsp;To&thinsp;Grimpe</a></span>
	</span>

		<span  style="float:right;" class="xs-hidden">
 <?php if (isAuthUser()) { ?>
	 <span class="glyphicon fa fa-user">&#160;<?=getAuthUserName()?></span>
 <?php } else  { ?>
	 <a style="color:#fff; text-decoration:none;" class="glyphicon fa fa-sign-in" href="#" Onclick="rtg_dialog('Authentification',rtg_getHtml('form_login.php'));  return false;" >&#160;S'authentifier</a>
 <?php } ?>
</span>


	</div><!-- /.navbar-collapse -->
</div><!-- /.container-fluid -->

</div>
<div class="content" id="contentbody">
	<div class="row breadcrumb breadcrumbXL">
		<a href="/" Onclick="rtg_reInit();  return false;"><span class="glyphicon glyphicon-home">&#160;</span></a>
		<a id="rtg_nav_si"></a>
		<a id="rtg_nav_sc_groupe"></a>
		<a id="rtg_nav_sc"></a>
		<a id="rtg_nav_sp"></a>
		<a id="rtg_nav_w"></a>
		<span class="btn-group" id="rtg_switch_zone" style="float:right;">
				<button id="rtg_switch_map" type="button" class="btn" onclick="rtg_switchMap()"><span  class="glyphicon glyphicon-globe">&#160;</span> Carte</button> 
				<button type="button" id="rtg_switch_topo" class="btn" onclick="rtg_switchTopo()"><span class="glyphicon glyphicon-picture">&#160;</span> Topo</button>
		</span>
	</div>
	<div class="row">
		<div id="map-outer" class="col-md-12">
			<div class="col-md-8">
				<div class="panel panel-default">
					<div class="panel-body">
						<div id="changeLog">
						</div>

						<div id="map-container"  ></div>
						<div id="topo-container" >

							<div id="topo-container-img"></div>
							<div id="topo-container-prev"><div style="display:none;"><a>&lt;</a></div></div>
							<div id="topo-container-next"><div style="display:none;"><a>&gt;</a></div></div>
						</div>
					</div>
				</div>

				<div id="map-under">
					<?php include("accueil.php"); ?>
				</div>
			</div>
			<div id="details" class="col-md-4">
			</div>
		</div><!-- /map-outer -->
	</div> <!-- /row -->

	<div class="row">
		<div class="col-md-8">
			<div id="desc"></div>
			<div id="lists"></div>
		</div>
		<div class="col-md-4">
			<div id="pi1"></div>
			<div id="comp"></div>
			<div id="pi2"></div>
		</div>
	</div><!-- /row -->

	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-heading"><b>Commentaires</b> <a id="addcommentbutton" class="btn btn-primary glyphicon glyphicon-plus btn-right btn-sm" onclick="AddCommentAction(); return false;"> Ajouter un commentaire</a> </div>
				<div class="panel-body" id="commentsListe"></div>
			</div>
		</div>
	</div><!-- /row -->





	<div id="popup2" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			Modal header</h3>
		</div>
		<div  class="modal-body">
			popup
		</div>
		<div id="popupFooter" class="modal-footer">
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			<button class="btn btn-primary">Save changes</button>
		</div>
	</div>



	<!-- les fenetre modal -->
	<div id="event-modal" class="modal fade">
		<div class="modal-dialog modal-rtg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3 id="popupTitle">Modal Heading</h3>
				</div>
				<div id="popupBody" class="modal-body">
					<p>Some information</p>
				</div>
			</div>
		</div>
	</div>


	<div id="upload-modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3>Chargement</h3>
				</div>
				<div id="popupBody" class="modal-body">
					<form id="upload-modal-form">
						<input type="file" id="upload-modal-selectImg" accept="image/*,.gpx" />
					</form>
					<input id="upload-modal-imgF" style="display:none"/>
					<input id="upload-modal-imgW" style="display:none"/>
					<input id="upload-modal-imgT" style="display:none"/>
					<a id="upload-modal-action" class="btn btn-primary glyphicon glyphicon-plus"> Sauvegarder</a>
				</div>
			</div>
		</div>
	</div>

	<div id="modify-modal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					<h3>Modifier</h3>
				</div>
				<div id="popupBody" class="modal-body">

					<div   id="modify-modal-imgO" >---</div>

					<form class="form-horizontal" role="form">
						<div class="form-group">
							<label for="inputEmail3" class="col-sm-3 control-label">Personalisation</label>
							<div class="col-sm-8">
								<input id="modify-modal-img-path" class="form-control"/>
								<input id="modify-modal-imgF" style="display:none"/>
								<input id="modify-modal-imgW" style="display:none"/>
								<input id="modify-modal-imgT" style="display:none"/>
							</div>
							<a id="modify-modal-update-path" class="btn btn-primary glyphicon glyphicon-refresh col-sm-1"> </a>
						</div>
					</form>
					<a id="modify-modal-action" class="btn btn-primary glyphicon glyphicon-plus"> Sauvegarder</a>



				</div>
			</div>
		</div>
	</div>






</div>
</div>

<!-- The menu -->
<nav id="menu">
	<ul role="menu">
		<?php if (isAuthUser()) { ?>
			<li ><span class="glyphicon fa fa-user">&#160;Mon Compte - <?=getAuthUserName()?></span>
				<ul >

					<li ><a class="glyphicon fa fa-sign-out" href="#" Onclick="return rtg_logout(); return false;" >&#160;Deconnexion</a></li>
					<li ><a class="glyphicon fa fa-clock-o" href="#" Onclick="rtg_dialog('Restez connecté',rtg_getHtml('login.php?action=PERMLOGIN'));  return false;" >&#160;Restez connecté</a></li>
					<li ><a class="glyphicon fa fa-check-square-o" href="#" Onclick="rtg_dialog('Mes voies',rtg_getHtml('view_mesvoies.php'));  return false;" >&#160;Mes voies</a></li>
				</ul>
			</li>

			<!--<li ><a class="glyphicon fa fa-refresh" href="#" Onclick="rtg_reinitMap();  return false;" >&#160;Reinitialize la carte</a></li>-->


		<?php } else  { ?>
			<li><a class="glyphicon fa fa-sign-in" href="#" Onclick="rtg_dialog('Authentification',rtg_getHtml('form_login.php'));  return false;" >&#160;S'authentifier</a></li>
		<?php } ?>

		<li><span class="glyphicon fa fa-map-marker">&#160;&#160;Les sites</span>
			<ul >
				<?php
				foreach($liste_sites_menu as $type=>$list)
				{
					echo "<li><span class=\"glyphicon fa fa-map-marker\">&#160;&#160;$type</span><ul>";
					echo "<li>".implode("</li><li>",$list)."</li>";
					echo "</ul></li>";
				}
				?>
			</ul>
		</li>
		<li><a class="glyphicon fa fa-comments" href="#" Onclick="rtg_dialog('Les derniers commentaires',rtg_getHtml('/rpc/rtg_commentslist.php'));  return false;" >&#160Les derniers commentaires</a></li>


		<?php if (isset($r) && (isset($r["admin"]) || isset($r["SIWrite"]))) {?>
			<li><span class="glyphicon fa fa-user-secret">&#160;Administration</span>
			<ul>
			<li><a class="glyphicon fa fa-users" href="#" Onclick="rtg_dialog('Gestion des utilisateurs',rtg_getHtml('admin_utilisateurs.php'));  return false;" >&#160;Utilisateurs</a></li>
			<li><a tabindex="-1" class="glyphicon fa fa-edit" href="#" Onclick="rtg_dialog('Gestion des sites',rtg_getHtml('admin_si.php'));  return false;" >&#160;Gestion des sites</a></li>
			<?php if (isset($r) && isset($r["admin"])) {?>
				<li ><a class="glyphicon fa fa-plus" href="#" Onclick="rtg_insertSI();  return false;" >&#160;Nouveau site</a></li>
				<li ><a class="glyphicon fa fa-database" href="#" Onclick="rtg_dialog('Backup BDD',rtg_getHtml('backup.php'));  return false;" >&#160;Backup BDD</a></li>
				</ul>
				</li>
			<?php }} ?>

		<li><a href="#" class="glyphicon fa fa-life-saver" Onclick="rtg_dialog('Legende',rtg_getHtml('legende.php'));  return false;">&#160;Legende</a></li>
		<li><a href="#" class="glyphicon fa fa-info" Onclick="rtg_dialog('A propos',rtg_getHtml('apropos.htm'));  return false;">&#160;&#160;A propos</a></li>
		<li><a href="#" class="glyphicon fa fa-legal" Onclick="rtg_dialog('Mentions légales',rtg_getHtml('legal.htm'));  return false;">&#160;Mentions légales</a></li>
		<li><a class="glyphicon fa fa-link" href="http://www.climbing-crew.fr" target="cc">&#160;Climbing-crew.fr</a></li>


</nav>
<!-- Piwik -->
<noscript><p><img src="//www.readytogrimpe.climbing-crew.fr/piwik/piwik.php?idsite=1" style="border:0;" alt="" /></p></noscript>
<!-- End Piwik Code -->
</body>
</html>
<?php // include_once("simplestats/simplestats.inc"); ?>


