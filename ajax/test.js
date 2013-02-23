// JavaScript Document
// jQuery initiering
jQuery(document).ready(function() {
	jQuery('#requestcontainer').append('<p><a href="#" id="simpleload">Load text</a></p>');
	jQuery('#requestcontainer').append('<p><a href="#" id="response1">Load no response</a></p>');
	jQuery('#requestcontainer').append('<p><a href="#" id="response2">Load response get (url)</a></p>');
	jQuery('#requestcontainer').append('<p><a href="#" id="response3">Load response post</a></p>');
	jQuery('#requestcontainer').append('<p><a href="#" id="response4">Load xml</a></p>');	
	
	jQuery('#simpleload').click( function(event) {
	  event.preventDefault();
	  jQuery('#responsecontainer').load('simpletext.php', function() {
		jQuery('#responsecontainer').animate({
			height: '100px'
		  }, 1000 );
	  });
	});
	
	jQuery('#response1').click( function(event) {
	  event.preventDefault();
	  jQuery('#responsecontainer').load('responsive.php', function() {
		jQuery('#responsecontainer').animate({
			height: '120px'
		  }, 1000 );
	  });
	});
	
	jQuery('#response2').click( function(event) {
	  event.preventDefault();
	  jQuery('#responsecontainer').load('responsive.php?req=test-text-url', function() {
		jQuery('#responsecontainer').animate({
			height: '130px'
		  }, 1000 );
	  });
	});
	
	jQuery('#response3').click( function(event) {
	  event.preventDefault();
	  jQuery('#responsecontainer').load('responsive.php', {req:'test-text-par'}, function() {
		jQuery('#responsecontainer').animate({
			height: '140px'
		  }, 1000 );
	  });
	});
	
  jQuery('#response4').click(function(event){
	  event.preventDefault();
      jQuery('#responsecontainer').load('demo_cd_catalog.xml', function(response,status,xhr) {
		  if (status=='success') {
			  jQuery('#responsecontainer').html('<ol></ol>');
			  jQuery(response).find('artist').each(function(){
				var item_text = jQuery(this).text();
				jQuery('<li></li>').html(item_text).appendTo('ol');
			  });
			  jQuery('#responsecontainer').append('<p>Contains <b>'+jQuery(response).find('cd').size()+'</b> items</p>');
		  } else {
			  jQuery('#responsecontainer').append("An error occured: <br/>" + xhr.status + " " + xhr.statusText)
		  }
		  jQuery('#responsecontainer').animate({
			height: '200px'
		  }, 1000 );
      });
  });
	
}); 