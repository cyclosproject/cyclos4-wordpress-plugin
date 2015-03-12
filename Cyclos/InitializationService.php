<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/InitializationService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class InitializationService extends Service {

    function __construct() {
        parent::__construct('initializationService');
    }
    
    /**

     * @return Java type: org.cyclos.model.access.BasicInitializationData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/InitializationService.html#getBasicInitializationData()
     */
    public function getBasicInitializationData() {
        return $this->run('getBasicInitializationData', array());
    }
    
    /**

     * @return Java type: org.cyclos.model.access.BasicInitializationWithContentData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/InitializationService.html#getBasicInitializationWithContentData()
     */
    public function getBasicInitializationWithContentData() {
        return $this->run('getBasicInitializationWithContentData', array());
    }
    
    /**

     * @return Java type: org.cyclos.model.users.users.HomeData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/InitializationService.html#getHomeData()
     */
    public function getHomeData() {
        return $this->run('getHomeData', array());
    }
    
    /**

     * @return Java type: org.cyclos.model.access.InitializationData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/InitializationService.html#getInitializationData()
     */
    public function getInitializationData() {
        return $this->run('getInitializationData', array());
    }
    
    /**
     * @param guestVersionData Java type: org.cyclos.model.access.MobileGuestDTO
     * @return Java type: org.cyclos.model.access.MobileGuestInitializationData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/InitializationService.html#getMobileGuestInitializationData(org.cyclos.model.access.MobileGuestDTO)
     */
    public function getMobileGuestInitializationData($guestVersionData) {
        return $this->run('getMobileGuestInitializationData', array($guestVersionData));
    }
    
    /**
     * @param dto Java type: org.cyclos.model.access.MobileUserDTO
     * @return Java type: org.cyclos.model.access.BaseMobileUserInitializationData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/InitializationService.html#getMobileUserInitializationData(org.cyclos.model.access.MobileUserDTO)
     */
    public function getMobileUserInitializationData($dto) {
        return $this->run('getMobileUserInitializationData', array($dto));
    }
    
    /**

     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/InitializationService.html#ping()
     */
    public function ping() {
        $this->run('ping', array());
    }
    
}