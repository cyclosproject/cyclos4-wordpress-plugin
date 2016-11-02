<?php namespace Cyclos;

/**
 * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/LocalizationService.html
 * 
 * Generated with Cyclos 4.7
 * 
 * WARNING: The API is subject to change between revision versions
 * (for example, 4.5 to 4.6).
 */
class LocalizationService extends Service {

    function __construct() {
        parent::__construct('localizationService');
    }
    
    /**

     * @return Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/LocalizationService.html#listCountries()
     */
    public function listCountries() {
        return $this->run('listCountries', array());
    }
    
    /**

     * @return Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/LocalizationService.html#listTimeZones()
     */
    public function listTimeZones() {
        return $this->run('listTimeZones', array());
    }
    
    /**
     * @param countryCode Java type: java.lang.String
     * @return Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/LocalizationService.html#listTimeZonesByCountry(java.lang.String)
     */
    public function listTimeZonesByCountry($countryCode) {
        return $this->run('listTimeZonesByCountry', array($countryCode));
    }
    
}