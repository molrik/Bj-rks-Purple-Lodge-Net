<?php 
function popupic($pic_db) {
	$pic_db = trim($pic_db);	//initiel rensning
	if ($pic_db!="") {		//hvis billede angivet
		if (substr(strtolower($pic_db),0,4)!="http") {
			$pic_path = "images/".$pic_db;			//lokal fil
			if (file_exists($pic_path)) {
				$pic_inf = getimagesize($pic_path);
				$pic_w = $pic_inf[0];
				$pic_h = $pic_inf[1];
			} 		
		} else {
			$pic_path = $pic_db;						//ekstern fil
			$pic_w = 600;
			$pic_h = 600;
		}
		$pic_popup_link = "<a href=\"".$pic_path."\" target=\"popupPic\" onclick=\"window.open(this.href,this.target,'width=".$pic_w.",height=".$pic_h."');return false;\">".$pic_db."</a>";
		$pic_popup_link .= "<br />Pic_db: ".$pic_db;
		$pic_popup_link .= "<br />Pic_path: ".$pic_path;
		$pic_popup_link .= "<br />Pic_w: ".$pic_w;
		$pic_popup_link .= "<br />Pic_h: ".$pic_h;
		
		return $pic_popup_link;
	} 
}

function popup_image_fe($pic_db) {
	$pic_db = trim($pic_db);	//initiel rensning
	if ($pic_db!="") {		//hvis billede angivet
		if (substr(strtolower($pic_db),0,4)!="http") {
			$pic_path = "images/".$pic_db;			//lokal fil
			if (file_exists($pic_path)) {
				$pic_inf = getimagesize($pic_path);
				$pic_w = $pic_inf[0];
				$pic_h = $pic_inf[1];
				$pic_popup_link = "<a href=\"javascript:open_picwin_fe('".$pic_path."',".$pic_w.",".$pic_h.");\">".$pic_db."</a>";
				//åbner billedet med picwin-vindue med close-click
			} 		
		} else {
			$pic_path = $pic_db;	//ekstern fil
			$pic_w = 502;			//default vindues-bredde			
			$pic_h = 525;			//default vindues-højde
			$pic_popup_link = "<a href=\"javascript:open_picwin_remote_fe('".$pic_path."',".$pic_w.",".$pic_h.");\">".$pic_db."</a>";
			//åbner billedet skaleret i et vindue (uden close-click)
		}
		
		return $pic_popup_link;
	} 

}
?>