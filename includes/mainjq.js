// JavaScript Document

// jQuery initiering
jQuery(document).ready(function() {

	jQuery('.showlink').click(function (event) {
	  event.preventDefault();
	  //jQuery(this).fadeOut();
	  //jQuery(this).parent().parent().parent().parent().parent().fadeOut();
	  var aid = jQuery(this).attr('id');
	  var aidnum = aid.replace(/showlink/g, '');
	  var tid = 'mainpic'+aidnum;
	  var prid = 'picrow'+aidnum;
	  //jQuery(this).append('<p>'+tid+'</p>');
	  jQuery('#'+tid).fadeOut();
	  jQuery('#'+prid).fadeIn();
	});

}); 