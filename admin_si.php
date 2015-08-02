<?php
include_once("inc/config.inc");
include_once("inc/auth.inc");
include_once("inc/bdd.inc");

if (!isAuthUser())
{
	exit();
}
$right = getUserRight();
if (!isset($right['admin']) && !isset($right['SIWrite']))
{
	exit;
}

?>
<script type="text/javascript">
piwikTracker.trackPageView('Admin SI');
function rtg_admin_si_viewSI(id)
{
		rtg_admin_si_sc_list(id)
		var r = rtg_getHtml('form_si.php?action=siUpdate&id='+id)
		$('#userDetail').html(r);
		return false;
}
function rtg_admin_si_viewSC(id)
{
		rtg_admin_si_sp_list(id)
		var r = rtg_getHtml('form_sc.php?action=scUpdate&id='+id)
		$('#userDetail').html(r);
		return false;
}
function rtg_admin_si_viewSP(id)
{
		rtg_admin_si_w_list(id)
		var r = rtg_getHtml('form_sp.php?action=spUpdate&id='+id)
		$('#userDetail').html(r);
		return false;
}
function rtg_admin_si_viewW(id)
{
		var r = rtg_getHtml('form_w.php?action=wUpdate&id='+id)
		$('#userDetail').html(r);
		return false;
}
function rtg_admin_si_search()
{
		$('#scList').html('')
		$('#spList').html('')
		$('#wList').html('')
		var data = $('#searchForm').serializeArray();
		var r = rtg_getFromBdd('rpc/rtg_getdatas.php',data);
		$('#searchList').html(r['html']);
		return false;
}

function rtg_admin_si_sc_list(id)
{
		$('#spList').html('')
		$('#wList').html('')
		var data = {'type':'listsc','id':id, 'calljs':'rtg_admin_si_viewSC'};
		var r = rtg_getFromBdd('rpc/rtg_getdatas.php',data);
		$('#scList').html('<h4>Secteur(s)</h4>'+r['html']);
		return false;
}
function rtg_admin_si_sp_list(id)
{
		$('#wList').html('')
		var data = {'type':'listsp','id':id, 'calljs':'rtg_admin_si_viewSP'};
		var r = rtg_getFromBdd('rpc/rtg_getdatas.php',data);
		$('#spList').html('<h4>Départ(s)</h4>'+r['html']);
		return false;
}
function rtg_admin_si_w_list(id)
{
		var data = {'type':'listw','id':id, 'calljs':'rtg_admin_si_viewW'};
		var r = rtg_getFromBdd('rpc/rtg_getdatas.php',data);
		$('#wList').html('<h4>Voie(s)</h4>'+r['html']);
		return false;
}
</script>
<div class="row">
		<div class="panel panel-default col-md-4">
			<div class="panel-body">
				 <div class="form-group">
					<label class="col-sm-3" class="sr-only" for="search">Sites</label>
					<div class="col-sm-9">
						<form class="form-horizontal" id="searchForm" role="form">
							<div class="row">
								<input type="hidden" name="type" value="listsi" >
								<input type="hidden" name="calljs" value="rtg_admin_si_viewSI" >
								<input name="search" value="" id="searchSI">
								<a class="btn btn-default glyphicon glyphicon-search" onclick="return rtg_admin_si_search();" aria-hidden="true"></a>
							</div>
						</form>
					</div>
				  </div>
			</div>
			<div class="panel-body" id="searchList"></div>
			<div class="panel-body" id="scList"></div>
			<div class="panel-body" id="spList"></div>
			<div class="panel-body" id="wList"></div>
		</div>
		<div class="panel col-md-8" id="userDetail">
			
		</div>
</div>

