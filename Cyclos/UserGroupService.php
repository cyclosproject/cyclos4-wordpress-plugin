<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserGroupService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class UserGroupService extends Service {

    function __construct() {
        parent::__construct('userGroupService');
    }
    
    /**
     * @param dto Java type: org.cyclos.model.users.groups.ChangeGroupDTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserGroupService.html#changeGroup(org.cyclos.model.users.groups.ChangeGroupDTO)
     */
    public function changeGroup($dto) {
        return $this->run('changeGroup', array($dto));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.users.groups.ChangeGroupData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserGroupService.html#getChangeGroupData(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getChangeGroupData($locator) {
        return $this->run('getChangeGroupData', array($locator));
    }
    
}