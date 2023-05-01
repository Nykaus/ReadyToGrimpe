<?php
header('Content-Type: text/html;  charset=UTF-8');
include_once("inc/bdd.inc");

switch($_GET["action"])
{
	case "scInsert":
		$w['site_id'] = $_GET["siid"];
		$w['secteur_id'] = "";
		$w['secteur_nom'] = "";
		$w['secteur_description_courte']="";
		$w['secteur_description_longue']="";
		$w['secteur_ordre']="";
		$w['secteur_complements']="{}";
		break;
	case "scUpdate":
		$q = "select * from topo_secteur where secteur_id=".$_GET['id'];
		$ws = $Bdd->fetch_all_array($q);
		$w = $ws[0];
		break;
}

$q = "select * from topo_secteur_groupe where site_id=".$w['site_id']." order by groupe_ordre,groupe_name";
$groupes = $Bdd->fetch_all_array($q);

?>
<ul id="myTab" class="nav nav-tabs" role="tablist">
	<li class="active"><a href="#generalesTab" role="tab" data-toggle="tab">Informations générales</a></li>
	<li><a href="#complementsTab" role="tab" data-toggle="tab">Informations complémentaires</a></li>
</ul>

<div id="myTabContent" class="tab-content">
	<div class="tab-pane fade active in" id="generalesTab">
		<form class="form-horizontal" id="scForm" role="form">
			<input id="action" name="action" value="<?=$_GET['action']?>" type="hidden">
			<input id="sc_id" name="sc_id" value="<?=$w['secteur_id']?>" type="hidden">
			<input name="si_id" value="<?=$w['site_id']?>" type="hidden">



			<div class="form-group">
				<label class="col-sm-3" class="sr-only" for="secteur_nom">Nom</label>
				<div class="col-sm-9">
					<input name="w[secteur_nom]" value="<?=$w['secteur_nom']?>" class="form-control" id="secteur_nom">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3" class="sr-only" for="secteur_groupe">Nom du groupe de secteur (Site de blocs)</label>
				<div class="col-sm-9">
					<select name="w[secteur_groupe]" class="form-control" id="secteur_groupe">
						<option value="<?=$w['secteur_groupe']?>"><?=$w['secteur_groupe']?></options>
						<option />
						<?php
						foreach($groupes as $k=>$v)
						{
							echo '<option value="'.$v['groupe_name'].'">'.$v['groupe_name'].'</options>';
						}

						?>
					</select>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3" class="sr-only" for="secteur_photo">Photo</label>
				<div class="col-sm-9">
					<input name="secteur_photo_F" id="secteur_photo_F" type="hidden">
					<input name="secteur_photo_W" id="secteur_photo_W" type="hidden">
					<input type="file" id="selectImg" accept="image/jpeg" />
					<?php $displayImg = ($w['secteur_id'] > 0)?"block":"none" ?>
					<img id="affImg" class="rtg_topo_img" src="bddimg/sc/v.W.<?=$w['secteur_id']?>.jpg" style="display:<?=$displayImg?>"/>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3" class="sr-only" for="secteur_description_courte">Description</label>
				<div class="col-sm-9">
					<input name="w[secteur_description_courte]" value="<?=$w['secteur_description_courte']?>"class="form-control" id="secteur_description_courte">
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3" class="sr-only" for="secteur_description_longue">Description longue</label>
				<div class="col-sm-9">
					<textarea name="w[secteur_description_longue]" class="form-control" id="secteur_description_longue" ><?=$w['secteur_description_longue']?></textarea>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3" class="sr-only" for="secteur_ordre">Forcer l'ordre</label>
				<div class="col-sm-9">
					<input name="w[secteur_ordre]" class="form-control" id="secteur_ordre" value="<?=$w['secteur_ordre']?>" />
				</div>
			</div>
			<textarea name="w[secteur_complements]" class="form-control" id="secteur_complements" style="display:none;"></textarea>
		</form>


	</div>
	<div class="tab-pane fade" id="complementsTab">

		<div class="form-group row">
			<label class="col-sm-3" >Ajouter un complements d'info</label>
			<div class="col-sm-9">
				<select class="form-control" id="AddCompFunc" name="add" onchange="rtg_AddCompFunc();"><option />
					<?php
					reset($config['complements']['sc']);
					foreach($config['complements']['sc'] as $k=>$v)
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
	function sc_form_sav()
	{
		$('#secteur_complements').val(JSON.stringify($('#complementForm').serializeArray()));
		rtg_setPrgBar('#savPrgBar',30,'Traitement de l\'image','progress-bar-info');
		rtg_imgFile_handleFileSelect('selectImg','secteur_photo_F',1080);
		rtg_imgFile_handleFileSelect('selectImg','secteur_photo_W',500);
		window.setTimeout(
			function(){
				var id = rtg_setDatas($('#scForm') ,'<?=$w['secteur_id']?>');
				$('#affImg').attr("src",'bddimg/sc/v.W.'+id+'.jpg?'+Math.random());
				console.log(id)
				if (id > 0)
				{
					$('#sc_id').val(id);
					rtg_viewSCDetails(id);
				}
				$('#action').val("scUpdate");
			},5000);
		return false
	}
	function sc_form_del()
	{
		if (confirm("Etes vous sur ?")) {
			$('#action').val("scDel");
			rtg_setDatas($('#scForm') ,'<?=$w['secteur_id']?>');
			$('#action').val("scInsert");
			return false;
		}
		return false;
	}


	function sc_regen_img()
	{
		rtg_regen_viewSCImg([<?=$w['secteur_id']?>],0);
		return false;
	}

	rtg_RegenForm(<?=($w['secteur_complements'])?$w['secteur_complements']:"{}" ?>);
</script>
<div id="popupBody" class="modal-footer">
	<div class="col-md-12">
		<div class="col-md-7" id="savPrgBar"> </div>
		<div class="col-md-5">
			<a class="btn btn-primary glyphicon glyphicon-floppy-save" onclick="return sc_form_sav();"> Sauvegarder</a>
			<a class="btn btn-primary glyphicon glyphicon-refresh" onclick="return sc_regen_img();"> Regénérer les images</a>
			<a class="btn btn-danger glyphicon glyphicon-trash" onclick="return sc_form_del();"> Supprimer</a>
			<a class="btn btn-default glyphicon glyphicon-remove" data-dismiss="modal" aria-hidden="true"> Fermer</a>
		</div>
	</div>



