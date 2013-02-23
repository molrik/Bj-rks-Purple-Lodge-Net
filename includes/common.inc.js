// JavaScript Document
function showallpics(id) {
	document.getElementById('picrow'+id).style.display='block';
	document.getElementById('showlink'+id).style.display='none';	
	document.getElementById('mainpic'+id).style.display='none';	
}
function ofrs(x) {
	document.getElementById(x).className='focus';
	document.getElementById('Submit_rs'+(x.slice((x.indexOf("_"))+1))).style.display='block';
	document.getElementById('delpic_'+(x.slice((x.indexOf("_"))+1))).style.display='none';
}
function ofs(x) {
	document.getElementById(x).className='focus';
	document.getElementById('Submit_s'+(x.slice((x.indexOf("_"))+1))).style.display='block';
}
function ofi(x) {
	document.getElementById(x).className='focus';
	switch(x) {
		case "sort_input":
				document.getElementById(x).value='';	
			break;
		case "thumb_input":
			if (document.getElementById(x).value=='thumbnail') {
				document.getElementById(x).value='';
			}
		case "image_input":
			if (document.getElementById(x).value=='fullsize') {
				document.getElementById(x).value = document.getElementById("thumb_input").value.replace('th_','');
				document.getElementById('sendto_'+x).style.display='inline';
			}
		case "descr_input":
			if (document.getElementById(x).value=='description') {
				document.getElementById(x).value='';
			}
	}
	document.getElementById('Submit_uri').style.display='block';
}
function ofse(x) {
	document.getElementById(x).className='focus';
	document.getElementById('se_submit').style.display='block';
	document.getElementById('rel_links_txt').style.display='none';
	document.getElementById('rel_links').style.display='none';
}

function chcol(x,y) {
	document.getElementById(x).style.color=y;
}
