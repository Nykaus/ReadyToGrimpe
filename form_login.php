<?php
header('Content-Type: text/html;  charset=UTF-8');

?>
<script "text/javascript">piwikTracker.trackPageView('Login');</script>
<div class="col-sm-6">
	<h2>M'authentifier par mail</h2>
	<ol>
		<li>Vous soumettez votre adresse email</li>
		<li>Nous vous transmetons un lien d’authentification (Qui a une duré limité à quelques jours)</li>
	</ol>


	<form class="form-horizontal" id="authForm" role="form" >
	 <input id="action" name="action" value="SENDLINK" type="hidden">
	  <div class="form-group">
		<div class="col-sm-1"></div>
	    <label class="col-sm-2" class="sr-only" for="email">Email</label>
	    <div class="col-sm-5">
	    	<div class="input-group">
			<span class="input-group-addon">@</span>
		    	<input name="email" value="" class="form-control" id="email" >
		</div>
	    </div>
	  </div>
	</form>

	<div class="col-md-4 col-md-offset-8"> 
		<a class="btn btn-primary" onclick="return auth();">Authentification</a> 
	</div>

</div>

<div class="col-sm-6">
	<h3>Ou me connecter avec</h3>
			<a href="#" onclick="ifIsSocial('@facebook.com')" ><img src="/img/facebook.png" /></a>
			<a href="#" onclick="ifIsSocial('@gmail.com')" ><img src="/img/gmail.png" /></a>
			<a href="#" onclick="ifIsSocial('@gmail.com')" ><img src="/img/googleplus.png" /></a>
			<a href="#" onclick="ifIsSocial('@twitter.com')" ><img src="/img/twitter.png" /></a>
			<a href="#" onclick="ifIsSocial('@live.com')" ><img src="/img/microsoft.png" /></a>

</div>

<script type="text/javascript">

function ifIsSocial(userIdParam)
{
	    var r;
		if (userIdParam)
			userIdVal = userIdParam;
		else
			userIdVal = $("#inputId").val();
		var gotoVal = ""+window.location;
		var dataVal = {userId: userIdVal, location: gotoVal};
		$.ajax({
			url: "checkId.php",
			dataType:"JSON",
			type:"POST",
			async: false,
			data: dataVal,
			complete: function (xhr, datas){
				try {	
					eval('r='+xhr["responseText"]);
				}
				catch(err){
					console.log("Error parse : " + xhr["responseText"])
					r = {id:"ERROR"};
				}
			}
		})
		console.log(r);
		if (r.type == "redirect")	
		{
			window.location = r.redirect;
			return false;			
		}
		return true;
}

function auth()
{
	
        rtg_setPrgBar('#savPrgBar',50,'Envoie d’un mail','progress-bar-info');

		console.log('mail local');
		var data = $('#authForm').serializeArray();
		console.log('data');
		var s = rtg_setToBdd('rpc/rtg_auth.php',data);
		if (s['r']=='true')
		{
			rtg_setPrgBar('#savPrgBar',100,'Envoie éffectué','progress-bar-success');
		}
		else
		{
			rtg_setPrgBar('#savPrgBar',100,'Echec de l’envoie : verifier la saisie de votre email','progress-bar-danger');
		}

}


</script>
</div>
<div id="popupBody" class="modal-footer">
  <div class="col-md-12">
	<div class="col-md-8" id="savPrgBar"> </div>
	<div class="col-md-4"> 
		<a class="btn btn-default" data-dismiss="modal" aria-hidden="true">Fermer</a>
	</div>
  </div>



