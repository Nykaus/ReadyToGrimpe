<?php
header('Content-Type: text/html;  charset=UTF-8');
include_once("inc/bdd.inc");

switch($_REQUEST["action"])
{ 
	case "siInsert":
		$w['site_id']="";
		$w['site_nom']="";
		$w['site_public']="0";
		$w['site_type']="Falaise";
		$w['site_description_courte']="";
		$w['site_description_longue']="";
		$w['site_lon']="";
		$w['site_lat']="";
		$w['site_hauteur_min']="";
		$w['site_hauteur_max']="";
		$w['site_description_longue']="";
		$w['site_complements']="{}";
	break;
	case "siUpdate":
		$q = "select * from topo_site where site_id=".$_REQUEST["id"];
		$ws = $Bdd->fetch_all_array($q);
		$w = $ws[0];
	break;
}

$q = "select * from topo_secteur_groupe where site_id=".$_REQUEST["id"]." order by groupe_ordre,groupe_name";
$groupes = $Bdd->fetch_all_array($q);



if (isset($_GET["lon"]) && isset($_GET["lat"]))
{
		$w['site_lon'] =  $_GET["lon"];
		$w['site_lat'] =  $_GET["lat"];
}

?>
<ul id="myTab" class="nav nav-tabs" role="tablist">
  <li class="active"><a href="#generalesTab" role="tab" data-toggle="tab">Informations générales</a></li>
  <li><a href="#groupesTab" role="tab" data-toggle="tab">Groupes de secteurs</a></li>
  <li><a href="#complementsTab" role="tab" data-toggle="tab">Informations complémentaires</a></li>
</ul>

<div id="myTabContent" class="tab-content">
<div class="tab-pane fade active in" id="generalesTab">
 <form class="form-horizontal" id="siForm" role="form">
 <input id="w_action" name="action" value="<?=$_GET['action']?>" type="hidden">
  <input name="si_id" value="<?=$w['site_id']?>" type="hidden">

  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="site_nom">Nom</label>
    <div class="col-sm-9">
    	<input name="w[site_nom]" value="<?=$w['site_nom']?>"class="form-control" id="site_nom">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3" class="sr-only">Visiblitité</label>
    <div class="col-sm-1">
    	<input type="radio" name="w[site_public]" value="2" <?=($w['site_public']==2)?"CHECKED=TRUE":""?> class="form-control" id="site_public">
    </div>
    <label class="col-sm-2" class="sr-only" for="site_public">Public sans topo</label>
    <div class="col-sm-1">
    	<input type="radio" name="w[site_public]" value="1" <?=($w['site_public']==1)?"CHECKED=TRUE":""?> class="form-control" id="site_public">
    </div>
    <label class="col-sm-2" class="sr-only" for="site_public">Public</label>    
    <div class="col-sm-1">
    	<input type="radio" name="w[site_public]" value="0" <?=($w['site_public']==0)?"CHECKED=TRUE":""?> class="form-control" id="site_prive">
    </div>
    <label class="col-sm-2" class="sr-only" for="site_prive">Privé</label>
  </div>


  <div class="form-group">
    <label class="col-sm-3" class="sr-only">Type</label>
    <div class="col-sm-1">
    	<input type="radio" name="w[site_type]" value="Falaise" <?=(strtolower($w['site_type'])=="falaise")?"CHECKED=TRUE":""?> class="form-control" id="site_type">
    </div>
    <label class="col-sm-2" class="sr-only" for="site_type">Falaise</label>
    <div class="col-sm-1">
    	<input type="radio" name="w[site_type]" value="Bloc" <?=(strtolower($w['site_type'])=="bloc")?"CHECKED=TRUE":""?> class="form-control" id="site_type">
    </div>
    <label class="col-sm-2" class="sr-only" for="site_type">Bloc</label>    
    <div class="col-sm-1">
    	<input type="radio" name="w[site_type]" value="SAE" <?=(strtolower($w['site_type'])=="sae")?"CHECKED=TRUE":""?> class="form-control" id="site_type">
    </div>
    <label class="col-sm-2" class="sr-only" for="site_type">SAE</label>
  </div>

  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="site_description_courte">Description</label>
    <div class="col-sm-9">
    	<input name="w[site_description_courte]" value="<?=$w['site_description_courte']?>" class="form-control" id="site_description_courte">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="site_description_longue">Description longue</label>
    <div class="col-sm-9">
    	<textarea name="w[site_description_longue]" class="form-control" id="site_description_longue" ><?=$w['site_description_longue']?></textarea>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="site_url_achat">Lien pour achat</label>
    <div class="col-sm-9">
    	<input name="w[site_url_achat]" class="form-control" id="site_url_achat" value="<?=$w['site_url_achat']?>"/>
    </div>
  </div>



  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="depart_lat">Latitude par défaut</label>
    <div class="col-sm-3">
    	<input name="w[site_lat]" value="<?=$w['site_lat']?>"class="form-control" id="site_lat">
    </div>
    <label class="col-sm-3" class="sr-only" for="depart_lon">Longitude par défaut</label>
    <div class="col-sm-3">
    	<input name="w[site_lon]" value="<?=$w['site_lon']?>"class="form-control" id="site_lon">
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="depart_lat">Hauteur minimun par défaut</label>
    <div class="col-sm-3">
    	<input name="w[site_hauteur_min]" value="<?=$w['site_hauteur_min']?>"class="form-control" id="site_hauteur_min">
    </div>
    <label class="col-sm-3" class="sr-only" for="depart_lon">Hauteur maximun défaut</label>
    <div class="col-sm-3">
    	<input name="w[site_hauteur_max]" value="<?=$w['site_hauteur_max']?>"class="form-control" id="site_hauteur_max">
    </div>
  </div>


  <textarea name="w[site_complements]" class="form-control" id="site_complements" style="display:none;"></textarea>
  

 </form>
 </div>
  <div class="tab-pane fade" id="groupesTab">
	<form id="groupeForm">
	 <input id="w_action" name="action" value="updateSecteurGroupe" type="hidden">
	  <input name="si_id" value="<?=$w['site_id']?>" type="hidden">

	<?php
		reset($groupes);
		while(list($k,$v) = each($groupes))
		{
	?>
		  <div class="form-group">
		    <label class="col-sm-6"><?=$v['groupe_name']?></label>
		    <div class="col-sm-6">
		    	<input name="groupe[<?=$v['groupe_name']?>]" value="<?=$v['groupe_ordre']?>" class="form-control">
		    </div>
		  </div>
	<?php
		}
	?>
	</form>
		  <div class="form-group">
			 <div class="col-sm-12">Mettre à 0 pour supprimer un groupe</div>
		  </div>
		  <div class="form-group">
		    <label class="col-sm-6"><input id="addGroupeLabel" class="form-control"></label>
		    <div class="col-sm-6">
		    	<a class="btn btn-primary glyphicon glyphicon-plus" onclick="return rtg_AddGroupeSecFunc();"> Ajouter</a>
		    </div>
		  </div>
  </div>

  <div class="tab-pane fade" id="complementsTab">
	  <div class="form-group row">
	    <label class="col-sm-3" >Ajouter un complements d'info</label>
	    <div class="col-sm-9">
	    	<select class="form-control" id="AddCompFunc" name="add" onchange="rtg_AddCompFunc();"><option />
		<?php
		reset($config['complements']['si']);
		while(list($k,$v) = each($config['complements']['si']))
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

<div id="img-gen"></div>

<script type="text/javascript">
	function rtg_AddGroupeSecFunc() {

	    var newValue = $('#addGroupeLabel').val();
	    var ht = '<div class="form-group">'+
		    '<label class="col-sm-6">'+newValue+'</label>'+
		    '<div class="col-sm-6">'+
		    	'<input name="groupe['+newValue+']" value="1" class="form-control">'+
		    '</div>'+
		  '</div>'
	    	   $('#groupeForm').append(ht);
	    }	


	function si_regen_img() 
	{
			var dataSI  = rtg_getSIdatas('<?=$w['site_id']?>');
			rtg_regen_viewSCImg(dataSI['sc'],0);
			return false;
	}
	

function si_form_sav()
{
	var id = rtg_setDatas($('#groupeForm') ,'<?=$w['site_id']?>');

	$('#site_complements').val(JSON.stringify($('#complementForm').serializeArray()));

	var id = rtg_setDatas($('#siForm') ,'<?=$w['site_id']?>');
	if (id > 0)
	{
		$('#si_id').val(id);
		rtg_viewSIDetails(id);
	}
	$('#w_action').val("siUpdate");
	return false;
}

$('#myTab a').click(function (e) {
  e.preventDefault()
  $(this).tab('show')
})

rtg_RegenForm(<?=($w['site_complements'])?$w['site_complements']:"{}" ?>);		
 	

</script>

</div>
<div id="popupBody" class="modal-footer">
  <div class="col-md-12">
	<div class="col-md-7" id="savPrgBar"> </div>
	<div class="col-md-5"> 
		<a class="btn btn-primary glyphicon glyphicon-floppy-save" onclick="return si_form_sav();"> Sauvegarder</a> 
		<a class="btn btn-primary glyphicon glyphicon-refresh" onclick="return si_regen_img();"> Regénérer les images</a> 
		<a class="btn btn-default glyphicon glyphicon-remove" data-dismiss="modal" aria-hidden="true"> Fermer</a>
	</div>
  </div>





