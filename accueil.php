<?php
include_once("inc/auth.inc");
include_once("inc/logs.inc");

$q = "select si.site_id siid,site_nom,site_public from topo_site si,topo_secteur sc where site_public > 0 and sc.site_id = si.site_id group by si.site_id";
$a = $GLOBALS["Bdd"]->fetch_all_array($q);
$liste_sites= array();
for ($i=0;$i<sizeof($a);$i++)
{
	$liste_sites[] = "<div class=\"breadcrumb\" style=\"display:inline-table;\"><a href=\"/si/".$a[$i]["siid"]."/".$a[$i]["site_nom"]."\" onclick=\"rtg_viewSIDetails(".$a[$i]["siid"].");return false;\">".$a[$i]["site_nom"]."</a><a></a></div>";	
}
?>
<div class="panel panel-default">
	<div class="panel-heading">Ready to Grimpe</div>
	<div class="panel-body comment">
		<p>Utiliser la carte ou le menu (en haut à gauche) pour selectionner le site d'escalade à consulter.</p>
		<p>
		<h3>Les sites déjà renseignés sont :</h3>
		<?php echo implode("",$liste_sites);?></p>
		<h3>Les dernières mise à jour :</h3>
		<?php echo getTableLogs(getLogs("",""),false); ?>

	</div>
</div>
<div class="panel panel-default">
	<div class="panel-heading">Apropos</div>
	<div class="panel-body comment">
		<?php include("apropos.htm");?>
	</div>
</div>
