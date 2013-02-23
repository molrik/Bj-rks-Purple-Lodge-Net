// JavaScript Document
function open_picwin_fe(pic,w,h) {
	doc_to_open = "picwin.php?pic="+pic;
	win_name =	"Picwin";
	win_set = "width="+w+",height="+h+",location=no,menubar=no,toolbar=no,status=no,resizable=no,top=250,left=350";
	winData = window.open(doc_to_open, win_name, win_set);	
}
