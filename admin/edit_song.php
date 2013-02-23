<?php 
session_start(); // sessioninit skal ske som noget af det f�rste i dokumentet 
if (!isset($_SESSION['login'])) header("Location: login.php?logon=".$_SERVER['PHP_SELF']);	//hvis en bruger ikke er logget ind g� til login-side

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

$editFormAction = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
  $editFormAction .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}

if ((isset($_POST["MM_update"])) && ($_POST["MM_update"] == "form1")) {
  $time = intval($_POST['sek'])+(60*(intval($_POST['min'])));	//lav om til sekunder
  $updateSQL = sprintf("UPDATE bpl_song SET title=%s, `time`=%s, `comment`=%s, alt_title=%s, mix_info=%s, artist=%s, hl=%s, main=%s, touch=%s WHERE id=%s",
                       GetSQLValueString($_POST['title'], "text"),
                       GetSQLValueString($time, "int"),
                       GetSQLValueString($_POST['comment'], "text"),
                       GetSQLValueString($_POST['alt_title'], "text"),
                       GetSQLValueString($_POST['mix_info'], "text"),
                       GetSQLValueString($_POST['artist'], "text"),
                       GetSQLValueString(isset($_POST['hl']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString(isset($_POST['main']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['touch'], "date"),
                       GetSQLValueString($_POST['id'], "int"));

  mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
  $Result1 = mysql_query($updateSQL, $db_purplelodge_net) or die(mysql_error());

  if (isset($_GET['origin'])) {
  	  $updateGoTo = $_GET['origin'];
  } else {
	  $updateGoTo = "index.php";
	  if (isset($_SERVER['QUERY_STRING'])) {
		$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
		$updateGoTo .= $_SERVER['QUERY_STRING'];
	  }
  }
  header(sprintf("Location: %s", $updateGoTo));
}

$colname_edit_song = "-1";
if (isset($_GET['s_id'])) {
  $colname_edit_song = (get_magic_quotes_gpc()) ? $_GET['s_id'] : addslashes($_GET['s_id']);
}
mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
$query_edit_song = sprintf("SELECT * FROM bpl_song WHERE id = %s", GetSQLValueString($colname_edit_song, "int"));
$edit_song = mysql_query($query_edit_song, $db_purplelodge_net) or die(mysql_error());
$row_edit_song = mysql_fetch_assoc($edit_song);
$totalRows_edit_song = mysql_num_rows($edit_song);

$colname_song_appearances = "-1";
if (isset($_GET['s_id'])) {
  $colname_song_appearances = (get_magic_quotes_gpc()) ? $_GET['s_id'] : addslashes($_GET['s_id']);
}
mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
$query_song_appearances = sprintf("SELECT * FROM bpl_rel_song WHERE s_id = %s ORDER BY id ASC", GetSQLValueString($colname_song_appearances, "int"));
$song_appearances = mysql_query($query_song_appearances, $db_purplelodge_net) or die(mysql_error());
$row_song_appearances = mysql_fetch_assoc($song_appearances);
$totalRows_song_appearances = mysql_num_rows($song_appearances);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Bj&ouml;rks Purple Lodge admin login</title>
<link href="../bpl.css" rel="stylesheet" type="text/css" />
</head>

<body style="margin:0; background-image:url(../syspics/left2.jpg); background-repeat:repeat-y">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:100%">
  <tr>
    <td width="150" height="150" align="center" valign="middle" style="background-image:url(../syspics/corner2.jpg)"><a href="../index.php"><img src="../syspics/rose.gif" alt="Corner" name="corner" width="140" height="140" border="0" id="corner" /></a></td>
    <td height="150" align="center" valign="middle" style="background-image:url(../syspics/top2.jpg)"><h1>BPL song edit</h1></td>
  </tr>
  <tr>
    <td width="150" align="center" valign="top" style="background-image:url(../syspics/left2.jpg)"><?php include("menu.inc.php"); ?></td>
    <td align="center" valign="middle"><form method="post" name="form1" action="<?php echo $editFormAction; ?>">
      <table align="center">
        <tr valign="baseline">
          <td nowrap align="right">ID:</td>
          <td><?php echo $row_edit_song['id']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Title:</td>
          <td><input type="text" name="title" id="se_title" value="<?php echo $row_edit_song['title']; ?>" size="32" onfocus='javascript:ofse(this.id)' /></td>
        </tr>
        <tr valign="baseline">
		<?php $ms = explode(":",(timecalc($row_edit_song['time']))); ?>
          <td nowrap align="right">Time:</td>
          <td nowrap align="left"><input type="hidden" name="time" id="se_time" value="<?php echo $row_edit_song['time']; ?>" /><input type="text" name="min" id="se_min" value="<?php echo intval($ms[0]) ?>" size="2" onfocus='javascript:ofse(this.id)' />:<input type="text" name="sek" id="se_sek" value="<?php echo $ms[1] ?>" size="2" onfocus='javascript:ofse(this.id)' /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Comment:</td>
          <td><input type="text" name="comment" id="se_comment" value="<?php echo $row_edit_song['comment']; ?>" size="32" onfocus='javascript:ofse(this.id)' /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Alt_title:</td>
          <td><input type="text" name="alt_title" id="se_alt_title" value="<?php echo $row_edit_song['alt_title']; ?>" size="32" onfocus='javascript:ofse(this.id)' /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Remix info:</td>
          <td><input type="text" name="mix_info" id="se_mix_info" value="<?php echo $row_edit_song['mix_info']; ?>" size="32" onfocus='javascript:ofse(this.id)' /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Artist:</td>
          <td><input type="text" name="artist" id="se_artist" value="<?php echo $row_edit_song['artist']; ?>" size="32" onfocus='javascript:ofse(this.id)' /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Highlight:</td>
          <td><input type="checkbox" name="hl" id="se_hl" value=""  <?php if (!(strcmp($row_edit_song['hl'],1))) { echo "checked=\"checked\"";} ?> onclick='javascript:ofse(this.id)' /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Main:</td>
          <td><input type="checkbox" name="main" id="se_main" value=""  <?php if (!(strcmp($row_edit_song['main'],1))) {echo "checked=\"checked\"";} ?> onclick='javascript:ofse(this.id)' /></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">Touched:</td>
          <td><?php echo $row_edit_song['touch']; ?></td>
        </tr>
        <tr valign="baseline">
          <td nowrap align="right">&nbsp;</td>
          <td><input type="submit" id="se_submit" value="Update record" class="hidden" /></td>
        </tr>
      </table>
      <input type="hidden" name="touch" value="" />
	  <input type="hidden" name="MM_update" value="form1" />
      <input type="hidden" name="id" value="<?php echo $row_edit_song['id']; ?>" />
    </form>
      <?php if (isset($_GET['origin'])) { 
	  	echo "<br />Back to origin: <a href=\"".$_GET['origin']."\">".$_GET['origin']."</a>"; 
	  } ?>
      <p id="rel_links_txt" class="show_rel_links">The song appears in <?php echo $totalRows_song_appearances ?> release<?php if ($totalRows_song_appearances<>1) echo "s"; ?><?php if ($totalRows_song_appearances) { echo ":"; } else { echo "."; } ?></p>
	  <?php if ($totalRows_song_appearances) { ?>
	  <div id="rel_links" class="show_rel_links">
      <table border="0" cellpadding="2" cellspacing="0" class="all_sides">
        <tr>
          <td class="header_row">id</td>
          <td class="header_row">side</td>
          <td class="header_row">#</td>
          <td class="header_row">timestamp</td>
        </tr>
        <?php 
		$rownum = 1; //init counter
		do { 
		?>
          <tr class="<?php if ($rownum%2) { echo "oddrow"; } else { echo "evenrow"; } ?>">
            <td align="right"><a href="index_test.php?act=updrel&id=<?php echo $row_song_appearances['r_id']; ?>"><?php echo $row_song_appearances['r_id']; ?></a></td>
            <td align="right"><?php echo $row_song_appearances['side']; ?></td>
            <td align="right"><?php echo $row_song_appearances['track']; ?></td>
            <td><?php echo $row_song_appearances['touch']; ?></td>
          </tr>
          <?php 
		  $rownum++;
		  } while ($row_song_appearances = mysql_fetch_assoc($song_appearances)); ?>
      </table>
	  </div>
	  <?php } else { //end table ?>
	  <p><a href="del_song.php?id=<?php echo $_GET['s_id']; ?><?php if (isset($_GET['origin'])) { echo "&amp;origin=".$_GET['origin']; } ?>" title="Click here to delete this song title - NB: no further warnings!" class="alert"><img src="../syspics/drop.png" alt="Delete song title" border="0" /> Delete this song title</a></p>
	  <?php } ?>
      <p>
        <?php if (isset($fejl) && ($fejl)) { ?>
        <?php echo "<p class=\"alert\">".$alert."</p>"; ?>  
        <?php } //fejl ?>  
      </p>
      <p class="alert">Note: Hvis man har lavet &aelig;ndringer i sangen og vil benytte release-linket, skal man promptes for om man vil opdatere f&oslash;rst? Lige nu fjernes linksne blot helt, hvis man r&oslash;r ved et af felterne (s&aring; update-knappen dukker frem). Men det er m&aring;ske ogs&aring; OK? </p>
      <p>Siden senest opdateret <?php echo date("j/n Y H:i", getlastmod()); ?></p>
    </td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($edit_song);

mysql_free_result($song_appearances);
?>