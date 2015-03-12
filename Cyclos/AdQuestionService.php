<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdQuestionService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class AdQuestionService extends Service {

    function __construct() {
        parent::__construct('adQuestionService');
    }
    
    /**
     * @param id Java type: java.lang.Long     * @param answer Java type: java.lang.String
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdQuestionService.html#answer(java.lang.Long,%20java.lang.String)
     */
    public function answer($id, $answer) {
        $this->run('answer', array($id, $answer));
    }
    
    /**
     * @param adId Java type: java.lang.Long     * @param question Java type: java.lang.String
     * @return Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdQuestionService.html#ask(java.lang.Long,%20java.lang.String)
     */
    public function ask($adId, $question) {
        return $this->run('ask', array($adId, $question));
    }
    
    /**
     * @param adId Java type: java.lang.Long
     * @return Java type: java.util.List
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdQuestionService.html#list(java.lang.Long)
     */
    public function _list($adId) {
        return $this->run('list', array($adId));
    }
    
    /**
     * @param id Java type: java.lang.Long
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdQuestionService.html#remove(java.lang.Long)
     */
    public function remove($id) {
        $this->run('remove', array($id));
    }
    
    /**
     * @param query Java type: org.cyclos.model.marketplace.advertisements.AdQuestionQuery
     * @return Java type: org.cyclos.utils.Page
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/marketplace/AdQuestionService.html#search(org.cyclos.model.marketplace.advertisements.AdQuestionQuery)
     */
    public function search($query) {
        return $this->run('search', array($query));
    }
    
}