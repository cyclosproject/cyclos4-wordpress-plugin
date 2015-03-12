<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransactionService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class TransactionService extends Service {

    function __construct() {
        parent::__construct('transactionService');
    }
    
    /**
     * @param from Java type: org.cyclos.model.banking.accounts.AccountOwner     * @param to Java type: org.cyclos.model.banking.accounts.AccountOwner
     * @return Java type: org.cyclos.model.banking.transactions.PerformPaymentData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getPaymentData(org.cyclos.model.banking.accounts.AccountOwner,%20org.cyclos.model.banking.accounts.AccountOwner)
     */
    public function getPaymentData($from, $to) {
        return $this->run('getPaymentData', array($from, $to));
    }
    
    /**
     * @param from Java type: org.cyclos.model.banking.accounts.AccountOwner     * @param to Java type: org.cyclos.model.banking.accounts.AccountOwner
     * @return Java type: org.cyclos.model.banking.transactions.PerformPaymentToOwnerData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getPaymentToOwnerData(org.cyclos.model.banking.accounts.AccountOwner,%20org.cyclos.model.banking.accounts.AccountOwner)
     */
    public function getPaymentToOwnerData($from, $to) {
        return $this->run('getPaymentToOwnerData', array($from, $to));
    }
    
    /**
     * @param from Java type: org.cyclos.model.banking.accounts.AccountOwner     * @param to Java type: org.cyclos.model.banking.accounts.AccountOwner     * @param paymentTypeId Java type: java.lang.Long
     * @return Java type: org.cyclos.model.banking.transactions.PerformPaymentTypeData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getPaymentTypeData(org.cyclos.model.banking.accounts.AccountOwner,%20org.cyclos.model.banking.accounts.AccountOwner,%20java.lang.Long)
     */
    public function getPaymentTypeData($from, $to, $paymentTypeId) {
        return $this->run('getPaymentTypeData', array($from, $to, $paymentTypeId));
    }
    
    /**

     * @return Java type: org.cyclos.model.banking.transactions.ReceivePaymentData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getReceivePaymentData()
     */
    public function getReceivePaymentData() {
        return $this->run('getReceivePaymentData', array());
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.banking.transactions.ReceivePaymentFromUserData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getReceivePaymentFromUserData(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getReceivePaymentFromUserData($locator) {
        return $this->run('getReceivePaymentFromUserData', array($locator));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO     * @param paymentTypeId Java type: java.lang.Long
     * @return Java type: org.cyclos.model.banking.transactions.PerformPaymentTypeData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getReceivePaymentTypeData(org.cyclos.model.users.users.UserLocatorVO,%20java.lang.Long)
     */
    public function getReceivePaymentTypeData($locator, $paymentTypeId) {
        return $this->run('getReceivePaymentTypeData', array($locator, $paymentTypeId));
    }
    
    /**
     * @param owner Java type: org.cyclos.model.banking.accounts.AccountOwner
     * @return Java type: org.cyclos.model.banking.transactions.TransactionSearchData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getSearchData(org.cyclos.model.banking.accounts.AccountOwner)
     */
    public function getSearchData($owner) {
        return $this->run('getSearchData', array($owner));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.banking.transactions.TransactionVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransactionService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param transactionNumber Java type: java.lang.String
     * @return Java type: org.cyclos.model.banking.transactions.TransactionVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransactionService.html#loadByTransactionNumber(java.lang.String)
     */
    public function loadByTransactionNumber($transactionNumber) {
        return $this->run('loadByTransactionNumber', array($transactionNumber));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransactionService.html#print(java.lang.Long)
     */
    public function _print($id) {
        return $this->run('print', array($id));
    }
    
    /**
     * @param query Java type: org.cyclos.model.banking.transactions.BaseTransactionQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransactionService.html#search(org.cyclos.model.banking.transactions.BaseTransactionQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
}