<?php namespace Cyclos;

/**
 * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/marketplace/AdWebShopSettingService.html
 * 
 * Generated with Cyclos 4.7
 * 
 * WARNING: The API is subject to change between revision versions
 * (for example, 4.5 to 4.6).
 */
class AdWebShopSettingService extends Service {

    function __construct() {
        parent::__construct('adWebShopSettingService');
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.marketplace.webshopsettings.AdWebShopSettingDTO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/marketplace/AdWebShopSettingService.html#getSetting(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getSetting($locator) {
        return $this->run('getSetting', array($locator));
    }
    
    /**
     * @param dto Java type: org.cyclos.model.marketplace.webshopsettings.AdWebShopSettingDTO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/marketplace/AdWebShopSettingService.html#save(org.cyclos.model.marketplace.webshopsettings.AdWebShopSettingDTO)
     */
    public function save($dto) {
        $this->run('save', array($dto));
    }
    
}