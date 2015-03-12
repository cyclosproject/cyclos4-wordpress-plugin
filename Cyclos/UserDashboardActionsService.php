<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserDashboardActionsService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class UserDashboardActionsService extends Service {

    function __construct() {
        parent::__construct('userDashboardActionsService');
    }
    
    /**

     * @return Java type: org.cyclos.model.users.dashboardsettings.UserDashboardActionsData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserDashboardActionsService.html#getData()
     */
    public function getData() {
        return $this->run('getData', array());
    }
    
    /**

     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserDashboardActionsService.html#restoreDefaultDashboardActions()
     */
    public function restoreDefaultDashboardActions() {
        $this->run('restoreDefaultDashboardActions', array());
    }
    
    /**
     * @param dashboardAction Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserDashboardActionsService.html#save(java.util.List)
     */
    public function save($dashboardAction) {
        $this->run('save', array($dashboardAction));
    }
    
    /**
     * @param dashboardActions Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserDashboardActionsService.html#saveOrder(java.util.List)
     */
    public function saveOrder($dashboardActions) {
        $this->run('saveOrder', array($dashboardActions));
    }
    
}