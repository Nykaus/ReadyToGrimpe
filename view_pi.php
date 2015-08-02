<?php
header('Content-Type: text/html;  charset=UTF-8');
include_once("inc/config.inc");
include_once("inc/auth.inc");
include_once("inc/bdd.inc");
include_once("inc/json.inc");



$q = "select * from topo_pi where pi_id=".$_REQUEST['id'];
$ws = $Bdd->fetch_all_array($q);
$w = $ws[0];

// recuperation des inforations general
$nfo = $config["PI"][$w['pi_type']];

$complements = "";
$complementsImg = "";

$comp = rtg_json_decode($w['pi_complements']);

for ($i=0;$i<sizeof($comp);$i++)
{
        $info = $config['complements']['pi'][$comp[$i]->name];
	switch ($info['mode'])
	{
		case "image":
			$complementsImg .='<div class="col-sm-4"><img src="'.$config["BaseUrl"].'bddimg/comp/W.'.$comp[$i]->value.'.jpg?s='.time().'" class="img-thumbnail"/></div>';
		break;
		case "url":
			$complements .='<div class="row">
			    <div class="col-sm-3" class="sr-only" for="pi_lat">'.$info['nom'].'</div>
			    <div class="col-sm-9">
			    	<a href="'.$comp[$i]->value.'" target=_blank>'.$comp[$i]->value.'</a>
			    </div>
			</div>';
		break;		
		default:
			$complements .='<div class="row">
			    <div class="col-sm-3" class="sr-only" for="pi_lat">'.$info['nom'].'</div>
			    <div class="col-sm-9">
			    	'.$comp[$i]->value.'
			    </div>
			</div>';
		break;		
	}
}


?>

<script "text/javascript">piwikTracker.trackPageView('PI > <?=$w['pi_type']?> > <?=$_REQUEST['id']?> - <?=$w['pi_description_courte']?> ');</script>

<div class="row">
  <div class="panel panel-default">
    <div class="panel-heading">
	<span id="linkMyrendre" class="btn-right"></span>
        <img src="<?=$config["BaseUrl"]?>img/<?=$nfo['icon']?>" align="right" />
    	<h4><?=$w['pi_description_courte']?></h4>
	<script type="text/javascript">
		$('#linkMyrendre').html(rtg_myrendre(<?=$w['pi_lat']?>,<?=$w['pi_lon']?>))
	</script>
    </div>
<?php if ($complementsImg) { ?>
  <div class="panel-body">
	<?=$complementsImg ?>
 </div>
<?php } ?>    
<?php    if ($w['pi_description_longue']) {?>
    <div class="panel-body" id="descToHtml"><?=$w['pi_description_longue']?></div>
	<script type="text/javascript">
	descToHtml_text = $('#descToHtml').html()
	descToHtml_html = rtg_markdownrender.makeHtml(descToHtml_text)
	$('#descToHtml').html(descToHtml_html)
	</script>
<?php } ?>    
  </div>
</div>


<?php if ($complements) { ?>
<div class="row">
<div class="panel panel-default">
  <div class="panel-body">
	<?=$complements ?>
 </div>
</div>
</div>
<?php } ?>


<div class="row">
    <div class="col-sm-3" class="sr-only" for="pi_lat"><b>Latitude</b></div>
    <div class="col-sm-3">
    	<?=$w['pi_lat']?>
    </div>
    <div class="col-sm-3" class="sr-only" for="pi_lon"><b>Longitude</b></div>
    <div class="col-sm-3">
    	<?=$w['pi_lon']?>
    </div>
</div>


<?php

if (isAuthUser())
{


	$right = getUserRight();
	
	
	if (isset($right['admin']) 
	|| isset($right['PIWrite']) && is_array($right['PIWrite']) && in_array($_REQUEST['id'],$right['PIWrite']))
	{
?>
<div id="popupBody" class="modal-footer">
  <div class="col-md-12">
	<div class="col-md-3 col-md-offset-9 "> 
		<a class="btn btn-primary glyphicon glyphicon-floppy-save" onclick="rtg_dialog('Mise à jour d`un point d`interet',rtg_getHtml('form_pi.php?action=piUpdate&amp;pi_id=<?=$w['pi_id']?>'));"> Modifier</a> 
	</div>
  </div>
<?php	
	}
}
?>


