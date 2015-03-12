<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/NotificationSettingsService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class NotificationSettingsService extends Service {

    function __construct() {
        parent::__construct('notificationSettingsService');
    }
    
    /**

     * @return Java type: org.cyclos.model.messaging.notificationsettings.AdminNotificationSettingsData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/NotificationSettingsService.html#getAdminNotificationSettingsData()
     */
    public function getAdminNotificationSettingsData() {
        return $this->run('getAdminNotificationSettingsData', array());
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.messaging.notificationsettings.UserNotificationSettingsData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/NotificationSettingsService.html#getUserNotificationSettingsData(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getUserNotificationSettingsData($locator) {
        return $this->run('getUserNotificationSettingsData', array($locator));
    }
    
    /**
     * @param dto Java type: org.cyclos.model.messaging.notificationsettings.AdminNotificationSettingsDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/NotificationSettingsService.html#saveAdmin(org.cyclos.model.messaging.notificationsettings.AdminNotificationSettingsDTO)
     */
    public function saveAdmin($dto) {
        $this->run('saveAdmin', array($dto));
    }
    
    /**
     * @param dto Java type: org.cyclos.model.messaging.notificationsettings.UserNotificationSettingsDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/NotificationSettingsService.html#saveUser(org.cyclos.model.messaging.notificationsettings.UserNotificationSettingsDTO)
     */
    public function saveUser($dto) {
        $this->run('saveUser', array($dto));
    }
    
}