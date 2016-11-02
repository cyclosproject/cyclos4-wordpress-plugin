<?php namespace Cyclos;

/**
 * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html
 * 
 * Generated with Cyclos 4.7
 * 
 * WARNING: The API is subject to change between revision versions
 * (for example, 4.5 to 4.6).
 */
class RecordCustomFieldPossibleValueService extends Service {

    function __construct() {
        parent::__construct('recordCustomFieldPossibleValueService');
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**
     * @param customField Java type: org.cyclos.model.system.fields.CustomFieldVO     * @param customFieldPossibleValueCategory Java type: org.cyclos.model.system.fields.CustomFieldPossibleValueCategoryVO     * @param possibleValues Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#insert(org.cyclos.model.system.fields.CustomFieldVO,%20org.cyclos.model.system.fields.CustomFieldPossibleValueCategoryVO,%20java.util.List)
     */
    public function insert($customField, $customFieldPossibleValueCategory, $possibleValues) {
        $this->run('insert', array($customField, $customFieldPossibleValueCategory, $possibleValues));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long     * @param up Java type: boolean
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#move(java.lang.Long,%20boolean)
     */
    public function move($id, $up) {
        $this->run('move', array($id, $up));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
    /**
     * @param query Java type: org.cyclos.model.system.fields.CustomFieldPossibleValueQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/RecordCustomFieldPossibleValueService.html#search(org.cyclos.model.system.fields.CustomFieldPossibleValueQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
}