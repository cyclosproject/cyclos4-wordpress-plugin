<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/GeneralReferenceService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class GeneralReferenceService extends Service {

    function __construct() {
        parent::__construct('generalReferenceService');
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/GeneralReferenceService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/GeneralReferenceService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**
     * @param query Java type: org.cyclos.model.users.references.GeneralReferenceQuery
     * @return Java type: org.cyclos.model.users.references.GeneralReferenceSearchData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/GeneralReferenceService.html#getSearchData(org.cyclos.model.users.references.GeneralReferenceQuery)
     */
    public function getSearchData($query) {
        return $this->run('getSearchData', array($query));
    }
    
    /**
     * @param params Java type: org.cyclos.model.users.references.GeneralReferenceQuery
     * @return Java type: org.cyclos.model.users.references.ReferenceStatisticsVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/GeneralReferenceService.html#getStatistics(org.cyclos.model.users.references.GeneralReferenceQuery)
     */
    public function getStatistics($params) {
        return $this->run('getStatistics', array($params));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/GeneralReferenceService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/GeneralReferenceService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/GeneralReferenceService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/GeneralReferenceService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
    /**
     * @param query Java type: org.cyclos.model.users.references.GeneralReferenceQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/GeneralReferenceService.html#search(org.cyclos.model.users.references.GeneralReferenceQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
}