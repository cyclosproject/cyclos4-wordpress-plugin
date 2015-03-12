<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/SetupService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class SetupService extends Service {

    function __construct() {
        parent::__construct('setupService');
    }
    
    /**
     * @param params Java type: org.cyclos.model.system.setup.SetupDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/system/SetupService.html#setup(org.cyclos.model.system.setup.SetupDTO)
     */
    public function setup($params) {
        $this->run('setup', array($params));
    }
    
}