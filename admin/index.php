<?php 
session_start(); // sessioninit skal ske som noget af det første i dokumentet 
if (!isset($_SESSION['login'])) { 
	header("Location: login.php?logon=".$_SERVER['PHP_SELF']); 	//hvis en bruger ikke er logget ind gå til login-side
} else {
	header("Location: index_test.php");
}

// the rest is history

if (isset($_GET['act'])) {
	if (trim($_GET['act'])=='newrel') {$newrel = true; } else {$newrel = false; } ;
	if (trim($_GET['act'])=='updrel') {$updrel = true; } else {$updrel = false; } ;	
	if (trim($_GET['act'])=='delrel') {$delrel = true; } else {$delrel = false; } ;	
}
?>
<?php require_once('../../Connections/db_purplelodge_net.php'); ?>
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

mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
$query_songs = "SELECT * FROM bpl_song ORDER BY title ASC, main DESC, comment ASC";
$songs = mysql_query($query_songs, $db_purplelodge_net) or die(mysql_error());
$row_songs = mysql_fetch_assoc($songs);
$totalRows_songs = mysql_num_rows($songs);

$colname_releases = "-1";
if (isset($_GET['id'])) {
  $colname_releases = (get_magic_quotes_gpc()) ? $_GET['id'] : addslashes($_GET['id']);
}
mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);

$query = "SHOW COLUMNS FROM `bpl_rel` LIKE 'reltype'";	//indhenter gyldige værdier fra enum
$result=mysql_query($query);
if(mysql_num_rows($result)>0) {
	$row=mysql_fetch_row($result);
	$options=explode("','",preg_replace("/(enum|set)\('(.+?)'\)/","\\2",$row[1]));
}

if ($updrel) {	//hent data hvis update
	$query_releases = sprintf("SELECT * FROM bpl_rel WHERE id = %s", GetSQLValueString($colname_releases, "int"));
	$releases = mysql_query($query_releases, $db_purplelodge_net) or die(mysql_error());
	$row_releases = mysql_fetch_assoc($releases);
	$totalRows_releases = mysql_num_rows($releases);
}

if ($delrel) {	//slet sang-release-relation
	if ((isset($_GET['rs_id'])) && ($_GET['rs_id'] != "")) {
	  $deleteSQL = sprintf("DELETE FROM bpl_rel_song WHERE id=%s",
						   GetSQLValueString($_GET['rs_id'], "int"));
	  mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
	  $DelResult = mysql_query($deleteSQL, $db_purplelodge_net) or die(mysql_error());
	  header("Location: ".$_SERVER['PHP_SELF']."?act=updrel&id=".$_GET['rid']);
	}  
}

/* Submit release */
if ($_POST['Submit']) {
	if ($_POST['rel_id']=="") {	//new release
		$tt = "new relase";
  		$insertSQL = sprintf("INSERT INTO bpl_rel (title, artist, reldate, reltype,  media, `case`, label, serial, country, `comment`, showtime, mo, main) VALUES (%s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_POST['reltitle'], "text"),
                       GetSQLValueString($_POST['relartist'], "text"),
                       GetSQLValueString($_POST['reldate'], "date"),
                       GetSQLValueString($_POST['reltypesel'], "text"),
                       GetSQLValueString($_POST['relmedia'], "text"),
                       GetSQLValueString($_POST['relcase'], "text"),
                       GetSQLValueString($_POST['rellabel'], "text"),
                       GetSQLValueString($_POST['relserial'], "text"),
                       GetSQLValueString($_POST['relcountry'], "text"),
                       GetSQLValueString(trim($_POST['relcomment']), "text"),
                       GetSQLValueString(isset($_POST['checkbox_showtime']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['relmo'], "int"),
                       GetSQLValueString(isset($_POST['checkbox_main']) ? "true" : "", "defined","1","0"));

  					   mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
  					   $Result1 = mysql_query($insertSQL, $db_purplelodge_net) or die(mysql_error());
					   header("Location: ".$_SERVER['PHP_SELF']."?act=updrel&id=".mysql_insert_id());
	} else {	//update
		$tt = "update";
  		$updateSQL = sprintf("UPDATE bpl_rel SET title=%s, artist=%s, reldate=%s, reltype=%s, media=%s, `case`=%s, label=%s, serial=%s, country=%s, `comment`=%s, showtime=%s, mo=%s, main=%s, touch=now() WHERE id=%s",
                       GetSQLValueString($_POST['reltitle'], "text"),
                       GetSQLValueString($_POST['relartist'], "text"),
                       GetSQLValueString($_POST['reldate'], "date"),
                       GetSQLValueString($_POST['reltypesel'], "text"),
                       GetSQLValueString($_POST['relmedia'], "text"),
                       GetSQLValueString($_POST['relcase'], "text"),
                       GetSQLValueString($_POST['rellabel'], "text"),
                       GetSQLValueString($_POST['relserial'], "text"),
                       GetSQLValueString($_POST['relcountry'], "text"),
                       GetSQLValueString(trim($_POST['relcomment']), "text"),
                       GetSQLValueString(isset($_POST['checkbox_showtime']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($_POST['relmo'], "int"),
                       GetSQLValueString(isset($_POST['checkbox_main']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString($_POST['rel_id'], "int"));

  	mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
  	$Result1 = mysql_query($updateSQL, $db_purplelodge_net) or die(mysql_error());
	header("Location: ".$_SERVER['PHP_SELF']."?act=updrel&id=".$_POST['rel_id']);
	}
}

if (isset($_GET['Submit_rs'])) { //update/insert af rel_song
  if ($_GET['Submit_rs']) {	//update
  	$rsSQL = sprintf("UPDATE bpl_rel_song SET track=%s, side=%s, touch=now() WHERE id=%s",
                       GetSQLValueString($_GET['track'], "int"),
					   GetSQLValueString($_GET['side'], "text"),
                       GetSQLValueString($_GET['Submit_rs'], "int"));
  } else { //insert
  	$rsSQL = sprintf("INSERT INTO bpl_rel_song (r_id, s_id, track, side) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_GET['rid'], "int"),
					   GetSQLValueString($_GET['s_id'], "int"),
                       GetSQLValueString($_GET['track'], "int"),
					   GetSQLValueString($_GET['side'], "text"));
  }			  
  mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
  $Result_rs = mysql_query($rsSQL, $db_purplelodge_net) or die(mysql_error());
  //echo $rsSQL;
  header("Location: ".$_SERVER['PHP_SELF']."?act=updrel&id=".$_GET['rid']);
}

if (isset($_GET['Submit_s'])) {	//update af song
  $time = intval($_GET['sek'])+(60*(intval($_GET['min'])));	//lav om til sekunder
  if ($_GET['Submit_s']) {	//update
  	$sSQL = sprintf("UPDATE bpl_song SET title=%s, hl=%s, time=%s, artist=%s, comment=%s, main=%s, touch=now() WHERE id=%s",
                       GetSQLValueString($_GET['title'], "text"),
                       GetSQLValueString(isset($_GET['hl']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($time, "int"),
                       GetSQLValueString($_GET['artist'], "text"), 
					   GetSQLValueString($_GET['comment'], "text"), 
					   GetSQLValueString(isset($_GET['main']) ? "true" : "", "defined","1","0"),
					   GetSQLValueString($_GET['Submit_s'], "int"));
  mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
  $Result_s = mysql_query($sSQL, $db_purplelodge_net) or die(mysql_error());
  } else { //insert
  	//først sangen
  	$sSQL = sprintf("INSERT INTO bpl_song (title, hl, time, artist, comment, main) VALUES (%s, %s, %s, %s, %s, %s)",
                       GetSQLValueString($_GET['title'], "text"),
                       GetSQLValueString(isset($_GET['hl']) ? "true" : "", "defined","1","0"),
                       GetSQLValueString($time, "int"),
                       GetSQLValueString($_GET['artist'], "text"), 
					   GetSQLValueString($_GET['comment'], "text"), 
					   GetSQLValueString(isset($_GET['main']) ? "true" : "", "defined","1","0"));	
  mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
  $Result_s = mysql_query($sSQL, $db_purplelodge_net) or die(mysql_error());
  $s_id = mysql_insert_id();	//gem sang-ID til relationen
  // og så relationen
  	$rsSQL = sprintf("INSERT INTO bpl_rel_song (r_id, s_id, track, side) VALUES (%s, %s, %s, %s)",
                       GetSQLValueString($_GET['rid'], "int"),
					   GetSQLValueString($s_id, "int"),
                       GetSQLValueString($_GET['track'], "int"),
					   GetSQLValueString($_GET['side'], "text"));
  mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
  $Result_rs = mysql_query($rsSQL, $db_purplelodge_net) or die(mysql_error());
  }			  
  header("Location: ".$_SERVER['PHP_SELF']."?act=updrel&id=".$_GET['rid']);	
}

if (isset($_GET['addsong'])) {	//add song to release
	if ($_GET['song_sel']) {	// if not new
		
		header("Location: ".$_SERVER['PHP_SELF']."?act=updrel&id=".$_GET['rid']."&song=".$_GET['song_sel']);	
	} else {
	
		header("Location: ".$_SERVER['PHP_SELF']."?act=updrel&id=".$_GET['rid']."&song=new");	
	}
}

//image records
if (isset($_GET['Submit_uri'])) {	//update af image record
  //update
  	$iSQL = sprintf("UPDATE bpl_img SET thumb=%s, image=%s, descr=%s, sort=%s, touch=now() WHERE id=%s",
                       GetSQLValueString($_GET['thumb_input'], "text"),
                       GetSQLValueString($_GET['image_input'], "text"), 
					   GetSQLValueString($_GET['descr_input'], "text"), 
					   GetSQLValueString($_GET['sort_input'], "int"),
					   GetSQLValueString($_GET['Submit_uri'], "int"));
  mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
  $Result_i = mysql_query($iSQL, $db_purplelodge_net) or die(mysql_error());
  header("Location: ".$_SERVER['PHP_SELF']."?act=updrel&id=".$_GET['rid']);	
}

if (isset($_GET['add_img'])) { 
	$mSQL = "SELECT MAX(sort) AS sortmax FROM bpl_img WHERE r_id = ".$_GET['rid'];	//største sorteringsnummer
	$Result_m = mysql_query($mSQL, $db_purplelodge_net) or die(mysql_error());
	$row_Result_m = mysql_fetch_assoc($Result_m);
	$totalRows_Result_m = mysql_num_rows($Result_m);
	if ($totalRows_Result_m) {
		$next_sort = ++$row_Result_m['sortmax'];
	} else {
		$next_sort = 0;
	}
 //insert
  	$iSQL = sprintf("INSERT INTO bpl_img (r_id, thumb, image, descr, sort) VALUES (%s, %s, %s, %s, %s)",
 					   GetSQLValueString($_GET['rid'], "int"),
                       GetSQLValueString("thumbnail", "text"),
                       GetSQLValueString("fullsize", "text"), 
					   GetSQLValueString("description", "text"), 
					   GetSQLValueString($next_sort, "int"));
  mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
  $Result_i = mysql_query($iSQL, $db_purplelodge_net) or die(mysql_error());
  $i_id = mysql_insert_id();	//gem ID til location
  header("Location: ".$_SERVER['PHP_SELF']."?act=updrel&id=".$_GET['rid']."&edit_rel_img=".$i_id);	
}

if ((isset($_GET['del_img']))&& ($_GET['del_img']!="")) { 
	//delete
	  $dSQL = sprintf("DELETE FROM bpl_img WHERE id=%s",
						   GetSQLValueString($_GET['del_img'], "int"));
	  mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
	  $DelResult = mysql_query($dSQL, $db_purplelodge_net) or die(mysql_error());
	  header("Location: ".$_SERVER['PHP_SELF']."?act=updrel&id=".$_GET['rid']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Bj&ouml;rks Purple Lodge Admin</title>
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
<script language="JavaScript" type="text/javascript" src="../includes/window.js"></script>
<link href="../bpl.css" rel="stylesheet" type="text/css" />
<link href="images.css" rel="stylesheet" type="text/css" />
</head>

<body style="margin:0; background-image:url(../syspics/left2.jpg); background-repeat:repeat-y" onload="MM_preloadImages('../molrik.gif','../taatoo.gif','../umurna2.gif','../cubes.gif','../bjorkb.gif','../eye8.gif','../bear.gif')">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:100%">
  <tr>
    <td width="150" height="150" align="center" valign="middle" style="background-image:url(../syspics/corner2.jpg)"><a href="../index.php"><img src="../syspics/rose.gif" alt="Corner" name="corner" width="140" height="140" border="0" id="corner" /></a></td>
    <td height="150" align="center" valign="middle" style="background-image:url(../syspics/top2.jpg)"><h1>BPL Admin </h1></td>
  </tr>
  <tr>
    <td width="150" align="center" valign="top" style="background-image:url(../syspics/left2.jpg)"><?php include("menu.inc.php"); ?></td>
    <td align="center" valign="middle" style="background:url(<?php if (substr_count(strtolower($_SERVER['PHP_SELF']),"test")) echo "test_bg.gif"  ?>)"><p><a href="<?php echo $_SERVER['PHP_SELF']; ?>?act=newrel">Enter new release</a></p>
        <!-- Release start -->
        <?php if ($newrel || $updrel) { ?>
        <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" name="relform">
          <table width="98%" border="0" cellpadding="2" cellspacing="2" <?php if ($newrel) echo "class=\"newrel\""?><?php if ($updrel) echo "class=\"updrel\""?>>
            <tr>
              <td align="center" valign="top" class="xsmall">&nbsp;</td>
              <td colspan="2" class="xsmall">Title</td>
              <td colspan="2" align="left" class="xsmall">Artist</td>
              <td align="right" class="xsmall">Release type </td>
              <td align="left" class="xsmall">Release date </td>
            </tr>
            <tr>
              <td width="75" align="center" valign="top">&nbsp;</td>
              <td colspan="2"><acronym title="Enter the title of the release here">
                <input name="reltitle" type="text" value="<?php if ($updrel) echo $row_releases['title']; ?>" size="40" />
              </acronym></td>
              <td colspan="2" align="left"><acronym title="Enter the artist here">
                <input name="relartist" type="text" value="<?php if ($updrel) echo $row_releases['artist']; ?>" size="40" />
              </acronym></td>
              <td align="right"><acronym title="Choose the release type from these options">
                <select name="reltypesel">
                  <?php foreach($options as $val) { ?>
                  <option value="<?php echo trim($val); ?>"<?php if (trim($val)==$row_releases['reltype']) echo " selected=\"selected\""; ?>><?php echo $val; ?></option>
                  <?php } ?>
                </select>
              </acronym> </td>
              <td align="left"><acronym title="Enter the release data yyyy-mm-dd - 00 for unknown">
                <input name="reldate" type="text" value="<?php if ($updrel) echo $row_releases['reldate']; ?>" size="20" />
              </acronym></td>
            </tr>
            <tr>
              <td align="center" valign="top" class="xsmall">&nbsp;</td>
              <td class="xsmall">Media</td>
              <td class="xsmall">Label</td>
              <td class="xsmall">Relase number </td>
              <td class="xsmall">Country</td>
              <td class="xsmall">Casing</td>
              <td class="xsmall">Molrik</td>
            </tr>
            <tr>
              <td width="75" align="center" valign="top">&nbsp;</td>
              <td><acronym title="Enter the media here">
                <input name="relmedia" type="text" value="<?php if ($updrel) echo $row_releases['media']; ?>" size="10" />
              </acronym></td>
              <td><acronym title="Enter the record label here">
                <input name="rellabel" type="text" value="<?php if ($updrel) echo $row_releases['label']; ?>" size="20" />
              </acronym></td>
              <td><acronym title="Enter the release number here">
                <input name="relserial" type="text" value="<?php if ($updrel) echo $row_releases['serial']; ?>" size="15" />
              </acronym></td>
              <td><acronym title="Enter the country here">
                <input name="relcountry" type="text" value="<?php if ($updrel) echo $row_releases['country']; ?>" size="10" />
              </acronym></td>
              <td><acronym title="Enter the casing here if it differs from standard">
                <input name="relcase" type="text" value="<?php if ($updrel) echo $row_releases['case']; ?>" size="20" />
              </acronym></td>
              <td><acronym title="Enter the number of copies owned by Molrik here">
                <input name="relmo" type="text" value="<?php if ($updrel) { echo $row_releases['mo']; } else { echo "0"; } ?>" size="2" />
              </acronym></td>
            </tr>
            <tr class="xsmall">
              <td>&nbsp;</td>
              <td colspan="6"><acronym>Comment</acronym></td>
            </tr>
            <tr>
              <td><acronym title="Check if this a main release">
                <label><span class="xsmall">Main:</span>
                <input name="checkbox_main" type="checkbox" id="checkbox_main" value="checkbox" <?php if ($updrel) { if(intval($row_releases['main'])) echo "checked=\"checked\""; } ?> />
                </label>
              </acronym></td>
              <td colspan="6">
			  	<acronym title="Enter release comments here"><textarea name="relcomment" cols="100" rows="2" id="relcomment"><?php if ($updrel) echo trim($row_releases['comment']); ?></textarea></acronym>
			  </td>
            </tr>
            <tr>
              <td><acronym title="Check if time is to be shown">
                <label><span class="xsmall">Time:</span>
                <input name="checkbox_showtime" type="checkbox" id="checkbox_showtime" value="checkbox" <?php if ($updrel) { if(intval($row_releases['showtime'])) echo "checked=\"checked\""; } else { echo "checked=\"checked\""; } ?> />
                </label>
              </acronym></td>
              <td colspan="2"><input type="submit" name="Submit" value="Submit" />
                  <input name="Reset" type="reset" id="Reset" value="Reset" />
                  <input name="rel_id" type="hidden" id="rel_id" value="<?php if ($updrel) echo $row_releases['id']; ?>" />
                <?php if ($updrel) echo "Id: ".$row_releases['id']; ?>
              </td>
              <td colspan="2" align="center"><?php if ($updrel) { ?>
                Latest release update: <?php echo $row_releases['touch']; } ?></td>
              <td colspan="2">&nbsp;</td>
            </tr>
          </table>
        </form>
      <!-- updrel start -->
        <?php if ($updrel) { ?>
        <!-- Songs start -->
        <?php
	$r_id = $row_releases['id'];	//noter release-id
	$query_rel_songs = "SELECT bpl_rel_song.id as brs_id, bpl_rel_song.side, bpl_rel_song.track, bpl_song.id as s_id, bpl_song.title, bpl_song.time, bpl_song.comment, bpl_song.alt_title, bpl_song.artist, bpl_song.hl, bpl_song.main, bpl_song.touch ";	//hvad
	$query_rel_songs .= "FROM bpl_rel_song LEFT JOIN bpl_song ON bpl_rel_song.s_id = bpl_song.id ";	//hvorfra
	$query_rel_songs .= "WHERE bpl_rel_song.r_id = $r_id ORDER BY track ASC";	//hvordan
	$rel_songs = mysql_query($query_rel_songs, $db_purplelodge_net) or die(mysql_error());
	$row_rel_songs = mysql_fetch_assoc($rel_songs);
	$totalRows_rel_songs = mysql_num_rows($rel_songs);
	?>
        <table width="98%" border="0" cellpadding="1" cellspacing="1" class="updrel" id="song_table">
          <tr class="xxsmall" style="display:none">
            <td><table cellspacing="0">
                <tr>
                  <td width="10" align="right">Side</td>
                  <td width="10" align="right">Track</td>
                  <td width="10" align="right">rs_id</td>
                </tr>
            </table></td>
            <td><table>
                <tr>
                  <td>Title</td>
                  <td width="10" align="center">hl</td>
                  <td align="right">Time</td>
                  <td>Artist</td>
                  <td>Comment</td>
                  <td width="10" align="center">main</td>
                  <td class="xxsmall">timestamp</td>
                  <td width="10" align="right">s_id</td>
                </tr>
            </table></td>
          </tr>
          <?php do { // start while ?>
          <tr>
            <td><?php if ($totalRows_rel_songs) { //ingen fremvisning uden indhold ?>
                <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" name="brs_form<?php echo $row_rel_songs['brs_id']; ?>">
                  <table cellspacing="0">
                    <tr>
                      <td width="10" align="right"><acronym title="Side of record">
                        <input id="side_<?php echo $row_rel_songs['brs_id']; ?>" name="side" type="text" value="<?php echo $row_rel_songs['side']; ?>" size="4" onfocus='javascript:ofrs(this.id)' />
                      </acronym> </td>
                      <?php $last_side = $row_rel_songs['side']; //save for later ?>
                      <td width="10" align="right"><acronym title="Track number">
                        <input id="track_<?php echo $row_rel_songs['brs_id']; ?>" name="track" type="text" value="<?php echo $row_rel_songs['track']; ?>" size="4" onfocus='javascript:ofrs(this.id)' />
                      </acronym> </td>
                      <?php $next_track = intval($row_rel_songs['track'])+1; //save for later ?>
                      <td width="10" align="right"><acronym title="Click to update the values of song-release-relation <?php echo $row_rel_songs['brs_id']; ?>">
                        <input id="Submit_rs<?php echo $row_rel_songs['brs_id']; ?>" name="Submit_rs" type="submit" value="<?php echo $row_rel_songs['brs_id']; ?>" style="display:none" />
                        </acronym>
                          <input name="rid" type="hidden" value="<?php echo $r_id; ?>" />
                        <a href="<?php echo $_SERVER['PHP_SELF'] ?>?act=delrel&amp;rs_id=<?php echo $row_rel_songs['brs_id']; ?>&amp;rid=<?php echo $r_id; ?>">
						<img src="../syspics/drop.png" id="delpic_<?php echo $row_rel_songs['brs_id']; ?>" alt="drop" width="16" height="16" border="0" title="Click to delete song-release-relation <?php echo $row_rel_songs['brs_id']; ?>" style="display:block" 
						 />
						<!-- 
						Følgende bringer fint et confirm-vindue frem, men temp-variablen skal vist behandles før afsendelse for det virker:
						onClick="temp = window.confirm('Delete song-release-relation <?php echo $row_rel_songs['brs_id']; ?>?'); window.status=(temp)?'confirm: true':'confirm: false'; " 
						-->
						</a> 
						</td>
                    </tr>
                  </table>
                </form></td>
            <td><form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" name="s_form<?php echo $row_rel_songs['s_id']; ?>">
                <table>
                  <tr>
                    <td><acronym title="Song title">
                      <input name="title" type="text" id="title_<?php echo $row_rel_songs['s_id']; ?>" value="<?php echo $row_rel_songs['title']; ?>" size="30" onfocus='javascript:ofs(this.id)' />
                    </acronym> </td>
                    <td width="10" align="center"><acronym title="Set to colour as Bjork-track">
                      <input id="hl_<?php echo $row_rel_songs['s_id']; ?>" name="hl" type="checkbox" value="<?php echo $row_rel_songs['hl']; ?>" <?php if ($row_rel_songs['hl']) echo "checked=\"checked\""; ?> onfocus='javascript:ofs(this.id)' />
                    </acronym> </td>
                    <td align="right"><?php if ($row_releases['showtime']) { 
			$ms = explode(":",(timecalc($row_rel_songs['time']))); ?>
                        <input id="min_<?php echo $row_rel_songs['s_id']; ?>" name="min" type="text" value="<?php echo intval($ms[0]) ?>" size="2" onfocus='javascript:ofs(this.id)' />
                      :
                      <input id="sek_<?php echo $row_rel_songs['s_id']; ?>" name="sek" type="text" value="<?php echo ($ms[1]) ?>" size="2" onfocus='javascript:ofs(this.id)' />
                        <?php } ?>
                    </td>
                    <td><acronym title="Artist">
                      <input id="artist_<?php echo $row_rel_songs['s_id']; ?>" name="artist" type="text" value="<?php echo $row_rel_songs['artist']; ?>" size="30" onfocus='javascript:ofs(this.id)' />
                    </acronym> </td>
                    <td><acronym title="Comment/mix">
                      <input id="comment_<?php echo $row_rel_songs['s_id']; ?>" name="comment" type="text" value="<?php echo $row_rel_songs['comment']; ?>" size="30" onfocus='javascript:ofs(this.id)' />
                    </acronym> </td>
                    <td width="10" align="center"><acronym title="Set as a main track">
                      <input id="main_<?php echo $row_rel_songs['s_id']; ?>" name="main" type="checkbox" value="<?php echo $row_rel_songs['main']; ?>" <?php if ($row_rel_songs['main']) echo "checked=\"checked\""; ?> onfocus='javascript:ofs(this.id)' />
                    </acronym> </td>
                    <td class="xxsmall"><acronym title="Latest song update"> <?php echo shortsdate($row_rel_songs['touch']); ?> </acronym> </td>
                    <td width="10" align="right"><acronym title="Click to update the values of song <?php echo $row_rel_songs['s_id']; ?>">
                      <input id="Submit_s<?php echo $row_rel_songs['s_id']; ?>" name="Submit_s" type="submit" value="<?php echo $row_rel_songs['s_id']; ?>" style="display:none" />
                      </acronym>
                        <input name="rid" type="hidden" value="<?php echo $r_id; ?>" />
                    </td>
                  </tr>
                </table>
            </form>
                <?php } //end fremvising med indhold ?>
            </td>
          </tr>
          <?php } while ($row_rel_songs = mysql_fetch_assoc($rel_songs)); ?>
          <!-- Choose element -->
          <?php if (!isset($_GET['song'])) { ?>
          <tr class="xxsmall">
            <td align="right">Add an additional song:</td>
            <td><form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" name="form_song_sel" id="form_song_sel">
                <select name="song_sel">
                  <option value="0">New song entry</option>
                  <?php
do {  
?>
                  <option value="<?php echo $row_songs['id']?>"><?php echo $row_songs['title']?> (<?php echo $row_songs['comment']?>) <?php echo timecalc($row_songs['time']); ?> - <?php echo $row_songs['artist']?></option>
                  <?php
} while ($row_songs = mysql_fetch_assoc($songs));
  $rows = mysql_num_rows($songs);
  if($rows > 0) {
      mysql_data_seek($songs, 0);
	  $row_songs = mysql_fetch_assoc($songs);
  }
?>
                </select>
                <input name="rid" type="hidden" value="<?php echo $r_id; ?>" />
                <input name="addsong" type="submit" id="addsong" value="Submit" />
            </form></td>
          </tr>
          <?php } else { ?>
          <!-- Use old element -->
          <?php if (intval($_GET['song'])) {
		$useold = true;
		$query_song =  "SELECT * FROM bpl_song WHERE id=".intval($_GET['song']);
		$song = mysql_query($query_song, $db_purplelodge_net) or die(mysql_error());
		$row_song = mysql_fetch_assoc($song);
		$totalRows_song = mysql_num_rows($song);
	?>
          <tr>
            <td><form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" name="rs_form_new">
                <table cellspacing="0" class="newrel">
                  <tr>
                    <td width="10" align="right"><input name="side" type="text" value="<?php if (isset($last_side)) echo $last_side; ?>" size="4" />
                    </td>
                    <td width="10" align="right"><input name="track" type="text" value="<?php if (isset($next_track)) { echo $next_track; } else { echo "1"; } ;?>" size="4" />
                    </td>
                    <td width="10" align="right"><acronym title="Click here to assign release-song relation between release <?php echo $r_id; ?> and song <?php echo $row_song['id']; ?>">
                      <input name="Submit_rs" type="submit" value="0" />
                      </acronym>
                        <input name="rid" type="hidden" value="<?php echo $r_id; ?>" />
                        <input name="s_id" type="hidden" value="<?php echo $row_song['id']; ?>" />
                    </td>
                  </tr>
                </table>
            </form></td>
            <td><form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" name="s_form_new">
                <table class="updrel">
                  <tr>
                    <td><input name="title" type="text" value="<?php echo $row_song['title']; ?>" size="30" /></td>
                    <td width="10" align="center"><input name="hl" type="checkbox" value="<?php echo $row_song['hl']; ?>" 
		<?php if ($row_song['hl']) echo "checked=\"checked\""; ?> />
                    </td>
                    <td align="right"><?php if (($row_song['time'])&&($row_releases['showtime'])) { 
			$ms = explode(":",(timecalc($row_song['time']))); ?>
                        <input id="min" name="min" type="text" value="<?php echo intval($ms[0]); ?>" size="2" />
                      :
                      <input id="sek" name="sek" type="text" value="<?php echo $ms[1]; ?>" size="2" />
                        <?php } ?>
                    </td>
                    <td><input name="artist" type="text" value="<?php echo $row_song['artist']; ?>" size="30" /></td>
                    <td><input name="comment" type="text" value="<?php echo $row_song['comment']; ?>" size="30" /></td>
                    <td width="10" align="center"><input name="main" type="checkbox" value="
		<?php echo $row_song['main']; ?>" <?php if ($row_song['main']) echo "checked=\"checked\""; ?> />
                    </td>
                    <td class="xxsmall"><?php echo shortsdate($row_song['touch']); ?></td>
                    <td width="10" align="right"></td>
                  </tr>
                </table>
            </form></td>
          </tr>
          <!-- use new element -->
          <?php } else { 
	$useold = false; ?>
          <tr class="newrel">
            <td>
			  <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" name="srs_form_new">
                <table cellspacing="0">
                  <tr>
                    <td width="10" align="right"><acronym title="This is the default value - it can be altered after song submission"><input name="side" type="text" value="<?php if (isset($last_side)) echo $last_side; ?>" size="4" disabled="disabled" /></acronym></td>
                    <td width="10" align="right"><acronym title="This is the default value - it can be altered after song submission"><input name="track" type="text" value="<?php if (isset($next_track)) { echo $next_track; } else { echo "1"; } ;?>" size="4" disabled="disabled" /></acronym></td>
                    <td width="10" align="right"></td>
                  </tr>
                </table>
              </form>
			</td>
            <td>
			  <form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="get" name="s_form_new">
                <table>
                  <tr>
                    <td>
					<input name="side" type="hidden" value="<?php if (isset($last_side)) echo $last_side; ?>" />
					<input name="track" type="hidden" value="<?php if (isset($next_track)) { echo $next_track; } else { echo "1"; } ;?>" />
					<input name="title" type="text" size="30" /></td>
                    <td width="10" align="center"><input name="hl" type="checkbox" value="<?php if ($row_releases['reltype']!="compilation") { echo "1"; } else { echo "0"; } ; ?>" <?php if ($row_releases['reltype']!="compilation") echo "checked=\"checked\""; ?> /></td>
                    <td align="right"><input id="min" name="min" type="text" size="2" />
                      :
                        <input id="sek" name="sek" type="text" size="2" /></td>
                    <td><input name="artist" type="text" value="<?php if ($row_releases['reltype']!="compilation") echo $row_releases['artist']; ?>" size="30" /></td>
                    <td><input name="comment" type="text" size="30" /></td>
                    <td width="10" align="center"><input name="main" type="checkbox" value="0" /></td>
                    <td class="xxsmall">New song</td>
                    <td width="10" align="right"><input name="Submit_s" type="submit" value="0" />
                        <input name="rid" type="hidden" value="<?php echo $r_id; ?>" /></td>
                  </tr>
              </table>
			 </form> 
			</td>
          </tr>
          <?php } ?>
          <!-- End use new element -->
          <?php } ?>
          <!-- End new element -->
        </table>
      <!-- Songs end -->
        <!-- Pics start -->
		<?php 
		$image_page = "img_inc.php";
		if (substr_count(strtolower($_SERVER['PHP_SELF']),"test")) { $image_page = "img_inc_test.php"; }
        include($image_page); ?>
        <!-- Pics end -->
        <?php } ?>
        <!-- updrel end -->
        <?php } // end show releaseform ?>
        <!-- Release end -->
        <?php 
if (substr_count(strtolower($_SERVER['PHP_SELF']),"test")) {
	$link = "index.php";
} else {
	$link = "index_test.php";
}
if (isset($_SERVER['QUERY_STRING'])) {
  $link .= "?" . htmlentities($_SERVER['QUERY_STRING']);
}
?>
        <p><a href=<?php echo $link; ?>>G&aring; til <?php echo basename($link); ?></a></p>
      <?php if (intval(trim($r_id))) { ?>
      <p><a href="../disco.php?rid=<?php echo $r_id; ?>">Vis titel i frontend</a></p>
      <?php } ?>
        <p>Siden senest opdateret <?php echo date("j/n Y H:i", getlastmod()); ?></p></td>
  </tr>
</table>
</body>
</html>
<?php
mysql_free_result($songs);
if ($updrel) {	
	mysql_free_result($releases);
}
?>