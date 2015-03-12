<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class RecordService extends Service {

    function __construct() {
        parent::__construct('recordService');
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**
     * @param recordTypeId Java type: java.lang.Long     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.users.records.RecordSearchData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordService.html#getSearchData(java.lang.Long,%20org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getSearchData($recordTypeId, $locator) {
        return $this->run('getSearchData', array($recordTypeId, $locator));
    }
    
    /**

     * @return Java type: org.cyclos.model.users.records.RecordSearchData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordService.html#getSharedFieldsSearchData()
     */
    public function getSharedFieldsSearchData() {
        return $this->run('getSharedFieldsSearchData', array());
    }
    
    /**
     * @param recordTypeId Java type: java.lang.Long     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.users.records.TiledRecordsData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordService.html#getTiledRecordsData(java.lang.Long,%20org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getTiledRecordsData($recordTypeId, $locator) {
        return $this->run('getTiledRecordsData', array($recordTypeId, $locator));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.users.records.RecordVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordService.html#loadVO(java.lang.Long)
     */
    public function loadVO($id) {
        return $this->run('loadVO', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
    /**
     * @param query Java type: org.cyclos.model.users.records.RecordQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordService.html#search(org.cyclos.model.users.records.RecordQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
}