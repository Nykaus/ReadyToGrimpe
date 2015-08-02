<?php
header('Content-Type: text/html;  charset=UTF-8');
include_once("inc/bdd.inc");

switch($_GET["action"])
{
	case "spInsert":
		$w['secteur_id'] = $_GET["sc_id"];
		$w['depart_lon'] =  $_GET["lon"];
		$w['depart_lat'] =  $_GET["lat"];
		$w['depart_exposition'] = "";
		$w['depart_id'] = "";
		$w['depart_description_courte'] = "";
		$w['depart_description_longue'] = "";
		$w['depart_ordre'] = "";		
		$w['depart_complements'] = "";			
	break;
	case "spUpdate":
		$q = "select * from topo_depart where depart_id=".$_GET['id'];
		$ws = $Bdd->fetch_all_array($q);
		$w = $ws[0];
	break;
}

if (isset($_GET["lon"]) && isset($_GET["lat"]))
{
		$w['depart_lon'] =  $_GET["lon"];
		$w['depart_lat'] =  $_GET["lat"];
}
?>
<ul id="myTab" class="nav nav-tabs" role="tablist">
  <li class="active"><a href="#generalesTab" role="tab" data-toggle="tab">Informations générales</a></li>
  <li><a href="#complementsTab" role="tab" data-toggle="tab">Informations complémentaires</a></li>
</ul>

<div id="myTabContent" class="tab-content">
<div class="tab-pane fade active in" id="generalesTab">
<form class="form-horizontal" id="spForm" role="form">
 <input id="action" name="action" value="<?=$_GET['action']?>" type="hidden">
 <input name="sc_id" value="<?=$w['secteur_id']?>" type="hidden">
 <input id="sp_id" name="sp_id" value="<?=$w['depart_id']?>" type="hidden">



  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="depart_lat">Latitude</label>
    <div class="col-sm-3">
    	<input name="w[depart_lat]" value="<?=$w['depart_lat']?>"class="form-control" id="voie_degaine">
    </div>
    <label class="col-sm-3" class="sr-only" for="depart_lon">Longitude</label>
    <div class="col-sm-3">
    	<input name="w[depart_lon]" value="<?=$w['depart_lon']?>"class="form-control" id="voie_hauteur">
    </div>
  </div>


  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="depart_exposition">Exposition</label>
    <div class="col-sm-3">
    	<select id="depart_exposition" name="w[depart_exposition]" class="form-control">
        <option selected><?=$w['depart_exposition']?></option>
        <option>N</option>
        <option>NE</option>
        <option>E</option>
        <option>SE</option>
		<option>S</option>        
		<option>SO</option>        		
		<option>O</option>        				
		<option>NO</option>		
      </select>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="depart_description_courte">Nom</label>
    <div class="col-sm-9">
    	<input name="w[depart_description_courte]" value="<?=$w['depart_description_courte']?>"class="form-control" id="depart_description_courte">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="depart_description_longue">Description longue</label>
    <div class="col-sm-9">
    	<textarea name="w[depart_description_longue]" class="form-control" id="depart_description_longue" ><?=$w['depart_description_longue']?></textarea>
    </div>
  </div>


  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="depart_ordre">Forcer l'ordre</label>
    <div class="col-sm-9">
    	<input name="w[depart_ordre]" value="<?=$w['depart_ordre']?>"class="form-control" id="depart_ordre">
    </div>
  </div>

 <textarea name="w[depart_complements]" class="form-control" id="depart_complements" style="display:none;"></textarea>
 </form>

</div>
<div class="tab-pane fade" id="complementsTab">

 
 <div class="form-group row">
    <label class="col-sm-3" >Ajouter un complements d'info</label>
    <div class="col-sm-9">
    	<select class="form-control" id="AddCompFunc" name="add" onchange="rtg_AddCompFunc();"><option />
	<?php
	reset($config['complements']['sp']);
	while(list($k,$v) = each($config['complements']['sp']))
	{
		echo "<option value=\"".$k."\">".$v['nom']."</option>";
	}
	?>
	</select>
    </div>
  </div>

	
<form id="complementForm">	
	
</form>
</div>
</div>
<script type="text/javascript">
function sp_form_sav()
{
	$('#depart_complements').val(JSON.stringify($('#complementForm').serializeArray()));
	var id = rtg_setDatas($('#spForm') ,'<?=$w['depart_id']?>');
	console.log(id)
	if (id > 0)
	{
		$('#sp_id').val(id);
		rtg_viewSPDetails(id);
	}
	$('#action').val("spUpdate");
	return false 
}
function sp_form_del()
{
	if (confirm("Etes vous sur ?")) {
		$('#action').val("spDel");
		rtg_setDatas($('#spForm') ,'<?=$w['depart_id']?>');
		$('#action').val("spInsert");
		return false;
	}
	return false;
}

rtg_RegenForm(<?=($w['depart_complements'])?$w['depart_complements']:"{}" ?>);
</script>
<div id="popupBody" class="modal-footer">
  <div class="col-md-12">
	<div class="col-md-7" id="savPrgBar"> </div>
	<div class="col-md-5"> 
		<a class="btn btn-primary glyphicon glyphicon-floppy-save" onclick="return sp_form_sav();"> Sauvegarder</a> 
		<a class="btn btn-danger glyphicon glyphicon-trash" onclick="return sp_form_del();"> Supprimer</a> 
		<a class="btn btn-default glyphicon glyphicon-remove" data-dismiss="modal" aria-hidden="true"> Fermer</a>
	</div>
  </div>



