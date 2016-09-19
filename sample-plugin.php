<?php
/*
Plugin Name: Sample For adding custom rest services end points to your Plugin
Plugin URI: https://github.com/DavidEBrumbaugh/give-it-a-REST
Description: This plugin gives an example of the "Class to Resource Model"
Version: 0.2
Author: David Brumbaugh
Author URI: https://codementor.io/davidbrumbaugh
License: GPL3+
*/

require_once( 'wp-custom-ep-example.php' ); // Include the endpoint class

$resource_object = new OptionAsResource( 'optiontest','v1' );

$resource_object->add_route( '/valid_options', 'get' );
			// The full url is: http://example.com/wp-json/optiontest/v1/valid_options
$resource_object->add_route( '/(?P<option>[0-9a-z-_]+)', 'get' );
			// The full url is: http://example.com/wp-json/optiontest/v1/{option}
$resource_object->add_route( '/(?P<option>[0-9a-z-_]+)/(?P<id>[0-9a-z-_]+)', 'get' );
		// The full url is: http://example.com/wp-json/optiontest/v1/{option}/{id}

$resource_object->add_route( '/rest_test', 'post' );
				// The full url is: http://example.com/wp-json/optiontest/v1/rest_test

$resource_object->init_routes();

/*
Authentication
Please see: http://v2.wp-api.org/guide/authentication/ for details
*/

define( 'GIVEIT_A_REST_URL',     plugin_dir_url( __FILE__ ) );
// Assume we want to authenticate on both the front and back end
add_action( 'admin_enqueue_scripts', 'giveitarest_enqueue_scripts' );
add_action( 'wp_enqueue_scripts', 'giveitarest_enqueue_scripts' );

function giveitarest_enqueue_scripts() {
	wp_enqueue_script( 'wp-api' );
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script(
		'giveitarest-js',
		PS_BRIDGE_URL . '/js/test.js',
		array( 'jquery', 'wp-api' )
	);
}
