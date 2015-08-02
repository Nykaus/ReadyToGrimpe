<?php
include_once("inc/config.inc");
include_once("inc/auth.inc");
include_once("inc/bdd.inc");

if (!isAuthUser())
{
	exit();
}
$right = getUserRight();
if (!isset($right['admin']))
{
	exit;
}

function &backup_tables($host, $user, $pass, $name, $tables = '*'){
  $data = "\n/*---------------------------------------------------------------".
          "\n  SQL DB BACKUP ".date("d.m.Y H:i")." ".
          "\n  HOST: {$host}".
          "\n  DATABASE: {$name}".
          "\n  TABLES: {$tables}".
          "\n  ---------------------------------------------------------------*/\n";
  $link = mysql_connect($host,$user,$pass);
  mysql_select_db($name,$link);
  mysql_query( "SET NAMES `utf8` COLLATE `utf8_general_ci`" , $link ); // Unicode

  if($tables == '*'){ //get all of the tables
    $tables = array();
    $result = mysql_query("SHOW TABLES");
    while($row = mysql_fetch_row($result)){
      $tables[] = $row[0];
    }
  }else{
    $tables = is_array($tables) ? $tables : explode(',',$tables);
  }

  foreach($tables as $table){
    $data.= "\n/*---------------------------------------------------------------".
            "\n  TABLE: `{$table}`".
            "\n  ---------------------------------------------------------------*/\n";           
    $data.= "DROP TABLE IF EXISTS `{$table}`;\n";
    $res = mysql_query("SHOW CREATE TABLE `{$table}`", $link);
    $row = mysql_fetch_row($res);
    $data.= $row[1].";\n";

    $result = mysql_query("SELECT * FROM `{$table}`", $link);
    $num_rows = mysql_num_rows($result);    

    if($num_rows>0){
      $vals = Array(); $z=0;
      for($i=0; $i<$num_rows; $i++){
        $items = mysql_fetch_row($result);
        $vals[$z]="(";
        for($j=0; $j<count($items); $j++){
          if (isset($items[$j])) { $vals[$z].= "'".mysql_real_escape_string( $items[$j], $link )."'"; } else { $vals[$z].= "NULL"; }
          if ($j<(count($items)-1)){ $vals[$z].= ","; }
        }
        $vals[$z].= ")"; $z++;
      }
      $data.= "INSERT INTO `{$table}` VALUES ";      
      $data .= "  ".implode(";\nINSERT INTO `{$table}` VALUES ", $vals).";\n";
    }
  }
  mysql_close( $link );
  return $data;
}


$backup_file = 'backup/db-backup-'.time().'.sql';
$tables[] = "topo_site";
$tables[] = "topo_secteur";
$tables[] = "topo_depart";
$tables[] = "topo_voie";
$tables[] = "topo_utilisateurs";
$tables[] = "topo_pi";
$tables[] = "topo_pi_site";
$tables[] = "topo_log";
$tables[] = "topo_commentaires";
$tables[] = "topo_mesvoies";
$tables[] = "topo_secteur_groupe";



// get backup
$mybackup = backup_tables($config["Bdd"]["server"],$config["Bdd"]["user"],$config["Bdd"]["password"],$config["Bdd"]["bddname"],$tables);
// save to file
$handle = fopen($backup_file,'w+');
fwrite($handle,$mybackup);
fclose($handle);
echo "Backup ... OK<br/>";
echo "<script \"text/javascript\">piwikTracker.trackPageView('Backup');</script>";
echo "<a href='".$backup_file."'.sql' target=_blank>".$backup_file."</a>";
?>
