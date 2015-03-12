<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/ProductsUserService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class ProductsUserService extends Service {

    function __construct() {
        parent::__construct('productsUserService');
    }
    
    /**
     * @param productId Java type: java.lang.Long     * @param ownerId Java type: java.lang.Long
     * @return Java type: org.cyclos.model.users.products.ActiveProductsData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/ProductsUserService.html#assign(java.lang.Long,%20java.lang.Long)
     */
    public function assign($productId, $ownerId) {
        return $this->run('assign', array($productId, $ownerId));
    }
    
    /**
     * @param ownerId Java type: java.lang.Long
     * @return Java type: org.cyclos.model.users.products.ActiveProductsData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/ProductsUserService.html#getActiveProducts(java.lang.Long)
     */
    public function getActiveProducts($ownerId) {
        return $this->run('getActiveProducts', array($ownerId));
    }
    
    /**
     * @param productId Java type: java.lang.Long     * @param ownerId Java type: java.lang.Long
     * @return Java type: org.cyclos.model.users.products.ActiveProductsData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/ProductsUserService.html#unassign(java.lang.Long,%20java.lang.Long)
     */
    public function unassign($productId, $ownerId) {
        return $this->run('unassign', array($productId, $ownerId));
    }
    
}