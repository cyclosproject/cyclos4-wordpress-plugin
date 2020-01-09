<?php
/**
 * CaptchaService class contains operations regarding the user authentication, such as login configuration and forgotten password functionality.
 *
 * @package Cyclos
 */

namespace Cyclos\Services\Cyclos4;

/**
 * The CaptchaService class.
 */
class CaptchaService extends Service {

	/**
	 * Returns a new captcha challenge.
	 *
	 * @return object|\WP_Error       The body from the Cyclos server response or a WP_Error object on failure.
	 */
	public function new_captcha() {
		$this->method = 'POST';
		$this->route  = '/captcha';
		return $this->run();
	}

	/**
	 * Returns the captcha image content of the given id.
	 *
	 * @param string $id        The id identifying the captcha image.
	 * @return object|\WP_Error       The body from the Cyclos server response or a WP_Error object on failure.
	 */
	public function get_captcha_content( $id ) {
		$this->method = 'GET';
		$this->route  = '/captcha/' . $id;
		return $this->run();
	}

}
