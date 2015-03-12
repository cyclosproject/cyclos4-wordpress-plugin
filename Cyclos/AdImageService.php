<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdImageService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class AdImageService extends Service {

    function __construct() {
        parent::__construct('adImageService');
    }
    
    /**
     * @param ownerId Java type: java.lang.Long
     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdImageService.html#list(java.lang.Long)
     */
    public function _list($ownerId) {
        return $this->run('list', array($ownerId));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: VO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdImageService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param key Java type: java.lang.String
     * @return Java type: VO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdImageService.html#loadByKey(java.lang.String)
     */
    public function loadByKey($key) {
        return $this->run('loadByKey', array($key));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdImageService.html#readContent(java.lang.Long)
     */
    public function readContent($id) {
        return $this->run('readContent', array($id));
    }
    
    /**
     * @param key Java type: java.lang.String
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdImageService.html#readContentByKey(java.lang.String)
     */
    public function readContentByKey($key) {
        return $this->run('readContentByKey', array($key));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdImageService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param param Java type: NP     * @param name Java type: java.lang.String     * @param contents Java type: org.cyclos.server.utils.SerializableInputStream     * @param contentType Java type: java.lang.String
     * @return Java type: VO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdImageService.html#save(NP,%20java.lang.String,%20org.cyclos.server.utils.SerializableInputStream,%20java.lang.String)
     */
    public function save($param, $name, $contents, $contentType) {
        return $this->run('save', array($param, $name, $contents, $contentType));
    }
    
    /**
     * @param id Java type: java.lang.Long     * @param name Java type: java.lang.String
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdImageService.html#saveName(java.lang.Long,%20java.lang.String)
     */
    public function saveName($id, $name) {
        $this->run('saveName', array($id, $name));
    }
    
    /**
     * @param ownerId Java type: java.lang.Long     * @param imageIds Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdImageService.html#saveOrder(java.lang.Long,%20java.util.List)
     */
    public function saveOrder($ownerId, $imageIds) {
        $this->run('saveOrder', array($ownerId, $imageIds));
    }
    
}