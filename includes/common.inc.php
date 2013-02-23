<?php 
/* A collection of functions for the Purple Lodge Database-site */

function timecalc($time) {	//gets time in seconds - returns a string of mm:ss
	$min = floor($time / 60);
	$sec = $time % 60;
	if ($sec < 10) { $sec = "0".$sec; };
	$res = $min.":".$sec;
	return $res;
}

function datefix($dato) {
	$months = array("","January","February","March","April","May","June","July","August","September","October","November","December");
	$year  = substr($dato, 0, 4);
	$day   = intval(substr($dato, 8, 2)); if ((!$day) || ($day > 31)) { $day = ""; } else { $day = $day.". "; } ;
	$month = intval(substr($dato, 5, 2)); if ((!$month) || ($month > 12)) { $month = ""; } else { $month = $months[intval($month)]." "; } ;
	$res = $month.$day.$year;
	return $res;
}

function shortsdate($stamp) {
	$sdate = substr($stamp,2,14);
	return $sdate;
}

function chkth($th_db) {
	$th_db = trim($th_db);	//initiel rensning
	if ($th_db!="") {		//hvis thumb angivet
		$th_path = "../thumbnails/".$th_db;
		if (file_exists($th_path)) {
			$th_mod = "<a class=\"thumbnail\" href=\"".$th_path."\">".$th_db."<span><img src=\"".$th_path."\" /></span></a>"; 
			$th_show = $th_mod;
		} else {
			$th_show = "<span class=\"alert_pic\"><acronym title=\"thumbfile ".$th_path." not present\">".$th_db."</acronym></span>";
		}
	} else {
		$th_show = "<span class=\"alert_pic\">no thumbnail in db</span>";
	}
	echo $th_show;
}

function chkpic($pic_db) {
	$pic_db = trim($pic_db);	//initiel rensning
	if ($pic_db!="") {		//hvis billede angivet
		if (substr(strtolower($pic_db),0,4)!="http") {
			$pic_path = "../images/".$pic_db;			//lokal fil
			if (file_exists($pic_path)) {
				$pic_inf = getimagesize($pic_path);
				$pic_mod = "<acronym title=\"Local: W".$pic_inf[0]." H".$pic_inf[1]." ".htmlentities($pic_inf['mime'])."\">
				<a href=\"".$pic_path."\">".$pic_db."</a></acronym>"; 
				$pic_show = $pic_mod;
			} else {
				$pic_show = "<span class=\"alert_pic\"><acronym title=\"Local file ".$pic_path." not present\">".$pic_db."</acronym></span>";
			}		
		} else {
			$pic_path = $pic_db;						//ekstern fil
			if ($_SESSION['check_remote_files']) {		//kun hvis flag sat
				@$pic_inf = getimagesize($pic_path);		//check om filen er der - @ undertrykker evt. fejl
				if ($pic_inf[0]!=null) {					//har billedet en bredde m� det jo ogs� v�re der!
					$pic_mod = "<span class=\"remote_pic\"><acronym title=\"Remote in ".dirname($pic_path).": W".$pic_inf[0]." H".$pic_inf[1]."\"><a href=\"".$pic_path."\">".basename($pic_db)."</a></acronym></span>";
				} else {									//ellers mangler det jo nok...
					$pic_mod = "<span class=\"alert_remote_pic\"><acronym title=\"Remote in ".dirname($pic_path).": not present\">".basename($pic_db)."</acronym></span>";
				}
			} else {	//ellers vis kun at den er remote
				$pic_mod = "<span class=\"remote_pic\"><acronym title=\"Remote in ".dirname($pic_path)."\"><a href=\"".$pic_path."\">".basename($pic_db)."</a></acronym></span>";
			}
			$pic_show = $pic_mod;
		}
	} else {
		$pic_show = "none";
	}
	echo $pic_show;
}

function chkthval($th_db) { //ikke spor f�rdigudviklet yet
	$th_db = trim($th_db);	//initiel rensning
	if ($th_db!="") {		//hvis thumb angivet
		$th_path = "../thumbnails/".$th_db;
		if (file_exists($th_path)) {
			$th_colortxt = " style=\"color:#009900\""; 
		} else {
			$th_colortxt = " style=\"color:#CC0000\""; 
		}
	} 
	return $th_colortxt;
}

function cleanmedialist($medeainch) {	//simpel rensning af output inch >> null
	$medea = strtolower(str_replace('inch','',$medeainch));
	return $medea;
}

function reltypeshort($rtl) {	//forkortning af udgivelsestype
	$rts = strtoupper(substr($rtl,0,1)).substr($rtl,1,2);
	return $rts;
}

function cutsekel($dl) {	//�rforkortelse
	$dy = substr($dl,2,2);
	$dm = substr($dl,5,2);
	$dd = substr($dl,8,2);
	if (!intval($dd)) { $dd = ""; } else { $dd = "-".intval($dd); }
	if (!intval($dm)) { $dm = ""; } else { $dm = "-".intval($dm); }
	$ds = $dy.$dm.$dd;
	return $ds;
}

function trans2pic($media) {
	switch(trim($media)) {
		case '12':
			$media = str_replace('12','<img src="syspics/vin12.gif" width="15" height="15" border="0" />',$media);
		break;
		case '2x12':
			$media = str_replace('2x12','<img src="syspics/vin12.gif" width="15" height="15" border="0" />',$media);
		break;
        case '3x12':
            $media = str_replace('3x12','<img src="syspics/vin12.gif" width="15" height="15" border="0" />',$media);
        break;
		case '10':
			$media = str_replace('10','<img src="syspics/vin10.gif" width="15" height="15" border="0" />',$media);
		break;
		case '7':
			$media = str_replace('7','<img src="syspics/vin7.gif" width="15" height="15" border="0" />',$media);
		break;
		case '7 flexi':
			$media = str_replace('7 flexi','<img src="syspics/vin7.gif" width="15" height="15" border="0" />',$media);
		break;
		case 'lp':
			$media = str_replace('lp','<img src="syspics/vin12.gif" width="15" height="15" border="0" />',$media);
		break;
		case 'dlp':
			$media = str_replace('dlp','<img src="syspics/vin12.gif" width="15" height="15" border="0" />',$media);
		break;
		case '2lp':
			$media = str_replace('2lp','<img src="syspics/vin12.gif" width="15" height="15" border="0" />',$media);
		break;
		case 'cd':
			$media = str_replace('cd','<img src="syspics/cd5.gif" width="15" height="15" border="0" />',$media);
		break;
		case 'dcd':
			$media = str_replace('dcd','<img src="syspics/cd5.gif" width="15" height="15" border="0" />',$media);
		break;
		case '2cd':
			$media = str_replace('2cd','<img src="syspics/cd5.gif" width="15" height="15" border="0" />',$media);
		break;
		case 'dvd':
			$media = str_replace('dvd','<img src="syspics/dvd5.gif" width="15" height="15" border="0" />',$media);
		break;
		case 'mc':
			$media = str_replace('mc','<img src="syspics/cas.gif" width="15" height="15" border="0" />',$media);
		break;
		case 'dat':
			$media = str_replace('dat','<img src="syspics/dat.gif" width="15" height="15" border="0" />',$media);
		break;
		case '3 cd':
			$media = str_replace('3 cd','<img src="syspics/cd3.gif" width="15" height="15" border="0" />',$media);
		break;
		case '12/cd/dvd':
			$media = str_replace('12/cd/dvd','<img src="syspics/mm12cddvd.gif" width="41" height="15" border="0" />',$media);
		break;
		case 'cd/dvd':
			$media = str_replace('cd/dvd','<img src="syspics/mmcddvd.gif" width="28" height="15" border="0" />',$media);
		break;		
		case 'm4a':
			$media = str_replace('m4a','<img src="syspics/dd.gif" width="15" height="15" border="0" />',$media);
		break;
		case 'mp3':
			$media = str_replace('mp3','<img src="syspics/dd.gif" width="15" height="15" border="0" />',$media);
		break;
		case 'md':
			$media = str_replace('md','<img src="syspics/md.gif" width="15" height="15" border="0" />',$media);
		break;
		
	}
	return $media;
}

function txt2upper($media) {
	switch(strtolower(trim($media))) {
		case 'xxx':
            $adjtxt = $media;   
		break;
		default:
            $adjtxt = strtoupper($media);
            if (strstr($adjtxt,'INCH')) {
                $adjtxt = str_replace('INCH', 'inch', $adjtxt);
            }  
		break;
	}
	return $adjtxt;
}

function showbullet($media) {
    switch(cleanmedialist(trim($media))) {
        case '12':
            $bulletclass = "b_12";  
        break;
        case '2x12':
            $bulletclass = "b_12";  
        break;
        case '3x12':
            $bulletclass = "b_12";  
        break;
                case '10':
            $bulletclass = "b_10";  
        break;
        case '7':
            $bulletclass = "b_7";   
        break;
        case '7 flexi':
            $bulletclass = "b_7";   
        break;
        case 'lp':
            $bulletclass = "b_12";  
        break;
        case 'dlp':
            $bulletclass = "b_12";  
        break;
        case '2lp':
            $bulletclass = "b_12";  
        break;
        case 'cd':
            $bulletclass = "b_cd";  
        break;
        case '2cd':
            $bulletclass = "b_cd";  
        break;
        case 'dcd':
            $bulletclass = "b_cd";  
        break;
        case 'dvd':
            $bulletclass = "b_dvd"; 
        break;
        case 'mc':
            $bulletclass = "b_mc";  
        break;
        case 'dat':
            $bulletclass = "b_dat"; 
        break;
        case '3 cd':
            $bulletclass = "b_3cd"; 
        break;
        case '12/cd/dvd':
            $bulletclass = "b_12cddvd"; 
        break;
        case 'cd/dvd':
            $bulletclass = "b_cd";  
        break;
        case 'm4a':
            $bulletclass = "b_dd";  
        break;
        case 'mp3':
            $bulletclass = "b_dd";  
        break;
        case 'md':
            $bulletclass = "b_md";  
        break;
        default:
            $bulletclass = "none";  
        break;
    }
    return $bulletclass;
}


function updateuserlog($beuser, $message) {
	mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
	$q_usermatch = "SELECT id,log FROM bpl_admins WHERE username = '".trim($beuser)."'";
	$q_usermatch_res = mysql_query($q_usermatch, $db_purplelodge_net) or die(mysql_error());
	$q_usermatch_row = mysql_fetch_assoc($q_usermatch_res);
	$q_usermatch_num = mysql_num_rows($q_usermatch_res);	//hvis den er nul findes brugeren ikke
	if ($q_usermatch_num) {
		$newlog = $q_usermatch_row['log']." ".$message; //Par gammel med ny
		$uid = $q_usermatch_row['id'];
  		$updateSQL = "UPDATE bpl_admins SET log=".$newlog." WHERE id=".$uid;
		mysql_select_db($database_db_purplelodge_net, $db_purplelodge_net);
  		$res = mysql_query($updateSQL, $db_purplelodge_net) or die(mysql_error());
	}
}

function holidays()
{
	$today = date(DATE_COOKIE);
    $now = strtotime($today);
    $easterbegin = strtotime('22-03-2013');
    $easterend = strtotime('02-04-2013');
    if (($now > $easterbegin) && ($now < $easterend)) {
        $holimessage = '<h2>Gle&eth;ilega p&aacute;ska!</h2>';
    } else {
        $holimessage = '...';
    }
    return $today.' '.$holimessage;
}
?>