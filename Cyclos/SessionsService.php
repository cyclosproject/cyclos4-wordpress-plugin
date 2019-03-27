<?php namespace Cyclos;

/**
 * Operations for administrators managing sessions of other users.
 */
class SessionsService extends Service {

    function __construct() {
        parent::__construct('sessions');
    }
    
    /**
     * Logins a user, returning data from the new session
     */
    public function loginUser($principal, $password, $remoteAddress) {
    	$user = new \stdClass();
    	$user->user = $principal;
    	$user->password = $password;
    	$user->remoteAddress = $remoteAddress;
    	$user->channel = 'main';
        return $this->post(NULL, NULL, NULL, $user);
    }       
    
}