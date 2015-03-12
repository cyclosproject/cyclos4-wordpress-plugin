<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class RecordCustomFieldPossibleValueService extends Service {

    function __construct() {
        parent::__construct('recordCustomFieldPossibleValueService');
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**
     * @param customFieldId Java type: java.lang.Long     * @param categoryId Java type: java.lang.Long     * @param possibleValues Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#insert(java.lang.Long,%20java.lang.Long,%20java.util.List)
     */
    public function insert($customFieldId, $categoryId, $possibleValues) {
        $this->run('insert', array($customFieldId, $categoryId, $possibleValues));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long     * @param up Java type: boolean
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#move(java.lang.Long,%20boolean)
     */
    public function move($id, $up) {
        $this->run('move', array($id, $up));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
    /**
     * @param query Java type: org.cyclos.model.system.fields.CustomFieldPossibleValueQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#search(org.cyclos.model.system.fields.CustomFieldPossibleValueQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
}