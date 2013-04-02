// JavaScript Document

// jQuery initiering
jQuery(document).ready(function() {
	
	//the old version
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

	//the new design
	jQuery('.bpl_showmore').click(function (event) {
	  //do not actually link anywhere	
	  event.preventDefault();
	  //get current id
	  var aid = jQuery(this).attr('id');
	  //extract release-number
	  var aidnum = aid.replace(/showlink/g, '');
	  //hide the trigger gracefully
	  jQuery(this).fadeOut();
	  //increase headersize from 87 to 163px, images sticks to bottom
	  jQuery('#div_'+aidnum+' .relheader-outer').animate({height:'163px'}, 400, function(){
	  	//make the image-div deside table-height (important with too images to fit in one row
	  	jQuery('#div_'+aidnum+' .bpl_images').css('position', 'relative');	
	  	//show the hidden images
	  	jQuery('#div_'+aidnum+' .bpl_secondary_images').fadeIn(200);
	  });
	  //reduce left space from 120 to 0
	  jQuery('#div_'+aidnum+' .relheader-inner').animate({paddingLeft:'0px'}, 600);
	});


}); 