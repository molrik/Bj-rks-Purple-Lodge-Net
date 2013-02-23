<?php 
//Defaults
$konfiguration["upload_bibliotek"] = "../thumbnails";
$folder = basename($konfiguration["upload_bibliotek"]);

//Functions
function chk_th($th_db) {	//modificeret udgave af den i common.inc.php
	$th_db = trim($th_db);	//initiel rensning
	if ($th_db!="") {		//hvis thumb angivet
		$th_path = "../thumbnails/".$th_db;	
		//$th_path = $konfiguration["upload_bibliotek"].$th_db; //doesn't work - how come? virkefelter?
		if (file_exists($th_path)) {
			$pic_inf = @getimagesize($th_path);
			$th_mod = "<td class=\"pic_sel\"><a class=\"thumbnail\" href=\"#\" onClick=\"self.opener.document.upd_rel_img.thumb_input.value='".$th_db."';window.close();\" >".$th_db."<span><img src=\"".$th_path."\" /></span></a></td>"; 
			$th_mod .= "<td class=\"pic_wid\">".$pic_inf[0]."</td><td class=\"pic_hei\">".$pic_inf[1]."</td>
			<td class=\"pic_mim\">".htmlentities($pic_inf['mime'])."</td>";
			$th_mod .= "<td class=\"pic_siz\">".filesize($th_path)."</td>";
			$th_mod .= "<td class=\"pic_sta\">".date("d-m-Y H:i:s ", filemtime($th_path))."</td>";
			$th_show = $th_mod;
		} else {
			$th_show = "<td class=\"pic_sel\"><span class=\"alert_pic\"><acronym title=\"thumbfile ".$th_path." not present\">".$th_db."</acronym></span></td>"; //burde aldrig blive aktuelt her
		}
	} else {
		$th_show = "<td class=\"pic_sel\"><span class=\"alert_pic\">no thumbnail in db</span></td>";	//burde aldrig blive aktuelt her heller
	}
	return $th_show;
}

function listFiler($bibliotek) {

  if($bib = @opendir($bibliotek)) {
	$time_start = microtime(true);
    while (false !== ($fil = readdir($bib))) {
      if($fil != "." && $fil != ".." && !ereg("^\..+", $fil) && !ereg("^_", $fil) && ereg("\.+", $fil)) {
          $fil_liste[] = chk_th($fil);
		  //$succes = sort($fil_liste);
      }
    }
	$succes = sort($fil_liste);
	$time_end = microtime(true);
	$time = $time_end - $time_start;
    closedir($bib);

 	//each-version  
 	if(is_array($fil_liste)) {
		$liste = "<table class=\"xsmall\" cellpadding=\"2\" cellspacing=\"0\" width=\"100%\" id=\"thumb_table\">";
		foreach ($fil_liste as $key => $value) {
			if (intval($key)%2) { $rclass = "row_odd"; } else { $rclass = "row_even"; }
			$liste .= "<tr class=\"".$rclass."\"><td class=\"pic_num\">".($key+1).": </td>".$value."</tr>"; //nummer
		}
		$liste .= "</table>";
		/* skal v�k:  <tr></tr> (tom tr) */
		$liste = str_replace("<tr></tr>", "", $liste);
    } else {
      	$liste = "Ingen filer i biblioteket";
    }
    return $liste."<p class=\"xsmall\">Time: ".$time." seconds</p>";
  } else {
    die("Kunne ikke &aring;bne biblioteket: " . $bibliotek);
  }

} //slut funktion


//Output
echo listFiler($konfiguration["upload_bibliotek"]);

?>