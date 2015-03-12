<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeImageService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class ThemeImageService extends Service {

    function __construct() {
        parent::__construct('themeImageService');
    }
    
    /**
     * @param themeId Java type: java.lang.Long     * @param type Java type: org.cyclos.model.contentmanagement.themes.ThemeImageType     * @param name Java type: java.lang.String
     * @return Java type: org.cyclos.model.contentmanagement.themes.ThemeImageVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeImageService.html#getImageVO(java.lang.Long,%20org.cyclos.model.contentmanagement.themes.ThemeImageType,%20java.lang.String)
     */
    public function getImageVO($themeId, $type, $name) {
        return $this->run('getImageVO', array($themeId, $type, $name));
    }
    
    /**
     * @param themeId Java type: java.lang.Long     * @param types Java type: java.util.Set
     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeImageService.html#list(java.lang.Long,%20java.util.Set)
     */
    public function _list($themeId, $types) {
        return $this->run('list', array($themeId, $types));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: VO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeImageService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param key Java type: java.lang.String
     * @return Java type: VO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeImageService.html#loadByKey(java.lang.String)
     */
    public function loadByKey($key) {
        return $this->run('loadByKey', array($key));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeImageService.html#readContent(java.lang.Long)
     */
    public function readContent($id) {
        return $this->run('readContent', array($id));
    }
    
    /**
     * @param key Java type: java.lang.String
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeImageService.html#readContentByKey(java.lang.String)
     */
    public function readContentByKey($key) {
        return $this->run('readContentByKey', array($key));
    }
    
    /**
     * @param themeId Java type: java.lang.Long     * @param type Java type: org.cyclos.model.contentmanagement.themes.ThemeImageType     * @param name Java type: java.lang.String     * @param colors Java type: java.util.List
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeImageService.html#readImageContent(java.lang.Long,%20org.cyclos.model.contentmanagement.themes.ThemeImageType,%20java.lang.String,%20java.util.List)
     */
    public function readImageContent($themeId, $type, $name, $colors) {
        return $this->run('readImageContent', array($themeId, $type, $name, $colors));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeImageService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param param Java type: NP     * @param name Java type: java.lang.String     * @param contents Java type: org.cyclos.server.utils.SerializableInputStream     * @param contentType Java type: java.lang.String
     * @return Java type: VO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeImageService.html#save(NP,%20java.lang.String,%20org.cyclos.server.utils.SerializableInputStream,%20java.lang.String)
     */
    public function save($param, $name, $contents, $contentType) {
        return $this->run('save', array($param, $name, $contents, $contentType));
    }
    
    /**
     * @param id Java type: java.lang.Long     * @param name Java type: java.lang.String
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/ThemeImageService.html#saveName(java.lang.Long,%20java.lang.String)
     */
    public function saveName($id, $name) {
        $this->run('saveName', array($id, $name));
    }
    
}