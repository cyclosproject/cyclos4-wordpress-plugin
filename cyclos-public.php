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

/*#################### Creates shortcode and inserts login form ####################*/
add_shortcode( 'cycloslogin', 'cyclosLoginForm' );

// Register style sheet
add_action( 'wp_enqueue_scripts', 'registerCyclosStyles' );

// Create the widget to display the login form
add_action('widgets_init', create_function('', 'return register_widget("CyclosPlugin");'));
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
    wp_register_style( 'cyclosWordpressStylesheet', plugin_dir_url( __FILE__) . 'stylesheet.css');
    wp_enqueue_style( 'cyclosWordpressStylesheet' );
}    

// Function which returns an appropriate error message for a validation exception
function validationExceptionMessage($e) {
    $val = $e->error->validation;
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
        return "Validation error";
    } else {
        return implode("\n", $errors);
    }
}

// Function that is called when the shortcode is used
function cyclosLoginForm($atts) {

    $t = cyclosGetTranslations();
    
    // Returns the html to show when shortcode is used.
    $out = '
        <div class="cyclosFormBox cyclosForgotContainer" style="display:none">
            <form class="cyclosForgotPasswordForm" action="#" method="post">
                <div class="cyclosFormTitle">' . $t->forgotTitle . '</div>
                <div class="cyclosFormField">
                    <input placeholder="' . $t->forgotEmail . '" name="cyclosEmail" type="email" required>
                </div>
                <div class="cyclosFormField">
                    <div>
                        <input placeholder="' . $t->forgotCaptcha . '" class="cyclosCaptcha" type="text" style="float: left; width: 60%" required>
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
        <div class="cyclosFormBox cyclosLoginContainer">
            <form class="cyclosLoginForm" action="#" method="post">
                <div class="cyclosFormTitle">' . $t->loginTitle . '</div>
                <div class="cyclosFormField">
                    <input placeholder="' . $t->loginName . '" name="cyclosPrincipal" type="text" required>
                </div>
                <div class="cyclosFormField">
                    <input placeholder="' . $t->loginPassword . '" name="cyclosPassword" type="password" required>
                </div>
                <div class="cyclosFormActions">
                    <input type="submit" class="cyclosLoginSubmit" value="' . $t->loginSubmit . '">
                    <div class="cyclosForgotPasswordBox">
                        <a class="cyclosForgotLink" href="#">' . $t->forgotLink . '</a>
                    </div>
                </div>
            </form>
        </div>';
    if (!array_key_exists('cyclos_behavior_applied', $GLOBALS)) {
        $GLOBALS['cyclos_behavior_applied'] = true;
        $out = $out . '
        <script>
            jQuery(document).ready(function($) {
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
                
                $(".cyclosLoginForm").submit(function(event) {
                    if (submitEnabled) {
                        var principal = this.cyclosPrincipal.value.trim();
                        var password = this.cyclosPassword.value.trim();
                        
                        if (principal != "" && password != "") {
                            var data = {
                                "principal": principal,
                                "password": password
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
                
                $(".cyclosForgotPasswordForm").submit(function(event) {
                    if (submitEnabled) {
                        var email = this.cyclosEmail.trim();
                        var captchaText = this.cyclosCaptcha.trim();
                        
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
                    var cyclosCaptcha = $(".cyclosCaptcha");
                    cyclosCaptcha.val("");
                    cyclosCaptcha.focus();
                }).click(stopEvent);
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
    $loginService = new Cyclos\LoginService();
    
    $t = cyclosGetTranslations();

    $redirectUrl = NULL;
    $errorMessage = NULL;
    
    // Perform the login
    try {
        $params = new stdclass();
        $params->user = array("principal" => sanitize_text_field($_POST["principal"]));
        $params->password = sanitize_text_field($_POST["password"]);
        $params->remoteAddress = $_SERVER['REMOTE_ADDR'];
        $result = $loginService->loginUser($params);
    } catch (Cyclos\ConnectionException $e) {
        $errorMessage = "Cyclos server couldn't be contacted";
    } catch (Cyclos\ServiceException $e) {
        switch ($e->errorCode) {
            case 'VALIDATION':
                $errorMessage = validationExceptionMessage($e);
                break;
            case 'LOGIN':
            case 'ENTITY_NOT_FOUND':
                $errorMessage = $t->errorLogin;
                break;
            case 'REMOTE_ADDRESS_BLOCKED':
                $errorMessage = $t->errorAddressBlocked;
                break;
            default:
                $errorMessage = "Error while performing login: {$e->errorCode}";
                break;
        }
    }
    
    // Get the redirect url if there were no errors
    if (!empty($result)) {
        $redirectUrl = Cyclos\Configuration::getRootUrl() . "?sessionToken=" . $result->sessionToken;
    }

    // Send the JSON response
    $response = array(
        "redirectUrl" => $redirectUrl,
        "errorMessage" => $errorMessage
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
        $id = $captchaService->generate();
        $content = $captchaService->readImage($id, null);
    } catch (Cyclos\ConnectionException $e) {
        $errorMessage = "Cyclos server couldn't be contacted";
    } catch (Cyclos\ServiceException $e) {
        $errorMessage = "Error while retrieving the image: {$e->errorCode}";
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
    $passwordService = new Cyclos\PasswordService();

    $t = cyclosGetTranslations();
    
    // Send the request
    $errorMessage = NULL;
    try {
        $params = new stdclass();
        $params->email = sanitize_text_field($_POST["email"]);
        $params->captchaId = sanitize_text_field($_POST["captchaId"]);
        $params->captchaText = sanitize_text_field($_POST["captchaText"]);
        $passwordService->forgotPasswordRequest($params);
    } catch (Cyclos\ConnectionException $e) {
        $errorMessage = "Cyclos server couldn't be contacted";
    } catch (Cyclos\ServiceException $e) {
        switch ($e->errorCode) {
            case 'ENTITY_NOT_FOUND':
                $errorMessage = $t->errorEmailNotFound;
                break;
            case 'VALIDATION':
                $errorMessage = validationExceptionMessage($e);
                break;
            default:
                $errorMessage = "Error while processing the request: {$e->errorCode}";
                break;
        }
    }
    
    // Send the JSON response
    $response = array(
        "errorMessage" => $errorMessage
    );
    wp_send_json($response);
}
?>
