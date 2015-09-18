<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserValidationService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class UserValidationService extends Service {

    function __construct() {
        parent::__construct('userValidationService');
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserValidationService.html#manuallyValidateEmailChange(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function manuallyValidateEmailChange($locator) {
        $this->run('manuallyValidateEmailChange', array($locator));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.users.users.UserValidationData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserValidationService.html#manuallyValidateRegistration(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function manuallyValidateRegistration($locator) {
        return $this->run('manuallyValidateRegistration', array($locator));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserValidationService.html#resendEmailChangeMail(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function resendEmailChangeMail($locator) {
        $this->run('resendEmailChangeMail', array($locator));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserValidationService.html#resendRegistrationValidationMail(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function resendRegistrationValidationMail($locator) {
        $this->run('resendRegistrationValidationMail', array($locator));
    }
    
    /**
     * @param validationKey Java type: java.lang.String
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserValidationService.html#validateEmailChange(java.lang.String)
     */
    public function validateEmailChange($validationKey) {
        return $this->run('validateEmailChange', array($validationKey));
    }
    
    /**
     * @param validationKey Java type: java.lang.String
     * @return Java type: org.cyclos.model.users.users.UserValidationData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/users/UserValidationService.html#validateRegistration(java.lang.String)
     */
    public function validateRegistration($validationKey) {
        return $this->run('validateRegistration', array($validationKey));
    }
    
}