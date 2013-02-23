// JavaScript Document

// jQuery initiering
jQuery(document).ready(function() {
	jQuery('#filelist_container').append('<a class="xsmall" href="#" id="listloader"><p>Load filelist</p></a>');
	jQuery('#filelist_container').append('<div id="listholder"></div>');

	jQuery('#listloader').click( function(event) {
	  event.preventDefault();
  	  jQuery('#listloader').remove();
	  jQuery('#filelist_container').append('<div id="listloading" style="position:relative;"></div>');
	  jQuery('#listloading').append('<p class="xsmall" style="position:absolute;">Loading...</p>');
	  jQuery('#listloading').append('<div style="position:absolute;height:16px;width:16px;left:100px;top:2px;"><img src="../syspics/ajax-loader.gif" alt="activity indicator" width="16" height="16" align="middle"></div>');
	  jQuery('#listholder').load('../ajax/filelistloader.php', function() {
		jQuery('#filelist_container').animate({
			height: '290px'
		  }, 1500 );
	  jQuery('#listloading').remove();
	  });
	});

}); 