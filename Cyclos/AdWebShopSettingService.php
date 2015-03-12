<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdWebShopSettingService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class AdWebShopSettingService extends Service {

    function __construct() {
        parent::__construct('adWebShopSettingService');
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.marketplace.webshopsettings.AdWebShopSettingDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdWebShopSettingService.html#getSetting(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getSetting($locator) {
        return $this->run('getSetting', array($locator));
    }
    
    /**
     * @param dto Java type: org.cyclos.model.marketplace.webshopsettings.AdWebShopSettingDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdWebShopSettingService.html#save(org.cyclos.model.marketplace.webshopsettings.AdWebShopSettingDTO)
     */
    public function save($dto) {
        $this->run('save', array($dto));
    }
    
}