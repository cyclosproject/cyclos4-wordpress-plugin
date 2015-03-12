<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class OrderService extends Service {

    function __construct() {
        parent::__construct('orderService');
    }
    
    /**
     * @param params Java type: org.cyclos.model.marketplace.webshoporders.AcceptOrderParams
     * @return Java type: org.cyclos.model.marketplace.webshoporders.OrderStatus
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#accept(org.cyclos.model.marketplace.webshoporders.AcceptOrderParams)
     */
    public function accept($params) {
        return $this->run('accept', array($params));
    }
    
    /**
     * @param adId Java type: java.lang.Long     * @param quantity Java type: java.math.BigDecimal
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#addToCart(java.lang.Long,%20java.math.BigDecimal)
     */
    public function addToCart($adId, $quantity) {
        $this->run('addToCart', array($adId, $quantity));
    }
    
    /**
     * @param sellerId Java type: java.lang.Long     * @param currencyId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#checkCartStock(java.lang.Long,%20java.lang.Long)
     */
    public function checkCartStock($sellerId, $currencyId) {
        $this->run('checkCartStock', array($sellerId, $currencyId));
    }
    
    /**
     * @param dto Java type: org.cyclos.model.marketplace.webshoporders.ShoppingCartCheckoutParams
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#checkout(org.cyclos.model.marketplace.webshoporders.ShoppingCartCheckoutParams)
     */
    public function checkout($dto) {
        return $this->run('checkout', array($dto));
    }
    
    /**

     * @return Java type: int
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#countCartItems()
     */
    public function countCartItems() {
        return $this->run('countCartItems', array());
    }
    
    /**
     * @param sellerId Java type: java.lang.Long     * @param currencyId Java type: java.lang.Long
     * @return Java type: org.cyclos.model.marketplace.webshoporders.OrderData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#getCheckoutData(java.lang.Long,%20java.lang.Long)
     */
    public function getCheckoutData($sellerId, $currencyId) {
        return $this->run('getCheckoutData', array($sellerId, $currencyId));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**

     * @return Java type: org.cyclos.model.marketplace.webshoporders.ShoppingCartDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#getMyCart()
     */
    public function getMyCart() {
        return $this->run('getMyCart', array());
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.marketplace.webshoporders.UserSalesData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#getUserSalesData(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getUserSalesData($locator) {
        return $this->run('getUserSalesData', array($locator));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param adId Java type: java.lang.Long     * @param quantity Java type: java.math.BigDecimal
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#modifyQuantityOnCart(java.lang.Long,%20java.math.BigDecimal)
     */
    public function modifyQuantityOnCart($adId, $quantity) {
        $this->run('modifyQuantityOnCart', array($adId, $quantity));
    }
    
    /**
     * @param params Java type: org.cyclos.model.marketplace.webshoporders.RejectOrderParams
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#reject(org.cyclos.model.marketplace.webshoporders.RejectOrderParams)
     */
    public function reject($params) {
        $this->run('reject', array($params));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param sellerId Java type: java.lang.Long     * @param currencyId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#removeCart(java.lang.Long,%20java.lang.Long)
     */
    public function removeCart($sellerId, $currencyId) {
        $this->run('removeCart', array($sellerId, $currencyId));
    }
    
    /**
     * @param adId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#removeCartItem(java.lang.Long)
     */
    public function removeCartItem($adId) {
        $this->run('removeCartItem', array($adId));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
    /**
     * @param query Java type: org.cyclos.model.marketplace.webshoporders.OrderQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#search(org.cyclos.model.marketplace.webshoporders.OrderQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
    /**
     * @param params Java type: org.cyclos.model.marketplace.webshoporders.SetDeliveryMethodParams
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#setDeliveryMethod(org.cyclos.model.marketplace.webshoporders.SetDeliveryMethodParams)
     */
    public function setDeliveryMethod($params) {
        $this->run('setDeliveryMethod', array($params));
    }
    
    /**
     * @param order Java type: org.cyclos.model.marketplace.webshoporders.OrderDTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/OrderService.html#submitToBuyer(org.cyclos.model.marketplace.webshoporders.OrderDTO)
     */
    public function submitToBuyer($order) {
        return $this->run('submitToBuyer', array($order));
    }
    
}