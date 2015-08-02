<?php
header('Content-Type: text/html;  charset=UTF-8');
include_once("inc/config.inc");
include_once("inc/auth.inc");
include_once("inc/bdd.inc");
include_once("inc/mesvoies.inc");
include_once("inc/json.inc");

$right = getUserRight();
?>



<?php
if (!isAuthUser())
{
?>
  <div class="form-group">
  <div class="col-sm-12">
       <div class="alert alert-info" >
       		Mémoriser les voies que vous avez effectué, les tentatives infrustueuse,...
       </div>
       <div class="alert alert-warning" >
       		Vous n’etes pas authentifié, veuillez vous connecter pour profiter de cette fonctionalitée.
       </div>
  </div>
  </div>
<?php
exit;
}
?>
<script "text/javascript">piwikTracker.trackPageView('Ajout à mes voies (details)');</script>
<form class="form-horizontal" id="mesVoiesForm" role="form">
 <input id="action" name="action" value="updateMesVoies" type="hidden">
 <input id="wid" name="w_id" value="<?=$_REQUEST['wid']?>" type="hidden">

  <div class="form-group">
    <label class="col-sm-3" class="sr-only">Voie</label>
    <div class="col-sm-7">
    	<b><?php echo getElemDisplayName("w",$_REQUEST['wid'],""); ?></b>
    </div>
  </div>
  
   <div class="form-group">
    <label class="col-sm-3" class="sr-only">Historique</label>
    <div class="col-sm-9">
  
  <?php
$filter["nb"] =   10; 
$filter["wid"] =   $_REQUEST['wid']; 
$userid = getAuthUserId();
$filter["date"] = "";
$filter["date_debut"] = "";
$filter["date_fin"] ="";
$filter["libelle_type"] ="";
$filter["libelle"] =   "";     
$filter["etat"] =   ""; 

	$r = getMesVoies($userid,1,$filter);
	if (sizeof($r) <= 0)
	{
	?>
	       <div class="alert alert-info" >
       			Mémoriser les voies que vous avez effectué, les tentatives infrustueuse,...
	       </div>	
	<?php
	}

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


  


  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="mesvoies_date">Quand</label>
    <div class="col-sm-2">
	<div id="mesvoies_date_dp"></div>
    	<input type="hidden" name="datas[quand]" class="form-control" id="mesvoies_date" />
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="comment_valide">Etat</label>
    <div class="col-sm-3">
    	<select name="datas[etat]" class="form-control" id="mesvoies_valide">
    		<option>Tentative</option>
    		<option>Tout les mouvements réussis</option>
    		<option>Avec repos</option>
    		<option>Echainée après travail</option>
    		<option>Echainée flash</option>
    		<option>Echainée à vue</option>
    		<option>Echainée</option>
    	</select>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="mesvoies_comment">Commentaire</label>
    <div class="col-sm-2">
    	<textarea name="datas[comment]" class="form-control" id="mesvoies_comment"></textarea>
    </div>
  </div>





 </form>

 
</div>
<script type="text/javascript">
function mesvoies_form_sav()
{
	var id = rtg_setDatas($('#mesVoiesForm') ,'');
        rtg_clearUserCacheData()
	$('#event-modal').modal('hide');
	return false 
}

function mesvoies_form_sav_quick(etat)
{
	$('#mesvoies_date').val('');
	$('#mesvoies_valide').val(etat);
	var id = rtg_setDatas($('#mesVoiesForm') ,'');
        rtg_clearUserCacheData()
	$('#event-modal').modal('hide');
	return false 
}

$( "#mesvoies_date_dp" ).datepicker(   );
$( "#mesvoies_date_dp" ).datepicker( "option","altField", "#mesvoies_date"   );
$( "#mesvoies_date_dp" ).datepicker( "option","altFormat", "dd/mm/yy"   );
$( "#mesvoies_date_dp" ).datepicker( "option","dateFormat", "dd/mm/yy"   );


 $( "#detailAccordion" ).accordion({heightStyle: "content"});

</script>

  <div id="popupBody" class="modal-footer">
  <div class="col-md-12">
	<div class="col-md-7" id="savPrgBar"> </div>
	<div class="col-md-5"> 
		<a class="btn btn-primary glyphicon glyphicon-floppy-save" onclick="return mesvoies_form_sav();"> Sauvegarder</a> 
		<a class="btn btn-default glyphicon glyphicon-remove" data-dismiss="modal" aria-hidden="true"> Fermer</a>
	</div>
  </div>



