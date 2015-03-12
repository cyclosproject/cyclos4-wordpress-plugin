<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/SmsOperationConfigurationService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class SmsOperationConfigurationService extends Service {

    function __construct() {
        parent::__construct('smsOperationConfigurationService');
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/SmsOperationConfigurationService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/SmsOperationConfigurationService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**
     * @param smsChannelConfigurationId Java type: java.lang.Long
     * @return Java type: org.cyclos.model.system.smsoperationconfigurations.SmsTextsListData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/SmsOperationConfigurationService.html#getSmsTextsListData(java.lang.Long)
     */
    public function getSmsTextsListData($smsChannelConfigurationId) {
        return $this->run('getSmsTextsListData', array($smsChannelConfigurationId));
    }
    
    /**
     * @param smsChannelConfigurationId Java type: java.lang.Long
     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/SmsOperationConfigurationService.html#listSmsTexts(java.lang.Long)
     */
    public function listSmsTexts($smsChannelConfigurationId) {
        return $this->run('listSmsTexts', array($smsChannelConfigurationId));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/SmsOperationConfigurationService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/SmsOperationConfigurationService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/SmsOperationConfigurationService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/SmsOperationConfigurationService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
}