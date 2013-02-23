<?php 
//session
session_start(); // sessioninit skal ske som noget af det første i dokumentet 
if (!isset($_SESSION['login'])) header("Location: login.php?logon=".$_SERVER['PHP_SELF']);	//hvis en bruger ikke er logget ind gå til login-side

//db-connection
require_once('../../Connections/db_purplelodge_net.php'); 

//Dreamweaver-stuff:
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

//get release
$colname_Sel_rel = "-1";
if (isset($_GET['id'])) {
  $colname_Sel_rel = $_GET['id'];
}
mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
$query_Sel_rel = sprintf("SELECT * FROM bpl_rel WHERE id = %s", GetSQLValueString($colname_Sel_rel, "int"));
$Sel_rel = mysql_query($query_Sel_rel, $db_purplelodge_net) or die(mysql_error());
$row_Sel_rel = mysql_fetch_assoc($Sel_rel);
$totalRows_Sel_rel = mysql_num_rows($Sel_rel);

//get songs
$colname_Sel_songs = "-1";
if (isset($_GET['id'])) {
  $colname_Sel_songs = $_GET['id'];
}
mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
$query_Sel_songs = sprintf("SELECT * FROM bpl_rel_song WHERE r_id = %s ORDER BY track ASC", GetSQLValueString($colname_Sel_songs, "int"));
$Sel_songs = mysql_query($query_Sel_songs, $db_purplelodge_net) or die(mysql_error());
$row_Sel_songs = mysql_fetch_assoc($Sel_songs);
$totalRows_Sel_songs = mysql_num_rows($Sel_songs);

//copy release
$insertSQLrel = sprintf("INSERT INTO bpl_rel (title, artist, reldate, reltype,  media, `case`, label, serial, country, `comment`, showtime, mo, main) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($row_Sel_rel['title'], "text"),
                       GetSQLValueString($row_Sel_rel['artist'], "text"),
                       GetSQLValueString($row_Sel_rel['reldate'], "date"),
                       GetSQLValueString($row_Sel_rel['reltype'], "text"),
                       GetSQLValueString($row_Sel_rel['media'], "text"),
                       GetSQLValueString($row_Sel_rel['case'], "text"),
                       GetSQLValueString($row_Sel_rel['label'], "text"),
                       GetSQLValueString($row_Sel_rel['serial'], "text"),
                       GetSQLValueString($row_Sel_rel['country'], "text"),
                       GetSQLValueString($row_Sel_rel['comment'], "text"),
                       GetSQLValueString($row_Sel_rel['showtime'], "int"),
                       GetSQLValueString($row_Sel_rel['mo'], "int"),
                       GetSQLValueString($row_Sel_rel['main'], "int"));

  					   mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
  					   $ResultInsertRel = mysql_query($insertSQLrel, $db_purplelodge_net) or die(mysql_error());
					   $copyrel_id = mysql_insert_id(); //noter id på ny post med det samme
					   
//copy songs					   
do { 
$insertSQLsong = sprintf("INSERT INTO bpl_rel_song (r_id, s_id, track, side) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($copyrel_id, "int"),
                       GetSQLValueString($row_Sel_songs['s_id'], "int"),
                       GetSQLValueString($row_Sel_songs['track'], "int"),
                       GetSQLValueString($row_Sel_songs['side'], "text"));

  					   mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
  					   $ResultInsertSong = mysql_query($insertSQLsong, $db_purplelodge_net) or die(mysql_error());
} while ($row_Sel_songs = mysql_fetch_assoc($Sel_songs)); 

mysql_free_result($Sel_rel);
mysql_free_result($Sel_songs);

//hop til kopieret release i admin
//$loc = $_SERVER['PHP_SELF'];
$loc = "index_test.php";
header("Location: ".$loc."?act=updrel&id=".$copyrel_id);
?>
