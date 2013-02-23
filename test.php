<?php 
session_start(); // sessioninit skal ske som noget af det første i dokumentet 
?>
<?php require_once('../Connections/db_purplelodge_net.php'); ?>
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

if (!isset($_GET['year']) && !isset($_GET['letter']) && !isset($_GET['title'])) { $selrel = "1=0"; } ; 	//just a precaution
if (isset($_GET['year'])) { $selrel = "year(reldate)=".$_GET['year']; }	;		//selecting years
if (isset($_GET['letter']))	{ $selrel = "title LIKE '".$_GET['letter']."%'"; } ; //selecting by letter
if (isset($_GET['title'])) { $selrel = "title LIKE '".$_GET['title']."'"; } ; //selecting title

mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
$query_releases = "SELECT * FROM bpl_rel WHERE ".$selrel." ORDER BY reldate ASC, title ASC, media ASC";
$releases = mysql_query($query_releases, $db_purplelodge_net) or die(mysql_error());
$row_releases = mysql_fetch_assoc($releases);
$totalRows_releases = mysql_num_rows($releases);

mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
$query_relyear = "SELECT DISTINCT year(reldate) AS reldate FROM bpl_rel ORDER BY year(reldate) ASC";
$relyear = mysql_query($query_relyear, $db_purplelodge_net) or die(mysql_error());
$row_relyear = mysql_fetch_assoc($relyear);
$totalRows_relyear = mysql_num_rows($relyear);

mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
$query_relletter = "SELECT DISTINCT UCASE(LEFT(title,1)) AS letter FROM bpl_rel ORDER BY bpl_rel.title";
$relletter = mysql_query($query_relletter, $db_purplelodge_net) or die(mysql_error());
$row_relletter = mysql_fetch_assoc($relletter);
$totalRows_relletter = mysql_num_rows($relletter);

if (isset($_GET['letter'])) {
	mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
	$query_reltitle = "SELECT DISTINCT title FROM bpl_rel WHERE title LIKE '".$_GET['letter']."%' ORDER BY title ASC";
	$reltitle = mysql_query($query_reltitle, $db_purplelodge_net) or die(mysql_error());
	$row_reltitle = mysql_fetch_assoc($reltitle);
	$totalRows_reltitle = mysql_num_rows($reltitle);
}

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
</head>

<body style="margin:0; background-image:url(syspics/left2.jpg); background-repeat:repeat-y" onload="MM_preloadImages('molrik.gif','taatoo.gif','umurna2.gif','cubes.gif','bjorkb.gif','eye8.gif','bear.gif')">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:100%">
  <tr>
    <td width="150" height="150" align="center" valign="middle" style="background-image:url(syspics/corner2.jpg)"><img src="syspics/eye8.gif" alt="Corner" name="corner" width="140" height="140" border="0" id="corner" /></td>
    <td height="150" align="center" valign="middle" style="background-image:url(syspics/top2.jpg)"><h3><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Discography</a> </h3>
	<table>
		<tr>
			<td>
				<?php do { ?>
				<a href="<?php echo $_SERVER['PHP_SELF']."?year=".$row_relyear['reldate']; ?>"><?php if (isset($_GET['year']) && ($_GET['year']==$row_relyear['reldate'])) { echo "<b>" ; $bend = "</b>"; } else { $bend = ""; } ?><?php echo substr($row_relyear['reldate'],2,2); ?><?php echo $bend; ?></a><?php echo " "; ?>
				<?php } while ($row_relyear = mysql_fetch_assoc($relyear)); ?>
			</td>
		</tr>
		<tr>
			<td>
				<?php do { ?>
				<a href="<?php echo $_SERVER['PHP_SELF']."?letter=".strtolower(trim($row_relletter['letter'])); ?>"><?php if (isset($_GET['letter']) && (strtolower($_GET['letter'])==strtolower($row_relletter['letter']))) { echo "<b>" ; $bend = "</b>"; } else { $bend = ""; } ?><?php echo $row_relletter['letter']; ?><?php echo $bend; ?></a><?php echo " "; ?>
				<?php } while ($row_relletter = mysql_fetch_assoc($relletter)); ?>
			</td>
		</tr>	
		<!-- this next row causes dreamweaver to act strange but the syntax is correct! -->
		<?php if (isset($_GET['letter'])) { ?>
		<tr>
			<td>
				<?php do { ?>
				<a href="<?php echo $_SERVER['PHP_SELF']."?title=".strtolower(trim($row_reltitle['title']))."&amp;letter=".$_GET['letter']; ?>"><?php if (isset($_GET['title']) && (strtolower($_GET['title'])==strtolower($row_reltitle['title']))) { echo "<b>" ; $bend = "</b>"; } else { $bend = ""; } ?><?php echo $row_reltitle['title']; ?><?php echo $bend; ?></a><?php echo " "; ?>
				<?php } while ($row_reltitle = mysql_fetch_assoc($reltitle)); ?>
			</td>
		</tr>
		<?php } ?>
		<!-- strange behaviour end -->
	</table>
	</td>
  </tr>
  <tr>
    <td width="150" align="center" valign="top" style="background-image:url(syspics/left2.jpg)"><?php include("menu.inc.php"); ?></td>
    <td align="left" valign="top">
	<?php if ($totalRows_releases) { //only if releases found ?>
      <?php do { ?>
	  <!-- fetch images -->
		<?php
		$r_id = $row_releases['id'];	//noter release-id
		$query_img = "SELECT * FROM bpl_img WHERE r_id = $r_id ORDER BY sort ASC";
		$img = mysql_query($query_img, $db_purplelodge_net) or die(mysql_error());
		$row_img = mysql_fetch_assoc($img);
		$totalRows_img = mysql_num_rows($img);
		if ($totalRows_img>1) { $morepics=true; } else { $morepics=false; } ;
		?>
	  <!-- end fetch images  -->
	  <table width="98%" border="0" cellpadding="2" cellspacing="2" class="sides_and_top">
		<tr> 
    <td width="75" height="75" rowspan="2" align="center" valign="top" id="mainpic<?php echo $row_releases['id']; ?>">
	<?php $thumb = "thumbnails/".$row_img['thumb'];	$imgdim = getimagesize($thumb); ?>  
	<?php if (trim($row_img['image'])!="") { $l = 1; } else { $l = 0; } ; ?>
	<?php if ($l) { ?><a href="<?php if (substr(strtolower(trim($row_img['image'])),0,4)!="http") echo "images/"; ?><?php echo $row_img['image']; ?>"><?php } ?>
	<img src="thumbnails/<?php echo $row_img['thumb']; ?>" alt="<?php echo $row_releases['title']; ?> - <?php echo $row_releases['artist']; ?> - <?php echo $row_releases['media']; ?> - <?php echo $row_releases['label']; ?> <?php echo $row_releases['serial']; ?> (<?php echo $row_releases['country']; ?>) - <?php echo $row_img['descr']; ?>" width="<?php echo $imgdim[0]; ?>" height="<?php echo $imgdim[1]; ?>" border="0" title="<?php echo $row_releases['title']; ?> - <?php echo $row_releases['artist']; ?> - <?php echo $row_releases['media']; ?> - <?php echo $row_releases['label']; ?> <?php echo $row_releases['serial']; ?> (<?php echo $row_releases['country']; ?>) - <?php echo $row_img['descr']; ?>" />
	<?php if ($l) { ?></a><?php } ?>
	</td>
    <td colspan="2"><strong><?php echo $row_releases['title']; ?></strong></td>
    <td colspan="2"> 
      <center>
        <?php echo $row_releases['artist']; ?>
      </center>    </td>
    <td colspan="2" align="right"> 
      <?php if ($row_releases['reltype'] == "promo") { echo $row_releases['reltype']; } //en switch er nok bedre her ?> 
      <?php echo datefix($row_releases['reldate']); ?></td>
  </tr>
  <tr> 
    <td colspan="6"> <ul>
        <li><?php echo $row_releases['media']; ?> - 
            <?php echo $row_releases['label']; ?> <?php echo $row_releases['serial']; ?> (<?php echo $row_releases['country']; ?>)</li>
      </ul>
	<?php if ($morepics) { ?>      
	<a href='javascript:showallpics(<?php echo $row_releases['id']; ?>);' class="xxsmall" id="showlink<?php echo $row_releases['id']; ?>">Show all images</a>
	<?php } ?>	</td>
  </tr>
</table>  
<table width="98%" border="0" cellpadding="2" cellspacing="2" class="sides">
  <?php if ($morepics) { // Show pictures if there are any more ?>
  <tr id="picrow<?php echo $row_releases['id']; ?>" style="display:none">
  <td>
  <?php do { ?>
  <?php $thumb = "thumbnails/".$row_img['thumb']; $imgdim = getimagesize($thumb); ?>  
  <?php if (trim($row_img['image'])!="") { $l = 1; } else { $l = 0; } ; ?>
  <?php if ($l) { ?><a href="<?php if (substr(strtolower(trim($row_img['image'])),0,4)!="http") echo "images/"; ?><?php echo $row_img['image']; ?>"><?php } ?>
  <img src="thumbnails/<?php echo $row_img['thumb']; ?>" alt="<?php echo $row_releases['title']; ?> - <?php echo $row_releases['artist']; ?> - <?php echo $row_releases['media']; ?> - <?php echo $row_releases['label']; ?> <?php echo $row_releases['serial']; ?> (<?php echo $row_releases['country']; ?>) - <?php echo $row_img['descr']; ?>" width="<?php echo $imgdim[0]; ?>" height="<?php echo $imgdim[1]; ?>" border="1" title="<?php echo $row_releases['title']; ?> - <?php echo $row_releases['artist']; ?> - <?php echo $row_releases['media']; ?> - <?php echo $row_releases['label']; ?> <?php echo $row_releases['serial']; ?> (<?php echo $row_releases['country']; ?>) - <?php echo $row_img['descr']; ?>" />
  <?php if ($l) { ?></a><?php } ?>
  <?php } while ($row_img = mysql_fetch_assoc($img)); ?>
  </td></tr>
  <?php } ?>
  
</table>
	<?php
	$r_id = $row_releases['id'];	//noter release-id
	$query_rel_songs = "SELECT bpl_rel_song.side, bpl_rel_song.track, bpl_song.title, bpl_song.time, bpl_song.comment, bpl_song.alt_title, bpl_song.artist, bpl_song.hl ";	//hvad
	$query_rel_songs .= "FROM bpl_rel_song LEFT JOIN bpl_song ON bpl_rel_song.s_id = bpl_song.id ";	//hvorfra
	$query_rel_songs .= "WHERE bpl_rel_song.r_id = $r_id ORDER BY track ASC";	//hvordan
	//$query_rel_songs = "SELECT * FROM bpl_rel_song WHERE r_id = $r_id ORDER BY track ASC";
	$rel_songs = mysql_query($query_rel_songs, $db_purplelodge_net) or die(mysql_error());
	$row_rel_songs = mysql_fetch_assoc($rel_songs);
	?>
	<table width="98%" border="0" cellpadding="2" cellspacing="2" class="all_sides">
	<?php $side = ""; //init af før-side ?>
    <?php do { ?>
		<tr>
		<td width="25" align="right"><?php if ($row_rel_songs['side'] != $side) { echo $row_rel_songs['side']; }; ?></td>
		<td width="50" align="right"><?php echo $row_rel_songs['track']; ?>.</td>
		<td <?php if ($row_rel_songs['hl']) echo "class=\"hl\""; ?>><?php echo $row_rel_songs['title']; ?></td>
		<td width="50" align="right"><?php if (($row_rel_songs['time'])&&($row_releases['showtime'])) { echo timecalc($row_rel_songs['time']); } ; ?></td>
		<td><?php if ($row_releases['reltype'] == "compilation") { echo $row_rel_songs['artist']; } ?> <?php echo $row_rel_songs['comment']; ?></td>
		</tr>
	<?php $side = $row_rel_songs['side']; //gem oplysninger om side til næste post ?>  
	<?php } while ($row_rel_songs = mysql_fetch_assoc($rel_songs)); ?>
	</table>
	<?php if ($row_releases['comment'] != "") { ?>
	<table width="98%" border="0" cellpadding="2" cellspacing="2" class="sides_and_bottom">
  		<tr>
    		<td align="left" valign="top"><span class="xxsmall"><?php echo "<b>Note:</b> ".$row_releases['comment']; ?></span></td>
  		</tr>
   </table> 
   <?php } ?>
   <br />       
   <?php } while ($row_releases = mysql_fetch_assoc($releases)); ?>
   <?php } else { ?> 
<table width="100%" border="0" class="xsmall" style="background:url(syspics/eye_big.gif)">
  <tr valign="top"> 
    <td><strong>Performer</strong></td>
    <td><strong>Albums</strong></td>
    <td><strong>Singles</strong></td>
  </tr>
  <tr valign="top"> 
    <td rowspan="26">Björk</td>
    <td rowspan="5">Debut (93)</td>
    <td>Human 
      behaviour (93) </td>
  </tr>
  <tr valign="top"> 
    <td>Venus 
      as a boy (93) </td>
  </tr>
  <tr valign="top"> 
    <td>Play 
      dead (93) </td>
  </tr>
  <tr valign="top"> 
    <td>Big 
      time sensuality (93) </td>
  </tr>
  <tr valign="top"> 
    <td>Violently 
      happy (94) </td>
  </tr>
  <tr valign="top"> 
    <td rowspan="5">Post (95)</td>
    <td>Army 
      of me (95) </td>
  </tr>
  <tr valign="top"> 
    <td>Isobel (95)</td>
  </tr>
  <tr valign="top"> 
    <td>It's 
      oh so quiet (95)</td>
  </tr>
  <tr valign="top"> 
    <td>Hyperballad (96) </td>
  </tr>
  <tr valign="top"> 
    <td>Possibly 
      maybe (96) </td>
  </tr>
  <tr valign="top"> 
    <td>Telegram (96)</td>
    <td>I 
      miss you (97) </td>
  </tr>
  <tr valign="top"> 
    <td rowspan="5">Homogenic (97)</td>
    <td>J&oacute;ga (97) </td>
  </tr>
  <tr valign="top"> 
    <td>Bachelorette (97) </td>
  </tr>
  <tr valign="top"> 
    <td>Hunter (98)</td>
  </tr>
  <tr valign="top"> 
    <td> Alarm 
      call (98) </td>
  </tr>
  <tr valign="top"> 
    <td>All 
      is full of love (99)</td>
  </tr>
  <tr valign="top"> 
    <td>Selmasongs (00) </td>
    <td>I've 
      seen it all (00)</td>
  </tr>
  <tr valign="top"> 
    <td rowspan="3">Vespertine (01) </td>
    <td>Hidden 
      place (01) </td>
  </tr>
  <tr valign="top"> 
    <td>Pagan 
      poetry (01) </td>
  </tr>
  <tr valign="top"> 
    <td>Cocoon (02)</td>
  </tr>
  <tr valign="top"> 
    <td>Greatest hits (02)</td>
    <td>It's in our hands (02)</td>
  </tr>
  <tr valign="top"> 
    <td>Family tree (02)</td>
    <td></td>
</tr>
<tr>	
    <td rowspan="2" valign="top">Medulla (04)</td>
    <td>Who is it? (04)</td>
  </tr>
  <tr valign="top">
    <td><a href="<?php echo $_SERVER['PHP_SELF'] ?>?title=triumph%20of%20a%20heart">Triumph of a heart</a> (05)</td>
  </tr>
<tr>	
    <td valign="top">Drawing restraint 9 (05)</td>
    <td valign="top"> </td>
  </tr>
<tr>	
    <td valign="top">Volta (07)</td>
    <td valign="top"><a href="<?php echo $_SERVER['PHP_SELF'] ?>?title=earth%20intruders">Earth intruders</a> (07)</td>
</tr>
  
  <tr valign="top"> 
    <td>Björk Guðmundsdóttir</td>
    <td>Björk (77)</td>
    <td></td>
  </tr>
  <tr valign="top"> 
    <td> <p>Björk 
        Guðmundsdóttir &amp; tríó <br />
        Guðmundar Ingólfssonar</p></td>
    <td>Gling-gl&oacute; 
      (90) </td>
    <td>&nbsp;</td>
  </tr>
  <tr valign="top"> 
    <td rowspan="2">Kukl</td>
    <td>The 
      eye (84)</td>
    <td>Söngull (83)</td>
  </tr>
  <tr> 
    <td>Holidays 
      in Europe (86)</td>
    <td>&nbsp;</td>
  </tr>
  <tr valign="top"> 
    <td>Sykurmolarnir</td>
    <td>Illur 
      arfur! (89) </td>
    <td><a href="<?php echo $_SERVER['PHP_SELF'] ?>?title=einn%20mol%20%E1%20mann">Einn mol' á mann</a> (86)</td>
  </tr>
  <tr valign="top"> 
    <td rowspan="11">Sugarcubes</td>
    <td rowspan="4" valign="top">Life's 
      too good (88)</td>
    <td><a href="<?php echo $_SERVER['PHP_SELF'] ?>?title=birthday">Birthday</a> (87)</td>
  </tr>
  <tr> 
    <td><a href="<?php echo $_SERVER['PHP_SELF'] ?>?title=coldsweat">Coldsweat</a> (88)</td>
  </tr>
  <tr> 
    <td>Deus (88)</td>
  </tr>
  <tr> 
    <td>Motorcrash (88)</td>
  </tr>
  <tr> 
    <td rowspan="2" valign="top">Here 
      today, tomorrow next week (89)</td>
    <td>Regina (89)</td>
  </tr>
  <tr> 
    <td>Planet (90)</td>
  </tr>
  <tr valign="top"> 
    <td rowspan="3" valign="top">Stick 
      around for joy (92)</td>
    <td>Hit (91)</td>
  </tr>
  <tr valign="top"> 
    <td>Walkabout (92) </td>
  </tr>
  <tr valign="top"> 
    <td>Vitamin (92)</td>
  </tr>
  <tr valign="top"> 
    <td valign="top">It's-it (92)</td>
    <td>Leash 
      called love (92)</td>
  </tr>
  <tr valign="top"> 
    <td valign="top">The 
      great crossover potential (98)</td>
    <td>&nbsp;</td>
  </tr>
  <tr valign="top"> 

    <td>Tappi Tikarass</td>
    <td>Miranda (83)</td>
    <td>Bitið 
      fast i vitið (82)</td>
  </tr>
</table>   
<?php } ?>
   </td>
  </tr>
</table>
</body>
</html>
