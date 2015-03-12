<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/LocalizationService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class LocalizationService extends Service {

    function __construct() {
        parent::__construct('localizationService');
    }
    
    /**

     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/LocalizationService.html#listTimeZones()
     */
    public function listTimeZones() {
        return $this->run('listTimeZones', array());
    }
    
    /**
     * @param countryCode Java type: java.lang.String
     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/LocalizationService.html#listTimeZonesByCountry(java.lang.String)
     */
    public function listTimeZonesByCountry($countryCode) {
        return $this->run('listTimeZonesByCountry', array($countryCode));
    }
    
}