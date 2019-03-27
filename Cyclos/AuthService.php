<?php namespace Cyclos;

/**
 * Operations regarding the user authentication, such as login / logout, activating / deactivating an access client and obtaining the current authenticated user information.
 */
class AuthService extends Service {

    function __construct() {
        parent::__construct('auth');
    }
    
    /**
     * Returns data about the currently authenticated user
     */
    public function getCurrentAuth() {
        return $this->get();
    }
    
    /**
     * Logs-in the currently authenticated user
     */
    public function login() {
    	return $this->post('session');
    }
    
    /**
     * Returns data containing the configuration for logging-in
     * Contains data useful for login, such as the allowed user identification methods, the password type and data for the forgot password request.
     */
    public function getDataForLogin() {
    	return $this->get('data-for-login');
    }
    
    /**
     * Requests a forgotten password, notifying the user with instructions to reset it
     */
    public function forgottenPasswordRequest($user, $captchaResponse) {
    	$params = new \stdClass();
    	$params->user = $user;
    	$params->captcha = $captchaResponse;
    	return $this->post('forgotten-password/request', NULL, NULL, $params);
    }
    
}