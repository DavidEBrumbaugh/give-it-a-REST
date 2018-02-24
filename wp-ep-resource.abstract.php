<?php
/**
 * Abstract class to derive actual resource from
 *
 * A complete URL identifies a "resource".  Each concrete subclass
 * of this abstract base class represents one "resource" identified by
 * a full route.
 *
 * A concrete subclass can get, post, put, delete or patch the resource in question
 *
 */

abstract class WpEpResourceHandler {
	protected $namespace;
	protected $version;
	protected $resource_routes;
	protected $implemented_methods;
	protected $args;

	/**
	 * Extracts the JSON data from the REST request and puts it into a standard PHP
	 * array.
	 *
	 * @param	WP_REST_Request $data Data From REST Request
	 * @return string|WP_Error		 json	object
	 */
	protected function extract_data( $data ) {
		if ( $data instanceof WP_REST_Request ) {
			$data = json_decode( $data->get_body(), true );
		} else {
			$data = new WP_Error( 'Invalid Data', __( 'Expecting WP_REST_Request' ) );
		}
		return $data;
	}

	/**
	 * Build a Resource Handler
	 * @param string $name_space    Namespace for resource see: http://v2.wp-api.org/extending/adding/
	 * @param string $version       Version for resource see: http://v2.wp-api.org/extending/adding/
 */
	public function __construct( $name_space, $version ) {
			$this->namespace = $name_space;
			$this->version = $version;
			$this->resource_routes = array();
	}
	public function get( $data ) {
		return 'not implemented';
	}
	public function post( $data ) {
		return 'not implemented';
	}
	public function put( $data ) {
		return 'not implemented';
	}
	public function delete( $data ) {
		return 'not implemented';
	}
	public function patch( $data ) {
		return 'not implemented';
	}


	/**
	 * Registeres the routes added by add_route
	 *  @return void
	 *  */
	public function register_the_routes() {
		$namespace_version = $this->namespace . '/' . $this->version;
		foreach ( $this->resource_routes as $route => $implementation ) {
			foreach ( $implementation as $method => $calls ) {
				$route_reg = array();
				$route_reg['methods'] = strtoupper( $method );
				$route_reg['callback'] = $calls['callback'];
				if ( isset( $calls['args'] ) ) {
					$route_reg['args'] = $calls['args'];
				}
				register_rest_route( $namespace_version, '/'.$route, $route_reg );
			}
		}
	}

	/**
	 * Use this to initailzie actual routes implemented must be implemented in
	 * concrete subclass.
	 *
	 * @return array routes
	 */
	public function init_routes() {
		add_action( 'rest_api_init', array( $this, 'register_the_routes' ) );
	}


	/**
	 * Adds a route to be handled.
	 * @param string $route  The route to the resource (regex)
	 * @param string $method 'get'|'post'|'put'|'delete'
	 * @param strng $member (optional) member function to call for method, defaults to $method
	 * @param array  $args  (optional) validation args
	 */
	public function add_route( $route, $method, $member = null, $args = array() ) {
		$valid_methods = array( 'get','post','put','patch','delete' );
		if ( ! $member ) {
			$member = $method;
		}
		if ( in_array( $method, $valid_methods, true ) ) {
			$this->resource_routes[ $route ][ $method ]['callback'] = array( $this, $member );
			if ( ! empty( $args ) ) {
					$this->resource_routes[ $route ][ $method ]['callback']  = $args;
			}
		}
	}
}
