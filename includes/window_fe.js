// JavaScript Document

//denne bruges af front-end til fremvisning af lokale billeder
function open_picwin_fe(pic,w,h) {
	doc_to_open = "picwin.php?pic="+pic+"&w="+w+"&w="+h;
	win_name =	"Picwin";
	win_set = "width="+w+",height="+h+",location=no,menubar=no,toolbar=no,status=no,resizable=no";
	winData = window.open(doc_to_open, win_name, win_set);	
}

//denne bruges af front-end til fremvisning af eksterne billeder
function open_picwin_remote_fe(pic,w,h) {
	doc_to_open = "picwin_remote.php?pic="+pic+"&w="+w+"&w="+h;
	win_name =	"Picwin";
	win_set = "width="+w+",height="+h+",location=no,menubar=no,toolbar=no,status=no,resizable=no";
	winData = window.open(doc_to_open, win_name, win_set);	
}

