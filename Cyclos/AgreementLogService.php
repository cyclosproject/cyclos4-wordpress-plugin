<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/AgreementLogService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class AgreementLogService extends Service {

    function __construct() {
        parent::__construct('agreementLogService');
    }
    
    /**
     * @param ids Java type: java.util.Set
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/AgreementLogService.html#accept(java.util.Set)
     */
    public function accept($ids) {
        $this->run('accept', array($ids));
    }
    
    /**

     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/AgreementLogService.html#getPendingAgreements()
     */
    public function getPendingAgreements() {
        return $this->run('getPendingAgreements', array());
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/AgreementLogService.html#list(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function _list($locator) {
        return $this->run('list', array($locator));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.access.agreementlogs.AgreementLogVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/AgreementLogService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
}