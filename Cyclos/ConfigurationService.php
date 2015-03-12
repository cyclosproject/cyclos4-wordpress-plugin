<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/ConfigurationService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class ConfigurationService extends Service {

    function __construct() {
        parent::__construct('configurationService');
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.system.configurations.ActiveConfigurationData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/ConfigurationService.html#getActiveConfiguration(java.lang.Long)
     */
    public function getActiveConfiguration($id) {
        return $this->run('getActiveConfiguration', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/ConfigurationService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/ConfigurationService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**

     * @return Java type: org.cyclos.model.system.configurations.ConfigurationVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/ConfigurationService.html#getDefault()
     */
    public function getDefault() {
        return $this->run('getDefault', array());
    }
    
    /**

     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/ConfigurationService.html#list()
     */
    public function _list() {
        return $this->run('list', array());
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/ConfigurationService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/ConfigurationService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/ConfigurationService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/ConfigurationService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
}