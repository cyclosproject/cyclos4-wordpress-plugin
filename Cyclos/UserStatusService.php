<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserStatusService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class UserStatusService extends Service {

    function __construct() {
        parent::__construct('userStatusService');
    }
    
    /**
     * @param params Java type: org.cyclos.model.users.users.ChangeUserStatusParams
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserStatusService.html#changeStatus(org.cyclos.model.users.users.ChangeUserStatusParams)
     */
    public function changeStatus($params) {
        $this->run('changeStatus', array($params));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.users.users.ChangeUserStatusData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserStatusService.html#getData(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getData($locator) {
        return $this->run('getData', array($locator));
    }
    
}