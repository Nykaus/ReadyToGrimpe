<?php
include_once(dirname(__FILE__)."/config.inc");
include_once(dirname(__FILE__)."/bdd.inc");
include_once(dirname(__FILE__)."/auth.inc");
include_once(dirname(__FILE__)."/logs.inc");


function commentTxtToHtml($txt)
{
   $html = $txt;
   

$patterns = array();
$patterns[0] = '/(https?:\/\/[^[:blank:]]+)/';
$replacements = array();
$replacements[0] = ' <a href="$1" target="_blanck">$1</a> ';
$html = preg_replace($patterns, $replacements, $txt);


   return $html;
}

function getCommentsPathElem($elemType,$elemId,$path)
{
	switch ($elemType)
	{
		case "":
			return ">";
		default:
		case "pi":
		case "si":
			return ">".$elemType.$elemId.$path.">";
		case "sc":
			$q = "select site_id as id from topo_secteur where secteur_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
			return getLogPathElem("si",$r[0]['id'] ,">".$elemType.$elemId.$path);
		case "sp":
			$q = "select secteur_id as id from topo_depart where depart_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
			return getLogPathElem("sc",$r[0]['id'] ,">".$elemType.$elemId.$path);
		case "w":
			$q = "select depart_id as id from topo_voie where voie_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
			return getLogPathElem("sp",$r[0]['id'] ,">".$elemType.$elemId.$path);

	}
}

function geCommentsPathElemDisplayName($elemType,$elemId,$path)
{
	switch ($elemType)
	{
		default:
		case "pi":		
			$q = "select pi_type, pi_description_courte from topo_pi where pi_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
                        if (!isset($r[0]['pi_type']))
                           return "";
			$disp = $GLOBALS['config']["PI"][$r[0]['pi_type']]["libelle"]." : ".$r[0]['pi_description_courte'];
			return " &gt; ".$disp." ".$path;

		case "si":
			$q = "select site_nom as disp from topo_site where site_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
                        if (!isset($r[0]['disp']))
                           return "";
			return " &gt; ".$r[0]['disp']." ".$path;
		case "sc":
			$q = "select secteur_nom as disp, site_id as id from topo_secteur where secteur_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
			return getLogPathElemDisplayName("si",$r[0]['id'] ," &gt; ".$r[0]['disp']." ".$path);
		case "sp":
			$q = "select depart_description_courte as disp, secteur_id as id from topo_depart where depart_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
			return getLogPathElemDisplayName("sc",$r[0]['id'] ," &gt; ".$r[0]['disp']." ".$path);
		case "w":
			$q = "select voie_nom as disp, depart_id as id from topo_voie where voie_id='".$elemId."'";
			$r = $GLOBALS['Bdd']->fetch_all_array($q);
                        if ($r[0]['disp'])
			      return getLogPathElemDisplayName("sp",$r[0]['id'] ," &gt; ".$r[0]['disp']." ".$path);
                        else
			      return getLogPathElemDisplayName("sp",$r[0]['id'] ,$path);
	}
}

function addComments($datas,$qui,$elemType,$elemId)
{
	$path = getLogPathElem($elemType,$elemId,"");

	$q = "select * from topo_utilisateurs where utilisateur_email = '".$qui."'";
	$r = $GLOBALS['Bdd']->fetch_all_array($q);
        // si l'utilisateur n'est pas connu, j'envoie un mail de confirmation
        $go = false;
        if (sizeof($r) == 0)
        {

                if (sendAuthLink($qui))
                    $go = true;
        }
        else
        {
                    $go = true;
        }
        if ($go)
        {
        
               $datas = str_replace("'","’",$datas);
 	       $q = "insert into topo_commentaires (commentaires_qui,commentaires_data,commentaires_element_type,commentaires_element_id,commentaires_date,commentaires_element_path) values('".$qui."','".$datas."','".$elemType."','".$elemId."',NOW(),'".$path."')";
	       $r = $GLOBALS['Bdd']->query($q);
        }
}
function PublicChangeComments($id,$etat)
{
	$q = "update topo_commentaires set commentaires_public = '$etat'  where commentaires_id = '$id'";
        $GLOBALS['Bdd']->query($q);
}

function DeleteComments($id)
{
	$q = "delete from topo_commentaires where commentaires_id = '$id'";
        $GLOBALS['Bdd']->query($q);
}


function getComments($elemType,$elemId)
{
	$allow['si'] = array();
	$allow['pi'] = array();
	if (hasRight())
	{
			$r = getUserRight();
			if (isset($r['SIWrite']) && is_array($r['SIWrite']) )
			{
				$allow['si'] = $r['SIWrite'];
			}
			if (isset($r['PIWrite']) && is_array($r['PIWrite']) )
			{
				$allow['pi'] = $r['SIWrite'];
			}
	}
       $count=0;
       $fait = array();
       $jsonArray= array();

     $pathLike = getCommentsPathElem($elemType,$elemId,"");
     $commentaires_element_path_array = array();
     $pathLike_ = explode(">",$pathLike);
     $stack=array();
     $stack[]="";
     for($xx=1;$xx<sizeof($pathLike_);$xx++)
     {
	array_push($stack, $pathLike_[$xx]);
	$commentaires_element_path_array[] = implode(">",$stack).">"; 
     }



     // droit de lecture des commentaires masqués
     $where = "and commentaires_public=1";
     $isAdmin = false;
     $path = getCommentsPathElem($elemType,$elemId,"");
     $rootElem= explode(">",$path);
     if (isAdmin() || @in_array($rootElem[2],$allow[$rootElem[1]]))
     {
           $where = "";
           $isAdmin = true;
     }




// commentaires_element_type = '".$elemType."' and commentaires_element_id = '".$elemId."'
	$q = "select * from  topo_commentaires  c LEFT JOIN topo_utilisateurs u ON  c.commentaires_qui = u.utilisateur_email  where    commentaires_element_path in ('".implode("','",$commentaires_element_path_array)."') ". $where."   order by commentaires_date desc ";

	$r = $GLOBALS['Bdd']->fetch_all_array($q);

                        $viewAllow = !isPrivate($elemType, $path);
                        

			if ($isAdmin)
			{
                                $viewAllow = true;
			}
			if ($viewAllow)
			{

	for($i=0;$i<sizeof($r);$i++)
	{
                           if ( $r[$i]["utilisateur_id"] > 0 || $isAdmin)
                           {
                                            $count++;
                                            $fait[$name] = true;
		                      	   $jsonArray[] = "{id:'".$r[$i]["commentaires_id"]."' ,elemUrl:'/".$r[$i]["commentaires_element_type"]."/".$r[$i]["commentaires_element_id"]."', elemPath:'".$r[$i]["commentaires_element_path"]."', elemName:'".getLogPathElemDisplayName($r[$i]["commentaires_element_type"],$r[$i]["commentaires_element_id"],"")."' ,qui:'".@preg_replace("/[\.-]/"," ",@preg_replace("/@.*/","",$r[$i]["commentaires_qui"])) ."',quand:'".$r[$i]["commentaires_date"]."', datas:".  $r[$i]["commentaires_data"].", public:'".$r[$i]["commentaires_public"]."', isadmin:'".$isAdmin."', uservalid:'".(($r[$i]["utilisateur_id"]>0)?"1":"0")."'}";
                            }
	}
                       }

	return "[". implode(",", $jsonArray) ."]";
}


function getLastComments($nb)
{
	$allow['si'] = array();
	$allow['pi'] = array();
	if (hasRight())
	{
			$r = getUserRight();
			if (isset($r['SIWrite']) && is_array($r['SIWrite']) )
			{
				$allow['si'] = $r['SIWrite'];
			}
			if (isset($r['PIWrite']) && is_array($r['PIWrite']) )
			{
				$allow['pi'] = $r['SIWrite'];
			}
	}
       $count=0;
       $fait = array();
       $jsonArray= array();

     // droit de lecture des commentaires masqués
     $where = " where commentaires_public=1";
     $isAdmin = false;
     $path = getCommentsPathElem($elemType,$elemId,"");
     $rootElem= explode(">",$path);
     if (isAdmin() || @in_array($rootElem[2],$allow[$rootElem[1]]))
     {
           $where = "";
           $isAdmin = true;
     }


	$q = "select * from  topo_commentaires  c LEFT JOIN topo_utilisateurs u ON  c.commentaires_qui = u.utilisateur_email ".$where."   order by commentaires_date desc limit ".($nb*4);
	$r = $GLOBALS['Bdd']->fetch_all_array($q);

                       
        $count = 0;
	for($i=0;$i<sizeof($r) && $count < $nb ;$i++)
	{

                       $viewAllow = !isPrivate($r[$i]["commentaires_element_type"], $r[$i]["commentaires_element_path"]);
                        

			if ($isAdmin)
			{
                                $viewAllow = true;
			}
			if ($viewAllow)
			{

                           if ( $r[$i]["utilisateur_id"] > 0 || $isAdmin)
                           {
                                            $count++;
                                            $fait[$name] = true;
		                      	   $jsonArray[] = "{id:'".$r[$i]["commentaires_id"]."', elemUrl:'/".$r[$i]["commentaires_element_type"]."/".$r[$i]["commentaires_element_id"]."', elemPath:'".$r[$i]["commentaires_element_path"]."', elemName:'".getLogPathElemDisplayName($r[$i]["commentaires_element_type"],$r[$i]["commentaires_element_id"],"")."' ,qui:'".@preg_replace("/[\.-]/"," ",@preg_replace("/@.*/","",$r[$i]["commentaires_qui"])) ."',quand:'".$r[$i]["commentaires_date"]."', datas:".  $r[$i]["commentaires_data"].", public:'".$r[$i]["commentaires_public"]."', isadmin:'".$isAdmin."', uservalid:'".(($r[$i]["utilisateur_id"]>0)?"1":"0")."'}";
                            }
	                }
                        $count++;
        }

	return "[". implode(",", $jsonArray) ."]";
}


?>
