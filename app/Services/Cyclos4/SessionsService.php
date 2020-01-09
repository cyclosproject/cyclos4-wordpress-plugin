<?php
/**
 * SessionsService class contains operations for administrators managing sessions of other users.
 *
 * @package Cyclos
 */

namespace Cyclos\Services\Cyclos4;

/**
 * The SessionsService class.
 */
class SessionsService extends Service {

	/**
	 * Let the user login into Cyclos.
	 *
	 * @param string $username        The username to login with.
	 * @param string $password        The password to login with.
	 * @param string $remote_address  The remote address from which to login.
	 * @return object|\WP_Error       The body from the Cyclos server response or a WP_Error object on failure.
	 */
	public function login_user( string $username, string $password, string $remote_address ) {
		$this->method = 'POST';
		$this->route  = '/sessions?fields=sessionToken';
		$data         = array(
			'user'          => $username,
			'password'      => $password,
			'remoteAddress' => $remote_address,
		);
		return $this->run( $data );
	}
}
