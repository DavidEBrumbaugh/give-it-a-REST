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
*		 So the full URL to the blog tagline would be:
*				 http://example.com/wp-json/optiontest/v1/blogdescription
*		 (See: https://developer.wordpress.org/reference/functions/get_option/)
*
*/
class OptionAsResource extends WpEpResourceHandler {
	/**
	* Build a (resource identified by the resource route)
	* Each instance represents a resource
	* @param string $resource_route : Routes are regular expressions
	*		 You can use: https://regex101.com/ to test them
	*
	*		 * This route gets a list of supported options:
	*			 /
	*			 The full url is: http://example.com/wp-json/optiontest/v1/
	*
	*			* This route gets the content of a single option:
	*				/(?P<option>[0-9a-z-_]+)
	*				The parameter name will be named "option"
	*				The full url is: http://example.com/wp-json/optiontest/v1/{option}
	*
	* 			* This route gets the content of a setting within an option
	* 				assuming, that the setting is an array index into the option
	* 				(as is often the case).
	*				The route generates two parameters: option and id:
	*				/(?P<option>[0-9a-z-_]+)/(?P<id>[0-9a-z-_]+)
	*				The full url is: http://example.com/wp-json/optiontest/v1/{option}/{id}
	*/
	public function __construct( $namespace, $version ) {
		parent::__construct( $namespace, $version );
	}

	protected static $valid_options = array(
		'blogdescription',
		'blogname',
		'sidebars_widgets',
	);

	// Values not just anyone can see
	protected static $protected_options = array(
		'admin_email',
		'upload_path',
		'rest_test',
	);
	/**
	* Gets the specified options
	* @param	array $data get parameters may be empty, option, or option and id
	* @return string | array			content of the option
	*/
	public function get( $data ) {
		$data = $data->get_params();
		$option = null;
		if ( ! is_array( $data ) || empty( $data ) ) {
			$option = self::$valid_options;
			if ( current_user_can( 'list_users' ) ) {
				$option = array_merge( $option, self::$protected_options );
			}
		} else {
			if ( isset( $data['option'] ) ) {
				if ( $this->validate_option( $data['option'] ) ) {
					$option = get_option( $data['option'] );
				} else {
					$option	= new WP_Error( 'rest_forbidden', esc_html( 'Sorry, you cannot get this resource:'. $data['option'] ), array( 'status' => rest_authorization_required_code() ) );
				}
			}
			if ( $option && is_array( $option ) && isset( $data['id'] ) ) {
				$option = $option[ $data['id'] ];
			}
		}
		return $option;
	}

	protected function validate_option( $option ) {
		$valid = false;
		if ( in_array( $option, self::$valid_options, true ) ) {
			$valid = true;
		} else if ( current_user_can( 'list_users' ) &&
		in_array( $option , self::$protected_options, true ) ) {
			$valid = true;
		}
		return $valid;
	}

	protected $valid_write_options = array( 'rest_test' );

	/*
	POST is "CREATE"
	Only valid path to post is rest_test
	We will allow the rest_test list to contain up to 5 items
	If an object does not have an ID property we will add one.
	If it has an ID property and the id does not exist we will add it.
	If it has an ID property and the id DOES exist, we will return 409 (Conflict)
	The ID property must be numeric or we will return 400 (Bad Request)
	If there are	5 or more items, we will return a 409 (Conflict) Error
	*/
	public function post( $data ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return	new WP_Error( 'rest_forbidden', esc_html( 'Sorry, you cannot post this resource: rest_test' ), array( 'status' => rest_authorization_required_code() ) );
		}
		$data = self::extract_data( $data );
		if ( is_wp_error( $data ) ) {
			return $data;
		}
		$option = get_option( 'rest_test' );
		if ( is_array( $option ) && count( $option ) >= 5 ) {
			return	new WP_Error( 'rest_conflict', esc_html( 'This resource is full: rest_test' ), array( 'status' => 409 ) );
		}
		if ( isset( $data['ID'] ) ) {
			if ( ! is_numeric( $data['ID'] ) ) {
				return	new WP_Error( 'rest_bad_request', esc_html( 'ID must be numeric' ), array( 'status' => 400 ) );
			}
			if ( empty( $option ) ) {
				$option = array();
			}
			if ( isset( $option[ $data['ID'] ] ) ) {
				return	new WP_Error( 'rest_conflict', esc_html( 'This resource already exists: rest_test/'.$data['ID'] ), array( 'status' => 409 ) );
			} else {
				$option[ $data['ID'] ] = $data;
			}
		}	else {
			$new_id = 1;
			foreach ( $option as $id => $value ) {
				while ( $id >= $new_id ) {
					$new_id++;
				}
			}
		}
		$data['ID'] = $new_id;
		$option[ $new_id ] = $data;
		update_option( 'rest_test', $option );
		$response = rest_ensure_response( $option[ $new_id ] );
		$response->set_status( 201 );
		$response->header( 'Location', rest_url( sprintf( '/%s/%s/%s/%d', $this->namespace , $this->version , 'rest_test', $data['ID'] ) ) );

		return $response;
	}
	/**
	 * Delete a rest test object
	 * @param  object $data Reqest Object
	 * @return object       Response Object
	 */
	public function delete( $data ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return	new WP_Error( 'rest_forbidden', esc_html( 'Sorry, you cannot delete this resource: rest_test' ), array( 'status' => rest_authorization_required_code() ) );
		}
		$data = $data->get_params();
		if ( is_wp_error( $data ) ) {
			return $data;
		}
		$option = get_option( 'rest_test' );

		if ( isset( $data['id'] ) ) {
			if ( ! is_numeric( $data['id'] ) ) {
				return	new WP_Error( 'rest_bad_request', esc_html( 'ID must be numeric' ), array( 'status' => 400 ) );
			}
			if ( empty( $option ) ) {
				$option = array();
			}
			if ( isset( $option[ $data['id'] ] ) ) {
				unset( $option[ $data['id'] ] );
				update_option( 'rest_test', $option );
				return new WP_REST_Response( true, 200 );
			} else {
				return	new WP_Error( 'rest_notfound', esc_html( 'Resource not found: rest_test/'.$data['ID'] ), array( 'status' => 404 ) );
			}
		}
		return	new WP_Error( 'cantdelete', esc_html( 'Cannot Delete: rest_test/(invalid)' ), array( 'status' => 500 ) );
	}

	/**
	 * Update a rest test object
	 * @param  object $data Reqest Object
	 * @return object       Response Object
	 */
	public function put( $data ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return	new WP_Error( 'rest_forbidden', esc_html( 'Sorry, you cannot put this resource: rest_test' ), array( 'status' => rest_authorization_required_code() ) );
		}
		$params = $data->get_params();
		$data = self::extract_data($data);
		if ( is_wp_error( $data ) ) {
			return $data;
		}
		$option = get_option( 'rest_test' );

		if ( isset( $params['id'] ) ) {
			if ( ! is_numeric( $params['id'] ) ) {
				return	new WP_Error( 'rest_bad_request', esc_html( 'ID must be numeric' ), array( 'status' => 400 ) );
			}
			if ( empty( $option ) ) {
					return	new WP_Error( 'rest_notfound', esc_html( 'Resource not found: rest_test/'.$data['ID'] ), array( 'status' => 404 ) );
			}
			if ( isset( $option[ $params['id'] ] ) ) {
				if ($data['ID'] != $option[ $params['id'] ]['ID']) {
					return	new WP_Error( 'cantput', esc_html( 'Cannot Update: rest_test/(invalid)' ), array( 'status' => 500 ) );
				}
				$option[ $params['id'] ] = $data;
				update_option( 'rest_test', $option );
				return new WP_REST_Response( true, 200 );
			} else {
				return	new WP_Error( 'rest_notfound', esc_html( 'Resource not found: rest_test/'.$data['ID'] ), array( 'status' => 404 ) );
			}
		}
		return	new WP_Error( 'cantput', esc_html( 'Cannot Delete: rest_test/(invalid)' ), array( 'status' => 500 ) );
	}

}
