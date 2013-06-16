// JavaScript Document

// jQuery initiering
jQuery(document).ready(function() {
	
	function adjustStyle(width) { 
	    width = parseInt(width);
	    if (width < 480) {
	        jQuery("#size-stylesheet").attr("href", "mobile.css");
	    } else if ((width >= 480) && (width < 701)) {
	        jQuery("#size-stylesheet").attr("href", "narrow.css");
	        //jQuery('.leftcol').width(75);
	    } else if ((width >= 701) && (width < 900)) {
	        jQuery("#size-stylesheet").attr("href", "medium.css");
	        //jQuery('.leftcol').width(100);
	    } else {
	        jQuery("#size-stylesheet").attr("href", "wide.css"); 
	        //jQuery('.leftcol').width(150);
	    }
	}
	
	jQuery(function() {
	    adjustStyle(jQuery(this).width());
	    jQuery(window).resize(function() {
	        adjustStyle(jQuery(this).width());
	    });
	});

}); 