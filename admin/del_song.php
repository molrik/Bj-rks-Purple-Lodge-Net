<?php 
session_start(); // sessioninit skal ske som noget af det første i dokumentet 
if (!isset($_SESSION['login'])) header("Location: login.php?logon=".$_SERVER['PHP_SELF']);	//hvis en bruger ikke er logget ind gå til login-side

require_once('../../Connections/db_purplelodge_net.php'); ?>
<?php
if (!function_exists("GetSQLValueString")) {
function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") 
{
  $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;

  $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

  switch ($theType) {
    case "text":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;    
    case "long":
    case "int":
      $theValue = ($theValue != "") ? intval($theValue) : "NULL";
      break;
    case "double":
      $theValue = ($theValue != "") ? "'" . doubleval($theValue) . "'" : "NULL";
      break;
    case "date":
      $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
      break;
    case "defined":
      $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
      break;
  }
  return $theValue;
}
}

if ((isset($_GET['id'])) && ($_GET['id'] != "")) {

	$colname_song_appearances = "-1";
	if (isset($_GET['id'])) {
	  $colname_song_appearances = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
	}
	mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
	$query_song_appearances = sprintf("SELECT * FROM bpl_rel_song WHERE s_id = %s", GetSQLValueString($colname_song_appearances, "int"));
	$song_appearances = mysql_query($query_song_appearances, $db_purplelodge_net) or die(mysql_error());
	$row_song_appearances = mysql_fetch_assoc($song_appearances);
	$totalRows_song_appearances = mysql_num_rows($song_appearances); //tæl optrædener for en sikkerheds skyld

  	if (!$totalRows_song_appearances) {
	  $deleteSQL = sprintf("DELETE FROM bpl_song WHERE id=%s",
						   GetSQLValueString($_GET['id'], "int"));
						   
	  mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
	  $Result1 = mysql_query($deleteSQL, $db_purplelodge_net) or die(mysql_error());
	
	  if (isset($_GET['origin'])) {
		  $deleteGoTo = $_GET['origin'];
	  } else {
		  $deleteGoTo = "index.php";
		  if (isset($_SERVER['QUERY_STRING'])) {
			$deleteGoTo .= (strpos($deleteGoTo, '?')) ? "&" : "?";
			$deleteGoTo .= $_SERVER['QUERY_STRING'];
		  }
	  }
	  header(sprintf("Location: %s", $deleteGoTo));
  } else { ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Bj&ouml;rks Purple Lodge delete songtitle</title>
<link href="../bpl.css" rel="stylesheet" type="text/css" />
</head>

<body style="margin:0; background-image:url(../syspics/left2.jpg); background-repeat:repeat-y">
<p class="alert" align="center">Error: this song-title appears in <?php echo $totalRows_song_appearances ?> releases! Deleting it will break the referential integrity.</p>
</body>
<?php   
  }
  mysql_free_result($song_appearances);
}
?>
