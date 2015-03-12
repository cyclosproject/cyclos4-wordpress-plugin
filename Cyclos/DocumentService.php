<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class DocumentService extends Service {

    function __construct() {
        parent::__construct('documentService');
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html#getData(java.lang.Long)
     */
    public function getData($id) {
        return $this->run('getData', array($id));
    }
    
    /**
     * @param params Java type: DP
     * @return Java type: D
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html#getDataForNew(DP)
     */
    public function getDataForNew($params) {
        return $this->run('getDataForNew', array($params));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: org.cyclos.model.contentmanagement.documents.DocumentVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html#getDocument(java.lang.Long)
     */
    public function getDocument($id) {
        return $this->run('getDocument', array($id));
    }
    
    /**
     * @param documentId Java type: java.lang.Long
     * @return Java type: org.cyclos.model.contentmanagement.documents.DocumentFileVO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html#getDocumentFile(java.lang.Long)
     */
    public function getDocumentFile($documentId) {
        return $this->run('getDocumentFile', array($documentId));
    }
    
    /**
     * @param query Java type: org.cyclos.model.contentmanagement.documents.DocumentQuery
     * @return Java type: org.cyclos.model.contentmanagement.documents.DocumentSearchData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html#getDocumentSearchData(org.cyclos.model.contentmanagement.documents.DocumentQuery)
     */
    public function getDocumentSearchData($query) {
        return $this->run('getDocumentSearchData', array($query));
    }
    
    /**
     * @param id Java type: java.lang.Long     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.contentmanagement.documents.ProcessDynamicDocumentData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html#getProcessDynamicDocumentData(java.lang.Long,%20org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getProcessDynamicDocumentData($id, $locator) {
        return $this->run('getProcessDynamicDocumentData', array($id, $locator));
    }
    
    /**

     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html#listMyDocuments()
     */
    public function listMyDocuments() {
        return $this->run('listMyDocuments', array());
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @return Java type: DTO
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html#load(java.lang.Long)
     */
    public function load($id) {
        return $this->run('load', array($id));
    }
    
    /**
     * @param params Java type: org.cyclos.model.contentmanagement.documents.ProcessDynamicDocumentDTO
     * @return Java type: java.lang.String
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html#processDynamicDocument(org.cyclos.model.contentmanagement.documents.ProcessDynamicDocumentDTO)
     */
    public function processDynamicDocument($params) {
        return $this->run('processDynamicDocument', array($params));
    }
    
    /**
     * @param documentId Java type: java.lang.Long
     * @return Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html#readContent(java.lang.Long)
     */
    public function readContent($documentId) {
        return $this->run('readContent', array($documentId));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param ids Java type: java.util.Collection
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html#removeAll(java.util.Collection)
     */
    public function removeAll($ids) {
        $this->run('removeAll', array($ids));
    }
    
    /**
     * @param object Java type: DTO
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html#save(DTO)
     */
    public function save($object) {
        return $this->run('save', array($object));
    }
    
    /**
     * @param documentId Java type: java.lang.Long     * @param contentType Java type: java.lang.String     * @param fileName Java type: java.lang.String     * @param contents Java type: org.cyclos.server.utils.SerializableInputStream
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html#saveFile(java.lang.Long,%20java.lang.String,%20java.lang.String,%20org.cyclos.server.utils.SerializableInputStream)
     */
    public function saveFile($documentId, $contentType, $fileName, $contents) {
        $this->run('saveFile', array($documentId, $contentType, $fileName, $contents));
    }
    
    /**
     * @param query Java type: org.cyclos.model.contentmanagement.documents.DocumentQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/contentmanagement/DocumentService.html#search(org.cyclos.model.contentmanagement.documents.DocumentQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
}