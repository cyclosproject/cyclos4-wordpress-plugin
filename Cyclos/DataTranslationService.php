<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DataTranslationService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class DataTranslationService extends Service {

    function __construct() {
        parent::__construct('dataTranslationService');
    }
    
    /**
     * @param languageId Java type: java.lang.Long     * @param type Java type: org.cyclos.model.contentmanagement.translations.DataTranslationType
     * @return Java type: org.cyclos.model.contentmanagement.translations.DataTranslationData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DataTranslationService.html#getData(java.lang.Long,%20org.cyclos.model.contentmanagement.translations.DataTranslationType)
     */
    public function getData($languageId, $type) {
        return $this->run('getData', array($languageId, $type));
    }
    
    /**
     * @param languageId Java type: java.lang.Long
     * @return Java type: org.cyclos.model.contentmanagement.translations.DataTranslationTypeData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DataTranslationService.html#getTypeData(java.lang.Long)
     */
    public function getTypeData($languageId) {
        return $this->run('getTypeData', array($languageId));
    }
    
    /**
     * @param languageId Java type: java.lang.Long     * @param translations Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DataTranslationService.html#translate(java.lang.Long,%20java.util.List)
     */
    public function translate($languageId, $translations) {
        $this->run('translate', array($languageId, $translations));
    }
    
}