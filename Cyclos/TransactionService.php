<?php namespace Cyclos;

/**
 * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionService.html
 * 
 * Generated with Cyclos 4.7
 * 
 * WARNING: The API is subject to change between revision versions
 * (for example, 4.5 to 4.6).
 */
class TransactionService extends Service {

    function __construct() {
        parent::__construct('transactionService');
    }
    
    /**
     * @param vo Java type: org.cyclos.model.banking.transactions.TransactionVO
     * @return Java type: org.cyclos.model.banking.transactions.TransactionData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getData(org.cyclos.model.banking.transactions.TransactionVO)
     */
    public function getData($vo) {
        return $this->run('getData', array($vo));
    }
    
    /**
     * @param query Java type: org.cyclos.model.banking.transactions.MaturityTableQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getMaturityTable(org.cyclos.model.banking.transactions.MaturityTableQuery)
     */
    public function getMaturityTable($query) {
        return $this->run('getMaturityTable', array($query));
    }
    
    /**
     * @param from Java type: org.cyclos.model.banking.accounts.InternalAccountOwner     * @param to Java type: org.cyclos.model.banking.accounts.InternalAccountOwner
     * @return Java type: org.cyclos.model.banking.transactions.PerformPaymentData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getPaymentData(org.cyclos.model.banking.accounts.InternalAccountOwner,%20org.cyclos.model.banking.accounts.InternalAccountOwner)
     */
    public function getPaymentData($from, $to) {
        return $this->run('getPaymentData', array($from, $to));
    }
    
    /**
     * @param from Java type: org.cyclos.model.banking.accounts.InternalAccountOwner     * @param to Java type: org.cyclos.model.banking.accounts.InternalAccountOwner
     * @return Java type: org.cyclos.model.banking.transactions.PerformPaymentToOwnerData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getPaymentToOwnerData(org.cyclos.model.banking.accounts.InternalAccountOwner,%20org.cyclos.model.banking.accounts.InternalAccountOwner)
     */
    public function getPaymentToOwnerData($from, $to) {
        return $this->run('getPaymentToOwnerData', array($from, $to));
    }
    
    /**
     * @param from Java type: org.cyclos.model.banking.accounts.InternalAccountOwner     * @param to Java type: org.cyclos.model.banking.accounts.InternalAccountOwner     * @param transferType Java type: org.cyclos.model.banking.transfertypes.TransferTypeVO
     * @return Java type: org.cyclos.model.banking.transactions.PerformPaymentTypeData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getPaymentTypeData(org.cyclos.model.banking.accounts.InternalAccountOwner,%20org.cyclos.model.banking.accounts.InternalAccountOwner,%20org.cyclos.model.banking.transfertypes.TransferTypeVO)
     */
    public function getPaymentTypeData($from, $to, $transferType) {
        return $this->run('getPaymentTypeData', array($from, $to, $transferType));
    }
    
    /**

     * @return Java type: org.cyclos.model.banking.transactions.ReceivePaymentData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getReceivePaymentData()
     */
    public function getReceivePaymentData() {
        return $this->run('getReceivePaymentData', array());
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.banking.transactions.ReceivePaymentFromUserData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getReceivePaymentFromUserData(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getReceivePaymentFromUserData($locator) {
        return $this->run('getReceivePaymentFromUserData', array($locator));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO     * @param transferType Java type: org.cyclos.model.banking.transfertypes.TransferTypeVO
     * @return Java type: org.cyclos.model.banking.transactions.PerformPaymentTypeData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getReceivePaymentTypeData(org.cyclos.model.users.users.UserLocatorVO,%20org.cyclos.model.banking.transfertypes.TransferTypeVO)
     */
    public function getReceivePaymentTypeData($locator, $transferType) {
        return $this->run('getReceivePaymentTypeData', array($locator, $transferType));
    }
    
    /**
     * @param owner Java type: org.cyclos.model.banking.accounts.InternalAccountOwner
     * @return Java type: org.cyclos.model.banking.transactions.TransactionSearchData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionService.html#getSearchData(org.cyclos.model.banking.accounts.InternalAccountOwner)
     */
    public function getSearchData($owner) {
        return $this->run('getSearchData', array($owner));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.banking.transactions.TransactionVO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param transactionNumber Java type: java.lang.String
     * @return Java type: org.cyclos.model.banking.transactions.TransactionVO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionService.html#loadByTransactionNumber(java.lang.String)
     */
    public function loadByTransactionNumber($transactionNumber) {
        return $this->run('loadByTransactionNumber', array($transactionNumber));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionService.html#print(java.lang.Long)
     */
    public function _print($id) {
        return $this->run('print', array($id));
    }
    
    /**
     * @param parameters Java type: org.cyclos.model.banking.transactions.PerformInternalTransactionDTO     * @param medium Java type: org.cyclos.model.access.passwordtypes.OTPSendMedium
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionService.html#requestNewOTPForReceive(org.cyclos.model.banking.transactions.PerformInternalTransactionDTO,%20org.cyclos.model.access.passwordtypes.OTPSendMedium)
     */
    public function requestNewOTPForReceive($parameters, $medium) {
        $this->run('requestNewOTPForReceive', array($parameters, $medium));
    }
    
    /**
     * @param query Java type: org.cyclos.model.banking.transactions.TransactionQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/banking/TransactionService.html#search(org.cyclos.model.banking.transactions.TransactionQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
}