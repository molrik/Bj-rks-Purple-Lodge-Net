<?php session_start(); // sessioninit skal ske som noget af det f�rste i dokumentet 
?>
<?php
    $duree = 2592000; //will expire in seconds 60*60*24*30 month
if ($_POST['cookie_accept']) {
    setcookie('bplac', 1, time()+intval($duree));
    header("Location: ".$_SERVER['PHP_SELF']); //redirect to self
}
if ($_POST['cookie_deny']) {
    setcookie('bplac', 0, time()-3600); //expired one hour ago
    setcookie('bpl[exclude_promos]', 0, time()-3600); //ditto
    setcookie('bpl[popup_lightbox]', 0, time()-3600); //ditto  
    header("Location: ".$_SERVER['PHP_SELF']); //redirect to self    
}
if ($_POST['status_submit']) { //cookie change
    if (intval($_POST['promostatus'])) { //promos
        setcookie('bpl[exclude_promos]', 1, time()+$duree);    //gem i cookie
    } else {
        setcookie('bpl[exclude_promos]', 0, time()+$duree);    //gem i cookie        
    }
    //lightbox?
    setcookie('bpl[popup_lightbox]', intval($_POST['popupstatus']), time()+$duree);    //gem i cookie
    
    header("Location: ".$_SERVER['PHP_SELF']); //redirect to self    
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
    <td align="center" valign="middle">
    <?php echo holidays() ?>
    <h3>Cookie disclaimer!</h3>
    <p>If you accept cookies, your session settings will be saved in your present browser.</p>
    <p>Only these settings will be stored, no tracking or spying will take place, and no personal data will be stored.</p>
    <p>You can at any time retrieve your cookie acceptance and thereby delete all stored information.</p>
    <?php 
    if ($_COOKIE['bplac']) {
        ?>
        <p>You seem to accept cookies on this site at this point!</p>
        <div id="mo_cookies_deny">
          <form id="cookie_deny_form" name="cookie_deny_form" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
            <input name="cookie_deny" type="hidden" value="1" />
            <input name="cookie_deny_submit" type="submit" value="I have changed my mind, please revoke my acceptance!" />
          </form>
        </div>
        <?php
    } else {
        ?>
        <div id="mo_cookies_accept">
          <form id="cookie_accept_form" name="cookie_accept_form" method="post" action="<?php $_SERVER['PHP_SELF']; ?>">
            <input name="cookie_accept" type="checkbox" value="1" />
            <input name="cookie_accept_submit" type="submit" value="Yes, I accept!" />
          </form>
        </div>
        <?php        
    }
    if ($_COOKIE['bplac']) {    //hvis cookies appepteres
        //if (isset($_COOKIE['bpl'])) {   //hvis cookiesettings
        if (1) {
            echo "<br /><div id=\"current_cookie_settings\">";
            echo "<h3>Current cookie settings:</h3>";
            echo "<form id=\"cookie_settings_form\" name=\"cookie_settings_form\" method=\"post\" action=\"".$_SERVER['PHP_SELF']."\">";
            echo "<label for=\"promostatus\">Exclude promos and testpressings</label>";
            echo "<input id=\"promostatus\" name=\"promostatus\" type=\"checkbox\" value=\"1\" ";
            if ($_COOKIE['bpl']['exclude_promos']) {
                echo "checked=\"checked\" ";
            };
            echo "/>";
            echo "<table><tr><td rowspan=\"2\"><span>Popup images in: </span></td>";
            echo "<td><label><input id=\"popupstatus_0\" name=\"popupstatus\" type=\"radio\" value=\"1\" ";
            if ($_COOKIE['bpl']['popup_lightbox']) {
                echo "checked=\"checked\" ";
            };
            echo "/>Lightbox</label></td></tr>";
            echo "<tr><td><label><input id=\"popupstatus_1\" name=\"popupstatus\" type=\"radio\" value=\"0\" ";
            if (!$_COOKIE['bpl']['popup_lightbox']) {
                echo "checked=\"checked\" ";
            };
            echo "/>New popup window</label></tr></td></table>";
            echo "<br /><br /><input id=\"status_submit\" name=\"status_submit\" type=\"submit\" value=\"Set\" />";
            
            echo "</form>";
            echo "</div>";
        }
    }
    ?>
    <?php if (isset($_SESSION['user'])) { ?>
    <p>Cookies: <span style="font-family: monospace;color:#F00;"><?php print_r($_COOKIE); ?></span></p>
    <p>Session: <span style="font-family: monospace;color:#00F;"><?php print_r($_SESSION); ?></span></p>
    <?php } ?>
  </tr>
</table>

</body>
</html>
