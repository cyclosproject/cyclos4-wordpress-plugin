<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MessageService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class MessageService extends Service {

    function __construct() {
        parent::__construct('messageService');
    }
    
    /**

     * @return Java type: int
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MessageService.html#countNewMessages()
     */
    public function countNewMessages() {
        return $this->run('countNewMessages', array());
    }
    
    /**

     * @return Java type: int
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MessageService.html#countUnreadMessages()
     */
    public function countUnreadMessages() {
        return $this->run('countUnreadMessages', array());
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.messaging.messages.MessageData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MessageService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**

     * @return Java type: org.cyclos.model.messaging.messages.MessageSearchData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MessageService.html#getSearchData()
     */
    public function getSearchData() {
        return $this->run('getSearchData', array());
    }
    
    /**
     * @param replyId Java type: java.lang.Long     * @param toUserId Java type: java.lang.Long
     * @return Java type: org.cyclos.model.messaging.messages.SendMessageData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MessageService.html#getSendData(java.lang.Long,%20java.lang.Long)
     */
    public function getSendData($replyId, $toUserId) {
        return $this->run('getSendData', array($replyId, $toUserId));
    }
    
    /**
     * @param ids Java type: java.util.Set     * @param isRead Java type: boolean
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MessageService.html#markAsRead(java.util.Set,%20boolean)
     */
    public function markAsRead($ids, $isRead) {
        $this->run('markAsRead', array($ids, $isRead));
    }
    
    /**
     * @param ids Java type: java.util.Set
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MessageService.html#moveToTrash(java.util.Set)
     */
    public function moveToTrash($ids) {
        $this->run('moveToTrash', array($ids));
    }
    
    /**
     * @param ids Java type: java.util.Set
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MessageService.html#removeAll(java.util.Set)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param ids Java type: java.util.Set
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MessageService.html#restoreAll(java.util.Set)
     */
    public function restoreAll($ids) {
        $this->run('restoreAll', array($ids));
    }
    
    /**
     * @param query Java type: org.cyclos.model.messaging.messages.MessageQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MessageService.html#search(org.cyclos.model.messaging.messages.MessageQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
    /**
     * @param object Java type: org.cyclos.model.messaging.messages.SendMessageDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MessageService.html#send(org.cyclos.model.messaging.messages.SendMessageDTO)
     */
    public function send($object) {
        $this->run('send', array($object));
    }
    
}