<?php namespace Cyclos;

/**
 * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/ProductsGroupService.html
 * 
 * Generated with Cyclos 4.7
 * 
 * WARNING: The API is subject to change between revision versions
 * (for example, 4.5 to 4.6).
 */
class ProductsGroupService extends Service {

    function __construct() {
        parent::__construct('productsGroupService');
    }
    
    /**
     * @param product Java type: org.cyclos.model.users.products.ProductVO     * @param owner Java type: VO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/ProductsGroupService.html#assign(org.cyclos.model.users.products.ProductVO,%20VO)
     */
    public function assign($product, $owner) {
        $this->run('assign', array($product, $owner));
    }
    
    /**
     * @param owner Java type: VO     * @param channel Java type: org.cyclos.model.access.channels.ChannelVO     * @param principalType Java type: org.cyclos.model.access.principaltypes.PrincipalTypeVO
     * @return Java type: org.cyclos.model.users.products.ActiveProductsData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/ProductsGroupService.html#getActiveProducts(VO,%20org.cyclos.model.access.channels.ChannelVO,%20org.cyclos.model.access.principaltypes.PrincipalTypeVO)
     */
    public function getActiveProducts($owner, $channel, $principalType) {
        return $this->run('getActiveProducts', array($owner, $channel, $principalType));
    }
    
    /**
     * @param product Java type: org.cyclos.model.users.products.ProductVO     * @param ownerId Java type: VO
     * @return Java type: boolean
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/ProductsGroupService.html#unassign(org.cyclos.model.users.products.ProductVO,%20VO)
     */
    public function unassign($product, $ownerId) {
        return $this->run('unassign', array($product, $ownerId));
    }
    
}