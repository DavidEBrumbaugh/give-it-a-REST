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
$routes = array();
$resource_object->add_route( '/valid_options', 'get' );
			// The full url is: http://example.com/wp-json/optiontest/v1/
$resource_object->add_route( '/(?P<option>[0-9a-z-_]+)', 'get' );
			// The full url is: http://example.com/wp-json/optiontest/v1/{option}
$resource_object->add_route( '/(?P<option>[0-9a-z-_]+)/(?P<id>[0-9a-z-_]+)', 'get' );
		// The full url is: http://example.com/wp-json/optiontest/v1/{option}/{id}

$resource_object->init_routes();
