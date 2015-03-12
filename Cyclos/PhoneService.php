<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/PhoneService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class PhoneService extends Service {

    function __construct() {
        parent::__construct('phoneService');
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/PhoneService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/PhoneService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.users.phones.PhoneListData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/PhoneService.html#getPhoneListData(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getPhoneListData($locator) {
        return $this->run('getPhoneListData', array($locator));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/PhoneService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long     * @param enabled Java type: boolean
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/PhoneService.html#markAsEnabledForSms(java.lang.Long,%20boolean)
     */
    public function markAsEnabledForSms($id, $enabled) {
        $this->run('markAsEnabledForSms', array($id, $enabled));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/PhoneService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/PhoneService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/PhoneService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/PhoneService.html#sendVerificationCode(java.lang.Long)
     */
    public function sendVerificationCode($id) {
        $this->run('sendVerificationCode', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long     * @param verificationCode Java type: java.lang.String
     * @return Java type: org.cyclos.model.users.phones.CodeVerificationStatus
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/PhoneService.html#verify(java.lang.Long,%20java.lang.String)
     */
    public function verify($id, $verificationCode) {
        return $this->run('verify', array($id, $verificationCode));
    }
    
}