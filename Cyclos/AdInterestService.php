<?php namespace Cyclos;

/**
 * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/marketplace/AdInterestService.html
 * 
 * Generated with Cyclos 4.7
 * 
 * WARNING: The API is subject to change between revision versions
 * (for example, 4.5 to 4.6).
 */
class AdInterestService extends Service {

    function __construct() {
        parent::__construct('adInterestService');
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/marketplace/AdInterestService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/marketplace/AdInterestService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/marketplace/AdInterestService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/marketplace/AdInterestService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/marketplace/AdInterestService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/marketplace/AdInterestService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
    /**
     * @param params Java type: org.cyclos.model.marketplace.interests.AdInterestQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/marketplace/AdInterestService.html#search(org.cyclos.model.marketplace.interests.AdInterestQuery)
     */
    public function search($params) {
        return $this->run('search', array($params));
    }
    
}