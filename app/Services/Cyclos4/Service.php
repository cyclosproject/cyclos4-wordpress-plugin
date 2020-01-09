<?php
/**
 * Service class. The base class for requests to the Cyclos REST API.
 *
 * @package Cyclos
 */

namespace Cyclos\Services\Cyclos4;

use Cyclos\Configuration;

/**
 * The Service class.
 */
class Service {

	/**
	 * The base URL of the Cyclos instance.
	 *
	 * @var string $root_url The base url of the Cyclos instance. For example: https://demo.cyclos.org.
	 */
	protected $root_url;

	/**
	 * The route in the Cyclos REST API, i.e. everything in the URL after the '/api' part.
	 *
	 * @var string $route The REST API route. Includes a slash at the start, so for example: /auth.
	 */
	protected $route;

	/**
	 * The HTTP method to use in the request, i.e. GET or POST.
	 *
	 * @var string $method The HTTP method.
	 */
	protected $method;

	/**
	 * The authentication to use in the request.
	 *
	 * @var array $authentication The authentication array.
	 */
	protected $authentication;

	/**
	 * The configuration with the plugin settings.
	 *
	 * @var Configuration $conf The configuration.
	 */
	protected $conf;

	/**
	 * Constructor.
	 *
	 * @param Configuration $conf The configuration.
	 */
	public function __construct( Configuration $conf ) {
		$this->conf = $conf;

		// Set sensible default values for some variables.
		$this->root_url = $this->conf->get_cyclos_url( false );
		$this->authenticate_with_accessclient();
	}

	/**
	 * Configure the authentication method used in the request to be guest, that is use no authentication at all.
	 */
	protected function authenticate_as_guest() {
		$this->authentication = array();
	}

	/**
	 * Configure the authentication method used in the request to use the accessclient token.
	 */
	protected function authenticate_with_accessclient() {
		$this->authentication = array(
			'Access-Client-Token' => $this->conf->get_accessclient_token(),
		);
	}

	/**
	 * Configure the authentication method used in the request to use basic authentication with username/password.
	 *
	 * @param string $username The username to authenticate with.
	 * @param string $password The password to authenticate with.
	 */
	protected function authenticate_with_basic_login( string $username, string $password ) {
		// phpcs:disable WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		$this->authentication = array(
			'Authorization' => 'Basic ' . base64_encode( $username . ':' . $password ),
		);
		// phpcs:enable
	}

	/**
	 * Execute a request to the Cyclos API.
	 *
	 * @param array $data      (Optional) The data to post to the Cyclos API.
	 * @return object|\WP_Error The body from the Cyclos server response or a WP_Error object on failure.
	 */
	protected function run( array $data = array() ) {
		$response = null;
		$url      = trailingslashit( $this->root_url ) . 'api' . $this->route;
		$args     = array(
			'headers' => array_merge(
				array(
					'Content-Type' => 'application/json',
				),
				$this->authentication
			),
		);

		// Execute the request.
		switch ( $this->method ) {
			case 'GET':
				$response = wp_safe_remote_get( $url, $args );
				break;
			case 'POST':
				if ( ! empty( $data ) ) {
					$args['body'] = wp_json_encode( $data );
				}
				$response = wp_safe_remote_post( $url, $args );
				break;
		}

		// If the request failed, return the response containing the WP_Error object.
		if ( is_wp_error( $response ) ) {
			return $response;
		}

		// Retrieve the response code and body.
		$response_code = wp_remote_retrieve_response_code( $response );
		$result        = wp_remote_retrieve_body( $response );

		// If the response body is json, decode it.
		$response_type = wp_remote_retrieve_header( $response, 'content-type' );
		if ( false !== strpos( $response_type, 'application/json' ) ) {
			$result = json_decode( $result );
		}

		// If the response code indicates no problems, return the response body object as the result.
		if ( in_array( $response_code, array( 200, 201, 204 ), true ) ) {
			return $result;
		}

		// If the response code indicates a problem, return a WP_Error object with the response information.
		return new \WP_Error( 'CYCLOS_EXCEPTION', $response_code, $result );
	}

}
