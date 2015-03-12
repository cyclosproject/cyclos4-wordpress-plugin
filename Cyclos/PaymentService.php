<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class PaymentService extends Service {

    function __construct() {
        parent::__construct('paymentService');
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.banking.transactions.PaymentData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param parameters Java type: org.cyclos.model.banking.transactions.PerformPaymentDTO
     * @return Java type: org.cyclos.model.banking.transactions.PaymentVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentService.html#perform(org.cyclos.model.banking.transactions.PerformPaymentDTO)
     */
    public function perform($parameters) {
        return $this->run('perform', array($parameters));
    }
    
    /**
     * @param parameters Java type: org.cyclos.model.banking.transactions.PerformPaymentDTO
     * @return Java type: org.cyclos.model.banking.transactions.PaymentPreviewVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentService.html#preview(org.cyclos.model.banking.transactions.PerformPaymentDTO)
     */
    public function preview($parameters) {
        return $this->run('preview', array($parameters));
    }
    
    /**
     * @param parameters Java type: org.cyclos.model.banking.transactions.PerformPaymentDTO
     * @return Java type: org.cyclos.model.banking.transactions.PaymentPreviewVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentService.html#previewReceive(org.cyclos.model.banking.transactions.PerformPaymentDTO)
     */
    public function previewReceive($parameters) {
        return $this->run('previewReceive', array($parameters));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentService.html#print(java.lang.Long)
     */
    public function _print($id) {
        return $this->run('print', array($id));
    }
    
    /**
     * @param parameters Java type: org.cyclos.model.banking.transactions.PerformPaymentDTO
     * @return Java type: org.cyclos.model.banking.transactions.PaymentVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentService.html#receive(org.cyclos.model.banking.transactions.PerformPaymentDTO)
     */
    public function receive($parameters) {
        return $this->run('receive', array($parameters));
    }
    
    /**
     * @param query Java type: org.cyclos.model.banking.transactions.PaymentQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentService.html#search(org.cyclos.model.banking.transactions.PaymentQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
}