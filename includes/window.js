<!--
function openWin( windowURL, windowName, windowFeatures ) { 
	wopen = window.open( windowURL, windowName, windowFeatures ) ; 
}

function open_win(ret) {
	doc_to_open = "img_popup_upload_test.php?ret="+ret;
	win_name =	"Datasupply";
	win_set = "width=800,height=400,location=yes,menubar=yes,toolbar=yes,status=yes,resizable=yes,top=300,left=400";
	winData = window.open(doc_to_open, win_name, win_set);
	//winData.opener = self; 
}

function open_filelist(doc,ret,val) {
	doc_to_open = doc+"?ret="+ret+"&val="+val;
	win_name =	"Filelist";
	win_set = "width=800,height=450,location=no,menubar=no,toolbar=no,status=no,resizable=yes,top=30,left=30";
	winData = window.open(doc_to_open, win_name, win_set);
	//winData.opener = self; 
}

//denne bruges til image-popup i back-enden
function open_picwin(pic,w,h) {
	doc_to_open = "../picwin.php?pic="+pic;
	win_name =	"Picwin";
	win_set = "width="+w+",height="+h+",location=no,menubar=no,toolbar=no,status=no,resizable=no,top=250,left=350";
	winData = window.open(doc_to_open, win_name, win_set);	
}

function open_remote(url) {
	remote_doc_to_open = url;
	remote_win_name = "Select_from";
	remote_win_set = "width=800,height=450,location=yes,menubar=yes,toolbar=yes,status=yes,resizable=yes,scrollbars=yes,top=400,left=30";
	remoteWin = window.open(remote_doc_to_open,remote_win_name,remote_win_set);
}
// -->
