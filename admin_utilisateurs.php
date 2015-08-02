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
piwikTracker.trackPageView('Admin Users');
function rtg_admin_utilisateurs_viewvoies(email)
{
	$('#userVoies').html(rtg_getHtml('view_mesvoies.php?wemail='+email))
	return false;
}
function rtg_admin_utilisateurs_viewUser(email)
{
		var r = rtg_getHtml('form_u.php?email='+email)
		$('#userDetail').html(r);
		$('#userVoies').html('<a class="btn btn-success glyphicon glyphicon-search" onclick="return rtg_admin_utilisateurs_viewvoies(\''+email+'\');">Ses voies</a>');
		return false;
}

function rtg_admin_utilisateurs_search()
{
		var data = $('#searchForm').serializeArray();
		var r = rtg_getFromBdd('rpc/rtg_auth.php',data);
		$('#searchList').html(r['html']);
		return false;
}
function rtg_admin_utilisateurs_addUser()
{
	var email = $( "#email").val();
	var r = rtg_getHtml('form_u.php?email='+email+'&action=ADDUSER')
	$('#userDetail').html(r);
	return false;
}

</script>
<div class="row">
		<div class="panel panel-default col-md-4">
			<div class="panel-body">
				 <div class="form-group">
					<label class="col-sm-3" class="sr-only" for="search">Utilisateur</label>
					<div class="col-sm-9">
						<form class="form-horizontal" id="searchForm" role="form">
							<?php
							if (isset($right['admin']))
							{
							?>
								<div class="row">
									<input type="text" name="email" id="email" value="" >
									<a class="btn btn-default glyphicon glyphicon-plus" onclick="return rtg_admin_utilisateurs_addUser();" aria-hidden="true"></a>
								</div>
							<?php
							}
							?>


							<div class="row">
								<input type="hidden" name="action" value="LISTUSER" >
								<input type="hidden" name="calljs" value="rtg_admin_utilisateurs_viewUser" >
								<input name="search" value="" id="searchEmail">
								<a class="btn btn-default glyphicon glyphicon-search" onclick="return rtg_admin_utilisateurs_search();" aria-hidden="true"></a>
							</div>
						</form>
					</div>
				  </div>
			</div>
			<div class="panel-body" id="searchList">
					  
 		    </div>
		</div>
		<div class="panel col-md-8" id="userDetail">
			
		</div>
		
		<div class="panel col-md-8 col-md-offset-4" id="userVoies">

		</div>
</div>

