<?php namespace Cyclos;

/**
 * Generate new captcha challenges, which are required for some operations performed as guest, in order to make it harder for bots to abuse the api.
 */
class CaptchaService extends Service {

    function __construct() {
        parent::__construct('captcha');
    }
    
    /**
     * Returns a new captcha challenge
     */
    public function newCaptcha($group = NULL) {
        $id = $this->post(NULL, array('group' => $group));        
        return strval($id);
    }

    /**
  	 * Returns a captcha image content
	 */
    public function getCaptchaContent($id, $group = NULL) {
        return $this->get(NULL, $id, array('group' => $group, 'width' => '250'));
    }
    
}