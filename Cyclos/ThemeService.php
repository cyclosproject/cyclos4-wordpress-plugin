<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class ThemeService extends Service {

    function __construct() {
        parent::__construct('themeService');
    }
    
    /**
     * @param configurationId Java type: java.lang.Long     * @param usersThemeId Java type: java.lang.Long     * @param guestsThemeId Java type: java.lang.Long     * @param mobileThemeId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeService.html#applyThemes(java.lang.Long,%20java.lang.Long,%20java.lang.Long,%20java.lang.Long)
     */
    public function applyThemes($configurationId, $usersThemeId, $guestsThemeId, $mobileThemeId) {
        $this->run('applyThemes', array($configurationId, $usersThemeId, $guestsThemeId, $mobileThemeId));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeService.html#export(java.lang.Long)
     */
    public function export($id) {
        return $this->run('export', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: java.lang.String
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeService.html#getCSS(java.lang.Long)
     */
    public function getCSS($id) {
        return $this->run('getCSS', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**
     * @param configurationId Java type: java.lang.Long
     * @return Java type: org.cyclos.model.contentmanagement.themes.ThemesListData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeService.html#getListData(java.lang.Long)
     */
    public function getListData($configurationId) {
        return $this->run('getListData', array($configurationId));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.contentmanagement.themes.ThemeVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeService.html#getVO(java.lang.Long)
     */
    public function getVO($id) {
        return $this->run('getVO', array($id));
    }
    
    /**
     * @param configurationId Java type: java.lang.Long     * @param importedFromFile Java type: java.lang.String     * @param in Java type: org.cyclos.server.utils.SerializableInputStream
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeService.html#importNew(java.lang.Long,%20java.lang.String,%20org.cyclos.server.utils.SerializableInputStream)
     */
    public function importNew($configurationId, $importedFromFile, $in) {
        return $this->run('importNew', array($configurationId, $importedFromFile, $in));
    }
    
    /**
     * @param configurationId Java type: java.lang.Long
     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeService.html#list(java.lang.Long)
     */
    public function _list($configurationId) {
        return $this->run('list', array($configurationId));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
}