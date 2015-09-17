<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/NotificationService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class NotificationService extends Service {

    function __construct() {
        parent::__construct('notificationService');
    }
    
    /**
     * @param userId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/NotificationService.html#markAllRead(java.lang.Long)
     */
    public function markAllRead($userId) {
        $this->run('markAllRead', array($userId));
    }
    
    /**
     * @param ids Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/NotificationService.html#markAsRead(java.util.List)
     */
    public function markAsRead($ids) {
        $this->run('markAsRead', array($ids));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/NotificationService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param notificationIds Java type: java.util.Collection
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/NotificationService.html#removeAll(java.util.Collection)
     */
    public function removeAll($notificationIds) {
        $this->run('removeAll', array($notificationIds));
    }
    
    /**
     * @param params Java type: org.cyclos.model.messaging.notifications.NotificationQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/NotificationService.html#search(org.cyclos.model.messaging.notifications.NotificationQuery)
     */
    public function search($params) {
        return $this->run('search', array($params));
    }
    
}