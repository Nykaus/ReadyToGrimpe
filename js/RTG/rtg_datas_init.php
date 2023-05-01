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
	var baseUrl="<?=$config["BaseUrl"]?>";
	var fullBaseUrl="<?=$config["BaseDNSHttp"].$config["BaseUrl"]?>";

<?php
if(isset($config)){
	if(!empty($config['PI'])){
		reset($config['PI']);
		foreach($config['PI'] as $k =>$v)
		{
			echo "rtg_pi['$k'] = {libelle:\"".$v['libelle']."\", zoomlevel:".$v['zoomlevel']." ,icon:\"".$v['icon']."\"};\n";
		}
	}

	if(!empty($config['complements'])){
		$valeurs=[];
		reset($config['complements']['w']);
		foreach($config['complements'] as $t => $vTv)
		{
			foreach($vTv as $k => $v )
			{
				unset($valeurs);
				if (isset($v['valeurs']))
				{
					reset($v['valeurs']);
					foreach($v['valeurs'] as $kv=>$vv)
					{
						$valeurs[] = "{val:'".@preg_replace("#'#","\'",$kv)."', aff:'".@preg_replace("'","\'",$vv)."'}";
					}
				}
				$valeur_s = "";
				if (isset($valeurs) && sizeof($valeurs) > 0){
					$valeur_s = implode(",",$valeurs);
					echo "rtg_complements['$k'] = {nom:\"".$v['nom']."\", mode:\"".$v['mode']."\" ,valeurs:[".$valeur_s."],position:\"".((isset($v['position']))? $v['position'] :"lateral")."\"};\n";
				}
			}
		}
	}
}