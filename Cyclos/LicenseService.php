<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/LicenseService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class LicenseService extends Service {

    function __construct() {
        parent::__construct('licenseService');
    }
    
    /**

     * @return Java type: org.cyclos.model.system.licensing.BasicLicenseVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/LicenseService.html#getBasicLicense()
     */
    public function getBasicLicense() {
        return $this->run('getBasicLicense', array());
    }
    
    /**

     * @return Java type: org.cyclos.model.system.licensing.LicenseVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/LicenseService.html#getLicense()
     */
    public function getLicense() {
        return $this->run('getLicense', array());
    }
    
    /**
     * @param in Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/LicenseService.html#offlineUpdate(org.cyclos.server.utils.SerializableInputStream)
     */
    public function offlineUpdate($in) {
        $this->run('offlineUpdate', array($in));
    }
    
    /**

     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/LicenseService.html#onlineUpdate()
     */
    public function onlineUpdate() {
        $this->run('onlineUpdate', array());
    }
    
}