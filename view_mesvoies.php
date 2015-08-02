<?php
header('Content-Type: text/html;  charset=UTF-8');
include_once("inc/config.inc");
include_once("inc/auth.inc");
include_once("inc/bdd.inc");
include_once("inc/json.inc");
include_once("inc/mesvoies.inc");

$filter["date"] = "";
$filter["date_debut"] = "";
$filter["date_fin"] ="";
$filter["libelle_type"] ="";
$filter["libelle"] =   "";     
$filter["etat"] =   ""; 
$filter["nb"] =   10; 

    
if (isset($_REQUEST["filter"]))
{
	$filterObjs = rtg_json_decode($_REQUEST["filter"]);
	for ($i=0;$i<sizeof($filterObjs);$i++)
	{
		if ($filterObjs[$i]->value)
		{
			$filter[$filterObjs[$i]->name] = $filterObjs[$i]->value;
		}
	}
}
?>
<script "text/javascript">piwikTracker.trackPageView('Voir mes voies');</script>
<ul id="myTab" class="nav nav-tabs" role="tablist">
  <li class="active"><a href="#mesvoies"     role="tab" data-toggle="tab">Mes voies</a></li>
  <li               ><a href="#statistiques" role="tab" data-toggle="tab">Statistiques</a></li>
</ul>

<div id="myTabContent" class="tab-content">
<div class="tab-pane fade active in" id="mesvoies">

<div id="recherche" class="panel panel-info">
<div class="panel-heading">
	Rechercher
</div>
	<div class="panel-body">
	<form id="mesvoies_filter">
		  

		  <div class="form-group row">
		    <label class="col-sm-3" class="sr-only" for="mesvoies_date">Dates</label>
		    <div class="col-sm-1">du</div>
		    <div class="col-sm-3">
			<div id="mesvoies_date_debut_dp"></div>
		    	<input type="hidden" name="date_debut" class="form-control" id="mesvoies_date_debut" value="<?=$filter["date_debut"]?>" />
		    </div>
		    <div class="col-sm-1">au</div>
		    <div class="col-sm-3">
			<div id="mesvoies_date_fin_dp"></div>
		    	<input type="hidden" name="date_fin" class="form-control" id="mesvoies_date_fin" value="<?=$filter["date_fin"]?>"/>
		    </div>
		  </div>
		  <div class="form-group row">
		    <label class="col-sm-3" class="sr-only" for="mesvoies_d">&#160;</label>
		    <div class="col-sm-1">
		    	<input type="checkbox" name="date" class="form-control" id="mesvoies_d" <?=($filter["date"])?"Checked":"" ?>/>
		    </div>
		    <div class="col-sm-8">Prendre en compte les dates</div>
		  </div>

		  <div class="form-group row">
		    <label class="col-sm-3" class="sr-only" for="mesvoies_nom">Libellé</label>
		    <div class="col-sm-3">
			<select name="libelle_type" class="form-control" id="mesvoies_n">
		    		<option value="w"  <?=($filter["libelle_type"] == "w")?"selected":"" ?>>de voies</option>
		    		<option value="sc"  <?=($filter["libelle_type"] == "sc")?"selected":"" ?>>de secteurs</option>
		    		<option value="si"  <?=($filter["libelle_type"]  == "si")?"selected":"" ?>>de sites</option>
		    	</select>
		    </div>
		    <div class="col-sm-6">
		    	<input type="text" name="libelle" class="form-control" value="<?=$filter["libelle"]?>" id="mesvoies_nom" />
		    </div>
		  </div>
		 <div class="form-group row">
		    <label class="col-sm-3" class="sr-only" for="comment_valide">Etat</label>
		    <div class="col-sm-3">
		    	<select name="etat" class="form-control" id="mesvoies_valide">
		    		<option></option>
		    		<option <?=($filter["etat"] == "Tentative")?"selected":"" ?>>Tentative</option>
		    		<option <?=($filter["etat"] == "Tout les mouvements réussis")?"selected":"" ?>>Tout les mouvements réussis</option>
		    		<option <?=($filter["etat"] == "Avec repos")?"selected":"" ?>>Avec repos</option>
		    		<option <?=($filter["etat"] == "Echainée après travail")?"selected":"" ?>>Echainée après travail</option>
		    		<option <?=($filter["etat"] == "Echainée flash")?"selected":"" ?>>Echainée flash</option>
		    		<option <?=($filter["etat"] == "Echainée à vue")?"selected":"" ?>>Echainée à vue</option>
		    		<option <?=($filter["etat"] == "Echain%")?"selected":"" ?> value="Echain%">Echainée</option>
		    	</select>
		    </div>
		  </div>
		  <div class="form-group row">
		    <label class="col-sm-3" class="sr-only" for="mesvoies_nb">Nombre de résultats</label>
		    <div class="col-sm-2">
		    	<input type="text" name="nb" class="form-control" value="<?=$filter["nb"]?>" id="mesvoies_nb" />
		    </div>
		  </div>
		  <div class="form-group row">
		    <div class="col-sm-3 col-sm-offset-9"><a class="btn btn-primary glyphicon glyphicon-search" onclick="return mesvoies_filter();"> Rechercher</a></div>
 		  </div> 
		</form>
	</div>
</div>

<div id="accordion" class="panel">
<?php
$userid = getAuthUserId();
$filteremail = "";
if (isset($_REQUEST['wemail']) && isAdmin())
{
    $userid = getAuthUserIdByMail($_REQUEST['wemail']);
    $filteremail = "&wemail=".urlencode($_REQUEST['wemail']);
}

	$r = getMesVoies($userid,$filter["nb"],$filter);
	for ($i=0;$i<sizeof($r);$i++)
	{
?>	

		<div class="panel-heading <?php echo ($r[$i]["mesvoies_valide"] == 1)?"panel-success":"panel-warning"; ?>">
			<div class="row">
				<div  class="col-sm-2"><?php echo substr($r[$i]["mesvoies_derniere_tentatives"],8,2)."/".substr($r[$i]["mesvoies_derniere_tentatives"],5,2)."/".substr($r[$i]["mesvoies_derniere_tentatives"],0,4); ?></div>						
				<div  class="col-sm-1"><div class="btn cb<?=$r[$i]["voie_cotation_indice"]?>"><?php echo $r[$i]["voie_cotation_indice"].$r[$i]["voie_cotation_lettre"].$r[$i]["voie_cotation_ext"] ?></div></div>
				<div  class="col-sm-7"><?php echo getElemDisplayName("w",$r[$i]["voie_id"],""); ?></div>
				<div  class="col-sm-2"><a class="btn btn-primary glyphicon glyphicon-eye-open" href="#"> Details</a></div>

			</div>
		</div>
		<div class="panel-body">
			<div class="row">
				<div  class="col-sm-10" id="vtype<?=$r[$i]["voie_id"]?>">

				</div>
					<script type="text/javascript"> 
						var type = <?= ($r[$i]['voie_type'])?$r[$i]['voie_type']:"{}" ?>;
						$('#vtype<?=$r[$i]["voie_id"]?>').html(rtg_viewType(type,'profil')+rtg_viewType(type,'prises')+rtg_viewType(type,'escalade'));
					</script>
				<div  class="col-sm-2">
					<a href="/w/<?=$r[$i]["voie_id"]?>" class="btn btn-primary glyphicon glyphicon-eye-open" onclick="rtg_viewWDetails('<?=$r[$i]["voie_id"]?>'); $('#event-modal').modal('hide'); return false;"> Voir la voie</a>
				
				</div>	
			</div>
			<?php
				$t = explode("§",$r[$i]["mesvoies_datas"]);
				for ($j=0;$j<sizeof($t);$j++)
				{
					$data = rtg_json_decode($t[$j]);
					?>
					<div class="row">
						<div  class="col-sm-3"><?php echo ($data->quand)?$data->quand:"-"; ?></div>		
						<div  class="col-sm-3"><?php echo $data->etat; ?></div>
						<div  class="col-sm-6"><?php echo $data->comment; ?></div>
					</div>
					<?php
				}
			?>
		</div>

<?php 	} ?>
</div>
</div>


<div class="tab-pane fade" id="statistiques">
<div>A venir ...</div>
</div>

</div>


<script type="text/javascript">
$('#myTab a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})
$(function() {
  $( "#accordion" ).accordion({heightStyle: "content"});
  $( "#recherche" ).accordion({heightStyle: "content" <?=(isset($_REQUEST["filter"]))?"":", collapsible: true, active: false"?> });


$( "#mesvoies_date_debut_dp" ).datepicker();
$( "#mesvoies_date_fin_dp" ).datepicker();

$( "#mesvoies_date_debut_dp" ).datepicker( "option","altField", "#mesvoies_date_debut"   );
$( "#mesvoies_date_debut_dp" ).datepicker( "option","altFormat", "dd/mm/yy"   );
$( "#mesvoies_date_debut_dp" ).datepicker( "option","dateFormat", "dd/mm/yy"   );
<?php
if ($filter["date_debut"])
{
	$d = explode("/",$filter["date_debut"]);
	echo "$( \"#mesvoies_date_debut_dp\" ).datepicker( \"setDate\", new Date(".$d[2].",".$d[1].",".$d[0].",0,0,0,0) );";
}
?>

$( "#mesvoies_date_fin_dp" ).datepicker( "option","altField", "#mesvoies_date_fin"   );
$( "#mesvoies_date_fin_dp" ).datepicker( "option","altFormat", "dd/mm/yy"   );
$( "#mesvoies_date_fin_dp" ).datepicker( "option","dateFormat", "dd/mm/yy"   );
<?php
if ($filter["date_fin"])
{
	$d = explode("/",$filter["date_fin"]);
	echo "$( \"#mesvoies_date_fin_dp\" ).datepicker( \"setDate\", new Date(".$d[2].",".$d[1].",".$d[0].",0,0,0,0) );";
}
?>

});
function mesvoies_filter()
{
	var data = $('#mesvoies_filter').serializeArray();
	rtg_dialog('Mes voies',rtg_getHtml('view_mesvoies.php?filter='+JSON.stringify(data)+'<?=$filteremail ?>'));
	return false;
}


</script>
