<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentRequestService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class PaymentRequestService extends Service {

    function __construct() {
        parent::__construct('paymentRequestService');
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.transactions.AcceptPaymentRequestDTO
     * @return Java type: org.cyclos.model.banking.transactions.PaymentRequestVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentRequestService.html#accept(org.cyclos.model.banking.transactions.AcceptPaymentRequestDTO)
     */
    public function accept($params) {
        return $this->run('accept', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.transactions.PaymentRequestActionDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentRequestService.html#cancel(org.cyclos.model.banking.transactions.PaymentRequestActionDTO)
     */
    public function cancel($params) {
        $this->run('cancel', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.transactions.PaymentRequestActionDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentRequestService.html#deny(org.cyclos.model.banking.transactions.PaymentRequestActionDTO)
     */
    public function deny($params) {
        $this->run('deny', array($params));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.banking.transactions.PaymentRequestData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentRequestService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param payee Java type: org.cyclos.model.banking.accounts.InternalAccountOwner     * @param payer Java type: org.cyclos.model.banking.accounts.InternalAccountOwner
     * @return Java type: org.cyclos.model.banking.transactions.RequestPaymentData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentRequestService.html#getRequestData(org.cyclos.model.banking.accounts.InternalAccountOwner,%20org.cyclos.model.banking.accounts.InternalAccountOwner)
     */
    public function getRequestData($payee, $payer) {
        return $this->run('getRequestData', array($payee, $payer));
    }
    
    /**
     * @param payee Java type: org.cyclos.model.banking.accounts.InternalAccountOwner     * @param payer Java type: org.cyclos.model.banking.accounts.InternalAccountOwner
     * @return Java type: org.cyclos.model.banking.transactions.RequestPaymentPayerData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentRequestService.html#getRequestPaymentPayerData(org.cyclos.model.banking.accounts.InternalAccountOwner,%20org.cyclos.model.banking.accounts.InternalAccountOwner)
     */
    public function getRequestPaymentPayerData($payee, $payer) {
        return $this->run('getRequestPaymentPayerData', array($payee, $payer));
    }
    
    /**
     * @param payee Java type: org.cyclos.model.banking.accounts.InternalAccountOwner     * @param payer Java type: org.cyclos.model.banking.accounts.InternalAccountOwner     * @param paymentTypeId Java type: java.lang.Long
     * @return Java type: org.cyclos.model.banking.transactions.RequestPaymentTypeData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentRequestService.html#getRequestPaymentTypeData(org.cyclos.model.banking.accounts.InternalAccountOwner,%20org.cyclos.model.banking.accounts.InternalAccountOwner,%20java.lang.Long)
     */
    public function getRequestPaymentTypeData($payee, $payer, $paymentTypeId) {
        return $this->run('getRequestPaymentTypeData', array($payee, $payer, $paymentTypeId));
    }
    
    /**

     * @return Java type: org.cyclos.model.banking.accounts.PaymentRequestSearchOverviewData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentRequestService.html#getSearchOverviewData()
     */
    public function getSearchOverviewData() {
        return $this->run('getSearchOverviewData', array());
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.banking.transactions.AcceptPaymentRequestPreviewVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentRequestService.html#previewAccept(java.lang.Long)
     */
    public function previewAccept($id) {
        return $this->run('previewAccept', array($id));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.transactions.AcceptPaymentRequestDTO
     * @return Java type: org.cyclos.model.banking.transactions.PaymentRequestVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentRequestService.html#reschedule(org.cyclos.model.banking.transactions.AcceptPaymentRequestDTO)
     */
    public function reschedule($params) {
        return $this->run('reschedule', array($params));
    }
    
    /**
     * @param query Java type: org.cyclos.model.banking.transactions.PaymentRequestQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentRequestService.html#search(org.cyclos.model.banking.transactions.PaymentRequestQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.transactions.PaymentRequestOverviewQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentRequestService.html#searchOverview(org.cyclos.model.banking.transactions.PaymentRequestOverviewQuery)
     */
    public function searchOverview($params) {
        return $this->run('searchOverview', array($params));
    }
    
    /**
     * @param parameters Java type: org.cyclos.model.banking.transactions.SendPaymentRequestDTO
     * @return Java type: org.cyclos.model.banking.transactions.PaymentRequestVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/PaymentRequestService.html#send(org.cyclos.model.banking.transactions.SendPaymentRequestDTO)
     */
    public function send($parameters) {
        return $this->run('send', array($parameters));
    }
    
}