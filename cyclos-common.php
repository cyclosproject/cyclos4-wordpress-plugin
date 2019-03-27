<?php
/*  Copyright 2015 Cyclos (www.cyclos.org)

    This plugin is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This plugin is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
    
    Be aware the plugin is publish under GPL2 Software, but Cyclos 4 PRO is not,
    you need to buy an appropriate license if you want to use Cyclos 4 PRO, see:
    www.cyclos.org.
*/

// Block people to access the script directly (against malicious attempts)
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Class to hold info about a translation key
 */ 
class CyclosKey {
    public function __construct($key, $title, $defaultValue) {
		$this->key = $key;
		$this->option = "cyclos_t_" . $key;
		$this->title = $title;
		$this->defaultValue = $defaultValue;
	}
	
	private static function add($result, $key, $title, $defaultValue) {
	    $result->$key = new CyclosKey($key, $title, $defaultValue);
	}
	
	public static function getAll() {
        $result = new stdclass();        
        CyclosKey::add($result, 'loginName', 'Login name placeholder', 'User');
        CyclosKey::add($result, 'loginPassword', 'Password placeholder', 'Password');
        CyclosKey::add($result, 'loginSubmit', 'Login button text', 'Login');
        CyclosKey::add($result, 'forgotLink', 'Forgot password link', 'Forgot your password?');        
        CyclosKey::add($result, 'forgotEmail', 'Forgot password user placeholder', 'User');
        CyclosKey::add($result, 'forgotCaptcha', 'Captcha placeholder', 'Visual validation');
        CyclosKey::add($result, 'forgotNewCaptcha', 'New captcha link', 'New code');
        CyclosKey::add($result, 'forgotCancel', 'Cancel forgot password request', 'Cancel');
        CyclosKey::add($result, 'forgotSubmit', 'Submit forgot password request', 'Submit');
        CyclosKey::add($result, 'forgotDone', 'Forgot password done', 'A link to reset your password has been submitted to {email}');
             
        // Errors        
        CyclosKey::add($result, 'errorConnection', 'Error message: the Cyclos server could not be contacted', 'The Cyclos server could not be contacted');        
        CyclosKey::add($result, 'errorGeneral', 'Error message: unknown error', 'Error performing the request: {code}');
        CyclosKey::add($result, 'errorInaccessibleChannel', 'Error message: inaccessible channel', 'Access denied. The channel used to connect to the server is not allowed or misconfigured');
        CyclosKey::add($result, 'errorInaccessiblePrincipal', 'Error message: inaccessible principal', 'You cannot use your {principal} in this channel');
        CyclosKey::add($result, "errorUserBlocked", 'Error message: user blocked', 'Your access has been blocked. Please, contact the administration.');
        CyclosKey::add($result, "errorUserDisabled", 'Error message: user disabled', 'Your user account has been disabled. Please, contact the administration.');
        CyclosKey::add($result, "errorUserPending", 'Error message: pending user', 'Your user account is pending for activation. Please, contact the administration for more information.');
        CyclosKey::add($result, "errorLogin", 'Error message: invalid login', 'Invalid username / password');
        CyclosKey::add($result, 'errorPasswordIndefinitelyBlocked', 'Error message: password indefinitely blocked', 'Your password has been disabled by exceeding the maximum of tries.\nPlease, contact the administration');
        CyclosKey::add($result, 'errorPasswordTemporarilyBlocked', 'Error message: password temporarily blocked', 'Your password has been blocked by exceeding the maximum of tries');
        CyclosKey::add($result, 'errorInvalidPassword', 'Error message: password invalid', 'The given password is incorrect. Please, try again');
        CyclosKey::add($result, "errorInvalidAccessClient", "Error message: invalid access client", "The current access client is not correctly configured");
        CyclosKey::add($result, 'errorOperatorWithPendingAgreements', 'Error message: operator pending agreements', 'A required agreement needs to be accepted by your manager before you can login');        
        CyclosKey::add($result, 'errorEntityNotFound', 'Error message: information not found', 'The required information was not found');
        CyclosKey::add($result, 'errorEntityNotFoundUser', 'Error message: user not found', 'The user was not found');
        CyclosKey::add($result, 'errorEntityNotFoundAccessClient', 'Error message: access client not found', 'The access client was not found or the activation code is not valid');
      
        return $result;
	}
}

/**
 * Returns the translated values
 */
function cyclosGetTranslations() {
    $t = new stdclass();
    foreach (CyclosKey::getAll() as $k => $key) {
        $value = get_option($key->option);
        if (empty($value)) {
            $value = $key->defaultValue;
        }
        $t->$k = $value;
    }
    return $t;
}

/**
 * Autoloads Cyclos classes
 */
function autoload_cyclos($c) {
    if (strpos($c, "Cyclos\\") >= 0) {
        include plugin_dir_path( __FILE__ ) . str_replace("\\", "/", $c) . ".php";
    }
}

/**
 * Sets up the Cyclos services to use the given access token, or load the stored settings if nothing is passed in
 */
function configureCyclos($rootUrl = NULL, $token = NULL) {
    if (empty($rootUrl)) $rootUrl = get_option('cyclos_url');
    if (empty($token)) $token = get_option('cyclos_token');

    spl_autoload_register('autoload_cyclos'); 
    Cyclos\Configuration::setRootUrl($rootUrl);
    Cyclos\Configuration::setAccessClientToken($token);    
    Cyclos\Configuration::setRedirectUrl(get_option('cyclos_redirectUrl'));
}

/**
 * Returns an appropriate error message for a validation exception
 */
function validationExceptionMessage($e) {
    $val = $e->error;
    $errors = array();
    if (!empty($val->generalErrors)) {
        $errors = array_merge($errors, $val->generalErrors);
    }
    if (!empty($val->propertyErrors)) {
        foreach ($val->propertyErrors as $key => $value) {
            $errors = array_merge($errors, $value);
        }
    }
    if (empty($errors)) {
        return 'Validation error';
    } else {
        return implode('\n', $errors);
    }
}

/**
 * Handles the given exception and returns an error message
 */
function handleError($e) {
		
	$class = get_class($e);
	$t = cyclosGetTranslations();
	
	$property = NULL;
	$errorMessage = NULL;
	
	if ($class == 'Cyclos\ServiceException') {	
		$status = $e->statusCode;		
		if($status == 0 || $status == 1 || $status == 503) {
			// Connection error
			$property = 'errorConnection';
		} elseif ($status == 422) {
			// Unprocessable entity error
			if(property_path_exists($e, 'error->code') && $e->error->code == 'validation') {
				$errorMessage = validationExceptionMessage($e);
			}
		} elseif ($status == 404) {
			if(property_path_exists($e, 'error->entityType')) {
				// Not found error
				$path = 'errorEntityNotFound'.ucwords($e->error->entityType);
				$property = property_exists($t, $path) ? $path : 'errorEntityNotFound';				
			} else {
				// Likely a connection error (no REST API found)
				$property = 'errorConnection';
			}		
		} elseif (property_path_exists($e, 'error->code')) {					
			// Conflict, forbidden or other errors which contains a 'code' attribute			
			if($e->error->code = 'login') {
				// Handle login error
				if(property_exists($e->error, 'passwordStatus')) {
					// Password errors
					$property = 'errorPassword'.ucwords($e->error->passwordStatus);
				} else if(property_exists($e->error, 'userStatus')) {
					// User errors
					$property = 'errorUser'.ucwords($e->error->userStatus);
				}
			}
			if($property == NULL) {
				$property = 'error'.ucwords($e->error->code);
			}
		} 
	}
	
	// Show a generic error if there is not a translated message
	if(empty($property) || !property_exists($t, $property)) {		
		$property = 'errorGeneral';						
	}	

	// Replace message parameters
	if(empty($errorMessage) && !empty($property)) {
		
		$errorMessage = $t->{$property};
		
		switch($property) {
			case 'errorGeneral':
				$errorMessage = str_replace('{code}', $status, $errorMessage);				
				break;
			case 'errorInaccessiblePrincipal':
				// TODO NO API YET
				//$errorMessage = str_replace('{principal}', $e->code, $errorMessage);
				break;
		}
	}
	 			
	return $errorMessage;
}

/**
 * Extension of property_exists() function. Searches 
 * if the he given path exists in the given object
 */
function property_path_exists($object, $property_path) {
	
	if(empty($object)) {
		return false;
	}
	
	$path_components = explode('->', $property_path);

	if (count($path_components) == 1) {
		return property_exists($object, $property_path);
	} else {
		return (
			property_exists($object, $path_components[0]) &&
			property_path_exists(
				$object->{array_shift($path_components)},
					implode('->', $path_components)
						)
				);
	}
}

?>
