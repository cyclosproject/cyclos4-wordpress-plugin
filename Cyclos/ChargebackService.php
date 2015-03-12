<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/ChargebackService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class ChargebackService extends Service {

    function __construct() {
        parent::__construct('chargebackService');
    }
    
    /**
     * @param params Java type: org.cyclos.model.banking.transfers.TransferActionDTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/banking/ChargebackService.html#chargeback(org.cyclos.model.banking.transfers.TransferActionDTO)
     */
    public function chargeback($params) {
        return $this->run('chargeback', array($params));
    }
    
}