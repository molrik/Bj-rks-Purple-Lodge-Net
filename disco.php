<?php 
session_start(); // sessioninit skal ske som noget af det f�rste i dokumentet 
if ($_COOKIE['bplac']) {    //hvis cookies appepteres
    if (isset($_COOKIE['bpl'])) {   //hvis cookiesettings
        $_SESSION['exclude_promos'] = $_COOKIE['bpl']['exclude_promos'];    //hent promostatus ind i sessionvar
    }
}
/* med eller uden promos */
if (isset($_GET['pro'])) {
    if (intval(trim($_GET['pro']))) {   //pro=1
        $_SESSION['exclude_promos'] = 0;
    } else {    //pro=0
        $_SESSION['exclude_promos'] = 1;
    }
}
if (($_COOKIE['bplac']) && (isset($_SESSION['exclude_promos']))) {  //hvis cookies accepteres og sessions er sat
    $duree = 2592000; //will expire in seconds 60*60*24*30 = 1 month
    $bplexpr = $_SESSION['exclude_promos'];
    setcookie('bpl[exclude_promos]', $bplexpr, time()+$duree);    //gem i cookie
}
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

/* til promos on/off */
$updateGoTo = $_SERVER['PHP_SELF'];
if (isset($_SERVER['QUERY_STRING'])) {
$updateGoTo .= (strpos($updateGoTo, '?')) ? "&" : "?";
$updateGoTo .= $_SERVER['QUERY_STRING'];
}

/* Valg af udv�lgelsesmetode og databaseconnections */
if (!isset($_GET['year']) && !isset($_GET['letter']) && !isset($_GET['rid']) && !isset($_GET['title']) && !isset($_GET['artist'])) {
	$selrel = "1=0"; 
	$footer_message = "";
	$seltyp = "none";
} ; 	//just a precaution

if (isset($_GET['year'])) { 
	$selrel = "year(reldate)=".$_GET['year']; //selecting years
	$seltyp = "year";
}
if (isset($_GET['letter']))	{ 
	$selrel = "title LIKE '".$_GET['letter']."%'";  //selecting by letter
	$seltyp = "letter";
}
if (isset($_GET['title'])) { 
	$selrel = "title LIKE '".addslashes($_GET['title'])."'";  //selecting title
	$seltyp = "title";
}
if (isset($_GET['rid'])) { 
	$selrel = "id=".$_GET['rid'];  //selecting by id
	$seltyp = "id";
}
if (isset($_GET['artist'])) { 
	$selrel = "artist LIKE '".addslashes($_GET['artist'])."'";  //selecting by artist
	$seltyp = "artist";
}
// vis kun test-udgivelser frem hvis admin er logget ind
if (!isset($_SESSION['user'])) {
	$relstat = " reltype<>'test'";	//kun aktive
	$selrel .= " AND".$relstat;	//kun aktive
} else {
	$relstat = "1";	//ellers alt
}

mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);	//hovedindsamleren
$query_releases = "SELECT * FROM bpl_rel WHERE ".$selrel." ORDER BY year(reldate) ASC, IF(month(reldate),month(reldate),13) ASC, IF(dayofmonth(reldate),dayofmonth(reldate),99) ASC, title ASC, main DESC, reltype ASC, media ASC, country ASC, label ASC, serial ASC";
$releases = mysql_query($query_releases, $db_purplelodge_net) or die(mysql_error());
$row_releases = mysql_fetch_assoc($releases);
$totalRows_releases = mysql_num_rows($releases);

mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net); //indsaml tilg�ngelige �rstal (til headermenuen)
$query_relyear = "SELECT DISTINCT year(reldate) AS reldate FROM bpl_rel WHERE ".$relstat." ORDER BY year(reldate) ASC";
$relyear = mysql_query($query_relyear, $db_purplelodge_net) or die(mysql_error());
$row_relyear = mysql_fetch_assoc($relyear);
$totalRows_relyear = mysql_num_rows($relyear);

mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);	//indsaml tilg�ngelige for-bogstaver (til headermenuen)
$query_relletter = "SELECT DISTINCT UCASE(LEFT(title,1)) AS letter FROM bpl_rel WHERE ".$relstat." ORDER BY bpl_rel.title";
$relletter = mysql_query($query_relletter, $db_purplelodge_net) or die(mysql_error());
$row_relletter = mysql_fetch_assoc($relletter);
$totalRows_relletter = mysql_num_rows($relletter);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Bj&ouml;rks Purple Lodge - Discography</title>
<!-- Dreamweaver-genererede javascripts - mine egne henvises der til i menu.inc.php -->
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

<body onload="MM_preloadImages('molrik.gif','taatoo.gif','umurna2.gif','cubes.gif','bjorkb.gif','eye8.gif','bear.gif','morepics.gif')">
<table width="100%" border="0" cellspacing="0" cellpadding="0" style="height:100%">
  <tr id="header_row">
    <td width="150" height="150" align="center" valign="middle" id="leftcorner"><img src="syspics/eye8.gif" alt="Corner" name="corner" width="140" height="140" border="0" id="corner" /></td>
	
    <td height="150" align="center" valign="middle" id="topmenu">
    <a name="sidetop"></a>
	<h3><a href="<?php echo $_SERVER['PHP_SELF']; ?>">Discography</a></h3>
	<table><tr><td id="year_row">
		<!-- Her fremvises alle forekommende �rstal med links -->
      	<?php do { ?>
			<a href="<?php echo $_SERVER['PHP_SELF']."?year=".$row_relyear['reldate']; ?>"><?php if (isset($_GET['year']) && ($_GET['year']==$row_relyear['reldate'])) { echo "<b>" ; $bend = "</b>"; } else { $bend = ""; } ?><?php echo substr($row_relyear['reldate'],2,2); ?><?php echo $bend; ?></a><?php echo " "; ?>
        <?php } while ($row_relyear = mysql_fetch_assoc($relyear)); ?>
		</td></tr>
		<tr><td id="letter_Row">
		<!-- Her fremvises alle forekommende forbogstaver med links -->
        <?php do { ?>
			<a href="<?php echo $_SERVER['PHP_SELF']."?letter=".strtolower(trim($row_relletter['letter'])); ?>"><?php if (isset($_GET['letter']) && (strtolower($_GET['letter'])==strtolower($row_relletter['letter']))) { echo "<b>" ; $bend = "</b>"; } else { $bend = ""; } ?><?php echo $row_relletter['letter']; ?><?php echo $bend; ?></a><?php echo " "; ?>
        <?php } while ($row_relletter = mysql_fetch_assoc($relletter)); ?>
	</td></tr></table>	</td>
  </tr>
  <tr>
    <td width="150" align="center" valign="top">
	<!-- Her kommer hovedmenuen - indeholder desuden faste php- og javascript-includes -->
	<?php include("menu.inc.php"); ?>	</td>
    <td align="left" valign="top">
	<!-- Her start hovedindholdet -->
    <?php 
	$count_rel = 0; //init antal fundne udgivelser 
	$count_pro_in = 0; //init antal fundne promos
	?>
	<?php if ($totalRows_releases) { //only if releases found ?>
      <?php do { //once pr found release ?>
      <!-- promos / no promos -->
      <?php if (!(($row_releases['reltype'] != "rel") && ($_SESSION['exclude_promos']))) { ?>
      <?php 
	  $count_rel++; //inkrementer antallet af udgivelser 
	  if ($row_releases['reltype'] != "rel") { $count_pro_in++; }
	  ?>
	  <!-- fetch images -->
		<?php
		$r_id = $row_releases['id'];	//noter release-id
		$query_img = "SELECT * FROM bpl_img WHERE r_id = $r_id ORDER BY sort ASC";
		$img = mysql_query($query_img, $db_purplelodge_net) or die(mysql_error());
		$row_img = mysql_fetch_assoc($img);
		$totalRows_img = mysql_num_rows($img);
		if ($totalRows_img>1) { $morepics=true; } else { $morepics=false; } ;
		if ($totalRows_img==0) { $row_img['thumb'] = "thx_def.gif"; } ;
		?>
	  <!-- end fetch images  -->
      <div id="div_<?php echo $r_id ?>" class="rel_div">
	  <table width="98%" border="0" cellpadding="2" cellspacing="2" class="sides_and_top">
		<tr> 
    <td width="75" height="75" rowspan="2" align="left" valign="top" id="mainpic<?php echo $row_releases['id']; ?>">
	<table border="0" cellspacing="0" cellpadding="0">
	  <tr>
		<td valign="bottom" class="th_holder">
			<!-- Billedbehandling starter her (enkeltvisning) -->
			<?php include("includes/images.php"); ?>
			<!-- Billedbehandling er slut her (enkeltvisning) -->		</td>
		<?php if ($morepics) { ?>
		<td valign="bottom" align="left">
			<a href='javascript:showallpicsjq(<?php echo $row_releases['id']; ?>);' id="showlink<?php echo $row_releases['id']; ?>" class="showlink"><img src="syspics/morepics_init.gif" alt="morepics.gif" name="morepics<?php echo $row_releases['id']; ?>" width="9" height="10" border="0" id="morepics<?php echo $row_releases['id']; ?>" title="Click here to show additional images" onmouseover="MM_swapImage('morepics<?php echo $row_releases['id']; ?>','','syspics/morepics.gif',1)" onmouseout="MM_swapImgRestore()" /></a>		
			</td>
		<?php } ?>
	  </tr>
	</table>	</td>
    <td colspan="2"><strong><?php echo $row_releases['title']; ?></strong></td>
    <td colspan="2"> 
      <center>
        <?php echo $row_releases['artist']; ?>
      </center>    </td>
    <td colspan="2" align="right"> 
      <?php if ($row_releases['reltype'] != "rel") {
	  	switch($row_releases['reltype']) {
			case "advance":
				echo $row_releases['reltype']." copy";
				break;
			default:
				echo $row_releases['reltype'];
				break;
		}
	  } //en switch er nok bedre her ?> 
      <?php echo datefix($row_releases['reldate']); ?></td>
  </tr>
  <tr> 
    <td colspan="5"> <ul class="mediainfo">
        <li class="<?php echo showbullet($row_releases['media']); ?>"><?php echo txt2upper($row_releases['media']); ?> - 
            <?php echo $row_releases['label']; ?> <?php echo $row_releases['serial']; ?> (<?php echo $row_releases['country']; ?>) <?php if (trim($row_releases['case'])!="") echo "<span class=\"xxsmall\">".$row_releases['case']."</span>"; ?> <?php if ($row_releases['mo']) { ?><img src="syspics/th_molrik.gif" alt="This item is in my collection" width="22" height="15" title="This item is in my collection" /><?php } ?></li>
      </ul>	</td>
<td align="right" valign="bottom">
<?php if (isset($_SESSION['user'])) { ?>
<a href="admin/index_test.php?act=updrel&amp;id=<?php echo $row_releases['id'] ?>"><img src="syspics/pencil.gif" alt="Edit this release" width="16" height="16" border="0" title="Edit this release" /></a>
<?php } ?></td>  
</tr>
</table>  
<table width="98%" border="0" cellpadding="2" cellspacing="2" class="sides">
  <?php if ($morepics) { // Show pictures if there are any more ?>
  <tr id="picrow<?php echo $row_releases['id']; ?>" style="display:none">
  <td>
  <?php do { ?>
  <!-- Billedbehandling starter her (batchvisning) -->
  <?php include("includes/images.php"); ?>
  <!-- Billedbehandling er slut her (batchvisning) -->
  <?php } while ($row_img = mysql_fetch_assoc($img)); ?>  </td></tr>
  <?php } ?>
</table>
	<?php
	//collect the relevant tracks
	$r_id = $row_releases['id'];	//noter release-id
	$query_rel_songs = "SELECT bpl_rel_song.side, bpl_rel_song.track, bpl_song.title, bpl_song.time, bpl_song.comment, bpl_song.alt_title, bpl_song.mix_info, bpl_song.artist, bpl_song.hl ";	//hvad
	$query_rel_songs .= "FROM bpl_rel_song LEFT JOIN bpl_song ON bpl_rel_song.s_id = bpl_song.id ";	//hvorfra
	$query_rel_songs .= "WHERE bpl_rel_song.r_id = $r_id ORDER BY track ASC";	//hvordan
	$rel_songs = mysql_query($query_rel_songs, $db_purplelodge_net) or die(mysql_error());
	$row_rel_songs = mysql_fetch_assoc($rel_songs);
	$totalRows_rel_songs = mysql_num_rows($rel_songs);
	if ($totalRows_rel_songs) {
	?>
	<table width="98%" border="0" cellpadding="2" cellspacing="2" class="all_sides">
	<?php $side = ""; //init af f�r-side ?>
    <?php do { ?>
		<tr class="track_row">
		<td class="track_side"><?php if ($row_rel_songs['side'] != $side) { echo $row_rel_songs['side']; }; ?></td>
		<td class="track_number"><?php echo $row_rel_songs['track']; ?>.</td>
		<td class="track_title<?php if ($row_rel_songs['hl']) echo " hl"; ?>" id="track_title_<?php echo $r_id ?>_<?php echo $row_rel_songs['track']; ?>"><?php 
        if ((trim($row_rel_songs['alt_title'])) || (trim($row_rel_songs['mix_info']))) {
            $addinfo = true;
        } else {
            $addinfo = false;
        }	
        if ($addinfo && !(trim($row_rel_songs['comment']))) {
            echo "<acronym title=\"";   //begin acro tag
            if (trim($row_rel_songs['alt_title'])) { echo "aka ".$row_rel_songs['alt_title']; } //alt title
            if ((trim($row_rel_songs['alt_title'])) && (trim($row_rel_songs['mix_info']))) { echo " - "; }
            if (trim($row_rel_songs['mix_info'])) { echo "remix by ".$row_rel_songs['mix_info']; } //remixer info
            echo "\">"; //end ecro tag          
        } 
	    echo $row_rel_songs['title']; 
        if ($addinfo && !(trim($row_rel_songs['comment']))) {
             echo "</acronym>"; 
        }
		?></td>
		<td class="track_time"><?php if (($row_rel_songs['time'])&&($row_releases['showtime'])) { echo timecalc($row_rel_songs['time']); } ; ?></td>
		<td class="track_comment"><?php if ($row_releases['reltype'] == "compilation") {
		    echo $row_rel_songs['artist'];
            if (trim($row_rel_songs['comment'])) {
                echo " - ";
            } 
		} 
        if ($addinfo && trim($row_rel_songs['comment'])) {
            echo "<acronym title=\"";   //begin acro tag
            if (trim($row_rel_songs['alt_title'])) { echo "aka ".$row_rel_songs['alt_title']; } //alt title
            if ((trim($row_rel_songs['alt_title'])) && (trim($row_rel_songs['mix_info']))) { echo " - "; }
            if (trim($row_rel_songs['mix_info'])) { echo "remix by ".$row_rel_songs['mix_info']; } //remixer info
            echo "\">"; //end ecro tag          
        } 
        echo $row_rel_songs['comment']; 
        if ($addinfo && trim($row_rel_songs['comment'])) {
             echo "</acronym>"; 
        }
        ?></td>
		</tr>
	<?php $side = $row_rel_songs['side']; //gem oplysninger om side til n�ste post ?>  
	<?php } while ($row_rel_songs = mysql_fetch_assoc($rel_songs)); ?>
	</table>
	<?php 
	} //end if table
	if ($row_releases['comment'] != "") { ?>
	<table width="98%" border="0" cellpadding="2" cellspacing="2" class="sides_and_bottom" id="note_<?php echo $r_id ?>">
  		<tr>
    		<td align="left" valign="top">
				<span class="xxsmall"><?php echo "<b>Note:</b> ".strip_tags($row_releases['comment'],'<a>'); ?></span>
			</td>
  		</tr>
   </table> 
   <?php } ?>
   </div>       
   <!-- Slut p� een release -->
   
   <?php
    $count_pro_out = intval($totalRows_releases) - intval($count_rel);	//t�l antallet af promos
	if ($count_pro_in || $count_pro_out) {
		if (isset($_SESSION['exclude_promos']) && ($_SESSION['exclude_promos'])) {
			$footer_message_switch = " - <a href=\"".$updateGoTo."&amp;pro=1"."\" title=\"Click here to include promos\">excluding ".$count_pro_out." promos</a>";
		} else {
			$footer_message_switch = " - <a href=\"".$updateGoTo."&amp;pro=0"."\" title=\"Click here to exclude promos\">including ".$count_pro_in." promos</a>";
		}
	} else {
		$footer_message_switch = "";
	}
	//testing
	//$footer_message_switch .= " ... <b>Debug:</b> All: ".intval($totalRows_releases)." - Count: ".intval($count_rel)." - Proint: ".$count_pro_in." - Proout: ".$count_pro_out;

   switch($seltyp) {
   		case "year":
			$footer_message = "Showing <acronym title=\"".$count_rel."\">all</acronym> releases from <b>".trim($_GET['year'])."</b>".$footer_message_switch;
			break;
		case "letter";
			$footer_message = "Showing <acronym title=\"".$count_rel."\">all</acronym> releases beginning with <b>".strtoupper(trim($_GET['letter']))."</b>".$footer_message_switch;
			break;
		case "title":
			$footer_message = "Showing <acronym title=\"".$count_rel."\">all</acronym> releases named <b>".stripslashes(trim($_GET['title']))."</b>".$footer_message_switch;
			break;
		case "artist";
			$footer_message = "Showing <acronym title=\"".$count_rel."\">all</acronym> releases by <b>".stripslashes(trim($_GET['artist']))."</b>".$footer_message_switch;	
			break;
   		case "id":
			$footer_message = "Showing the release with the unique id <b>".trim($_GET['rid'])."</b>";
   			$footer_message .= "<br />All releases named <a href=\"".$_SERVER['PHP_SELF']."?title=".($row_releases['title'])."\">".$row_releases['title']."</a>";
   			$footer_message .= " - all releases by the artist <a href=\"".$_SERVER['PHP_SELF']."?artist=".($row_releases['artist'])."\">".$row_releases['artist']."</a>";
			break;
		case "none":
			$footer_message = "";	//nothing really
		default:
			$footer_message = "";	//nothing either
			break;
   } 
   if (intval($count_rel)>3) {
   	$footer_message .= " <a href=\"#sidetop\"><img src=\"syspics/op.gif\" alt=\"op\" title=\"go to top\" border=\"0\"  width=\"11\" height=\"9\"  /></a>";
   }
   ?>
   <?php } // end if - show releases ?>
   <?php } while ($row_releases = mysql_fetch_assoc($releases)); ?>
   <?php if (!$count_rel) {
   		$footer_message = "<a href=\"".$updateGoTo."&amp;pro=1"."\" title=\"Click here to include promos\">No official releases</a>";
   } ?>
   <div id="footermessage" class="xxsmall" align="center"><?php echo $footer_message; ?></div>
   <?php } else { ?> 
<!-- Her starter den statiske default oversigt - skal redigeres med varsomhed -->
<?php include('staticmap.php'); ?>
<?php } ?>   
</td>
  </tr>
</table>
</body>
</html>
