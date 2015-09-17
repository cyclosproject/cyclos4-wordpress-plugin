<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class TokenService extends Service {

    function __construct() {
        parent::__construct('tokenService');
    }
    
    /**
     * @param dto Java type: org.cyclos.model.access.tokens.NFCDeviceActionDTO
     * @return Java type: org.cyclos.model.access.tokens.ActivateNFCDeviceDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#activateNFCDevice(org.cyclos.model.access.tokens.NFCDeviceActionDTO)
     */
    public function activateNFCDevice($dto) {
        return $this->run('activateNFCDevice', array($dto));
    }
    
    /**
     * @param dto Java type: org.cyclos.model.access.tokens.NFCTagActionDTO
     * @return Java type: org.cyclos.model.access.tokens.ActivateNFCTagDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#activateNFCTag(org.cyclos.model.access.tokens.NFCTagActionDTO)
     */
    public function activateNFCTag($dto) {
        return $this->run('activateNFCTag', array($dto));
    }
    
    /**
     * @param tokenId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#activatePending(java.lang.Long)
     */
    public function activatePending($tokenId) {
        $this->run('activatePending', array($tokenId));
    }
    
    /**
     * @param dto Java type: org.cyclos.model.access.tokens.TokenActionDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#assign(org.cyclos.model.access.tokens.TokenActionDTO)
     */
    public function assign($dto) {
        $this->run('assign', array($dto));
    }
    
    /**
     * @param tokenId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#block(java.lang.Long)
     */
    public function block($tokenId) {
        $this->run('block', array($tokenId));
    }
    
    /**
     * @param tokenId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#cancel(java.lang.Long)
     */
    public function cancel($tokenId) {
        $this->run('cancel', array($tokenId));
    }
    
    /**
     * @param dto Java type: org.cyclos.model.access.tokens.TokenActionDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#cancelNFCDevice(org.cyclos.model.access.tokens.TokenActionDTO)
     */
    public function cancelNFCDevice($dto) {
        $this->run('cancelNFCDevice', array($dto));
    }
    
    /**
     * @param dto Java type: org.cyclos.model.access.tokens.NFCTagActionDTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#cancelNFCTag(org.cyclos.model.access.tokens.NFCTagActionDTO)
     */
    public function cancelNFCTag($dto) {
        $this->run('cancelNFCTag', array($dto));
    }
    
    /**

     * @return Java type: org.cyclos.model.access.tokens.ActivateNFCDeviceData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#getActivateNFCDeviceData()
     */
    public function getActivateNFCDeviceData() {
        return $this->run('getActivateNFCDeviceData', array());
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.access.tokens.ActivateNFCTagData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#getActivateNFCTagData(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getActivateNFCTagData($locator) {
        return $this->run('getActivateNFCTagData', array($locator));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**
     * @param tokenType Java type: org.cyclos.model.access.principaltypes.TokenPrincipalTypeVO     * @param user Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.access.tokens.TokensListData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#getListData(org.cyclos.model.access.principaltypes.TokenPrincipalTypeVO,%20org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getListData($tokenType, $user) {
        return $this->run('getListData', array($tokenType, $user));
    }
    
    /**
     * @param tokenType Java type: org.cyclos.model.access.principaltypes.TokenPrincipalTypeVO
     * @return Java type: org.cyclos.model.access.tokens.TokenSearchData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#getSearchData(org.cyclos.model.access.principaltypes.TokenPrincipalTypeVO)
     */
    public function getSearchData($tokenType) {
        return $this->run('getSearchData', array($tokenType));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param dto Java type: org.cyclos.model.access.tokens.ExternalNFCTagAuthenticateDTO
     * @return Java type: org.cyclos.model.access.tokens.ExternalNFCTagAuthenticateData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#requestForExternalAuthenticate(org.cyclos.model.access.tokens.ExternalNFCTagAuthenticateDTO)
     */
    public function requestForExternalAuthenticate($dto) {
        return $this->run('requestForExternalAuthenticate', array($dto));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO     * @param tokenType Java type: org.cyclos.model.access.principaltypes.TokenPrincipalTypeVO     * @param medium Java type: org.cyclos.model.access.passwordtypes.OTPSendMedium
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#requestNewOTPForActivateNFCTag(org.cyclos.model.users.users.UserLocatorVO,%20org.cyclos.model.access.principaltypes.TokenPrincipalTypeVO,%20org.cyclos.model.access.passwordtypes.OTPSendMedium)
     */
    public function requestNewOTPForActivateNFCTag($locator, $tokenType, $medium) {
        $this->run('requestNewOTPForActivateNFCTag', array($locator, $tokenType, $medium));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
    /**
     * @param query Java type: org.cyclos.model.access.tokens.TokenQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#search(org.cyclos.model.access.tokens.TokenQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
    /**
     * @param tokenId Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/TokenService.html#unblock(java.lang.Long)
     */
    public function unblock($tokenId) {
        $this->run('unblock', array($tokenId));
    }
    
}