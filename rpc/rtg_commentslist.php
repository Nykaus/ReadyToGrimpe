<?php 
header('Content-Type: text/html; charset=UTF-8');
include_once("../inc/bdd.inc");
include_once("../inc/auth.inc");
include_once("../inc/json.inc");
include_once("../inc/comment.inc");

$nb = 20;
if (isset($_REQUEST['nb']))
	$nb = $_REQUEST['nb'];
	
$comments = rtg_json_decode(getLastComments($nb)); 

if (sizeof($comments) > 0)
{
  for ($i=0;$i <sizeof($comments);$i++)
  {
  
      if (true || $comments[$i]->datas->text || $comments[$i]->datas->recommandation)
      {
	      echo '<div class="comment">';
		      echo '<div class="commentMeta">';
			      echo "<div class=\"commentQui\">".$comments[$i]->qui."</div>";
			      echo "<div class=\"commentQuand\">".substr($comments[$i]->quand,0,10)."</div>";
		      echo '</div>';
		      echo "<div class=\"commentDatas\">";

				if ($comments[$i]->elemName)
				{
				echo "<div class=\"commentPath breadcrumb\">";
					$elsDisp = explode("&gt;",$comments[$i]->elemName);
					$elsPath = explode(">",$comments[$i]->elemPath);
					for ($xx=1;$xx<sizeof($elsDisp);$xx++)
	                                        echo "<a href=\"".preg_replace("/^(si|sc|sp|w)([0-9]*)/",'/\1/\2',$elsPath[$xx])."\">".$elsDisp[$xx]."</a>";
				echo "<a href=\"".$comments[$i]->elemUrl."\"></a></div>";
		               }
		      			if ($comments[$i]->isadmin)
		      			{
		      				      echo "<div class=\"commentPublic\">Public <input type='checkbox' ".(($comments[$i]->public)?"checked":"")." onchange=\"rtg_setToBdd('rpc/rtg_setdatas.php',{action:'commentPublicChange',id:'".$comments[$i]->id."',etat:'".(($comments[$i]->public)?"0":"1")."'}); rtg_updateCommentList('".$_REQUEST['elemType']."','".$_REQUEST['elemId']."');return false;\"/><a onclick=\"if (confirm('Supprimer ce commentaire ?')) {rtg_setToBdd('rpc/rtg_setdatas.php',{action:'commentDelete',id:'".$comments[$i]->id."'}); rtg_updateCommentList('".$_REQUEST['elemType']."','".$_REQUEST['elemId']."');}return false;\" style=\"color:red;\">&#160; 	&#x2327;&#160;</a>";
		      				      if (!$comments[$i]->uservalid)		      				      
			      				      echo "<span>(email non confirmé)</span>";
		      				      echo "</div>";
		      			}
		      			//echo $comments[$i]->datas->recommandation;
		      			if ( $comments[$i]->datas->recommandation > 0)
		      			{
		      				      echo "<div class=\"commentRecommandation\">";
		      				      for ($x=1;$x<=5;$x++)
		      				      {
							if ($x <= $comments[$i]->datas->recommandation)
								echo "★";
							else
								echo "☆";
		      				      }
		      				      echo "</div>";
		      			}
		      			
				      echo "<div class=\"commentText\">".commentTxtToHtml(nl2br(htmlspecialchars($comments[$i]->datas->text)))."</div>";
				      
				      	if ( $comments[$i]->datas->cot)
		      			{
		      				      echo "<div class=\"commentCot c".substr($comments[$i]->datas->cot,0,1)."\">";
		      				      echo $comments[$i]->datas->cot;
		      				      echo "</div>";
		      			}
				      
				      
	      	      echo '</div>';
	      echo '</div>';
      }
  }

}
exit;


?>
