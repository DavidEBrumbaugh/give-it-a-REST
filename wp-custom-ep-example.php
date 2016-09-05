<?php
/**
 * This is an admitedly stlighly artifical example of using the enpoint
 * framework to read a specified option.
 */

if ( ! class_exists( 'WpEpResource' ) ) {
	require_once( 'wp-ep-resource.abstract.php' );
}

/**
 * This class treats wordpress options as a resource.
 *     So the full URL to the blog tagline would be:
 *         http://example.com/wp-json/optiontest/v1/blogdescription
 *     (See: https://developer.wordpress.org/reference/functions/get_option/)
 *
 */
class OptionAsResource extends WpEpResourceHandler {
	/**
	 * Build a (resource identified by the resource route)
	 * Each instance represents a resource
	 * @param string $resource_route : Routes are regular expressions
	 *     You can use: https://regex101.com/ to test them
	 *
	 *     * This route gets a list of supported options:
	 *       /
	 *       The full url is: http://example.com/wp-json/optiontest/v1/
	 *
	 *      * This route gets the content of a single option:
	 *        /(?P<option>[0-9a-z-_]+)
	 *        The parameter name will be named "option"
	 *        The full url is: http://example.com/wp-json/optiontest/v1/{option}
	 *
	 * 			* This route gets the content of a setting within an option
	 * 			  assuming, that the setting is an array index into the option
	 * 			  (as is often the case).
	 *        The route generates two parameters: option and id:
	 *        /(?P<option>[0-9a-z-_]+)/(?P<id>[0-9a-z-_]+)
	 *        The full url is: http://example.com/wp-json/optiontest/v1/{option}/{id}
	 */
	public function __construct( $namespace, $version ) {
			parent::__construct( $namespace, $version );
	}

	protected static $valid_options = array(
		'blogdescription',
		'blogname',
		'sidebars_widgets',
	);
	/**
	 * Gets the specified options
	 * @param  array $data get parameters may be empty, option, or option and id
	 * @return string | array      content of the option
	 */
	public function get( $data ) {
		$data = $data->get_params();
		$option = null;
		if ( ! is_array( $data ) || empty( $data ) ) {
			$option = self::$valid_options;
		} else {
			if ( isset( $data['option'] ) ) {
				$option = get_option( $data['option'] );
			}
			if ( $option && is_array( $option ) && isset( $data['id'] ) ) {
				$option = $option[ $data['id'] ];
			}
		}
		return $option;
	}
}
