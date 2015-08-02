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
<script "text/javascript">piwikTracker.trackPageView('Ajout à mes voies');</script>
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
  <div class="col-sm-3">Actions</div>
  <div class="col-sm-9">
	<div class="btn-group-vertical btn-group-lg  col-sm-12" role="group">
		<a class="btn btn-success btn-group-lg glyphicon glyphicon-check col-sm-8" onclick="return mesvoies_form_sav_quick('Echainée');"> Echainée</a> 
  		<a class="btn btn-warning glyphicon glyphicon-check col-sm-8" onclick="return mesvoies_form_sav_quick('Tentative');"> Tentative</a> 
  	</div>
  	<div class="col-sm-12">&#160;</div>
	<div class="btn-group-vertical col-sm-12" role="group">
  		<a class="btn btn-primary glyphicon glyphicon-pencil col-sm-8" Onclick="rtg_dialog('Mes voies',rtg_getHtml('form_mesvoies_detail.php?wid=<?=$_REQUEST['wid']?>'));  return false;"> Saisie en mode avancé</a>
  		<a class="btn btn-primary glyphicon glyphicon-eye-open col-sm-8" Onclick="rtg_dialog('Mes voies',rtg_getHtml('view_mesvoies.php'));  return false;"> Toutes mes voies</a>  	
	</div>

  </div>
</div>


 
    	<input type="hidden" name="datas[quand]" class="form-control" id="mesvoies_date" />
    	<input type="hidden" name="datas[etat]" class="form-control" id="mesvoies_valide"/>
     	<input type="hidden" name="datas[comment]" class="form-control" id="mesvoies_comment"/>
 

  <div id="popupBody" class="modal-footer">
  <div class="col-md-12">
	<div class="col-md-7" id="savPrgBar"> </div>
	<div class="col-md-5"> 
		<a class="btn btn-default glyphicon glyphicon-remove" data-dismiss="modal" aria-hidden="true"> Fermer</a>
	</div>
  </div>


</div>
</div>


 </form>

 
</div>
<script type="text/javascript">

function mesvoies_form_sav_quick(etat)
{
	$('#mesvoies_date').val('');
	$('#mesvoies_valide').val(etat);
	var id = rtg_setDatas($('#mesVoiesForm') ,'');
        rtg_clearUserCacheData()
	$('#event-modal').modal('hide');
	return false 
}
</script>





