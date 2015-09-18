<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/ExternalPaymentService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class ExternalPaymentService extends Service {

    function __construct() {
        parent::__construct('externalPaymentService');
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.transactions.ExternalPaymentActionDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/ExternalPaymentService.html#cancel(org.cyclos.model.banking.transactions.ExternalPaymentActionDTO)
     */
    public function cancel($params) {
        $this->run('cancel', array($params));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.banking.transactions.ExternalPaymentData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/ExternalPaymentService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param from Java type: org.cyclos.model.banking.accounts.InternalAccountOwner
     * @return Java type: org.cyclos.model.banking.transactions.PerformExternalPaymentData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/ExternalPaymentService.html#getExternalPaymentData(org.cyclos.model.banking.accounts.InternalAccountOwner)
     */
    public function getExternalPaymentData($from) {
        return $this->run('getExternalPaymentData', array($from));
    }
    
    /**
     * @param from Java type: org.cyclos.model.banking.accounts.InternalAccountOwner     * @param paymentTypeId Java type: java.lang.Long
     * @return Java type: org.cyclos.model.banking.transactions.PerformExternalPaymentTypeData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/ExternalPaymentService.html#getExternalPaymentTypeData(org.cyclos.model.banking.accounts.InternalAccountOwner,%20java.lang.Long)
     */
    public function getExternalPaymentTypeData($from, $paymentTypeId) {
        return $this->run('getExternalPaymentTypeData', array($from, $paymentTypeId));
    }
    
    /**
     * @param parameters Java type: org.cyclos.model.banking.transactions.PerformExternalPaymentDTO
     * @return Java type: org.cyclos.model.banking.transactions.ExternalPaymentVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/ExternalPaymentService.html#perform(org.cyclos.model.banking.transactions.PerformExternalPaymentDTO)
     */
    public function perform($parameters) {
        return $this->run('perform', array($parameters));
    }
    
    /**
     * @param parameters Java type: org.cyclos.model.banking.transactions.PerformExternalPaymentDTO
     * @return Java type: org.cyclos.model.banking.transactions.ExternalPaymentPreviewVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/ExternalPaymentService.html#preview(org.cyclos.model.banking.transactions.PerformExternalPaymentDTO)
     */
    public function preview($parameters) {
        return $this->run('preview', array($parameters));
    }
    
    /**
     * @param query Java type: org.cyclos.model.banking.transactions.ExternalPaymentQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/ExternalPaymentService.html#search(org.cyclos.model.banking.transactions.ExternalPaymentQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
}