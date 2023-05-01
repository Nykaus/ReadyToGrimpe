<?php
header('Content-Type: text/html;  charset=UTF-8');
include_once("inc/bdd.inc");
include_once("inc/json.inc");

switch($_GET["action"])
{
	case "wInsert":
		$q = "select * from topo_depart where depart_id = ".$_GET["spid"];
		$depid=$_GET["spid"];
		$ws = $Bdd->fetch_all_array($q);
		$w = $ws[0];
		$w['voie_id']="";
		$w['voie_nom']="";
		$w['voie_cotation_indice']="6";
		$w['voie_cotation_lettre']="a";
		$w['voie_cotation_ext']="";
		$w['voie_hauteur']="";
		$w['voie_degaine']="";
		$w['voie_type']="{}";
		$w['voie_description_courte']="";
		$w['voie_description_longue']="";
		$w['voie_dessin']='{"items":[{"it":5,"is":2,"ic":"#ff0000","ia":1,"x":35,"y":85,"t":"D","ta":"l","tb":true},{"it":5,"is":2,"ic":"#ff0000","ia":1,"x":35,"y":35,"t":"F","ta":"l","tb":true}]}';
		$w['voie_complements']="";
		break;
	case "wUpdate":
	case "wCopy":
		$q = "select * from topo_voie, topo_depart where topo_voie.depart_id = topo_depart.depart_id and voie_id=".$_GET["id"];
		$ws = $Bdd->fetch_all_array($q);
		$w = $ws[0];
		$depid=$w["depart_id"];
		//print_r($w);
		break;
}

$q = "select * from topo_depart, topo_secteur, topo_site where topo_site.site_id = topo_secteur.site_id and topo_secteur.secteur_id = topo_depart.secteur_id and depart_id = ".$depid;
$sites = $Bdd->fetch_all_array($q);
$site_type = $sites[0]["site_type"];

$w['voie_type'] = rtg_json_decode($w['voie_type']);



?>
<ul id="myTab" class="nav nav-tabs" role="tablist">
	<li class="active"><a href="#generalesTab" role="tab" data-toggle="tab">Informations générales</a></li>
	<li><a href="#traceTab" role="tab" data-toggle="tab">Tracé</a></li>
	<li><a href="#complementsTab" role="tab" data-toggle="tab">Informations complémentaires</a></li>
</ul>

<div id="myTabContent" class="tab-content">
	<div class="tab-pane fade active in" id="generalesTab">
		<form class="form-horizontal" id="wForm" role="form">
			<input id="action" name="action" value="<?=$_GET['action']?>" type="hidden">
			<input id="w_id" name="w_id" value="<?=$w['voie_id']?>" type="hidden">
			<input name="sp_id" value="<?=$w['depart_id']?>" type="hidden">
			<div class="form-group">
				<label class="col-sm-3" class="sr-only" for="voie_nom">Nom</label>
				<div class="col-sm-9">
					<input name="w[voie_nom]" value="<?=$w['voie_nom']?>"class="form-control" id="voie_nom">
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3" class="sr-only" for="voie_nom"></label>
				<div class="col-sm-9">
					<span font="-2">Le nom est à definir seulement pour differentier les differentes longueurs</span>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-3" for="voie_cotation_indice">Cotation</label>
				<div class="col-sm-1">
					<select id="voie_cotation_indice" name="w[voie_cotation_indice]" class="form-control">
						<option selected><?=$w['voie_cotation_indice']?></option>
						<option>3</option>
						<option>4</option>
						<option>5</option>
						<option>6</option>
						<option>7</option>
						<option>8</option>
						<option>9</option>
					</select>
				</div>
				<div class="col-sm-1">
					<select id="voie_cotation_lettre" name="w[voie_cotation_lettre]" class="form-control">
						<option selected><?=$w['voie_cotation_lettre']?></option>
						<option>a</option>
						<option>b</option>
						<option>c</option>
					</select>
				</div>
				<div class="col-sm-1">
					<select id="voie_cotation_ext" name="w[voie_cotation_ext]" class="form-control">
						<option selected><?=$w['voie_cotation_ext']?></option>
						<option>+</option>
						<option></option>
						<option>-</option>
					</select>
				</div>
			</div>


			<div class="form-group">
				<label class="col-sm-3" for="voie_hauteur">Longueur</label>
				<div class="col-sm-1">
					<input name="w[voie_hauteur]" value="<?=$w['voie_hauteur']?>"class="form-control" id="voie_hauteur">
				</div>

				<?php
				switch($site_type)
				{
				default:
				case "Falaise":
				?>
				<div class="col-sm-2"></div>
				<label class="col-sm-3" for="voie_degaine">Nombre de degaines</label>
				<div class="col-sm-1">
					<input name="w[voie_degaine]" value="<?=$w['voie_degaine']?>"class="form-control" id="voie_degaine">
				</div>
			</div>
			<?php
			break;
			case "Bloc":
			?>
			<div class="col-sm-2"></div>
			<label class="col-sm-3" for="voie_type_depart">Type de depart</label>
			<div class="col-sm-3">
				<select name="w[voie_type_depart]"  class="form-control" id="voie_type_depart">
					<option><?=$w['voie_type_depart']?></option>
					<option>debout</option>
					<option>assis</option>
				</select>
			</div>
	</div>
	<?php
	break;
	}
	?>



	<div class="form-group">
		<label class="col-sm-3" class="sr-only" for="voie_description_courte">Description</label>
		<div class="col-sm-9">
			<input name="w[voie_description_courte]" value="<?=$w['voie_description_courte']?>"class="form-control" id="voie_nom">
		</div>
	</div>

	<div class="form-group">
		<label class="col-sm-3" class="sr-only" for="voie_description_longue">Description longue</label>
		<div class="col-sm-9">
			<textarea name="w[voie_description_longue]" class="form-control" id="voie_nom" ><?=$w['voie_description_longue']?></textarea>
		</div>
	</div>



	<?php

	foreach($config['type'] as $t=>$lv)
	{
		?>
		<div class="form-group">
			<label class="col-sm-3" ><?=$lv['nom']?></label>
			<div class="col-sm-9">
				<?php

				$vals[] = "";
				if (isset($w['voie_type']->$t))
					$vals = $w['voie_type']->$t;
				switch ($lv['mode'])
				{
					case "multi":

						for ($i=0;$i<sizeof($lv['valeurs']);$i++)
						{
							?>
							<div class="col-sm-3">
								<input type="checkbox" name="w[voie_type][<?=$t?>][]"
									   value="<?=$lv['valeurs'][$i]?>"
									<?=( in_array($lv['valeurs'][$i],$vals) )?"CHECKED=TRUE":""?>
									   id="<?=$lv[$i]?>">
								<label for="<?=$lv['valeurs'][$i]?>"><?=$lv['valeurs'][$i]?></label>
							</div>
							<?php
						}
						break;
					case "select":
						?>
						<select name="w[voie_type][<?=$t?>][]" class="form-control">
							<option></option>
							<?php
							for ($i=0;$i<sizeof($lv['valeurs']);$i++)
							{
								?>
								<option <?=( $lv['valeurs'][$i]==$vals[0] )?"SELECTED":""?>><?=$lv['valeurs'][$i]?></option>
								<?php
							}
							?>
						</select>
						<?php
						break;
				}
				?>
			</div>
		</div>
		<?php
	}
	?>

	<div class="form-group">
		<label class="col-sm-3" class="sr-only" for="voie_ordre">Force l'ordre</label>
		<div class="col-sm-2">
			<input type="text" name="w[voie_ordre]" class="form-control" id="voie_ordre" value="<?=$w['voie_ordre']?>"/>
		</div>
	</div>


	<div style="display:none;">
		<textarea name="w[voie_dessin]" class="form-control" id="voie_dessin" ><?=$w['voie_dessin']?></textarea>
		<div id="img-gen"></div>
		<textarea name="img_w" class="form-control" id="wTraceOut"></textarea>
		<textarea name="w[voie_complements]" class="form-control" id="voie_complements"></textarea>
	</div>
	</form>
</div>
<div class="tab-pane fade" id="traceTab">
	<div class="form-group">
		<label class="col-sm-3" class="sr-only" for="voie_dessin">Trace</label>
		<div class="col-sm-12">
			<img src="bddimg/sc/v.F.<?=$w['secteur_id']?>.jpg" id="trace">
		</div>
	</div>
</div>
<div class="tab-pane fade" id="complementsTab">

	<div class="form-group row">
		<label class="col-sm-3" >Ajouter un complements d'info</label>
		<div class="col-sm-9">
			<select class="form-control" id="AddCompFunc" name="add" onchange="rtg_AddCompFunc();"><option />
				<?php
				reset($config['complements']['w']);
				foreach($config['complements']['w'] as $k=>$v)
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


	var img = new Image();
	img.src = document.getElementById('trace').src;
	img.onload = function() {
		var r = img.height / 600;
		//console.log('R:'+r)
		var reg=new RegExp('"is":[^,]*,', "g");
		paths = '{"items":['+rtg_cleanPath('<?=preg_replace("/'/","’",$w['voie_dessin'])?>').replace(reg,'"is":'+r+',')+']}';
		paths = paths.replace(/({"it":[^}]*)#......("[^}]*"t":"[D|F])/gm,'$1#FFFFFF$2')
		BetaCreator(document.getElementById('trace'), function() {
			betaCreator = this;
			betaCreator.loadData(paths);
		},{
			onChange: function() {
				$('#voie_dessin').val(betaCreator.getData());
			},
			//zoom: 'contain',
			height: 700,
			width: 1100
		});
	}
	function w_form_sav()
	{
		$('#voie_complements').val(JSON.stringify($('#complementForm').serializeArray()));
		var id = rtg_setDatasW($('#wForm') ,'<?=$w['voie_id']?>',$('#savQuick').prop('checked'));
		if (id > 0)
		{
			$('#w_id').val(id);
			rtg_viewWDetails(id);
		}
		$('#action').val("wUpdate");
		return false
	}
	function w_form_del()
	{
		if (confirm("Etes vous sur ?")) {
			$('#action').val("wDel");
			rtg_setDatas($('#wForm') ,'<?=$w['voie_id']?>');
			$('#action').val("wInsert");
			return false;
		}
		return false;
	}

	$("form[name=wform]").bind('submit',function(){
		return false;
	});


	$('#myTab a').click(function (e) {
		e.preventDefault()
		$(this).tab('show')
	})
	rtg_RegenForm(<?=($w['voie_complements'])?$w['voie_complements']:"{}" ?>);
</script>
</div>
<div id="popupBody" class="modal-footer">
	<div class="col-md-12">
		<div class="col-md-8" id="savPrgBar"> </div>
		<div class="col-md-4">
			<input class="btn btn-primary" type="checkbox" id="savQuick" title="Ne pas regenerer les images" value="true">
			<a class="btn btn-primary glyphicon glyphicon-floppy-save" onclick="return w_form_sav();">Sauvegarder</a>
			<a class="btn btn-danger  glyphicon glyphicon-trash" onclick="return w_form_del();"> Supprimer</a>
			<a class="btn btn-default glyphicon glyphicon-remove" data-dismiss="modal" aria-hidden="true"> Fermer</a>
		</div>
	</div>



