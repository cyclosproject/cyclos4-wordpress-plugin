<?php namespace Cyclos;

/**
 * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/BannerService.html
 * 
 * Generated with Cyclos 4.7
 * 
 * WARNING: The API is subject to change between revision versions
 * (for example, 4.5 to 4.6).
 */
class BannerService extends Service {

    function __construct() {
        parent::__construct('bannerService');
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/BannerService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/BannerService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**
     * @param configuration Java type: org.cyclos.model.system.configurations.ConfigurationVO
     * @return Java type: org.cyclos.model.contentmanagement.contentitems.BannersListData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/BannerService.html#getListData(org.cyclos.model.system.configurations.ConfigurationVO)
     */
    public function getListData($configuration) {
        return $this->run('getListData', array($configuration));
    }
    
    /**
     * @param configuration Java type: org.cyclos.model.system.configurations.ConfigurationVO
     * @return Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/BannerService.html#list(org.cyclos.model.system.configurations.ConfigurationVO)
     */
    public function _list($configuration) {
        return $this->run('list', array($configuration));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/BannerService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long     * @param up Java type: boolean
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/BannerService.html#move(java.lang.Long,%20boolean)
     */
    public function move($id, $up) {
        $this->run('move', array($id, $up));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/BannerService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/BannerService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/contentmanagement/BannerService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
}