<?php namespace Cyclos;

/**
 * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html
 * 
 * Generated with Cyclos 4.7
 * 
 * WARNING: The API is subject to change between revision versions
 * (for example, 4.5 to 4.6).
 */
class CustomOperationService extends Service {

    function __construct() {
        parent::__construct('customOperationService');
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**
     * @param customOperation Java type: org.cyclos.model.system.operations.CustomOperationVO     * @param ad Java type: org.cyclos.model.marketplace.advertisements.BasicAdVO
     * @return Java type: org.cyclos.model.system.operations.RunCustomOperationData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#getRunAdData(org.cyclos.model.system.operations.CustomOperationVO,%20org.cyclos.model.marketplace.advertisements.BasicAdVO)
     */
    public function getRunAdData($customOperation, $ad) {
        return $this->run('getRunAdData', array($customOperation, $ad));
    }
    
    /**
     * @param customOperation Java type: org.cyclos.model.system.operations.CustomOperationVO
     * @return Java type: org.cyclos.model.system.operations.RunCustomOperationData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#getRunData(org.cyclos.model.system.operations.CustomOperationVO)
     */
    public function getRunData($customOperation) {
        return $this->run('getRunData', array($customOperation));
    }
    
    /**
     * @param customOperation Java type: org.cyclos.model.system.operations.CustomOperationVO     * @param record Java type: org.cyclos.model.users.records.RecordVO
     * @return Java type: org.cyclos.model.system.operations.RunCustomOperationData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#getRunRecordData(org.cyclos.model.system.operations.CustomOperationVO,%20org.cyclos.model.users.records.RecordVO)
     */
    public function getRunRecordData($customOperation, $record) {
        return $this->run('getRunRecordData', array($customOperation, $record));
    }
    
    /**
     * @param customOperation Java type: org.cyclos.model.system.operations.CustomOperationVO     * @param user Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.system.operations.RunCustomOperationData
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#getRunUserData(org.cyclos.model.system.operations.CustomOperationVO,%20org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getRunUserData($customOperation, $user) {
        return $this->run('getRunUserData', array($customOperation, $user));
    }
    
    /**

     * @return Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#getRunnableOperationsForSystem()
     */
    public function getRunnableOperationsForSystem() {
        return $this->run('getRunnableOperationsForSystem', array());
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#getRunnableOperationsForUser(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getRunnableOperationsForUser($locator) {
        return $this->run('getRunnableOperationsForUser', array($locator));
    }
    
    /**

     * @return Java type: java.util.List
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#list()
     */
    public function _list() {
        return $this->run('list', array());
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param params Java type: org.cyclos.model.system.operations.RunCustomOperationDTO
     * @return Java type: org.cyclos.model.system.operations.RunCustomOperationResult
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#run(org.cyclos.model.system.operations.RunCustomOperationDTO)
     */
    public function run($params) {
        return $this->run('run', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.system.operations.RunExternalRedirectCallbackParams
     * @return Java type: java.lang.String
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#runExternalRedirectCallback(org.cyclos.model.system.operations.RunExternalRedirectCallbackParams)
     */
    public function runExternalRedirectCallback($params) {
        return $this->run('runExternalRedirectCallback', array($params));
    }
    
    /**
     * @param params Java type: org.cyclos.model.system.operations.RunCustomOperationDTO     * @param inputFile Java type: org.cyclos.model.utils.FileInfo
     * @return Java type: org.cyclos.model.utils.FileInfo
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#runForCSV(org.cyclos.model.system.operations.RunCustomOperationDTO,%20org.cyclos.model.utils.FileInfo)
     */
    public function runForCSV($params, $inputFile) {
        return $this->run('runForCSV', array($params, $inputFile));
    }
    
    /**
     * @param params Java type: org.cyclos.model.system.operations.RunCustomOperationDTO     * @param inputFile Java type: org.cyclos.model.utils.FileInfo
     * @return Java type: org.cyclos.model.system.operations.CustomOperationContentResult
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#runForContent(org.cyclos.model.system.operations.RunCustomOperationDTO,%20org.cyclos.model.utils.FileInfo)
     */
    public function runForContent($params, $inputFile) {
        return $this->run('runForContent', array($params, $inputFile));
    }
    
    /**
     * @param params Java type: org.cyclos.model.system.operations.RunCustomOperationDTO     * @param inputFile Java type: org.cyclos.model.utils.FileInfo
     * @return Java type: org.cyclos.model.utils.FileInfo
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#runForFile(org.cyclos.model.system.operations.RunCustomOperationDTO,%20org.cyclos.model.utils.FileInfo)
     */
    public function runForFile($params, $inputFile) {
        return $this->run('runForFile', array($params, $inputFile));
    }
    
    /**
     * @param params Java type: org.cyclos.model.system.operations.RunCustomOperationDTO     * @param inputFile Java type: org.cyclos.model.utils.FileInfo
     * @return Java type: org.cyclos.model.utils.FileInfo
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#runForPDF(org.cyclos.model.system.operations.RunCustomOperationDTO,%20org.cyclos.model.utils.FileInfo)
     */
    public function runForPDF($params, $inputFile) {
        return $this->run('runForPDF', array($params, $inputFile));
    }
    
    /**
     * @param params Java type: org.cyclos.model.system.operations.RunCustomOperationDTO     * @param inputFile Java type: org.cyclos.model.utils.FileInfo
     * @return Java type: org.cyclos.model.system.operations.CustomOperationPageResult
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#runForPage(org.cyclos.model.system.operations.RunCustomOperationDTO,%20org.cyclos.model.utils.FileInfo)
     */
    public function runForPage($params, $inputFile) {
        return $this->run('runForPage', array($params, $inputFile));
    }
    
    /**
     * @param params Java type: org.cyclos.model.system.operations.RunCustomOperationDTO     * @param inputFile Java type: org.cyclos.model.utils.FileInfo
     * @return Java type: java.lang.String
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#runForString(org.cyclos.model.system.operations.RunCustomOperationDTO,%20org.cyclos.model.utils.FileInfo)
     */
    public function runForString($params, $inputFile) {
        return $this->run('runForString', array($params, $inputFile));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://documentation.cyclos.org/4.7/ws-api-docs/org/cyclos/services/system/CustomOperationService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
}