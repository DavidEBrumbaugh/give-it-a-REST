/**
 * jQuery Ajax test calls to demonstrate cookie Authentication
 * See: http://v2.wp-api.org/guide/authentication/
 *
 *  You have to enque wpi for wpApiSettings to be avaialable
 */


function test_valid_options( target_div ) {
// Use the console to view the reuults
// Lists all valid options.  If logged in as admin, admin_email and upload_path will be included
	 jQuery.ajax( {
	     url: wpApiSettings.root + 'optiontest/v1/valid_options',
	     method: 'GET',
	     beforeSend: function ( xhr ) {
	         xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
	     }
	 } ).done( function ( response ) {
		 	var display = '<pre>valid_options:\n' + JSON.stringify(response) + '</pre>';
			 jQuery(target_div).html(display);
	     console.log('valid_options');
	     console.log( response );
	 } );
 }

function test_blogname( target_div ) {
	// Should always give the blog name
	 jQuery.ajax( {
			 url: wpApiSettings.root + 'optiontest/v1/blogname',
			 method: 'GET',
			 beforeSend: function ( xhr ) {
					 xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
			 }
	 } ).done( function ( response ) {
			 var display = '<pre>blogname:\n' + JSON.stringify(response) + '</pre>';
			 jQuery(target_div).html(display);
			 console.log('blogname');
			 console.log( response );
	 } );
 }

function test_admin_email( target_div ) {
 // Will only give admin email if admin is logged in
  jQuery.ajax( {
 		 url: wpApiSettings.root + 'optiontest/v1/admin_email',
 		 method: 'GET',
 		 beforeSend: function ( xhr ) {
 				 xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
 		 }
  } ).done( function ( response ) {
		var display = '<pre>admin_email:\n' + JSON.stringify(response) + '</pre>';
		jQuery(target_div).html(display);
 		 console.log('admin_email');
 		 console.log( response );
  } );

}

function test_create( data, target_div ) {
		// Create a rest_test object with "data"
		 jQuery.ajax( {
				url: wpApiSettings.root + 'optiontest/v1/rest_test',
				method: 'POST',
				data: JSON.stringify(data),
				beforeSend: function ( xhr ) {
						xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
				}
		 } ).done( function ( response ) {
			  var display = '<pre>rest_test post:\n' + JSON.stringify(response) + '</pre>';
				jQuery(target_div).html(display);
				console.log('rest_test POST');
				console.log( response );
		 } );
}

function test_update( data, target_div ) {
		// Create a rest_test object with "data"
		 jQuery.ajax( {
				url: wpApiSettings.root + 'optiontest/v1/rest_test/'+data.ID,
				method: 'PUT',
				data: JSON.stringify(data),
				beforeSend: function ( xhr ) {
						xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
				}
		 } ).done( function ( response ) {
			  var display = '<pre>rest_test put:\n' + JSON.stringify(response) + '</pre>';
				jQuery(target_div).html(display);
				console.log('rest_test POST');
				console.log( response );
		 } );
}


function get_rest_test(target_div) {
	jQuery.ajax( {
			url: wpApiSettings.root + 'optiontest/v1/rest_test',
			method: 'GET',
			beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
			}
	} ).done( function ( response ) {
			var display = '<pre>rest_test:\n' + JSON.stringify(response) + '</pre>';
			jQuery(target_div).html(display);
			console.log('rest_test');
			console.log( response );
	} );

}

function delete_rest_test(id,target_div) {
	jQuery.ajax( {
			url: wpApiSettings.root + 'optiontest/v1/rest_test/'+id,
			method: 'DELETE',
			beforeSend: function ( xhr ) {
					xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
			}
	} ).done( function ( response ) {
			var display = '<pre>rest_test:\n' + JSON.stringify(response) + '</pre>';
			jQuery(target_div).html(display);
			console.log('rest_test');
			console.log( response );
	} );

}
