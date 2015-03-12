<?php namespace Cyclos;

/**
 * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/UserChannelService.html 
 * WARNING: The API is still experimental, and is subject to change.
 */
class UserChannelService extends Service {

    function __construct() {
        parent::__construct('userChannelService');
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO
     * @return Java type: org.cyclos.model.access.userchannels.UserChannelsData
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/UserChannelService.html#getChannelsData(org.cyclos.model.users.users.UserLocatorVO)
     */
    public function getChannelsData($locator) {
        return $this->run('getChannelsData', array($locator));
    }
    
    /**
     * @param locator Java type: org.cyclos.model.users.users.UserLocatorVO     * @param channelIds Java type: java.util.Set
     * @see http://www.cyclos.org/dev/current/ws-api-docs/org/cyclos/services/access/UserChannelService.html#saveChannels(org.cyclos.model.users.users.UserLocatorVO,%20java.util.Set)
     */
    public function saveChannels($locator, $channelIds) {
        $this->run('saveChannels', array($locator, $channelIds));
    }
    
}