<?php 
$tr_thumb = trim($row_img['thumb']);			//initiel rensning
if ($tr_thumb!="") {							//hvis thumb i db

	/* Thumb */
	$thumb = "thumbnails/".$tr_thumb;			//thumbnailsti
	if (file_exists($thumb)) { 					//hvis thumb eksisterer som fil
		$thumb_present = 1;						
	} else {
		$thumb_present = 0;							
		$thumb = "thumbnails/thx_def.gif";		//ellers brug default-billedet
	}
	$imgdim = getimagesize($thumb); 			//billed-parametre
	
	/* Image */
	$tr_image = trim($row_img['image']);		//initiel rensning
	if ($tr_image!="") {						//hvis billede i db
		if (substr(strtolower(trim($tr_image)),0,4)!="http") {	//hvis lokal fil
			$image = "images/".$tr_image;		//billedsti
			if (file_exists($image)) {			//hvis billede eksisterer som fil
				$linktype = 1;					//lokalt link
			} else {
				$linktype = 0;					//intet link
			}
		} else {								//ellers betragtes den som værende remote
			$image = $tr_image;					//hele stien rummes i db
			$linktype = 2;						//remote link
		}
	} else {									//hvis billede ej i db
		$linktype = 0;							//intet link
	}
	
	/* Rendering af output */
	if ($linktype) {							//hvis link - anchor begin
		switch($linktype) {
			case 1:								//lokal link
				$pic_inf = getimagesize($image);
				$pic_w = $pic_inf[0];
				$pic_h = $pic_inf[1];
				echo "<a href=\"javascript:open_picwin_fe('".$image."',".$pic_w.",".$pic_h.");\">";				
				break;
			case 2:								//remote link
				$pic_w = 502;					//default vindues-bredde	
				$pic_h = 525;					//default vindues-højde
				echo "<a href=\"javascript:open_picwin_remote_fe('".$image."',".$pic_w.",".$pic_h.");\">";
				break;
			default:
				echo "<a href=\"#\">";			//bare i tilfælde af fejl
				break;
		}
	}
	
	/* Thumbnail */
	if ($thumb_present) {								//sammensæt acronym/alttext/title
		$alt_title = 	$row_img['descr']." - ";		//beskrivelsen på image-db
		$alt_title .= 	$row_releases['title']." - ";	//Udgivelsestitel
		$alt_title .= 	$row_releases['artist']." - ";	//Kunstner
		$alt_title .= 	$row_releases['media']." - ";	//Medie
		$alt_title .=	$row_releases['label']." - ";	//Pladeselskab
		$alt_title .=	$row_releases['serial']." (";	//Udgivelsesnummer
		$alt_title .=	$row_releases['country'].")";	//Først udgivet i
	} else {
		$alt_title = 	"Thumbnail missing";			//I tilfælde af manglende thumbnail
	}
	
	$image_code = 		"<img "; 						//Billedtag
	$image_code .= 		"src=\"".$thumb."\" "; 			//Kilde
	$image_code .= 		"width=\"".$imgdim[0]."\" "; 	//Bredde
	$image_code .= 		"height=\"".$imgdim[1]."\" "; 	//Højde
	$image_code .= 		"border=\"1\" "; 				//Kant
	$image_code .= 		"alt=\"".$alt_title."\" "; 		//Tekst hvis kilde mangler
	$image_code .= 		"title=\"".$alt_title."\" />"; 	//Titel-tekst
	
	echo $image_code;							//show thumbnail
	
	if ($linktype) {							//hvis link - anchor end
		echo "</a>";
	}
}
?>