<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MailingListService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class MailingListService extends Service {

    function __construct() {
        parent::__construct('mailingListService');
    }
    
    /**

     * @return Java type: org.cyclos.model.messaging.mailinglists.MailingListSearchData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MailingListService.html#getSearchData()
     */
    public function getSearchData() {
        return $this->run('getSearchData', array());
    }
    
    /**
     * @param type Java type: org.cyclos.model.messaging.mailinglists.MailingListType
     * @return Java type: org.cyclos.model.messaging.mailinglists.SendMailingListData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MailingListService.html#getSendData(org.cyclos.model.messaging.mailinglists.MailingListType)
     */
    public function getSendData($type) {
        return $this->run('getSendData', array($type));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.messaging.mailinglists.MailingListDetailedVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MailingListService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param query Java type: org.cyclos.model.messaging.mailinglists.MailingListQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MailingListService.html#search(org.cyclos.model.messaging.mailinglists.MailingListQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
    /**
     * @param object Java type: org.cyclos.model.messaging.mailinglists.SendMailingListDTO
     * @return Java type: int
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/messaging/MailingListService.html#send(org.cyclos.model.messaging.mailinglists.SendMailingListDTO)
     */
    public function send($object) {
        return $this->run('send', array($object));
    }
    
}