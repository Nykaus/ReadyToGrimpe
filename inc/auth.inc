<?php
include_once(dirname(__FILE__)."/config.inc");
include_once(dirname(__FILE__)."/bdd.inc");

// ini on controle le $_REQUEST !!
	while(list($k,$v) = each($_REQUEST))
	{
		if ($v)
		{
			switch($k)
			{
				case "id":
					if (is_string($v)  && preg_match("/^[[:digit:],]+$/i",$v,$x) <= 0) {die("Error ".$k." : ".$v);}
				break;
				case "si_id":
				case "sc_id":
				case "sp_id":
				case "w_id":			
					if (is_string($v)  && preg_match("/^[[:digit:]]+$/i",$v,$x)  <= 0) {die("Error ".$k." : ".$v);}
				break;
				case "w":			
					if (is_string($v)  && preg_match("/^[[:digit:]]+$/i",$v,$x)  <= 0) {die("Error ".$k." : ".$v);}
				break;
				default:
				break;
			}
		}
	}






session_start();
function isAuthUser()
{
		if (isset($_SESSION["user"]))
		{
			return true;
		}
}

function isAdmin()
{
		getUserRight();
		if (isset($_SESSION["right"]["admin"]))
		{
			return true;
		}
}


function getAuthUserName()
{
		if (isset($_SESSION["user"]))
		{
			return preg_replace("/[\.-]/"," ",preg_replace("/@.*/","",$_SESSION["user"]));
		}
}
function getAuthUserId()
{
		if (isset($_SESSION["user"]) && !isset($_SESSION["userId"]))
		{
			$q = "select * from topo_utilisateurs where utilisateur_email='".$_SESSION["user"]."'";
			$a = $GLOBALS["Bdd"]->fetch_all_array($q);
                        if (sizeof($a) == 1)
                        {
                               $_SESSION["userId"] = $a[0]['utilisateur_id'];
                               session_commit();
                        }
		}
                if (isset($_SESSION["userId"]))
                         return $_SESSION["userId"];
}
function getAuthUserIdByMail($email)
{
			$q = "select * from topo_utilisateurs where utilisateur_email='".$email."'";
			$a = $GLOBALS["Bdd"]->fetch_all_array($q);
                        if (sizeof($a) == 1)
                        {
                             return $a[0]['utilisateur_id'];
                        }
}

function hasRight()
{
	getUserRight();
	return isset($_SESSION["right"]);
}
function unAuth()
{
	session_unset();
}

function getSign($arrayData,$salt)
{
	ksort($arrayData);
	$data ="";
	while(list($k,$v) = each($arrayData))
	{
		$data .= $k.":".$v.";";
	}

		
        if ($GLOBALS["config"]["KeySign"]["HashType"] == "CRYPT")
                return base64_encode(sha1($data. $GLOBALS["config"]["KeySign"]["HashKey"].$salt));
	return base64_encode(mhash($GLOBALS["config"]["KeySign"]["HashType"], $data, $GLOBALS["config"]["KeySign"]["HashKey"].$salt));
}


function getPiArrayFromSiArray($ids)
{
           $r = array();
           if (!is_array($ids))
              return $r;
           $q = "select pi_id from topo_pi_site where site_id in (".implode(',',$ids).")";
	   $a = $GLOBALS["Bdd"]->fetch_all_array($q);
           for($i=0;$i<sizeof($a);$i++)
           {
                  $r[] = $a[$i]['pi_id'];
            }
           return $r;
}

function getUserRight()
{
	if (isAuthUser())
	{
		if (!isset($_SESSION["right"]) || $_SESSION["right"] =="")
		{
			$q = "select utilisateur_droits from topo_utilisateurs where utilisateur_email='".$_SESSION["user"]."'";
			$a = $GLOBALS["Bdd"]->fetch_all_array($q);
                        $expliciteRight = unserialize($a[0]["utilisateur_droits"]);
                        $allRight = array();
                         reset( $expliciteRight);
                        while(list($exRight,$ids) = each($expliciteRight))
                        {
                             $allRight[$exRight] = $ids;
                             switch($exRight)
                             {
                                   case "SIWrite" :
                                            $allRight['PIWrite'] = getPiArrayFromSiArray($ids);
                                   break;
                                   case "SIRead" :
                                            $allRight['PIRead'] = getPiArrayFromSiArray($ids);
                                   break;
                             }
                        }

			$_SESSION["right"] = $allRight;
			session_commit();
		}
	}
	
	if (isset($_SESSION["right"]))
		return $_SESSION["right"];
}

function getUserRightByEmail($email)
{
	if (isAuthUser())
	{
			$q = "select utilisateur_droits from topo_utilisateurs where utilisateur_email='".$email."'";
			$a = $GLOBALS["Bdd"]->fetch_all_array($q);
			return unserialize($a[0]["utilisateur_droits"]);
	}
}

function delUserRight($right,$list_ids)
{
	if (isAuthUser())
	{
			$r = getUserRight();
			$ids = explode(',',$list_ids);
			for ($i=0;$i<sizeof($ids);$i++)
			{
				if (is_array($r[$right]) && in_array($ids[$i],$r[$right]))
				{
					$r[$right] = array_values($r[$right]);
					unset($r[$right][array_search($ids[$i],$r[$right])]);
					
				}
			}
			if (sizeof($r[$right]) <= 0)
			{
				unset($r[$right]);
			}
			$_SESSION["right"] = $r;
			session_commit();
			
		if (isset($_SESSION["right"]))
		{			
			$q = "update topo_utilisateurs set  utilisateur_droits = '".serialize($_SESSION["right"])."' where utilisateur_email='".$_SESSION["user"]."'";
			$GLOBALS["Bdd"]->query($q);
		}
	}
	
}

function delUserRightByEmail($email,$right,$list_ids)
{
	if (isAuthUser())
	{
		$r = getUserRightByEmail($email);
		$r[$right] = array_values($r[$right]);
		
			$ids = explode(',',$list_ids);
			for ($i=0;$i<sizeof($ids);$i++)
			{
				if (is_array($r[$right]) && in_array($ids[$i],$r[$right]))
				{
					$r[$right] = array_values($r[$right]);
					unset($r[$right][array_search($ids[$i],$r[$right])]);
					
				}
			}
			if (sizeof($r[$right]) <= 0)
			{
				unset($r[$right]);
			}
			
		if (isset($r))
		{			
			$q = "update topo_utilisateurs set  utilisateur_droits = '".serialize($r)."' where utilisateur_email='".$email."'";
			$GLOBALS["Bdd"]->query($q);
		}
	}
	
}

function addUserRight($right,$list_ids)
{
	if (true || isAuthUser())
	{
		$r = getUserRight();
		$ids = explode(',',$list_ids);
		for ($i=0;$i<sizeof($ids);$i++)
		{
			if (!isset($r[$right]) || !in_array($ids[$i],$r[$right]))
			{
				$r[$right][] = $ids[$i];
				$_SESSION["right"] = $r;
			}
		}
		session_commit();
	
	
		if (isAuthUser())
		{		
			$q = "update topo_utilisateurs set  utilisateur_droits = '".serialize($_SESSION["right"])."' where utilisateur_email='".$_SESSION["user"]."'";
			$GLOBALS["Bdd"]->query($q);
		}
	}
}

function addUserRightByEmail($email,$right,$list_ids)
{
	if (isAuthUser())
	{
		$r = getUserRightByEmail($email);
		$ids = explode(',',$list_ids);
		for ($i=0;$i<sizeof($ids);$i++)
		{
			if (!isset($r[$right]) || !in_array($ids[$i],$r[$right]))
			{
				$r[$right][] = $ids[$i];
			}
		}

		if (isAuthUser())
		{		
			$q = "update topo_utilisateurs set  utilisateur_droits = '".serialize($r)."' where utilisateur_email='".$email."'";
			$GLOBALS["Bdd"]->query($q);
		}
	}
}


function getAddRightLink($right,$list_ids)
{
	/* on va metre 
	 * login
	 * date de fin de validite
	 * signature
	 */
	 $url = $GLOBALS["config"]["Right"]["BaseUrl"]."?";
	 $data["action"] = "ADDRIGHT";
	 $data["right"] = $right;
	 $data["ids"] = $list_ids;
	 $data["valid"] = date("YmdHis", mktime(date("H"),date("i"), date("s")+$GLOBALS["config"]["Right"]["TokenTime"] ,date("m"),date("d"),date("Y")));
	 $data["sign"]  = getSign($data,$list_ids);
	 while(list($k,$v) = each($data))
	 {
		$url .= $k."=".urlencode($v)."&";
	 }
	 return $url;
}

function isValidAddRightLink($inData)
{
	 if (isset($inData["sign"]) && isset($inData["right"]) && isset($inData["ids"]) && isset($inData["valid"]))
	 {
		 $sign = $inData["sign"];
		 unset($inData["sign"]);
		 
		 $salt = $inData["ids"];
         if (isset($inData["email"]))
                $salt = $inData["email"];
		 
		 if ($sign == getSign($inData,$salt))
		 {
			 if (date("YmdHis") < $inData["valid"])
			 {
				addUserRight($inData["right"],$inData["ids"]);
				return true;
			 }
		 }
	 }
	 return false;

}



function getAuthLink($email,$time)
{
	/* on va metre 
	 * login
	 * date de fin de validite
	 * signature
	 */
	 $url = $GLOBALS["config"]["Auth"]["BaseUrl"]."?";
	 
	 $data["action"] = "AUTH";
	 $data["email"] = $email;
	 $data["valid"] = date("YmdHis", mktime(date("H"),date("i"), date("s")+$time ,date("m"),date("d"),date("Y")));
	 $data["sign"]  = getSign($data,$email);
	 while(list($k,$v) = each($data))
	 {
		$url .= $k."=".urlencode($v)."&";
	 }
	 return $url;
}


function isValidAuthLink($inData)
{
	 if (isset($inData["sign"]) && isset($inData["email"]) && isset($inData["valid"]))
	 {
		 unAuth();
		 $sign = $inData["sign"];
		 unset($inData["sign"]);
		 
		 
		 if ($sign == getSign($inData,$inData["email"]))
		 {
			 if (date("YmdHis") < $inData["valid"])
			 {
				$_SESSION['user'] = $inData["email"];
				session_commit();
				
				$q = "select * from topo_utilisateurs where utilisateur_email='".$_SESSION["user"]."'";
				$a = $GLOBALS["Bdd"]->fetch_all_array($q);
				if (sizeof($a) <= 0)
				{
					addUser($inData["email"]);
				}
				$q = "update topo_utilisateurs set utilisateur_derniere_connexion=NOW() where utilisateur_email='".$inData["email"]."'";
				$r = $GLOBALS["Bdd"]->query($q);



				return true;
			 }
		 }
	 }
	 return false;

}


function isValidCookie()
{

	 if (isAuthUser())
	 {
			return;
	 }
	
	 if (!isset($_COOKIE['RTGAUTH_email']))
		return;
		
	 if (isset($_COOKIE["RTGAUTH_sign"]) && isset($_COOKIE["RTGAUTH_email"]) && isset($_COOKIE["RTGAUTH_valid"]))
	 {
		 
		 unAuth();
		 $sign = $_COOKIE["RTGAUTH_sign"];
		 $inData["email"] = $_COOKIE["RTGAUTH_email"];
		 $inData["valid"] = $_COOKIE["RTGAUTH_valid"];
		 //echo $sign."==".getSign($inData,$inData["email"]);
		 if ($sign == getSign($inData,$inData["email"]))
		 {
			 if (date("YmdHis") < $inData["valid"])
			 {
				$_SESSION['user'] = $inData["email"];
				session_commit();
				//print_r($_SESSION);
				
				$q = "select * from topo_utilisateurs where utilisateur_email='".$_SESSION["user"]."'";
				$a = $GLOBALS["Bdd"]->fetch_all_array($q);
				if (sizeof($a) <= 0)
				{
					addUser($inData["email"]);
				}
				$q = "update topo_utilisateurs set utilisateur_derniere_connexion=NOW() where utilisateur_email='".$inData["email"]."'";
				$r = $GLOBALS["Bdd"]->query($q);
				

 

				//echo "auth par cookie OK !!";
				return true;
			 }
			 else
		 	 {
				//echo "pas la bonne validite";
			 }
		 }
		 else
		 {
			 //echo "pas la bonne signature";
		 }
	 }
	 else
	 {
		 	 //echo "manque un truc";
	 }
	 //echo "auth par cookie KO !!";
	 return false;

}

function setAuthCookie()
{
	 $valid = mktime(date("H"),date("i"), date("s")+365*24*3600 ,date("m"),date("d"),date("Y"));
	 $data["email"] = $_SESSION["user"];
	 $data["valid"] = date("YmdHis", $valid);
	 $data["sign"]  = getSign($data,$_SESSION["user"]);
	 setcookie('RTGAUTH_email',$data["email"],$valid);
	 setcookie('RTGAUTH_valid',$data["valid"],$valid);
	 setcookie('RTGAUTH_sign',$data["sign"],$valid);
	 
}

function delAuthCookie()
{
	 cookie('RTGAUTH_email');
	 setcookie('RTGAUTH_valid');
	 setcookie('RTGAUTH_sign');
	 
}

function addUser($email)
{
	$q = "insert into topo_utilisateurs(utilisateur_id,utilisateur_email) values(NULL,'".$email."')";
	$r = $GLOBALS["Bdd"]->query($q);
}

function delUser($email)
{
	$q = "delete from topo_utilisateurs where utilisateur_email='".$email."'";
	$r = $GLOBALS["Bdd"]->query($q);
}

function getListUsers($search,$callJs)
{
	$q = "select utilisateur_email from topo_utilisateurs where utilisateur_email='".$search."'";
	$right = getUserRight();
	if (isset($right['admin']))
	{
		$q = "select utilisateur_email from topo_utilisateurs where utilisateur_email like '".$search."%'";
	}
	
	$a = $GLOBALS["Bdd"]->fetch_all_array($q);
	$html = "";
	for ($i=0;$i<sizeof($a);$i++)
	{
		$html .= "<a class=\"btn btn-default glyphicon glyphicon-user\" onclick=\"".$callJs."('".$a[$i]['utilisateur_email']."')\"> ".$a[$i]['utilisateur_email']."</a>";
	}
	return $html;
}


function sendAuthLink($email)
{
	return mail($email, $GLOBALS["config"]["Auth"]["mail"]["subject"], @preg_replace("/§link§/", getAuthLink($email,$GLOBALS["config"]["Auth"]["TokenTime"]) ,$GLOBALS["config"]["Auth"]["mail"]["body"]) ,$GLOBALS["config"]["Auth"]["mail"]["from"]);
}

?>
