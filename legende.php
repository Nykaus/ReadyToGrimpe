<?php
header('Content-Type: text/html; charset=utf-8');
include_once("inc/config.inc");

?>
<div class="panel panel-default legende">
	<div class="panel-heading"><b>Difficultées par couleurs</b></div>
	<div class="panel-body">
		<div class="progress progress-striped"><div class="progress-bar" role="progressbar" aria-valuenow="13" aria-valuemin="0" aria-valuemax="100" style="width: 100%; background-color:#00DD00">3</div></div>
		<div class="progress progress-striped"><div class="progress-bar" role="progressbar" aria-valuenow="13" aria-valuemin="0" aria-valuemax="100" style="width: 100%; background-color:#00BB00">4</div></div>
		<div class="progress progress-striped"><div class="progress-bar" role="progressbar" aria-valuenow="13" aria-valuemin="0" aria-valuemax="100" style="width: 100%; background-color:#168EF7">5</div></div>
		<div class="progress progress-striped"><div class="progress-bar" role="progressbar" aria-valuenow="13" aria-valuemin="0" aria-valuemax="100" style="width: 100%; background-color:#F88017">6</div></div>
		<div class="progress progress-striped"><div class="progress-bar" role="progressbar" aria-valuenow="13" aria-valuemin="0" aria-valuemax="100" style="width: 100%; background-color:#FF0000">7</div></div>
		<div class="progress progress-striped"><div class="progress-bar" role="progressbar" aria-valuenow="13" aria-valuemin="0" aria-valuemax="100" style="width: 100%; background-color:#222222">8</div></div>
		<div class="progress progress-striped"><div class="progress-bar" role="progressbar" aria-valuenow="13" aria-valuemin="0" aria-valuemax="100" style="width: 100%; background-color:#000000">9</div></div>
		<br/>
		<div class="progress progress-striped"><div class="progress-bar" role="progressbar" aria-valuenow="13" aria-valuemin="0" aria-valuemax="100" style="width: 100%; background-color:#ADADAD">En construction</div></div>
	</div>
</div>
<div class="panel panel-default legende">
	<div class="panel-heading"><b>Carte</b></div>
	<div class="panel-body">
		<?php
		reset($config['PI']);
		foreach ($config['PI'] as $piK=>$PIv)
		{
			echo '<div class="item"><img src="img/'.$PIv['icon'].'">'.$PIv['libelle'].'</div>';
		}
		?>
		<div class="item"><img src="/img/legende/site.png">Site</div>
		<div class="item"><img src="/img/legende/secteur.png">Secteur</div>
		<div class="item"><img src="/img/legende/depart.png">Depart de voie</div>
	</div>
</div>
<div class="panel panel-default legende">
	<div class="panel-heading"><b>Topo</b></div>
	<div class="panel-body">
		<div class="item"><img src="/img/legende/point.png">Point</div>
		<div class="item"><img src="/img/legende/relais.png">Relais sans chaine</div>
		<div class="item"><img src="/img/legende/relaischaine.png">Relais avec chaine</div>
		<div class="item"><img src="/img/legende/voie.png">voie</div>
	</div>
</div>
<?php
reset($config['type']);
foreach ($config['type'] as $tk=>$type)
{
	?>
	<div class="panel panel-default legende">
		<div class="panel-heading"><b><?=$type['nom'] ?></b></div>
		<div class="panel-body">
			<?php
			foreach ($type['valeurs'] as $vk=>$val)
			{
				echo '<div class="item"><img src="img/'.$tk.'_'. strtolower(preg_replace('/[[:blank:]]/',"",$val)).'.png">'.$val.'</div>';
			}
			?>
		</div>
	</div>
	<?php
}

?>
		

