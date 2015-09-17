<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/LoginService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class LoginService extends Service {

    function __construct() {
        parent::__construct('loginService');
    }
    
    /**
     * @param channelName Java type: java.lang.String
     * @return Java type: org.cyclos.model.access.LoginData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/LoginService.html#getLoginData(java.lang.String)
     */
    public function getLoginData($channelName) {
        return $this->run('getLoginData', array($channelName));
    }
    
    /**

     * @return Java type: org.cyclos.model.users.users.UserLoginResult
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/LoginService.html#login()
     */
    public function login() {
        return $this->run('login', array());
    }
    
    /**
     * @param params Java type: org.cyclos.model.users.users.UserLoginDTO
     * @return Java type: org.cyclos.model.users.users.UserLoginResult
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/LoginService.html#loginUser(org.cyclos.model.users.users.UserLoginDTO)
     */
    public function loginUser($params) {
        return $this->run('loginUser', array($params));
    }
    
    /**

     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/LoginService.html#logout()
     */
    public function logout() {
        $this->run('logout', array());
    }
    
    /**
     * @param token Java type: java.lang.String
     * @return Java type: boolean
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/LoginService.html#logoutUser(java.lang.String)
     */
    public function logoutUser($token) {
        return $this->run('logoutUser', array($token));
    }
    
}