/**
 * jQuery Ajax test calls to demonstrate cookie Authentication
 * See: http://v2.wp-api.org/guide/authentication/
 *
 *  You have to enque wpi for wpApiSettings to be avaialable
 */


// Use the console to view the reuults
// Lists all valid options.  If logged in as admin, admin_email and upload_path will be included
 jQuery.ajax( {
     url: wpApiSettings.root + 'optiontest/v1/valid_options',
     method: 'GET',
     beforeSend: function ( xhr ) {
         xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
     }
 } ).done( function ( response ) {
     console.log('valid_options');
     console.log( response );
 } );

// Should always give the blog name
 jQuery.ajax( {
		 url: wpApiSettings.root + 'optiontest/v1/blogname',
		 method: 'GET',
		 beforeSend: function ( xhr ) {
				 xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
		 }
 } ).done( function ( response ) {
		 console.log('blogname');
		 console.log( response );
 } );

 // Will only give admin email if admin is logged in
  jQuery.ajax( {
 		 url: wpApiSettings.root + 'optiontest/v1/admin_email',
 		 method: 'GET',
 		 beforeSend: function ( xhr ) {
 				 xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
 		 }
  } ).done( function ( response ) {
 		 console.log('admin_email');
 		 console.log( response );
  } );

	function test_create( data ) {
		// Will only give admin email if admin is logged in
		 jQuery.ajax( {
				url: wpApiSettings.root + 'optiontest/v1/rest_test',
				method: 'POST',
				data: data,
				beforeSend: function ( xhr ) {
						xhr.setRequestHeader( 'X-WP-Nonce', wpApiSettings.nonce );
				}
		 } ).done( function ( response ) {
				console.log('rest_test POST');
				console.log( response );
		 } );
	}
