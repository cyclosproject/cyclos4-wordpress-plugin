<?php
/**
 * AuthService class contains operations regarding the user authentication, such as login configuration and forgotten password functionality.
 *
 * @package Cyclos
 */

namespace Cyclos\Services\Cyclos4;

/**
 * The AuthService class.
 */
class AuthService extends Service {

	/**
	 * Returns information on the Cyclos user used for the connection to Cyclos.
	 *
	 * @return object|\WP_Error The body from the Cyclos server response or a WP_Error object on failure.
	 */
	public function get_current_user_info() {
		$this->method = 'GET';
		$this->route  = '/auth?fields=permissions.users&fields=permissions.sessions';
		return $this->run();
	}

	/**
	 * Returns data useful for login, such as data for the forgot password request.
	 *
	 * @return object|\WP_Error The body from the Cyclos server response or a WP_Error object on failure.
	 */
	public function get_data_for_login() {
		$this->method = 'GET';
		$this->route  = '/auth/data-for-login';
		$this->authenticate_as_guest();
		// Note: we don't need to specify a channel, because the information we need from data-for-login does not depend on the channel.
		return $this->run();
	}


	/**
	 * Generates a forgotten password reset request.
	 *
	 * @param string $principal         The principal (i.e. username, e-mail, ..) to identify the user with.
	 * @param string $captcha_id        The ID of the captcha challenge.
	 * @param string $captcha_response  The response for the captcha challenge.
	 * @return object|\WP_Error The body from the Cyclos server response or a WP_Error object on failure.
	 */
	public function forgotten_password_request( string $principal, string $captcha_id, string $captcha_response ) {
		$this->method = 'POST';
		$this->route  = '/auth/forgotten-password/request';
		$data         = array(
			'user'    => $principal,
			'captcha' => array(
				'challenge' => $captcha_id,
				'response'  => $captcha_response,
			),
		);
		return $this->run( $data );
	}

}
