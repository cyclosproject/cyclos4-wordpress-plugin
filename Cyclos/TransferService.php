<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransferService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class TransferService extends Service {

    function __construct() {
        parent::__construct('transferService');
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.banking.transfers.TransferData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransferService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.banking.transfers.TransferVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransferService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param transactionNumber Java type: java.lang.String
     * @return Java type: org.cyclos.model.banking.transfers.TransferVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransferService.html#loadByTransactionNumber(java.lang.String)
     */
    public function loadByTransactionNumber($transactionNumber) {
        return $this->run('loadByTransactionNumber', array($transactionNumber));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/TransferService.html#printTransfer(java.lang.Long)
     */
    public function printTransfer($id) {
        return $this->run('printTransfer', array($id));
    }
    
}