<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class RecordCustomFieldService extends Service {

    function __construct() {
        parent::__construct('recordCustomFieldService');
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**
     * @param recordTypeId Java type: java.lang.Long     * @param customFieldId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldService.html#linkShared(java.lang.Long,%20java.lang.Long)
     */
    public function linkShared($recordTypeId, $customFieldId) {
        $this->run('linkShared', array($recordTypeId, $customFieldId));
    }
    
    /**
     * @param recordTypeId Java type: java.lang.Long
     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldService.html#list(java.lang.Long)
     */
    public function _list($recordTypeId) {
        return $this->run('list', array($recordTypeId));
    }
    
    /**

     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldService.html#listShared()
     */
    public function listShared() {
        return $this->run('listShared', array());
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
    /**
     * @param customFieldIds Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldService.html#saveOrder(java.util.List)
     */
    public function saveOrder($customFieldIds) {
        $this->run('saveOrder', array($customFieldIds));
    }
    
    /**
     * @param recordTypeId Java type: java.lang.Long     * @param customFieldIds Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldService.html#saveOrderOnType(java.lang.Long,%20java.util.List)
     */
    public function saveOrderOnType($recordTypeId, $customFieldIds) {
        $this->run('saveOrderOnType', array($recordTypeId, $customFieldIds));
    }
    
    /**
     * @param recordTypeId Java type: java.lang.Long     * @param customFieldId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldService.html#unlinkShared(java.lang.Long,%20java.lang.Long)
     */
    public function unlinkShared($recordTypeId, $customFieldId) {
        $this->run('unlinkShared', array($recordTypeId, $customFieldId));
    }
    
}