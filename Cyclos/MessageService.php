<?php namespace Cyclos;

/**
 * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/messaging/MessageService.html
 * 
 * Generated with Cyclos 4.7
 * 
 * WARNING: The API is subject to change between revision versions
 * (for example, 4.5 to 4.6).
 */
class MessageService extends Service {

    function __construct() {
        parent::__construct('messageService');
    }
    
    /**

     * @return Java type: int
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/messaging/MessageService.html#countNewMessages()
     */
    public function countNewMessages() {
        return $this->run('countNewMessages', array());
    }
    
    /**

     * @return Java type: int
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/messaging/MessageService.html#countUnreadMessages()
     */
    public function countUnreadMessages() {
        return $this->run('countUnreadMessages', array());
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.messaging.messages.MessageData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/messaging/MessageService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**

     * @return Java type: org.cyclos.model.messaging.messages.MessageSearchData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/messaging/MessageService.html#getSearchData()
     */
    public function getSearchData() {
        return $this->run('getSearchData', array());
    }
    
    /**
     * @param replyId Java type: java.lang.Long     * @param toUser Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.messaging.messages.SendMessageData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/messaging/MessageService.html#getSendData(java.lang.Long,%20org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getSendData($replyId, $toUser) {
        return $this->run('getSendData', array($replyId, $toUser));
    }
    
    /**

     * @return Java type: org.cyclos.model.messaging.messages.SendInviteMessageData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/messaging/MessageService.html#getSendInviteMessageData()
     */
    public function getSendInviteMessageData() {
        return $this->run('getSendInviteMessageData', array());
    }
    
    /**
     * @param ids Java type: java.util.Set     * @param isRead Java type: boolean
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/messaging/MessageService.html#markAsRead(java.util.Set,%20boolean)
     */
    public function markAsRead($ids, $isRead) {
        $this->run('markAsRead', array($ids, $isRead));
    }
    
    /**
     * @param ids Java type: java.util.Set
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/messaging/MessageService.html#moveToTrash(java.util.Set)
     */
    public function moveToTrash($ids) {
        $this->run('moveToTrash', array($ids));
    }
    
    /**
     * @param ids Java type: java.util.Set
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/messaging/MessageService.html#removeAll(java.util.Set)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param ids Java type: java.util.Set
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/messaging/MessageService.html#restoreAll(java.util.Set)
     */
    public function restoreAll($ids) {
        $this->run('restoreAll', array($ids));
    }
    
    /**
     * @param query Java type: org.cyclos.model.messaging.messages.MessageQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/messaging/MessageService.html#search(org.cyclos.model.messaging.messages.MessageQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
    /**
     * @param object Java type: org.cyclos.model.messaging.messages.SendMessageDTO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/messaging/MessageService.html#send(org.cyclos.model.messaging.messages.SendMessageDTO)
     */
    public function send($object) {
        $this->run('send', array($object));
    }
    
    /**
     * @param inviteMessageDTO Java type: org.cyclos.model.messaging.messages.SendInviteMessageDTO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/messaging/MessageService.html#sendInviteMessage(org.cyclos.model.messaging.messages.SendInviteMessageDTO)
     */
    public function sendInviteMessage($inviteMessageDTO) {
        $this->run('sendInviteMessage', array($inviteMessageDTO));
    }
    
}