<?php
/**
 * AccessClientService class for activating accessclients.
 *
 * @package Cyclos
 */

namespace Cyclos\Services\Cyclos4;

/**
 * The AccessClientService class.
 */
class AccessClientService extends Service {

	/**
	 * Activates an accessclient, returning the accessclient token.
	 *
	 * @param string $url             The Cyclos URL to call.
	 * @param string $username        The username to authenticate with.
	 * @param string $password        The password to authenticate with.
	 * @param string $activation_code The activation code.
	 * @return object|\WP_Error       The body from the Cyclos server response or a WP_Error object on failure.
	 */
	public function activate( string $url, string $username, string $password, string $activation_code ) {
		$this->root_url = $url;
		$this->method   = 'POST';
		$this->route    = '/clients/activate?code=' . $activation_code;
		$this->authenticate_with_basic_login( $username, $password );
		return $this->run();
	}
}
