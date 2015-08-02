<?php 
header('Content-Type: text/html; charset=UTF-8');
$json = array();
include_once("../inc/bdd.inc");
include_once("../inc/auth.inc");
include_once("../inc/logs.inc");


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
$elemType = $elemId = "";
if (isset($_REQUEST['hash']))
{
	$r = explode("-",$_REQUEST['hash']);
	$elemType = $r[0];
	$elemId = $r[1];
}
?>
<?php 
if (isset($_REQUEST['embeded']))
{

$colorRGBBG = "22, 142, 247";
$colorRGBFG = "255,255,255";
$colorDARK = 1;
$colorRGB2 = "90, 143, 0";
function rgba($alpha,$type="fg")
{
	switch($type) 
	{
		case "2":
			return "rgba(".$GLOBALS["colorRGB2"].",".abs( ($GLOBALS["colorRGB2"]-$alpha) ).")";
		case "bg":
			return "rgba(".$GLOBALS["colorRGBBG"].",".abs( ($GLOBALS["colorDARK"]-$alpha) ).")";
		case "bd":
		case "in":
			return "rgba(".$GLOBALS["colorRGBFG"].",".abs( ($GLOBALS["colorDARK"]-$alpha) ).")";
		default:
			return "rgba(".$GLOBALS["colorRGBFG"].",".abs( $alpha ).")";
	}
}


	echo '<link  href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.1.1/css/bootstrap.min.css" rel="stylesheet" media="screen">';
	echo '<style type="text/css">b.new {color:red;} td {font-size:.8em;}
.comment .breadcrumb, .table .breadcrumb{
	margin: 2px !important;
}
.comment .breadcrumb a,.table .breadcrumb a{
	font-size: 10px;
	line-height: 20px;
	padding: 0 8px 0 25px
}
.comment .breadcrumb a:after, .table .breadcrumb a:after{
	width: 20px; 
	height: 20px;
	right: -8px;
}
.breadcrumb a {
	text-decoration: none;
	outline: none;
	display: block;
	float: left;
	font-size: 16px;
	line-height: 36px;
	color: white;
	/*need more margin on the left of links to accomodate the numbers*/
	padding: 0 10px 0 40px;
	background: '.rgba( 0,"bg").';
	position: relative;
	font-size: 16px;
	font-weight: bold;
}
.breadcrumb a:first-child {
	border-radius: 5px 0 0 5px;
	padding-left: 10px;
}
.breadcrumb a:first-child:before {
	left: 14px;
}
.breadcrumb a:last-child {
	border-radius: 0 5px 5px 0;
	padding-right: 20px;
}
.breadcrumb a.active, .breadcrumb a:hover{
	background: '.rgba( 0,"in").';
        color:'.rgba( 0,"bg").';
}
.breadcrumb a.active:after, .breadcrumb a:hover:after {
	background: '.rgba( 0,"in").';
        color:'.rgba( 0,"bg").';
}

/*adding the arrows for the breadcrumbs using rotated pseudo elements*/
.breadcrumb a:after {
	content: \'\';
	position: absolute;
	top: 0;
	right: -18px;
	/*same dimension as the line-height of .breadcrumb a */
	width: 36px; 
	height: 36px;
	transform: scale(0.707) rotate(45deg);
	z-index: 1;
	background: '.rgba( 0,"bg").';
	border-radius: 0 5px 0 50px;
	box-shadow: 
		2px -2px 0 2px '.rgba( 0.4,"bg").', 
		3px -3px 0 2px '.rgba( 0.1,"in").';

}
.breadcrumb a:last-child:after {
	content: none;
}
</style>';
}
echo getTableLogs(getLogs($elemType,$elemId),isset($_REQUEST['embeded'])); 
?>
