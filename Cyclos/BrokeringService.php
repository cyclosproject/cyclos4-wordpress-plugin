<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/BrokeringService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class BrokeringService extends Service {

    function __construct() {
        parent::__construct('brokeringService');
    }
    
    /**
     * @param userLocator Java type: org.cyclos.model.users.users.UserLocatorVO     * @param brokerLocator Java type: org.cyclos.model.users.users.UserLocatorVO     * @param mainBroker Java type: boolean
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/BrokeringService.html#addBroker(org.cyclos.model.users.users.UserLocatorVO,%20org.cyclos.model.users.users.UserLocatorVO,%20boolean)
     */
    public function addBroker($userLocator, $brokerLocator, $mainBroker) {
        return $this->run('addBroker', array($userLocator, $brokerLocator, $mainBroker));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.users.brokering.AddBrokerData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/BrokeringService.html#getAddBrokerData(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getAddBrokerData($locator) {
        return $this->run('getAddBrokerData', array($locator));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/BrokeringService.html#getBrokeringLogs(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getBrokeringLogs($locator) {
        return $this->run('getBrokeringLogs', array($locator));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.users.brokering.BrokeringData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/BrokeringService.html#getData(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getData($locator) {
        return $this->run('getData', array($locator));
    }
    
    /**
     * @param userLocator Java type: org.cyclos.model.users.users.UserLocatorVO     * @param brokerLocator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/BrokeringService.html#removeBroker(org.cyclos.model.users.users.UserLocatorVO,%20org.cyclos.model.users.users.UserLocatorVO)
     */
    public function removeBroker($userLocator, $brokerLocator) {
        $this->run('removeBroker', array($userLocator, $brokerLocator));
    }
    
    /**
     * @param userLocator Java type: org.cyclos.model.users.users.UserLocatorVO     * @param brokerLocator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/BrokeringService.html#setMainBroker(org.cyclos.model.users.users.UserLocatorVO,%20org.cyclos.model.users.users.UserLocatorVO)
     */
    public function setMainBroker($userLocator, $brokerLocator) {
        $this->run('setMainBroker', array($userLocator, $brokerLocator));
    }
    
}