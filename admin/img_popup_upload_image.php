<?php 
session_start(); // sessioninit skal ske som noget af det første i dokumentet 
if (!isset($_SESSION['login'])) header("Location: login.php");	//hvis en bruger ikke er logget ind gå til login-side

include("../includes/common.inc.php"); 

/* Hvor skal filerne uploades til? */
$konfiguration["upload_bibliotek"] = "../images";
$folder = basename($konfiguration["upload_bibliotek"]);
/* Hvor mange kilobytes maa filerne fylde per styk? */
$konfiguration["max_stoerrelse"] = 500;

function chk_pic($pic_db) {	//modificeret udgave af den i common.inc.php
	$pic_db = trim($pic_db);	//initiel rensning
	if ($pic_db!="") {		//hvis picumb angivet
		$pic_path = "../images/".$pic_db;
		$th_path = "../thumbnails/th_".$pic_db;
		if (file_exists($pic_path)) {
			$pic_inf = @getimagesize($pic_path);
			$pic_mod = "<td class=\"pic_sel\"><a class=\"thumbnail\" href=\"#\" onClick=\"self.opener.document.".$_GET['ret'].".value='".$pic_db."';window.close();\" >";
			$pic_mod .= $pic_db;
			if (file_exists($th_path)) $pic_mod .= "<span><img src=\"".$th_path."\" /></span>";
			$pic_mod .= "</a></td>";
			$pic_mod .= "<td class=\"pic_wid\">".$pic_inf[0]."</td><td class=\"pic_hei\">".$pic_inf[1]."</td><td class=\"pic_mim\">
			<a href=\"javascript:open_picwin('".$pic_path."',".$pic_inf[0].",".$pic_inf[1].");\">".htmlentities($pic_inf['mime'])."</a></td>";
			$pic_mod .= "<td class=\"pic_siz\">".filesize($pic_path)."</td>";
			$pic_mod .= "<td class=\"pic_sta\">".date("d-m-Y H:i:s ", filemtime($pic_path))."</td>";
			$pic_show = $pic_mod;
		} 
	} 
	return $pic_show;
}

function listFiler($bibliotek) {

  if($bib = @opendir($bibliotek)) {
    /** 
     * Denne syntaks er forklaret i PHP-manualen:
     * http://www.php.net/manual/en/function.readdir.php
     */
    while (false !== ($fil = readdir($bib))) {
      if($fil != "." && $fil != ".." && !ereg("^\..+", $fil) && !ereg("^_", $fil) && ereg("\.+", $fil)) {
          $fil_liste[] = chk_pic($fil);
		  $succes = sort($fil_liste);
      }
    }
    closedir($bib);

 	//each-version  
 	if(is_array($fil_liste)) {
		/* Antal kolonner i tabellen */
		$liste = "<table class=\"xsmall\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\" id=\"image_table\">";
		foreach ($fil_liste as $key => $value) {
			if (intval($key)%2) { $rclass = "row_odd"; } else { $rclass = "row_even"; }
			$liste .= "<tr class=\"".$rclass."\"><td class=\"pic_num\">".($key+1).": </td>".$value."</tr>"; //nummer på
		}
		$liste .= "</table>";
		/* skal væk:  <tr></tr> (tom tr) */
		$liste = str_replace("<tr></tr>", "", $liste);
    } else {
      	$liste = "Ingen filer i biblioteket";
    }
    return "" . $liste . "";
  } else {
    die("Kunne ikke &aring;bne biblioteket: " . $bibliotek);
  }

} //slut funktion

if (isset($_POST['upload'])) {
	//action!
	// ext-limitation
	  $tempfilearr = explode('.',$_FILES["upfil"]["name"]); //name and extension in array
	  $tempfile = strtolower(trim($tempfilearr[1]));	//get ext
		switch($tempfile) {
			case 'jpg':
				$allowedimagefile = true;
			break;
			case 'jpeg':
				$allowedimagefile = true;
			break;
			case 'gif':
				$allowedimagefile = true;
			break;
			default:
				$allowedimagefile = false;
			break;
		}
	// start-limitation
		if (substr($_FILES["upfil"]["name"],0,3) == 'th_') {
				$allowedimagefile = false;
		}	
			
	  if (($_FILES["upfil"]["size"] > 0) && ($allowedimagefile)) {
	
	  /**
	   * Hvis der er en fil, saa uploader vi den.
	   *
	   * Foerst slaar vi lige fast, hvor filen skal flyttes fra og til.
	   */
	
	  $fra = $_FILES["upfil"]["tmp_name"];
	  $til = $konfiguration["upload_bibliotek"] . "/" . $_FILES["upfil"]["name"];
	
	  /**
	   *  Checker lige om filen er for stor til at vi vil acceptere den.
	   *  Vi bruger ceil() i stedet for round(), saa vi ikke faar den skoere
	   *  situation, at fejlmeldingen siger, at filen er for stor, men angiver
	   *  samme stoerrelse for filen og den oevre graense.
	   */
 	  $fil_stoerrelse = filesize($fra)/1024;
	  if($fil_stoerrelse > $konfiguration["max_stoerrelse"]) {
		  die("Desv&aelig;rre - filen er for stor. Jeg accepterer kun " . 
			   $konfiguration["max_stoerrelse"] . "kb, og din fil fylder " . 
			   ceil($fil_stoerrelse) . "kb");
	  }
	  
	  if(function_exists("move_uploaded_file")) {
		move_uploaded_file($fra, $til);
	  } else {
		copy($fra, $til);
	  }
	  $message = "File uploaded: <b>".$_FILES["upfil"]["name"]."</b>";	
	} else {
      $message = "No files uploaded"."&alert=true";
	  if (!$allowedimagefile) {
      	$message = "No files uploaded: File type not allowed"."&alert=true"; //
	  }
	}
	header("Location: " . $_SERVER["PHP_SELF"] . "?ret=" . $_POST['ret'] . "&message=" . $message );
	exit;	
}

if (substr(strtolower($_GET['val']),0,4)=="http") { $remote_file = true; } else { $remote_file = false; }

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>
<head>
	<title>Indhold af biblioteket <?php echo $folder ?></title>
	<script language="JavaScript" type="text/javascript" src="../includes/window.js"></script>
    <link href="../bpl.css" rel="stylesheet" type="text/css">
    <link href="images.css" rel="stylesheet" type="text/css">
    <style type="text/css">
<!--
#filelist_container {
	height: 250px;
	overflow: auto;
	background-color: #EEEEFF;
	border: 1px solid #9999FF;
}
body {
	background-color: #FFFFCC;
	margin: 0px;
	padding: 0px;
}
.pic_num {
	width: 35px;
	text-align: right;
}
.pic_sel {
	width: 320px;
	text-align: left;
}
.pic_wid {
	width: 30px;
	text-align: right;
}
.pic_hei {
	width: 30px;
	text-align: right;
}
.pic_mim {
	width: 75px;
	text-align: left;
	padding-left: 10px;
}
.pic_siz {
	width: 50px;
	text-align: right;
}
.pic_sta {
	text-align: left;
	padding-left: 10px;
}

.row_odd {
	background-color: #DDDDFF;
}
.row_even {
	background-color: #EEEEFF;
}
-->
    </style>
</head>

<body>
<table width="100%" border="0" cellspacing="0" cellpadding="5">
  <tr>
    <td id="page_container">
	<?php if (isset($_GET['message'])) { 
		echo "<p class=\"xsmall";
		if (isset($_GET['alert'])) echo " alert";
		echo "\">".$_GET['message']."</p>"; 
	} else { ?>
	<p class="xsmall">Select or upload file to select:</p>
	<?php } ?>
	<table width="770" border="0" cellspacing="0" cellpadding="2" class="xxsmall">
	  <tr>
		<td class="pic_num">number</td>
		<td class="pic_sel">filename</td>
		<td class="pic_wid">width</td>
		<td class="pic_hei">height</td>
		<td class="pic_mim">mimetype</td>
		<td class="pic_siz">filesize</td>
		<td class="pic_sta">timestamp</td>
	  </tr>
	</table>
	<div id="filelist_container">
	  <?php echo listFiler($konfiguration["upload_bibliotek"]); ?>
	</div>
		<!--<p class="xsmall">Billeder sorteres og vises alfanumerisk p&aring; siden. </p> --><!-- det gør de ikke før arrayet sorteres inden tabellen genereres -->
		<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" enctype="multipart/form-data">
<table border="0" cellpadding="5" cellspacing="5">
			   <tr>
				 <td colspan="2" align="right"><input name="upfil" type="file" size="100" /></td>
			   </tr>
			   <tr>
			     <td align="right" class="xxsmall"><input name="ret" type="hidden" value="<?php echo $_GET['ret'] ?>">Gyldige filtyper er
	  *.jpg/jpeg, *.gif og *.png. Maksimal filst&oslash;rrelse: <?php echo $konfiguration["max_stoerrelse"]; ?> Kbyte</td>
			     <td align="right"><input type="reset" name="Reset" value="Reset">
	             <input name="upload" type="submit" id="upload" value="Upload" /></td>
			   </tr>
		  </table>
	</form>	
	<form action="" method="get" name="remote_path_form" id="remote_path_form">
	  <label class="xsmall"><a href="javascript:open_remote('<?php if ($remote_file) { echo $_GET['val']; } else { echo "http://unit.bjork.com/77island/"; } ?>');">Remote path:</a>
	  <input name="remote_path_input" type="text" id="remote_path_input" value="<?php if ($remote_file) echo $_GET['val']; ?>" size="100" onFocus="javascript:document.getElementById(this.id).className='focus';">
	  </label>
	<a href="#" onClick="self.opener.document.<?php echo $_GET['ret'] ?>.value=document.remote_path_form.remote_path_input.value;window.close();" class="xxsmall">Send</a>
	</form>
	<div align="center" class="xxsmall">
	<?php echo "Opdateret ".date("j/n Y", getlastmod()); ?> &copy; molrik @ purplelodge.net
	</div>
	</td>
  </tr>
</table>
</body>
</html>