<?php
ob_start();
include_once("rpc/rtg_auth.php");
if (isset($_REQUEST['goto']))
	header('Location: '.$_REQUEST['goto']);   
else
	header('Location: index.php');   
ob_end_clean();
?>

