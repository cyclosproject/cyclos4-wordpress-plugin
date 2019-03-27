<?php namespace Cyclos;

/**
 * Access clients provide a token for user authentication without decoupled from the login name and password
 */
class AccessClientService extends Service {

    function __construct() {
        parent::__construct('clients');
    }
    
    /**
     * Activates an access client
     */
    public function activate($code, $prefix) {
        return $this->post('activate', NULL, array('code'=> $code, 'prefix'=> $prefix));
    }      
    
}