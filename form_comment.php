<?php
header('Content-Type: text/html;  charset=UTF-8');
include_once("inc/config.inc");
include_once("inc/auth.inc");
include_once("inc/bdd.inc");
include_once("inc/comment.inc");
$right = getUserRight();
?>
<script "text/javascript">piwikTracker.trackPageView('Ajout d\'un commentaire');</script>


<form class="form-horizontal" id="commentForm" role="form">
 <input id="action" name="action" value="comment" type="hidden">
 <input id="elemType" name="elemType" value="<?=$_REQUEST['elemType']?>" type="hidden">
 <input id="elemId" name="elemId" value="<?=$_REQUEST['elemId']?>" type="hidden">

<?php
if (!isAuthUser())
{
?>
  <div class="form-group">
  <div class="col-sm-12">
       <div class="alert alert-warning" >
       		Vous n'etes pas authentifié, si votre le mail que vous allez saisir n'a pas déjà été validé, un confirmation vous sera envoyé.<br/>
       		Seul les commentaires dont le mail à été validé sont publiés.
       <div>
  </div>
  </div>
  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="comment_mail">Mail</label>
    <div class="col-sm-9">
	<div class="input-group">
		<span class="input-group-addon">@</span>
	    	<input name="email" class="form-control" id="comment_mail" />
	</div>
    </div>
  </div>

<?php
}
?>

  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="comment_recommandation">Recommandation</label>
    <div class="col-sm-4">
    	<select name="datas[recommandation]" class="form-control" id="comment_recommandation">
    		<option value=""></option>
  		<option value="1">★☆☆☆☆</option>
  		<option value="2">★★☆☆☆</option>
   		<option value="3">★★★☆☆</option>
   		<option value="4">★★★★☆</option>
   		<option value="5">★★★★★</option>
    	</select>
    </div>
  </div>

  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="comment_text">Votre commentaire</label>
    <div class="col-sm-9">
    	<textarea name="datas[text]" class="form-control" id="comment_text"></textarea>
    </div>
  </div>

<?php
if ($_REQUEST['elemType'] == "w" || $_REQUEST['elemType'] == "sp")
{
?>


  <div class="form-group">
    <label class="col-sm-3" class="sr-only" for="comment_cot">Vous l'auriez coté ?</label>
    <div class="col-sm-4">
    	<select name="datas[cot]" class="form-control" id="comment_cot">
      		<option></option>
    	
    		<option>4a-</option>
    		<option>4a</option>
    		<option>4a+</option>
    		<option>4b-</option>
    		<option>4b</option>
    		<option>4b+</option>    		
    		<option>4c-</option>
    		<option>4c</option>
    		<option>4c+</option>
    		
    		<option>5a-</option>
    		<option>5a</option>
    		<option>5a+</option>
    		<option>5b-</option>
    		<option>5b</option>
    		<option>5b+</option>    		
    		<option>5c-</option>
    		<option>5c</option>
    		<option>5c+</option>      		   		

    		<option>6a-</option>
    		<option>6a</option>
    		<option>6a+</option>
    		<option>6b-</option>
    		<option>6b</option>
    		<option>6b+</option>    		
    		<option>6c-</option>
    		<option>6c</option>
    		<option>6c+</option>
    		
    		<option>7a-</option>
    		<option>7a</option>
    		<option>7a+</option>
    		<option>7b-</option>
    		<option>7b</option>
    		<option>7b+</option>    		
    		<option>7c-</option>
    		<option>7c</option>
    		<option>7c+</option>  
    		
    		<option>8a-</option>
    		<option>8a</option>
    		<option>8a+</option>
    		<option>8b-</option>
    		<option>8b</option>
    		<option>8b+</option>    		
    		<option>8c-</option>
    		<option>8c</option>
    		<option>8c+</option>    		   		
    	</select>
    </div>
  </div>
<?php
}
?>

 </form>

 
</div>
<script type="text/javascript">
function comment_form_sav()
{
	var id = rtg_setDatas($('#commentForm') ,'');
	rtg_updateCommentList('<?=$_REQUEST['elemType']?>','<?=$_REQUEST['elemId']?>')
	$('#event-modal').modal('hide');
	return false 
}
</script>
<div id="popupBody" class="modal-footer">
  <div class="col-md-12">
	<div class="col-md-7" id="savPrgBar"> </div>
	<div class="col-md-5"> 
		<a class="btn btn-primary glyphicon glyphicon-floppy-save" onclick="return comment_form_sav();"> Sauvegarder</a> 
		<a class="btn btn-default glyphicon glyphicon-remove" data-dismiss="modal" aria-hidden="true"> Fermer</a>
	</div>
  </div>




