<?php
ob_start();
include_once("inc/config.inc");
include_once("inc/bdd.inc");
include_once("inc/auth.inc");
include_once("inc/xslt.inc");
include_once("inc/PiwikTracker.php");
$piwikTracker = new PiwikTracker(1);

$sitebase = "http://".$_SERVER["HTTP_HOST"].$config["BaseUrl"];
if (substr_count($_SERVER["REQUEST_URI"],"/") > 1)
	$_REQUEST["id"] = ereg_replace("/",",",substr($_SERVER["REQUEST_URI"],7));


// reconstruction et filtrage 
$_id = explode(",",$_REQUEST["id"]);
$_rid = array();
for ($ii=0;$ii<sizeof($_id);$ii++)
{
	if (is_numeric($_id[$ii]))	
	{
		$_rid[] = $_id[$ii];
	}
	else
	{
		$_tmp = explode(":",$_id[$ii]);
		if (sizeof($_tmp) > 0)
		{
			$_REQUEST[$_tmp[0]] = $_tmp[1];
		}
	}
}
$_REQUEST["id"] = implode(",",$_rid);
$_REQUEST["hash"] = implode("/",$_rid);

ob_end_clean();


$path = dirname(__FILE__);

ob_start();
$whereSite = "site_public >= 1";
$pdfs = array();
if (hasRight())
{
	$r = getUserRight();
	if (isset($r['SIRead']) && is_array($r['SIRead']) )
	{
		$ids = $r['SIRead'];
		if (!isset($r['SIPdf']))
			$r['SIPdf']= $r['SIRead'];
	}
		
				
	if (isset($r['SIWrite']) && is_array($r['SIWrite']) )
		$ids = $r['SIWrite'];
		
	if (isset($r['SIWrite']) && is_array($r['SIWrite']) && isset($r['SIRead']) && is_array($r['SIRead']))
		$ids = array_merge($r['SIRead'],$r['SIWrite']);
		
	if (isset($ids) && is_array($ids))
	{
		$whereSite = "(site_public = 1 or topo_site.site_id in ('".implode("','",$ids)."'))";
	}
	
	
	if (isset($r['admin']))
	{
		$whereSite = "1 = 1";
	}
	
	
	if (isset($r['SIPdf']) && is_array($r['SIPdf']) )
	{
		while(list($k,$v) = each($r['SIPdf']))
		{
			if (file_exists($path."/pdf/".$v.".pdf"))
				$pdfs[$v] = "/pdf/".$v;
		}
	}
	
}




$q = "select site_id,site_nom,site_public from topo_site where ".$whereSite." order by site_nom";
$a = $Bdd->fetch_all_array($q);
$liste_sites= array();
$ids = explode(',',$_REQUEST["id"]);
for ($i=0;$i<sizeof($a);$i++)
{
	$icon = "";
	if ($a[$i]['site_public'] == 0)
	{
		$icon = "item-private";
	}
	if ($a[$i]['site_public'] == 2)
	{
		$icon = "item-notopo";
	}	
	$PdfLink = "";
//echo $path."/".$a[$i]['site_id'].".pdf";
	if (isset($pdfs[$a[$i]['site_id']]) || (($a[$i]['site_public'] == 1 || isadmin() ) && file_exists($path."/pdf/".$a[$i]['site_id'].".pdf") ))
		$PdfLink = " <br/>PDF disponible : <a target=\"pdf\" href=\"/pdf/".$a[$i]['site_id']."\">[Classic]</a> <a target=\"pdf\" href=\"/pdf/".$a[$i]['site_id']."/2p1\">[2 pages sur 1]</a> <a target=\"pdf\"href=\"/pdf/".$a[$i]['site_id']."/livret\">[Livret]</a>";

	$liste_sites[] = "<div class=\"item $icon\"><input type=\"checkbox\" name=\"id[]\" value=\"".$a[$i]['site_id']."\" ".((in_array($a[$i]['site_id'],$ids))?"checked":"").">".$a[$i]['site_nom'].$PdfLink."</div>";

	if (in_array($a[$i]['site_id'],$ids))
	{
		$piwikTracker->doTrackEvent('topo','Affichage mode impression',$a[$i]['site_nom']);
	}
}
?>
<div id="searchDiv">
<form id="searchForm" method="post">
<?php echo implode("",$liste_sites);?>

<div id="itemInfo">Zone non imprimée</div>
<div class="itemAction">
<!--<input type="checkbox" name="image" id="image" value="">-->
<select name="mode" id="mode">
<?php
$modes = array();
$modes[] = "Compact";
$modes[] = "Complet";
$modeHtmlView = $modes[0];
if (isset($_REQUEST["mode"]))
{
	$modeHtmlView = $_REQUEST["mode"];
}
for ($x=0;$x<sizeof($modes);$x++)
{
	echo "<option value=\"".$modes[$x]."\" ".(($modes[$x] == $modeHtmlView)?"selected":"").">".$modes[$x]."</option>";
}
?>
</select>
<a href="javascript:update();">Mettre à jour</a>
 <script type="text/javascript">
if (typeof Piwik != "undefined") {
    var piwikTracker = Piwik.getTracker('<?=$config["BaseUrl"]?>piwik/piwik.php',1);
	<?php if (isAuthUser()) { ?>
	        piwikTracker.setUserId('<?=getAuthUserId()." - ".getAuthUserName() ?>');
	<?php } ?>  
}
else
{
	var piwikTracker = {trackPageView: function(){}, trackEvent: function() {}}
}

        piwikTracker.trackPageView('Impression');

function update()
{
	var url = "<?=$sitebase?>print";
	var elem = document.forms['searchForm'].elements;
	console.log(elem)
	for (var i=0; i<elem.length; i++) {
		if (elem[i].checked)
			url += "/"+elem[i].value	;
	}
	document.location = url+"/mode:"+$('#mode').val();
}
</script>
</div>
</form>
</div>

<?php
$searchDiv = ob_get_contents();
ob_end_clean();





ob_start();
include("rpc/rtg_getxml.php");
$xml = ob_get_contents();
ob_end_clean();



ob_start();
$xml = @ereg_replace("</RTG>","<SITEBASE>".$sitebase."</SITEBASE><MODE>".$modeHtmlView."</MODE></RTG>",$xml);

$xsl = implode("",file("xsl/tohtml.xsl.xml"));
ob_end_clean();
@header_remove();
@header('Content-Type: text/html;  charset=UTF-8');
echo '<!DOCTYPE html>';
$html = @str_replace('<?xml version="1.0"?>',"",trim(rtg_xslt($xml,$xsl)));
$html = @str_replace("<body>","<body>".$searchDiv,$html);

echo $html;

?>
<?php include_once("simplestats/simplestats.inc"); ?>
