<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/NetworkService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class NetworkService extends Service {

    function __construct() {
        parent::__construct('networkService');
    }
    
    /**
     * @param network Java type: org.cyclos.model.system.networks.NetworkDTO     * @param data Java type: org.cyclos.model.system.networks.NetworkInitialDataDTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/NetworkService.html#createWithData(org.cyclos.model.system.networks.NetworkDTO,%20org.cyclos.model.system.networks.NetworkInitialDataDTO)
     */
    public function createWithData($network, $data) {
        return $this->run('createWithData', array($network, $data));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/NetworkService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/NetworkService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**

     * @return Java type: org.cyclos.model.system.networks.NetworkWithInitialDataData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/NetworkService.html#getDataForNewWithData()
     */
    public function getDataForNewWithData() {
        return $this->run('getDataForNewWithData', array());
    }
    
    /**
     * @param networkId Java type: java.lang.Long
     * @return Java type: java.lang.String
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/NetworkService.html#getFullURL(java.lang.Long)
     */
    public function getFullURL($networkId) {
        return $this->run('getFullURL', array($networkId));
    }
    
    /**
     * @param basicData Java type: org.cyclos.model.system.networks.BasicNetworkInitialDataDTO
     * @return Java type: org.cyclos.model.system.networks.NetworkInitialDataDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/NetworkService.html#getInitialData(org.cyclos.model.system.networks.BasicNetworkInitialDataDTO)
     */
    public function getInitialData($basicData) {
        return $this->run('getInitialData', array($basicData));
    }
    
    /**

     * @return Java type: org.cyclos.model.system.networks.NetworkSearchData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/NetworkService.html#getSearchData()
     */
    public function getSearchData() {
        return $this->run('getSearchData', array());
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/NetworkService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/NetworkService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/NetworkService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/NetworkService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
    /**
     * @param query Java type: org.cyclos.model.system.networks.NetworkQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/NetworkService.html#search(org.cyclos.model.system.networks.NetworkQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
}