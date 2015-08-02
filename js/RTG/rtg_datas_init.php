<?php
header('Content-Type: text/javascript;  charset=UTF-8');
include_once("../../inc/bdd.inc");
include_once("../../inc/json.inc");
?>
var rtg_markdownrender;
var rtg_plotMapInfos_datainfos = [];
var rtg_getWInfos_datainfos = {};
var rtg_getSPInfos_datainfos = {};
var rtg_getSCInfos_datainfos = {};
var rtg_getSIInfos_datainfos = {};
var rtg_getPIInfos_datainfos = {};
var rtg_getMesVoiesInfos_datainfos = {};
var rtg_GEOPORTAIL_KEY= '<?=$config["GeoportailKey"]?>';
var rtg_pi = {};
var rtg_complements = {};
var baseUrl="<?=$config["BaseUrl"]?>"
var fullBaseUrl="<?=$config["BaseDNSHttp"].$config["BaseUrl"]?>"
<?php
	reset($config['PI']);
	while(list($k,$v) = each($config['PI']))
	{
		echo "rtg_pi['$k'] = {libelle:\"".$v['libelle']."\", zoomlevel:".$v['zoomlevel']." ,icon:\"".$v['icon']."\"};\n";
	}

	reset($config['complements']['w']);
	while(list($t,$vTv) = each($config['complements']))
	{
	while(list($k,$v) = each($vTv))
	{
		unset($valeurs);
		if (isset($v['valeurs']))
		{

			reset($v['valeurs']);
			while(list($kv,$vv) = each($v['valeurs']))
			{
				$valeurs[] = "{val:'".@ereg_replace("'","\'",$kv)."', aff:'".@ereg_replace("'","\'",$vv)."'}";
			}
		}
		$valeur_s = "";
		if (sizeof($valeurs) > 0)
			$valeur_s = implode(",",$valeurs);
		echo "rtg_complements['$k'] = {nom:\"".$v['nom']."\", mode:\"".$v['mode']."\" ,valeurs:[".$valeur_s."],position:\"".(($v['position'])?$v['position']:"lateral")."\"};\n";
	}
	}
?>

