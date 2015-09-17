<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class PasswordService extends Service {

    function __construct() {
        parent::__construct('passwordService');
    }
    
    /**
     * @param passwordTypeId Java type: java.lang.Long
     * @return Java type: java.lang.String
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#activate(java.lang.Long)
     */
    public function activate($passwordTypeId) {
        return $this->run('activate', array($passwordTypeId));
    }
    
    /**
     * @param params Java type: org.cyclos.model.access.passwords.PasswordActionDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#allowActivation(org.cyclos.model.access.passwords.PasswordActionDTO)
     */
    public function allowActivation($params) {
        $this->run('allowActivation', array($params));
    }
    
    /**
     * @param dto Java type: org.cyclos.model.access.passwords.ChangePasswordDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#change(org.cyclos.model.access.passwords.ChangePasswordDTO)
     */
    public function change($dto) {
        $this->run('change', array($dto));
    }
    
    /**
     * @param dto Java type: org.cyclos.model.access.passwords.ChangeForgottenPasswordDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#changeForgottenPassword(org.cyclos.model.access.passwords.ChangeForgottenPasswordDTO)
     */
    public function changeForgottenPassword($dto) {
        $this->run('changeForgottenPassword', array($dto));
    }
    
    /**
     * @param params Java type: org.cyclos.model.access.passwords.PasswordActionDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#disable(org.cyclos.model.access.passwords.PasswordActionDTO)
     */
    public function disable($params) {
        $this->run('disable', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.access.passwords.PasswordActionDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#enable(org.cyclos.model.access.passwords.PasswordActionDTO)
     */
    public function enable($params) {
        $this->run('enable', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.access.passwords.ForgotPasswordRequestDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#forgotPasswordRequest(org.cyclos.model.access.passwords.ForgotPasswordRequestDTO)
     */
    public function forgotPasswordRequest($params) {
        $this->run('forgotPasswordRequest', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.access.passwords.ChangeGeneratedPasswordDTO
     * @return Java type: java.lang.String
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#generateNew(org.cyclos.model.access.passwords.ChangeGeneratedPasswordDTO)
     */
    public function generateNew($params) {
        return $this->run('generateNew', array($params));
    }
    
    /**
     * @param validationKey Java type: java.lang.String
     * @return Java type: org.cyclos.model.access.passwords.ChangeForgottenPasswordData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#getChangeForgottenPasswordData(java.lang.String)
     */
    public function getChangeForgottenPasswordData($validationKey) {
        return $this->run('getChangeForgottenPasswordData', array($validationKey));
    }
    
    /**

     * @return Java type: org.cyclos.model.access.passwords.ChangePasswordData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#getChangePasswordData()
     */
    public function getChangePasswordData() {
        return $this->run('getChangePasswordData', array());
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.access.passwords.UserPasswordsData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#getData(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getData($locator) {
        return $this->run('getData', array($locator));
    }
    
    /**
     * @param medium Java type: org.cyclos.model.access.passwordtypes.OTPSendMedium
     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#requestNewOTP(org.cyclos.model.access.passwordtypes.OTPSendMedium)
     */
    public function requestNewOTP($medium) {
        return $this->run('requestNewOTP', array($medium));
    }
    
    /**
     * @param params Java type: org.cyclos.model.access.passwords.PasswordActionDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#reset(org.cyclos.model.access.passwords.PasswordActionDTO)
     */
    public function reset($params) {
        $this->run('reset', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.access.passwords.PasswordActionDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#resetAndSend(org.cyclos.model.access.passwords.PasswordActionDTO)
     */
    public function resetAndSend($params) {
        $this->run('resetAndSend', array($params));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#resetSecurityQuestion(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function resetSecurityQuestion($locator) {
        $this->run('resetSecurityQuestion', array($locator));
    }
    
    /**
     * @param params Java type: org.cyclos.model.access.passwords.SetSecurityQuestionDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#setSecurityQuestion(org.cyclos.model.access.passwords.SetSecurityQuestionDTO)
     */
    public function setSecurityQuestion($params) {
        $this->run('setSecurityQuestion', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.access.passwords.PasswordActionDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#unblock(org.cyclos.model.access.passwords.PasswordActionDTO)
     */
    public function unblock($params) {
        $this->run('unblock', array($params));
    }
    
}