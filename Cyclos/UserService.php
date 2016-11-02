<?php namespace Cyclos;

/**
 * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html
 * 
 * Generated with Cyclos 4.7
 * 
 * WARNING: The API is subject to change between revision versions
 * (for example, 4.5 to 4.6).
 */
class UserService extends Service {

    function __construct() {
        parent::__construct('userService');
    }
    
    /**
     * @param owner Java type: org.cyclos.model.users.users.UserLocatorVO     * @param contact Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: boolean
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#addContact(org.cyclos.model.users.users.UserLocatorVO,%20org.cyclos.model.users.users.UserLocatorVO)
     */
    public function addContact($owner, $contact) {
        return $this->run('addContact', array($owner, $contact));
    }
    
    /**
     * @param params Java type: org.cyclos.model.users.users.UserQuery
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#exportToCSV(org.cyclos.model.users.users.UserQuery)
     */
    public function exportToCSV($params) {
        return $this->run('exportToCSV', array($params));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.system.configurations.ActiveConfigurationData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#getActiveConfiguration(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getActiveConfiguration($locator) {
        return $this->run('getActiveConfiguration', array($locator));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.access.passwords.PasswordInputDTO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#getConfirmationPasswordInputForRemove(java.lang.Long)
     */
    public function getConfirmationPasswordInputForRemove($id) {
        return $this->run('getConfirmationPasswordInputForRemove', array($id));
    }
    
    /**

     * @return Java type: org.cyclos.model.users.users.UserWithRolesVO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#getCurrentUser()
     */
    public function getCurrentUser() {
        return $this->run('getCurrentUser', array());
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**
     * @param group Java type: org.cyclos.model.users.groups.InitialGroupVO
     * @return Java type: org.cyclos.model.users.users.PublicRegistrationData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#getPublicRegistrationData(org.cyclos.model.users.groups.InitialGroupVO)
     */
    public function getPublicRegistrationData($group) {
        return $this->run('getPublicRegistrationData', array($group));
    }
    
    /**

     * @return Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#getPublicRegistrationGroups()
     */
    public function getPublicRegistrationGroups() {
        return $this->run('getPublicRegistrationGroups', array());
    }
    
    /**
     * @param context Java type: org.cyclos.model.users.users.UserSearchContext
     * @return Java type: org.cyclos.model.users.users.UserSearchData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#getSearchData(org.cyclos.model.users.users.UserSearchContext)
     */
    public function getSearchData($context) {
        return $this->run('getSearchData', array($context));
    }
    
    /**

     * @return Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#getUserRegistrationGroups()
     */
    public function getUserRegistrationGroups() {
        return $this->run('getUserRegistrationGroups', array());
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.users.users.ViewProfileData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#getViewProfileData(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getViewProfileData($locator) {
        return $this->run('getViewProfileData', array($locator));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.users.users.UserDetailedVO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#locate(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function locate($locator) {
        return $this->run('locate', array($locator));
    }
    
    /**
     * @param query Java type: org.cyclos.model.users.users.UserQuery
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#printUsers(org.cyclos.model.users.users.UserQuery)
     */
    public function printUsers($query) {
        return $this->run('printUsers', array($query));
    }
    
    /**
     * @param user Java type: org.cyclos.model.users.users.PublicRegistrationDTO
     * @return Java type: org.cyclos.model.users.users.UserRegistrationResult
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#publicRegister(org.cyclos.model.users.users.PublicRegistrationDTO)
     */
    public function publicRegister($user) {
        return $this->run('publicRegister', array($user));
    }
    
    /**
     * @param user Java type: org.cyclos.model.users.users.UserRegistrationDTO
     * @return Java type: org.cyclos.model.users.users.UserRegistrationResult
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#register(org.cyclos.model.users.users.UserRegistrationDTO)
     */
    public function register($user) {
        return $this->run('register', array($user));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param ids Java type: java.util.Collection     * @param confirmationPassword Java type: java.lang.String
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#removeAllWithConfirmationPassword(java.util.Collection,%20java.lang.String)
     */
    public function removeAllWithConfirmationPassword($ids, $confirmationPassword) {
        $this->run('removeAllWithConfirmationPassword', array($ids, $confirmationPassword));
    }
    
    /**
     * @param owner Java type: org.cyclos.model.users.users.UserLocatorVO     * @param contact Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: boolean
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#removeContact(org.cyclos.model.users.users.UserLocatorVO,%20org.cyclos.model.users.users.UserLocatorVO)
     */
    public function removeContact($owner, $contact) {
        return $this->run('removeContact', array($owner, $contact));
    }
    
    /**
     * @param id Java type: java.lang.Long     * @param confirmationPassword Java type: java.lang.String
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#removeWithConfirmationPassword(java.lang.Long,%20java.lang.String)
     */
    public function removeWithConfirmationPassword($id, $confirmationPassword) {
        $this->run('removeWithConfirmationPassword', array($id, $confirmationPassword));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
    /**
     * @param object Java type: DTO     * @param confirmationPassword Java type: java.lang.String
     * @return Java type: java.lang.Long
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#saveWithConfirmationPassword(DTO,%20java.lang.String)
     */
    public function saveWithConfirmationPassword($object, $confirmationPassword) {
        return $this->run('saveWithConfirmationPassword', array($object, $confirmationPassword));
    }
    
    /**
     * @param query Java type: org.cyclos.model.users.users.UserQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#search(org.cyclos.model.users.users.UserQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
    /**
     * @param type Java type: org.cyclos.model.users.users.UserActivityType
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/users/UserService.html#updateUserActivity(org.cyclos.model.users.users.UserActivityType)
     */
    public function updateUserActivity($type) {
        $this->run('updateUserActivity', array($type));
    }
    
}