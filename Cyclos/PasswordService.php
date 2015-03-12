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
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO     * @param passwordTypeId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#allowActivation(org.cyclos.model.users.users.UserLocatorVO,%20java.lang.Long)
     */
    public function allowActivation($locator, $passwordTypeId) {
        $this->run('allowActivation', array($locator, $passwordTypeId));
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
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO     * @param passwordTypeId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#disable(org.cyclos.model.users.users.UserLocatorVO,%20java.lang.Long)
     */
    public function disable($locator, $passwordTypeId) {
        $this->run('disable', array($locator, $passwordTypeId));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO     * @param passwordTypeId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#enable(org.cyclos.model.users.users.UserLocatorVO,%20java.lang.Long)
     */
    public function enable($locator, $passwordTypeId) {
        $this->run('enable', array($locator, $passwordTypeId));
    }
    
    /**
     * @param params Java type: org.cyclos.model.access.passwords.ForgotPasswordRequestDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#forgotPasswordRequest(org.cyclos.model.access.passwords.ForgotPasswordRequestDTO)
     */
    public function forgotPasswordRequest($params) {
        $this->run('forgotPasswordRequest', array($params));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO     * @param passwordTypeId Java type: java.lang.Long
     * @return Java type: java.lang.String
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#generateNew(org.cyclos.model.users.users.UserLocatorVO,%20java.lang.Long)
     */
    public function generateNew($locator, $passwordTypeId) {
        return $this->run('generateNew', array($locator, $passwordTypeId));
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
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO     * @param passwordTypeId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#reset(org.cyclos.model.users.users.UserLocatorVO,%20java.lang.Long)
     */
    public function reset($locator, $passwordTypeId) {
        $this->run('reset', array($locator, $passwordTypeId));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO     * @param passwordTypeId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#resetAndSend(org.cyclos.model.users.users.UserLocatorVO,%20java.lang.Long)
     */
    public function resetAndSend($locator, $passwordTypeId) {
        $this->run('resetAndSend', array($locator, $passwordTypeId));
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
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO     * @param passwordTypeId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/PasswordService.html#unblock(org.cyclos.model.users.users.UserLocatorVO,%20java.lang.Long)
     */
    public function unblock($locator, $passwordTypeId) {
        $this->run('unblock', array($locator, $passwordTypeId));
    }
    
}