<?php namespace Cyclos;

/**
 * Holds the configuration for accessing Cyclos services.
 * Basically, needs a root url to be used (up to the network path) and an username / password to access services
 */
class Configuration {
   
	private static $rootUrl;
    private static $accessType;
    private static $lastAccessType;
    
    private static $channel;
    
    private static $principal;
    private static $password;

    private static $sessionToken;
    private static $forwardRemoteAddress;
    
    private static $accessClientToken;
    
    private static $redirectUrl;
    
    private static $debug = false;
    
    /**
     * Sets the Cyclos root url
     */
    public static function setRootUrl($rootUrl) {
        Configuration::$rootUrl = $rootUrl;
    }

    /**
     * Returns the Cyclos root url
     */
    public static function getRootUrl() {
        return Configuration::$rootUrl;
    }
   
    /**
   	 * Returns if the plugin is in debug mode
     */
    public static function isDebug() {
    	return Configuration::$debug;
    }
    
    /**
     * Sets if the plugin is in debug mode, useful for
     * debugging CURL requests and other important stuff
     */
    public static function setDebug($debug) {
    	Configuration::$debug = $debug;
    }
    
    /**
     * Configure the request options to be performed as guest 
     */
   public static function setGuest($guest) {
   		if($guest) {
	   		Configuration::$lastAccessType = Configuration::$accessType;
   			Configuration::$accessType = 'GUEST';
   		} else {
   			Configuration::$accessType = Configuration::$lastAccessType;
   		}
   }

    /**
     * Sets the redirect URL after a succesful login.
     * If empty it will redirect to root URL
     */
    public static function setRedirectUrl($redirectUrl) {
    	Configuration::$redirectUrl = $redirectUrl;
    }
    
    /**
     * Returns the redirect URL after a succesful login.
     * If empty it will redirect to root URL
     */
    public static function getRedirectUrl() {
    	return Configuration::$redirectUrl;
    }
           
    /**
     * Sets the Cyclos channel
     */
    public static function setChannel($channel) {
        Configuration::$channel = $channel;
    }

    /**
     * Returns the Cyclos channel
     */
    public static function getChannel() {
        return Configuration::$channel;
    }
    
    /**
     * Configures a stateless access, passing the username and password on each request
     */
    public static function setAuthentication($principal, $password) {
        Configuration::$principal = $principal;
        Configuration::$password = $password;
        Configuration::$accessType = 'LOGIN';
    }

    /**
     * Configures a stateful access, passing the session token on each request
     */
    public static function setSessionToken($sessionToken) {
        Configuration::$sessionToken = $sessionToken;
        Configuration::$accessType = 'SESSION';
    }

    /**
     * Configures an access client token, using it on each request
     */
    public static function setAccessClientToken($accessClientToken) {
        Configuration::$accessClientToken = $accessClientToken;
        Configuration::$accessType = 'ACCESS_CLIENT';
    }
    
    /**
     * Sets that, in case of stateful access, the remote address of the client connection will be forwarded to Cyclos on each request
     */
    public static function setForwardRemoteAddress($forwardRemoteAddress) {
        Configuration::$forwardRemoteAddress = $forwardRemoteAddress;
    }
    
    /**
     * Returns the full url to access the service with the given url part 
     */
    public static function url($serviceUrlPart, $pathVariables, $queryParameters) {    	
        $url = Configuration::$rootUrl . '/api/' . $serviceUrlPart;
                
        // Append path variables
       	if(!empty($pathVariables)) {   
       		if(is_array($pathVariables)) {
	       		foreach($pathVariables as $variable) {       			
    	   			if(!empty($variable)) {
	    	   			$url .= '/' .$variable;
       				} 
	       		}
       		} else {
       			$url .= '/' .$pathVariables;
       		}
       	}
       	
       	
       	
       	// Append query parameters
       	if(!empty($queryParameters)) {
       		$keys = array_keys($queryParameters);
       		foreach($keys as $key) {
       			$value = $queryParameters[$key];
       			if(!empty($value)) {
	       			if(is_array($value)) { // handle array
	       				$value = implode(',', $value);
	       			} elseif(!is_string($value)) { // handle object
	       				$value = \json_encode($value);
	       			}
	       			$url .= strpos($url, '?') !== false ? '&' : '?';
	       			$url .= urlencode($key) . '=' . urlencode($value);
       			}
       		}
       	}       	       	     
       	
        return $url; 
    }
    
    /**
     * Returns the curl options to execute a call of the given operation, with the given parameters
     */
    public static function curlOptions() {
                
        // Set the CURL options
        $opts = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => array('Content-type: application/json', 'Accept: application/json'),
        		CURLOPT_TIMEOUT => 30
        );

        // Set the channel header
        if (!empty(Configuration::$channel)) {
        	$opts[CURLOPT_HTTPHEADER][] = 'Channel: ' . Configuration::$channel;
        }
        
        // Set the authentication header
        switch (Configuration::$accessType) {
            case 'LOGIN':
                $opts[CURLOPT_USERPWD] = Configuration::$principal . ':' . Configuration::$password;
                break;
            case 'SESSION':
                $opts[CURLOPT_HTTPHEADER][] = 'Session-Token: ' . Configuration::$sessionToken;
                if (Configuration::$forwardRemoteAddress) {
                    $opts[CURLOPT_HTTPHEADER][] = 'Remote-Address: ' . $_SERVER['REMOTE_ADDR'];
                }
                break;
            case 'ACCESS_CLIENT':
                $opts[CURLOPT_HTTPHEADER][] = 'Access-Client-Token: ' . Configuration::$accessClientToken;
                break;
        }
        
        return $opts;
    }
}
