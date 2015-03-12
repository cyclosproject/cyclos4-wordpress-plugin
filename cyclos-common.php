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

// Class to hold info about a translation key
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
        CyclosKey::add($result, "loginTitle", "Login form title", "Member login:");
        CyclosKey::add($result, "loginName", "Login name placeholder", "User");
        CyclosKey::add($result, "loginPassword", "Password placeholder", "Password");
        CyclosKey::add($result, "loginSubmit", "Login button text", "Login");
        CyclosKey::add($result, "forgotLink", "Forgot password link", "Forgot your password?");
        CyclosKey::add($result, "forgotTitle", "Forgot password title", "Password recovery");
        CyclosKey::add($result, "forgotEmail", "E-mail placeholde", "E-mail");
        CyclosKey::add($result, "forgotCaptcha", "Captcha placeholder", "Visual validation");
        CyclosKey::add($result, "forgotNewCaptcha", "New captcha link", "New code");
        CyclosKey::add($result, "forgotCancel", "Cancel forgot password request", "Cancel");
        CyclosKey::add($result, "forgotSubmit", "Submit forgot password request", "Submit");
        CyclosKey::add($result, "forgotDone", "Forgot password done", "A link to reset your password has been submitted to {email}");
        CyclosKey::add($result, "errorLogin", "Error message: invalid login", "Invalid username / password");
        CyclosKey::add($result, "errorAddressBlocked", "Error message: address blocked", "Your access is blocked by exceeding invalid login attempts");
        CyclosKey::add($result, "errorEmailNotFound", "Error message: invalid e-mail", "The given e-mail address didn't match any valid user");
        return $result;
	}
}

// Returns the translated values
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

// Function that autoloads Cyclos classes
function autoload_cyclos($c) {
    if (strpos($c, "Cyclos\\") >= 0) {
        include plugin_dir_path( __FILE__ ) . str_replace("\\", "/", $c) . ".php";
    }
}

// Sets up the Cyclos services to use the given access token, or load the stored settings if nothing is passed in
function configureCyclos($rootUrl = NULL, $token = NULL) {
    if (empty($rootUrl)) $rootUrl = get_option('cyclos_url');
    if (empty($token)) $token = get_option('cyclos_token');

    spl_autoload_register('autoload_cyclos'); 
    Cyclos\Configuration::setRootUrl($rootUrl);
    Cyclos\Configuration::setAccessClientToken($token);
}

?>
