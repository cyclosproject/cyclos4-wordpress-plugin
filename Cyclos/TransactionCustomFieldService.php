<?php namespace Cyclos;

/**
 * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionCustomFieldService.html
 * 
 * Generated with Cyclos 4.7
 * 
 * WARNING: The API is subject to change between revision versions
 * (for example, 4.5 to 4.6).
 */
class TransactionCustomFieldService extends Service {

    function __construct() {
        parent::__construct('transactionCustomFieldService');
    }
    
    /**
     * @param transferType Java type: org.cyclos.model.banking.transfertypes.TransferTypeVO     * @param customField Java type: org.cyclos.model.system.fields.CustomFieldVO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionCustomFieldService.html#addRelation(org.cyclos.model.banking.transfertypes.TransferTypeVO,%20org.cyclos.model.system.fields.CustomFieldVO)
     */
    public function addRelation($transferType, $customField) {
        $this->run('addRelation', array($transferType, $customField));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionCustomFieldService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionCustomFieldService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**

     * @return Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionCustomFieldService.html#list()
     */
    public function _list() {
        return $this->run('list', array());
    }
    
    /**
     * @param transferType Java type: org.cyclos.model.banking.transfertypes.TransferTypeVO
     * @return Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionCustomFieldService.html#listAllRelated(org.cyclos.model.banking.transfertypes.TransferTypeVO)
     */
    public function listAllRelated($transferType) {
        return $this->run('listAllRelated', array($transferType));
    }
    
    /**
     * @param transferType Java type: org.cyclos.model.banking.transfertypes.TransferTypeVO
     * @return Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionCustomFieldService.html#listRelated(org.cyclos.model.banking.transfertypes.TransferTypeVO)
     */
    public function listRelated($transferType) {
        return $this->run('listRelated', array($transferType));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionCustomFieldService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionCustomFieldService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionCustomFieldService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param transferType Java type: org.cyclos.model.banking.transfertypes.TransferTypeVO     * @param customField Java type: org.cyclos.model.system.fields.CustomFieldVO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionCustomFieldService.html#removeRelation(org.cyclos.model.banking.transfertypes.TransferTypeVO,%20org.cyclos.model.system.fields.CustomFieldVO)
     */
    public function removeRelation($transferType, $customField) {
        $this->run('removeRelation', array($transferType, $customField));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionCustomFieldService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
    /**
     * @param customFieldIds Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionCustomFieldService.html#saveOrder(java.util.List)
     */
    public function saveOrder($customFieldIds) {
        $this->run('saveOrder', array($customFieldIds));
    }
    
}