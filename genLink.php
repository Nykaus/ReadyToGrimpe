<?php
include_once("inc/config.inc");
include_once("inc/auth.inc");
include_once("inc/bdd.inc");



if (isset($_REQUEST["action"]))
{
	echo "<li>Action : ".$_REQUEST["action"];
	switch($_REQUEST["action"])
	{
		case "AUTH":
			if (isValidAuthLink($_GET))
			{
				echo "<li>Auth is valide";
			}
			else
			{
				echo "<li>Auth is not valide !!";
			}			
		break;
		case "SENDLINK":
			sendAuthLink($_REQUEST["email"]);
		break;	
		
		case "GENLINK":
		?>
			 <li><a href="<?=getAuthLink($_REQUEST["email"],3600); ?>"><?=getAuthLink($_REQUEST["email"],3600); ?></a>
			 <li><a href="<?=getAuthLink($_REQUEST["email"],3600*24); ?>"><?=getAuthLink($_REQUEST["email"],3600*34); ?></a>
			 <li><a href="<?=getAuthLink($_REQUEST["email"],3600*24*30); ?>"><?=getAuthLink($_REQUEST["email"],3600*34*31); ?></a>
			 <li><a href="<?=getAuthLink($_REQUEST["email"],3600*34*365); ?>"><?=getAuthLink($_REQUEST["email"],3600*34*365); ?></a>
		<?php
		break;			
		
			
		case "UNAUTH":
			unAuth();
		break;		
		case "ADDRIGHT":
			if (isValidAddRightLink($_GET))
			{
				echo "<li>Right is valide";
			}
			else
			{
				echo "<li>Right is not valide !!";
			}		
		break;			
		case "DELRIGHT":
			delUserRight($_REQUEST["right"],$_REQUEST["ids"]);
		break;	
		case "GENRIGHT":
			$a = getAddRightLink($_REQUEST["right"],$_REQUEST["ids"]);
			?>
				<a href="<?=$a; ?>"><?=$a; ?></a>
			<?php
			
		break;
	}
}

if (isAuthUser())
{
	echo "<li>Utilisateur authentifié :".getAuthUserName() ;
}

echo "<li> droits : <pre>";
print_r(getUserRight());
echo "</pre>";

?>
<hr/>




<form action="?" method="POST">
<input type="text" name="email" value="toto@exemple.com">
<input type="submit" value="GENLINK">
<input type="hidden" name="action" value="GENLINK">
</form>


<form action="?" method="POST">
<input type="text" name="email">
<input type="submit" value="SENDLINK">
<input type="hidden" name="action" value="SENDLINK">
</form>

<form action="?" method="POST">
<input type="submit" value="UNAUTH">
<input type="hidden" name="action" value="UNAUTH">
</form>


<form action="?" method="POST">
<input type="text" name="right">
<input type="text" name="ids">
<input type="submit" name="action" value="GENRIGHT">
<input type="submit" name="action" value="DELRIGHT">
</form>



<form action="?" method="POST">
<input type="submit" name="action" value="refresh">
</form>

<hr/>
<pre>
<?php print_r($_SESSION); ?>
</pre>
