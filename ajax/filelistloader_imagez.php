<?php 
//Defaults
$konfiguration["upload_bibliotek"] = "../images";
$folder = basename($konfiguration["upload_bibliotek"]);

//Functions
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
			$liste .= "<tr class=\"".$rclass."\"><td class=\"pic_num\">".($key+1).": </td>".$value."</tr>"; //nummer p�
		}
		$liste .= "</table>";
		/* skal v�k:  <tr></tr> (tom tr) */
		$liste = str_replace("<tr></tr>", "", $liste);
    } else {
      	$liste = "Ingen filer i biblioteket";
    }
    return "" . $liste . "";
  } else {
    die("Kunne ikke &aring;bne biblioteket: " . $bibliotek);
  }

} //slut funktion

//Output
echo listFiler($konfiguration["upload_bibliotek"]);

?>