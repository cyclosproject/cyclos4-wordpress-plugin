<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/ConnectedUserService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class ConnectedUserService extends Service {

    function __construct() {
        parent::__construct('connectedUserService');
    }
    
    /**
     * @param userLocator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: int
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/ConnectedUserService.html#disconnect(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function disconnect($userLocator) {
        return $this->run('disconnect', array($userLocator));
    }
    
    /**
     * @param ids Java type: java.util.Set
     * @return Java type: int
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/ConnectedUserService.html#disconnectBySessionIds(java.util.Set)
     */
    public function disconnectBySessionIds($ids) {
        return $this->run('disconnectBySessionIds', array($ids));
    }
    
    /**

     * @return Java type: org.cyclos.model.users.users.ConnectedUserSearchData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/ConnectedUserService.html#getSearchData()
     */
    public function getSearchData() {
        return $this->run('getSearchData', array());
    }
    
    /**
     * @param query Java type: org.cyclos.model.users.users.ConnectedUserQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/ConnectedUserService.html#search(org.cyclos.model.users.users.ConnectedUserQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
}