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

// Register style sheet
add_action('wp_print_styles', 'registerCyclosStyles', 99);
add_action('wp_enqueue_scripts', 'registerJQuery');

/*#################### Creates shortcode and inserts login form ####################*/
add_shortcode( 'cycloslogin', 'cyclosLoginForm' );

// Create the widget to display the login form
add_action('widgets_init',  function(){return register_widget("CyclosPlugin");});

class CyclosPlugin extends WP_Widget {

    // constructor
    public function __construct() {
         parent::__construct(false, $name = __('Cyclos', 'wp_widget_plugin') );
    }

    // widget display
    public function widget($args, $instance) {
        echo ($args['before_widget'] . cyclosLoginForm(NULL) . $args['after_widget']);
    }
}

function registerCyclosStyles() {
    wp_register_style('cyclosWordpressStylesheet', plugin_dir_url( __FILE__) . 'stylesheet.css');
    wp_enqueue_style('cyclosWordpressStylesheet' );
    wp_enqueue_style('google-fonts','//fonts.googleapis.com/css?family=Open+Sans', array(), NULL);
}    

function registerJQuery() {
	wp_enqueue_script('jquery');
}

// Function that is called when the shortcode is used
function cyclosLoginForm($atts) {
    configureCyclos();
    $authService = new Cyclos\AuthService();

    $out = '';
    $returnToValue = '';

    // Execute this request as guest, otherwise it would return 204 (no content)
    Cyclos\Configuration::setGuest(true);
    // Request login data for main channel
    Cyclos\Configuration::setChannel('main');
    
    // See if the forgot password should be shown
    try {
        $loginData = $authService->getDataForLogin();          
        $showForgotPassword = !empty($loginData) && 
        						!empty($loginData->forgotPasswordMediums) && 
        						in_array('email', $loginData->forgotPasswordMediums, TRUE);	
    } catch (Exception $e) {    	
        $showForgotPassword = true;
    }
    
    // Revert guest request and headers
    Cyclos\Configuration::setGuest(false);
    Cyclos\Configuration::setChannel(NULL);

    $t = cyclosGetTranslations();
    
    // Returns the html to show when shortcode is used.

    if ($showForgotPassword) {
        if(isset($_GET["returnTo"])){
            $returnToValue = $_GET["returnTo"];
        }
        $out = $out . '
            <div class="cyclosFormBox cyclosForgotContainer" style="display:none">
                <form class="cyclosForgotPasswordForm" action="#" method="post">                    
                    <div class="cyclosFormField">
                        <input placeholder="' . $t->forgotEmail . '" name="cyclosEmail" type="text" required>
                    </div>
                    <div class="cyclosFormField">
                        <div>
                            <input placeholder="' . $t->forgotCaptcha . '" name="cyclosCaptcha" type="text" style="float: left; width: 60%" required>
                            <a class="cyclosNewCaptcha" href="#">' . $t->forgotNewCaptcha . '</a>
                        </div>
                        <div>
                            <img alt="captcha" class="cyclosCaptchaImage" style="display:none">
                        </div>
                    </div>
                    <div class="cyclosFormActions">
                        <input class="cyclosForgotSubmit" type="submit" value="' . $t->forgotSubmit . '">
                        <div class="cyclosForgotPasswordBox">
                            <a class="cyclosForgotCancel" href="#">' . $t->forgotCancel . '</a>
                        </div>
                    </div>
                </form>
            </div>
        ';
    }
    $out = $out . '
        <div class="cyclosFormBox cyclosLoginContainer">
            <form class="cyclosLoginForm" action="#" method="post">                
                <div>
                    <input name="cyclosReturn" value="'. $returnToValue .'" type="hidden">
                </div>
                <div class="cyclosFormField">
                    <input placeholder="' . $t->loginName . '" name="cyclosPrincipal" type="text" required>
                </div>
                <div class="cyclosFormField">
                    <input placeholder="' . $t->loginPassword . '" name="cyclosPassword" type="password" required>
                </div>
                <div class="cyclosFormActions">
                    <input type="submit" class="cyclosLoginSubmit" value="' . $t->loginSubmit . '">
    ';
    if ($showForgotPassword) {
        $out = $out . '
                    <div class="cyclosForgotPasswordBox">
                        <a class="cyclosForgotLink" href="#">' . $t->forgotLink . '</a>
                    </div>
        ';
    }
    $out = $out . '
                </div>
            </form>
        </div>';
    if (!array_key_exists('cyclos_behavior_applied', $GLOBALS)) {
        $GLOBALS['cyclos_behavior_applied'] = true;
        $out = $out . '
        <script>
            jQuery(document).ready(function($) {
        ';
        $out = $out . '
                $(".cyclosLoginForm").submit(function(event) {
        			var submitEnabled = true;
                    if (submitEnabled) {
                        var principal = this.cyclosPrincipal.value.trim();
                        var password = this.cyclosPassword.value.trim();
                        var returnTo = this.cyclosReturn.value.trim();
                        
                        if (principal != "" && password != "") {
                            var data = {
                                "principal": principal,
                                "password": password,
                                "returnTo": returnTo
                            }

                            submitEnabled = false;
                            
                            $.post("' . admin_url('admin-ajax.php?action=cyclos_login') . '", data)
                                .done(function(response) {
                                    response = response || {};                            		
                                    if (response.redirectUrl) {
                                        location.href = response.redirectUrl;                                        
                                    } else {
                                        alert(response.errorMessage || "Invalid data received from server");
                                        submitEnabled = true;
                                    }
                                })
                                .fail(function() {
                                    submitEnabled = true;
                                });
                        }
                    }
                    
                    // Don\'t actually submit
                    event.preventDefault();
                    return false;
                });
        ';
        if ($showForgotPassword) {
            $out = $out . '
                var submitEnabled = true;
                var captchaId = null;
                
                function stopEvent(event) {
                    event.preventDefault();
                    return false;
                }
                
                function showLogin() {
                    if (submitEnabled) {
                        $(".cyclosLoginForm").trigger("reset");
                        $(".cyclosLoginContainer").show();
                        $(".cyclosForgotContainer").hide();
                        $(".cyclosPrincipal").focus();
                    }
                }
                
                function showForgotPassword() {
                    if (submitEnabled) {
                        $(".cyclosForgotPasswordForm").trigger("reset");
                        $(".cyclosLoginContainer").hide();
                        $(".cyclosForgotContainer").show();
                        $(".cyclosEmail").focus();
                        if (captchaId == null) {
                            newCaptcha();
                        }
                    }
                }
                
                function newCaptcha() {
                    var img = $(".cyclosCaptchaImage");
                    img.css("opacity", 0.2);
                    $.post("' . admin_url('admin-ajax.php?action=cyclos_captcha') . '")
                        .done(function(response) {
                            captchaId = (response || {}).id;
                            if (captchaId != null) {
                                img.attr("src", "data:image/png;base64," + response.content);
                                img.css("opacity", 1);
                                img.show();
                            } else {
                                img.hide();
                            }
                        });
                }
                
                $(".cyclosForgotPasswordForm").submit(function(event) {
                    if (submitEnabled) {
                        var email = this.cyclosEmail.value.trim();
                        var captchaText = this.cyclosCaptcha.value.trim();
                        
                        if (email != "" && captchaText != "") {
                            var data = {
                                "email": email,
                                "captchaText": captchaText,
                                "captchaId": captchaId
                            }

                            submitEnabled = false;
                            
                            $.post("' . admin_url('admin-ajax.php?action=cyclos_forgot_password') . '", data)
                                .done(function(response) {
                                    submitEnabled = true;
                                    response = response || {};
                                    if (response.errorMessage) {
                                        alert(response.errorMessage || "Invalid data received from server");
                                    } else {
                            			captchaId = null;
                                        showLogin();
                                        alert("' . $t->forgotDone . '".replace("{email}", email));
                                    }
                                })
                                .fail(function() {
                                    submitEnabled = true;
                                });
                        }
                    }
                    
                    // Don\'t actually submit
                    event.preventDefault();
                    return false;
                });
                
                $(".cyclosForgotLink").click(showForgotPassword).click(stopEvent);
                $(".cyclosForgotCancel").click(showLogin).click(stopEvent);
                $(".cyclosNewCaptcha").click(function() {
                    newCaptcha();
                    var cyclosCaptcha = $("input[name=cyclosCaptcha]");
                    cyclosCaptcha.val("");
                    cyclosCaptcha.focus();
                }).click(stopEvent);            
            ';
        }
        $out = $out . '
        		});
        </script>
        ';
    }
    return $out;
}

/*#################### Ajax action handler to perform the Cyclos login ####################*/
add_action( 'wp_ajax_cyclos_login', 'cyclosLogin' );
add_action( 'wp_ajax_nopriv_cyclos_login', 'cyclosLogin' );
function cyclosLogin() {
    configureCyclos();
    $sessionsService = new Cyclos\SessionsService();
    
    $t = cyclosGetTranslations();

    $redirectUrl = NULL;
    $errorMessage = NULL;
    
    $returnTo = $_POST['returnTo'];        

    // Perform the login
    try {        
        $result = $sessionsService->loginUser(
        			sanitize_text_field($_POST['principal']), 
        			sanitize_text_field($_POST['password']),
        			$_SERVER['REMOTE_ADDR']);       
    } catch (\Exception $e) {    	
        $errorMessage = handleError($e);
    }
    // Get the redirect url if there were no errors
    if (!empty($result)) {
    	// Check if there is a redirect URL defined otherwise go to classic frontend
    	$redirectUrl = Cyclos\Configuration::getRedirectUrl();    	
    	if(empty($redirectUrl)) {
    		$redirectUrl = Cyclos\Configuration::getRootUrl();    		
    	}    	
    
    	// Replace with the relative path of the external site (may apply when the
    	// session expires so we can redirect the user to the last visited page)    	    	
    	if(strpos($redirectUrl, '%p') !== false) {	    		
			// Append path at the specified position
	    	$redirectUrl = str_replace('%p', (empty($returnTo) ? '/' : $returnTo), $redirectUrl);
		} else {	    		
	    	// Append path at the end
	    	$separator = strripos($redirectUrl, '/') !== false ? '' : '/';
	    	$redirectUrl .= $separator . (empty($returnTo) ? '' : $returnTo);		    	    	
    	}    	
    	// Replace with session token
    	if(strpos($redirectUrl, '%s') !== false) {
    		// Append session token at the specified position
    		$redirectUrl = str_replace('%s', $result->sessionToken, $redirectUrl);
    	} else {
    		// Append session token at the end    		
    		$separator = strripos($redirectUrl, '?') !== false ? '&' : '?';
    		$redirectUrl .= $separator . 'sessionToken='.$result->sessionToken;    		
    	}
    }

    // Send the JSON response
    $response = array(
        'redirectUrl' => $redirectUrl,        
        'errorMessage' => $errorMessage
    );
    wp_send_json($response);
}

/*#################### Ajax action handler to generate a new captcha ####################*/
add_action( 'wp_ajax_cyclos_captcha', 'cyclosCaptcha' );
add_action( 'wp_ajax_nopriv_cyclos_captcha', 'cyclosCaptcha' );
function cyclosCaptcha() {
    configureCyclos();
    $captchaService = new Cyclos\CaptchaService();

    $t = cyclosGetTranslations();
    
    $id = NULL;
    $content = NULL;
    $errorMessage = NULL;
    
    // Generate a captcha
    try {
        $id = $captchaService->newCaptcha();
        $content = base64_encode($captchaService->getCaptchaContent($id));
    } catch (\Exception $e) {
        $errorMessage = handleError($e);
    }
    
    // Send the JSON response
    $response = array(
        "errorMessage" => $errorMessage,
        "id" => $id,
        "content" => $content
    );
    wp_send_json($response);
}

/*#################### Ajax action handler to send the forgot password request ####################*/
add_action( 'wp_ajax_cyclos_forgot_password', 'forgotPassword' );
add_action( 'wp_ajax_nopriv_cyclos_forgot_password', 'forgotPassword' );
function forgotPassword() {
	
    configureCyclos();
    $authService = new Cyclos\AuthService();

    $t = cyclosGetTranslations();
    
    // Send the request
    $errorMessage = NULL;
        
    try {
        $captchaResponse = new stdclass();       
        $captchaResponse->challenge = sanitize_text_field($_POST["captchaId"]);
        $captchaResponse->response = sanitize_text_field($_POST["captchaText"]);       
        $authService->forgottenPasswordRequest(
        		sanitize_text_field($_POST["email"]),
        		$captchaResponse);
    } catch (\Exception $e) {    	
        $errorMessage = handleError($e);
    }
    
    // Send the JSON response
    $response = array(
        "errorMessage" => $errorMessage
    );
    wp_send_json($response);
}
?>
