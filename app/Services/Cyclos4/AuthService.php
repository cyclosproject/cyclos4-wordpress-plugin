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
	 * @param string $send_medium       (Optional) The medium (email/sms) to use for sending the verification code to the visitor.
	 * @return object|\WP_Error The body from the Cyclos server response or a WP_Error object on failure.
	 */
	public function forgotten_password_request( string $principal, string $captcha_id, string $captcha_response, string $send_medium = null ) {
		$this->method = 'POST';
		$this->route  = '/auth/forgotten-password/request';
		$data         = array(
			'user'    => $principal,
			'captcha' => array(
				'challenge' => $captcha_id,
				'response'  => $captcha_response,
			),
		);
		if ( $send_medium ) {
			$data['sendMedium'] = $send_medium;
		}
		return $this->run( $data );
	}

	/**
	 * Returns configuration data used to change a forgotten password after the initial request.
	 *
	 * @param string $principal         The principal (i.e. username, e-mail, ..) to identify the user with.
	 * @param string $code              The verification code which was sent to the user.
	 *
	 * @return object|\WP_Error The body from the Cyclos server response or a WP_Error object on failure.
	 */
	public function forgotten_password_data_for_change( string $principal, string $code ) {
		$this->method = 'GET';
		$this->route  = '/auth/forgotten-password/data-for-change';
		$this->route .= '?user=' . $principal . '&code=' . $code;
		$this->authenticate_as_guest();
		return $this->run();
	}

	/**
	 * Changes the forgotten password after the user has completed the request.
	 *
	 * @param string $principal         The principal (i.e. username, e-mail, ..) to identify the user with.
	 * @param string $code              The verification code which was sent to the user.
	 * @param string $new_password      The new password.
	 * @param string $confirm_password  The new password again as a way of confirmation.
	 * @param string $security_answer   (Optional) The answer to the security question if one was used.
	 *
	 * @return object|\WP_Error The body from the Cyclos server response or a WP_Error object on failure.
	 */
	public function forgotten_password( string $principal, string $code, string $new_password, string $confirm_password, string $security_answer = null ) {
		$this->method = 'POST';
		$this->route  = '/auth/forgotten-password';
		$data         = array(
			'user'                    => $principal,
			'code'                    => $code,
			'newPassword'             => $new_password,
			'newPasswordConfirmation' => $confirm_password,
			'checkConfirmation'       => true, // This is needed to have Cyclos check the confirmation password value.
		);
		if ( $security_answer ) {
			$data['securityAnswer'] = $security_answer;
		}
		$this->authenticate_as_guest();
		return $this->run( $data );
	}

}
