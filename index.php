<?php session_start(); // sessioninit skal ske som noget af det første i dokumentet 
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

mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
$query_latest_update = "SELECT * FROM bpl_rel WHERE bpl_rel.reltype <> 'test' ORDER BY touch DESC LIMIT 1";
$latest_update = mysql_query($query_latest_update, $db_purplelodge_net) or die(mysql_error());
$row_latest_update = mysql_fetch_assoc($latest_update);
$totalRows_latest_update = mysql_num_rows($latest_update);
 
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
    <td width="150" height="150" align="center" valign="middle" id="leftcorner"><img src="syspics/rose.gif" alt="Corner" name="corner" width="140" height="140" border="0" id="corner" /></td>
    <td height="150" align="center" valign="middle" id="topmenu"><h1>Bj&ouml;rks Purple Lodge</h1></td>
  </tr>
  <tr>
    <td width="150" align="center" valign="top" id="leftmenu"><?php include("menu.inc.php"); ?></td>
    <td align="center" valign="middle"><!-- <h2>Gle&eth;ilega  p&aacute;ska!</h2> -->
    <p>BEWARE - This site is under construction! </p>
    <p><img src="pics/bjork_looking_down_purple.jpg" alt="Bj&ouml;rk looking down" width="300" height="400" /></p>
    <p>Feel free to explore it - but much content is missing... Here is <a href="http://purplelodge.net/bpl/" target="_blank">the old version</a>.</p>
    <p>Latest release update: <a href="disco.php?rid=<?php echo $row_latest_update['id']; ?>" title="<?php echo $row_latest_update['label']." ".$row_latest_update['serial']; ?>"><?php echo $row_latest_update['title']; ?> (<em><?php echo $row_latest_update['artist']; ?></em>)</a></p></td>
  </tr>
</table>
<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
var pageTracker = _gat._getTracker("UA-3450425-1");
pageTracker._initData();
pageTracker._trackPageview();
</script>
</body>
</html>
<?php
mysql_free_result($latest_update);
?>