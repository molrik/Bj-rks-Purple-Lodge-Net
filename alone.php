<?php 
session_start(); // sessioninit skal ske som noget af det første i dokumentet 
?>
<?php require_once('../Connections/db_purplelodge_net.php'); ?>
<?php
/* Dreamweaver-indsatte funktioner */
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
//justering af session-variabel
if (isset($_POST['Submit_incgroup'])) {
	if (isset($_POST['incgroup'])) {
		$_SESSION['show_all_on_alone'] = 1;
	} else {
		$_SESSION['show_all_on_alone'] = 0;
	}
}
/* Valg af udvælgelsesmetode og databaseconnections */
if (!isset($_GET['letter']) && !isset($_GET['sid']) && !isset($_GET['title']) && !isset($_GET['artist'])) { $selrel = "1=0"; $footer_message = "";} ; 	//just a precaution
if (isset($_GET['letter']))	{ 
	$selrel = "title LIKE '".$_GET['letter']."%'";  //selecting by letter
	$footer_message = "Showing all songs beginning with <b>".strtoupper(trim($_GET['letter']))."</b>";	
}
if (!(isset($_SESSION['show_all_on_alone']) && $_SESSION['show_all_on_alone'])) {
	$selartist = " AND artist NOT IN ('Tappi Tíkarrass','Kukl','Sugarcubes','Sykurmolarnir','Johnny Triumph and the Sugarcubes')"; //NOT the groups
} else {
	$selartist = "";	//everything
}
$selrel .= $selartist;

mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);	//hovedindsamleren
$query_songs = "SELECT * FROM bpl_song WHERE ".$selrel." AND hl=1 ORDER BY title ASC, main DESC, comment ASC";
$songs = mysql_query($query_songs, $db_purplelodge_net) or die(mysql_error());
$row_songs = mysql_fetch_assoc($songs);
$totalRows_songs = mysql_num_rows($songs);

mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);	//indsaml tilgængelige for-bogstaver (til headermenuen)
$query_songletter = "SELECT DISTINCT UCASE(LEFT(title,1)) AS letter FROM bpl_song WHERE hl=1".$selartist." ORDER BY bpl_song.title";
$songletter = mysql_query($query_songletter, $db_purplelodge_net) or die(mysql_error());
$row_songletter = mysql_fetch_assoc($songletter);
$totalRows_songletter = mysql_num_rows($songletter);

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Bj&ouml;rks Purple Lodge</title>
<script type="text/JavaScript">
<!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.01
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && d.getElementById) x=d.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
//-->
</script>
<link href="bpl.css" rel="stylesheet" type="text/css" />
<link href="bpl_bg1.css" rel="stylesheet" type="text/css" />
</head>

<body onload="MM_preloadImages('molrik.gif','taatoo.gif','umurna2.gif','cubes.gif','bjorkb.gif','eye8.gif','bear.gif')">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:100%">
  <tr>
    <td width="150" height="150" align="center" valign="middle" id="leftcorner"><img src="syspics/bjorkb.gif" alt="Corner" name="corner" width="140" height="140" border="0" id="corner" /></td>
    <td height="150" align="center" valign="middle" id="topmenu">
	<h3>
	<a href="<?php echo $_SERVER['PHP_SELF']; ?>">List of Bj&ouml;rk songs</a>
	<?php if (!(isset($_SESSION['show_all_on_alone']) && $_SESSION['show_all_on_alone'])) { ?>
		- solo works only
	<?php } else { ?>
		- including groups
	<?php } ?>
	</h3>
	<table><tr><td id="year_row">
		<!-- Her fremvises alle forekommende årstal med links (måske senere) -->
		</td></tr>
		<tr><td id="letter_Row">
		<!-- Her fremvises alle forekommende forbogstaver med links -->
        <?php do { ?>
			<a href="<?php echo $_SERVER['PHP_SELF']."?letter=".strtolower(trim($row_songletter['letter'])); ?>"><?php if (isset($_GET['letter']) && (strtolower($_GET['letter'])==strtolower($row_songletter['letter']))) { echo "<b>" ; $bend = "</b>"; } else { $bend = ""; } ?><?php echo ($row_songletter['letter']); ?><?php echo $bend; ?></a><?php echo " "; ?>
        <?php } while ($row_songletter = mysql_fetch_assoc($songletter)); ?>
	</td></tr></table>	</td>
  </tr>
  <tr>
    <td width="150" align="center" valign="top"><?php include("menu.inc.php"); ?></td>
    <td align="center" valign="top">
	<!-- main window begin -->
	<?php include("includes/songlist.inc.php"); ?>	
	<br />
	<?php if (!$totalRows_songs) { //only if no songs found ?>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="listallsongs" id="listallsongs" class="xsmall">	
	  <table border="0" cellspacing="0" cellpadding="0">
		  <tr>
			<td>Include groups</td>
			<td>
			<input name="incgroup" type="checkbox" id="incgroup" value="x" <?php if (isset($_SESSION['show_all_on_alone']) && $_SESSION['show_all_on_alone']) { echo "checked=\"checked\""; } ?>  onclick="document.getElementById('listallsongs').submit()" /><input name="Submit_incgroup" type="hidden" id="Submit_incgroup" value="1" />
			</td>
		  </tr>
		</table>
	</form>	
	<?php } ?>
	<!-- main window end -->	
	</td>
  </tr>
</table>
</body>
</html>
