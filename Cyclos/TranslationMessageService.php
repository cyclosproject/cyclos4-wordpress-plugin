<?php namespace Cyclos;

/**
 * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/TranslationMessageService.html
 * 
 * Generated with Cyclos 4.7
 * 
 * WARNING: The API is subject to change between revision versions
 * (for example, 4.5 to 4.6).
 */
class TranslationMessageService extends Service {

    function __construct() {
        parent::__construct('translationMessageService');
    }
    
    /**
     * @param language Java type: org.cyclos.model.system.languages.LanguageVO
     * @return Java type: org.cyclos.model.contentmanagement.translations.ApplicationTranslationData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/TranslationMessageService.html#getApplicationTranslationData(org.cyclos.model.system.languages.LanguageVO)
     */
    public function getApplicationTranslationData($language) {
        return $this->run('getApplicationTranslationData', array($language));
    }
    
    /**
     * @param language Java type: org.cyclos.model.system.languages.LanguageVO
     * @return Java type: java.util.Properties
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/TranslationMessageService.html#getCustomizedKeysPerLanguage(org.cyclos.model.system.languages.LanguageVO)
     */
    public function getCustomizedKeysPerLanguage($language) {
        return $this->run('getCustomizedKeysPerLanguage', array($language));
    }
    
    /**
     * @param language Java type: org.cyclos.model.system.languages.LanguageVO     * @param key Java type: java.lang.String
     * @return Java type: org.cyclos.model.contentmanagement.translations.TranslationKeyData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/TranslationMessageService.html#getTranslationKeyData(org.cyclos.model.system.languages.LanguageVO,%20java.lang.String)
     */
    public function getTranslationKeyData($language, $key) {
        return $this->run('getTranslationKeyData', array($language, $key));
    }
    
    /**

     * @return Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/TranslationMessageService.html#listTranslatableLanguages()
     */
    public function listTranslatableLanguages() {
        return $this->run('listTranslatableLanguages', array());
    }
    
    /**
     * @param language Java type: org.cyclos.model.system.languages.LanguageVO     * @param key Java type: java.lang.String
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/TranslationMessageService.html#restoreOriginalTranslation(org.cyclos.model.system.languages.LanguageVO,%20java.lang.String)
     */
    public function restoreOriginalTranslation($language, $key) {
        $this->run('restoreOriginalTranslation', array($language, $key));
    }
    
    /**
     * @param language Java type: org.cyclos.model.system.languages.LanguageVO     * @param key Java type: java.lang.String     * @param value Java type: java.lang.String
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/TranslationMessageService.html#saveKey(org.cyclos.model.system.languages.LanguageVO,%20java.lang.String,%20java.lang.String)
     */
    public function saveKey($language, $key, $value) {
        $this->run('saveKey', array($language, $key, $value));
    }
    
    /**
     * @param params Java type: org.cyclos.model.contentmanagement.translations.TranslationQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/TranslationMessageService.html#searchKeys(org.cyclos.model.contentmanagement.translations.TranslationQuery)
     */
    public function searchKeys($params) {
        return $this->run('searchKeys', array($params));
    }
    
    /**
     * @param keys Java type: java.util.Properties     * @param language Java type: org.cyclos.model.system.languages.LanguageVO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/TranslationMessageService.html#setCustomizedKeysPerLanguage(java.util.Properties,%20org.cyclos.model.system.languages.LanguageVO)
     */
    public function setCustomizedKeysPerLanguage($keys, $language) {
        $this->run('setCustomizedKeysPerLanguage', array($keys, $language));
    }
    
}