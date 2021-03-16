<?php
/**
 * Class that handles calls to the Cyclos REST API.
 *
 * @package Cyclos
 */

namespace Cyclos\Services;

use Cyclos\Configuration;
use Cyclos\Services\Cyclos4 as Cyclos4;

/**
 * The CyclosAPI class.
 */
class CyclosAPI {

	/**
	 * The configuration.
	 *
	 * @var Configuration $conf The configuration.
	 */
	private $conf;

	/**
	 * Constructor.
	 *
	 * @param Configuration $conf The configuration.
	 */
	public function __construct( Configuration $conf ) {
		$this->conf = $conf;
	}

	/**
	 * Method to let a user login into Cyclos, returning the Cyclos URL to redirect the user to.
	 *
	 * @param string $username        The username to login with.
	 * @param string $password        The password to login with.
	 * @param string $remote_address  The remote address from which to login.
	 * @param string $return_to       (Optional) The Cyclos returnTo request parameter indicating where to go within Cyclos after logging in.
	 * @return array                  Array containing the redirect URL to the Cyclos server or an errormessage on failure.
	 */
	public function login( string $username, string $password, string $remote_address, string $return_to = '' ) {
		$redirect_url  = '';
		$error_message = '';

		// Use the sessions service to log the user in and retrieve a sessionToken.
		$cyclos_service  = new Cyclos4\SessionsService( $this->conf );
		$cyclos_response = $cyclos_service->login_user( $username, $password, $remote_address );

		// Set the redirect URL or an error message, depending on whether we have an error situation or not.
		if ( is_wp_error( $cyclos_response ) ) {
			$error_message = $this->handle_error( $cyclos_response );
		} else {
			// Note: the sessionToken variable is in the json we receive from Cyclos. So ignore the coding standard for snake case on this line.
			$session_token = $cyclos_response->sessionToken ?? ''; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			if ( ! empty( $session_token ) ) {
				// We have a session token, so build up the Cyclos URL we can redirect to.

				// The Cyclos base URL is either the custom frontend URL or the Cyclos URL that is also used for the REST API.
				$redirect_url = $this->conf->get_custom_cyclos_frontend_url( false );
				if ( empty( $redirect_url ) ) {
					$redirect_url = $this->conf->get_cyclos_url( false );
				}

				// The custom frontend URL setting can have %s and %p tokens we must replace.
				// If there are no tokens, we use the standard pattern with the session token and path as query parameters.
				$query_args = array();
				if ( strpos( $redirect_url, '%s' ) !== false ) {
					$redirect_url = str_replace( '%s', $session_token, $redirect_url );
				} else {
					$query_args['sessionToken'] = $session_token;
				}
				if ( strpos( $redirect_url, '%p' ) !== false ) {
					$redirect_url = str_replace( '%p', ( $return_to ?? '' ), $redirect_url );
				} else {
					if ( ! empty( $return_to ) ) {
						$query_args['returnTo'] = $return_to;
					}
				}
				if ( count( $query_args ) > 0 ) {
					$redirect_url .= ( strpos( $redirect_url, '?' ) === false ? '?' : '&' ) . http_build_query( $query_args );
				}
			}
		}

		return array(
			'redirectUrl'  => $redirect_url,
			'errorMessage' => $error_message,
		);
	}

	/**
	 * Method to activate an accessclient, returning the accessclient token.
	 *
	 * @param string $url             The Cyclos URL to call.
	 * @param string $username        The username to authenticate with.
	 * @param string $password        The password to authenticate with.
	 * @param string $activation_code The activation code.
	 * @return string|\WP_Error       The accessclient token or a WP_Error object on failure.
	 */
	public function generate_token( string $url, string $username, string $password, string $activation_code ) {
		// Use the AccessClientService to activate the accessclient, returning the accessclient token.
		$cyclos_service  = new Cyclos4\AccessClientService( $this->conf );
		$cyclos_response = $cyclos_service->activate( $url, $username, $password, $activation_code );

		// If the request failed, return a WP_Error object with the error message.
		if ( is_wp_error( $cyclos_response ) ) {
			$message = $this->handle_error( $cyclos_response );
			return new \WP_Error( 'CYCLOS_EXCEPTION', $message );
		}

		// If we have no error, return the token.
		return $cyclos_response->token ?? '';
	}

	/**
	 * Returns information on the Cyclos user used for the connection to Cyclos.
	 *
	 * @return array   An array with status (error, warning or success) and a message containing information about the connection.
	 */
	public function get_connection() {
		// Use the AuthService to get information about the current user, i.e. the wp_admin_user configured in the plugin settings.
		$cyclos_service  = new Cyclos4\AuthService( $this->conf );
		$cyclos_response = $cyclos_service->get_current_user_info();

		// If the request indicates an error, set the status to 'error' and set the error message.
		if ( is_wp_error( $cyclos_response ) ) {
			$message = $this->handle_error( $cyclos_response );
			$status  = 'error';
		} else {
			// The user exists in Cyclos and the account has no problems. Now check if it has all the required permissions.
			// Note: when a property does not exist, we cannot check it, so we skip the check and don't warn (fallback to default true).
			// This happens when the REST API does not contain a property yet in older Cyclos versions.
			$can_login        = $cyclos_response->permissions->sessions->login ?? true;
			$can_view_profile = $cyclos_response->permissions->users->viewProfile ?? true;
			$can_view_map     = $cyclos_response->permissions->users->map ?? true;

			// Check for problems for the active components.
			$login_problem        = $this->conf->is_active( 'login_form' ) && ! $can_login;
			$view_profile_problem = $this->conf->is_active( 'login_form' ) && ! $can_view_profile;
			$view_map_problem     = $this->conf->is_active( 'user_directory' ) && ! $can_view_map;
			// If one of the required permissions is not set correctly, set the status to 'warning' and set the error message to indicate the problem.
			if ( $login_problem ) {
				/* translators: 'Login users via web services' is a string in Cyclos. Leave as-is or use the default translation for USERS.PRODUCTS.loginUsers from the Cyclos crowdin project. This text may have a custom translation in Cyclos however. */
				$message = __( "The Cyclos user needs permission to login other users. Please correct its group permission in Cyclos: set the 'Login users via web services' permission to 'Yes'.", 'cyclos' );
				$status  = 'warning';
			} elseif ( $view_profile_problem ) {
				/* translators: 'Accessible user groups' and 'All groups' are strings in Cyclos. Leave as-is or use the default translation for USERS.PRODUCTS.userGroupAccessibility and USERS.PRODUCTS.userGroupAccessibility.ALL from the Cyclos crowdin project. These texts may have a custom translation in Cyclos however. */
				$message = __( "The Cyclos user needs permission to access user groups. Please correct its group permission in Cyclos: set the 'Accessible user groups' permission to 'All groups'.", 'cyclos' );
				$status  = 'warning';
			} elseif ( $view_map_problem ) {
				/* translators: 'View user directory (map) on groups, 'Accessible user groups' and 'All groups' are strings in Cyclos. Leave as-is or use the default translation for USERS.PRODUCTS.userDirectoryOnGroups, USERS.PRODUCTS.userGroupAccessibility and USERS.PRODUCTS.userGroupAccessibility.ALL from the Cyclos crowdin project. These texts may have a custom translation in Cyclos however. */
				$message = __( "The Cyclos user needs permission to view the user map directory. Please correct its group permission in Cyclos: set the 'View user directory (map) on groups' permission to 'All groups', possibly setting 'Accessible user groups' to 'All groups' first, otherwise the 'View user directory (map) on groups' permission can not be set.", 'cyclos' );
				$status  = 'warning';
			} else {
				// Everything is fine. Set the status to 'success' and set a success message.
				// Note: There might still be a problem if 'Login name' is not set to 'Visible' and 'User keywords', but there is no way for us to check automatically.
				$message = __( 'The connection to Cyclos was setup successfully.', 'cyclos' );
				$status  = 'success';
			}
		}

		return array(
			'status'  => $status,
			'message' => $message,
		);
	}

	/**
	 * Returns an array with information needed for the forgot password functionality.
	 *
	 * @return array {
	 *     @type boolean $is_forgot_password_enabled  Whether the forgotten password functionality is enabled in Cyclos or not.
	 *     @type string $captcha_provider             The captcha provider as configured in Cyclos ('internal' or 'recaptchaV2'), or 'disabled' if no captcha is configured.
	 *     @type string $recaptchav2_sitekey          The site key for Google recaptcha V2 if this is used as the captcha provider, or empty string if not.
	 *     @type boolean $is_captcha_enabled          Whether the captcha functionality is enabled in Cyclos or not.
	 *     @type boolean $has_complex_forgot_password Whether the current Cyclos version uses a more complex forgot password wizard.
	 *     @type Array $forgot_password_mediums       List of mediums the user can choose to receive the forgot password verification code.
	 * }
	 */
	public function login_configuration() {
		// Use the AuthService to get the data for login, containing information for the forgot password functionality.
		$cyclos_service  = new Cyclos4\AuthService( $this->conf );
		$cyclos_response = $cyclos_service->get_data_for_login();

		// If the request indicates an error, return false.
		if ( is_wp_error( $cyclos_response ) ) {
			return array();
		}

		// If we have no error, return an array with all relevant login configuration information.
		// From Cyclos 4.13 on, the forgot password construction is more complex, requiring a small wizard in our login template.
		// We use the existence of the identityProviders property that was added in 4.13 to indicate this.
		// Note: the variables in the json we receive from Cyclos. So disable the coding standard for snake case on the following lines.
		// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		// The password type must be manual and there must be a medium to reset it. Otherwise we can not do a forgotten password request.
		$login_pw_mode        = $cyclos_response->loginPasswordInput->mode ?? '';
		$is_forgot_pw_allowed = ( 'manual' === $login_pw_mode ) && ! empty( $cyclos_response->forgotPasswordMediums );
		$captcha_provider     = $cyclos_response->forgotPasswordCaptchaInput->provider ?? $cyclos_response->forgotPasswordCaptchaProvider ?? 'disabled';
		$is_captcha_enabled   = ( 'internal' === $captcha_provider );
		return array(
			'is_forgot_password_enabled'  => $is_forgot_pw_allowed,
			'captcha_provider'            => $captcha_provider,
			'recaptchav2_sitekey'         => $cyclos_response->forgotPasswordCaptchaInput->recaptchaKey ?? '',
			'is_captcha_enabled'          => $is_captcha_enabled,
			'has_complex_forgot_password' => isset( $cyclos_response->identityProviders ),
			'forgot_password_mediums'     => $cyclos_response->forgotPasswordMediums,
		);
		// phpcs:enable
	}

	/**
	 * Retrieves a new captcha from Cyclos and returns its ID and image contents.
	 *
	 * @return array {
	 *     @type $id             The ID of the captcha.
	 *     @type $content        The base64 encoded binary content of the captcha image.
	 *     @type $errorMessage   The error message or empty string if no error occured.
	 * }
	 */
	public function get_captcha() {
		$id            = '';
		$content       = '';
		$error_message = '';

		// Request a new captcha from Cyclos.
		$cyclos_service  = new Cyclos4\CaptchaService( $this->conf );
		$cyclos_response = $cyclos_service->new_captcha();

		// If the request indicates an error, set the error message.
		if ( is_wp_error( $cyclos_response ) ) {
			$error_message = $this->handle_error( $cyclos_response );
		} else {
			// If we have no error, the response should contain a captcha ID.
			// Use this to request the captcha image contents.
			$id = $cyclos_response ?? '';
			if ( ! empty( $id ) ) {
				// Retrieve the image content for the given captcha ID.
				$cyclos_response = $cyclos_service->get_captcha_content( $id );
				if ( is_wp_error( $cyclos_response ) ) {
					$error_message = $this->handle_error( $cyclos_response );
				} else {
					// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
					$content = base64_encode( $cyclos_response );
				}
			}
		}

		// Return the array with captcha information.
		return array(
			'id'           => $id,
			'content'      => $content,
			'errorMessage' => $error_message,
		);
	}

	/**
	 * Method to let a user request a forgotten password reset.
	 * This is only used on Cyclos versions before 4.13. From 4.13 on, the forgot password is handled via a wizard.
	 *
	 * @param string $principal        The principal (i.e. username, e-mail, ..) to identify the user with.
	 * @param string $captcha_id       The ID of the captcha challenge.
	 * @param string $captcha_response The response for the captcha challenge.
	 * @return array                   Array containing a successmessage or an errormessage on failure.
	 */
	public function forgot_password( string $principal, string $captcha_id, string $captcha_response ) {
		$success_message = '';
		$error_message   = '';

		// Use the authentication service to request the password reset.
		$cyclos_service  = new Cyclos4\AuthService( $this->conf );
		$cyclos_response = $cyclos_service->forgotten_password_request( $principal, $captcha_id, $captcha_response );

		// Set the error message, depending on whether we have an error situation or not.
		if ( is_wp_error( $cyclos_response ) ) {
			$error_message = $this->handle_error( $cyclos_response );
		} else {
			$success_message = __( 'You will receive an e-mail shortly with your user identification and instructions on how to reset your password', 'cyclos' );
		}

		return array(
			'successMessage' => $success_message,
			'errorMessage'   => $error_message,
		);
	}

	/**
	 * Method to let a user request a forgotten password reset using the new Cyclos mechanism, requiring a small wizard with steps.
	 * This method handles step 1 of the wizard.
	 *
	 * @param string $principal        The principal (i.e. username, e-mail, ..) to identify the user with.
	 * @param string $captcha_id       The ID of the captcha challenge.
	 * @param string $captcha_response The response for the captcha challenge.
	 * @param string $send_medium      The medium (email/sms) to use for sending the verification code to the visitor.
	 * @return array                   Array containing a successmessage or an errormessage on failure.
	 */
	public function forgot_password_step_request( string $principal, string $captcha_id, string $captcha_response, string $send_medium ) {
		$success_message = '';
		$error_message   = '';

		// Use the authentication service to request the password reset.
		$cyclos_service  = new Cyclos4\AuthService( $this->conf );
		$cyclos_response = $cyclos_service->forgotten_password_request( $principal, $captcha_id, $captcha_response, $send_medium );

		// Set the error or success message, depending on whether we have an error situation or not.
		if ( is_wp_error( $cyclos_response ) ) {
			$error_message = $this->handle_error( $cyclos_response );
		} else {
			$sent_to = $cyclos_response->sentTo; // phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			if ( is_array( $sent_to ) && count( $sent_to ) > 0 ) {
				$success_message = __( 'A verification code has been sent to', 'cyclos' ) . ' ' . $sent_to[0];
			} else {
				$error_message = __( 'Something went wrong while trying to send you the verification code. Please contact the administration.', 'cyclos' );
			}
		}

		return array(
			'successMessage' => $success_message,
			'errorMessage'   => $error_message,
		);
	}

	/**
	 * Method to handle the verification code step in a user request for a forgotten password reset.
	 * This method handles step 2 of the wizard.
	 *
	 * @param string $principal        The principal (i.e. username, e-mail, ..) to identify the user with.
	 * @param string $code             The verification code which was sent to the user.
	 * @return array                   Array containing the security question (or empty string if not enabled in Cyclos) or an errormessage on failure.
	 */
	public function forgot_password_step_code( string $principal, string $code ) {
		$error_message = '';
		$sec_question  = '';

		// Use the authentication service to request the password reset code step.
		$cyclos_service  = new Cyclos4\AuthService( $this->conf );
		$cyclos_response = $cyclos_service->forgotten_password_data_for_change( $principal, $code );

		// Set the error if we have an error situation or fill the security question if available.
		if ( is_wp_error( $cyclos_response ) ) {
			$error_message = $this->handle_error( $cyclos_response );
		} else {
			if ( isset( $cyclos_response->securityQuestion ) ) {
				$sec_question = __( 'Please answer your security question', 'cyclos' ) . ': ' . $cyclos_response->securityQuestion;
			}
		}

		return array(
			'passwordHint'     => $cyclos_response->passwordType->description ?? '',
			'securityQuestion' => $sec_question,
			'errorMessage'     => $error_message,
		);
	}

	/**
	 * Method to handle the change step in a user request for a forgotten password reset.
	 * This method handles step 3 of the wizard.
	 *
	 * @param string $principal        The principal (i.e. username, e-mail, ..) to identify the user with.
	 * @param string $code             The verification code which was sent to the user.
	 * @param string $new_password     The new password.
	 * @param string $confirm_password The new password again as a way of confirmation.
	 * @param string $security_answer  (Optional) The answer to the security question if one was used.
	 * @return array                   Array containing a successmessage or an errormessage on failure.
	 */
	public function forgot_password_step_change( string $principal, string $code, string $new_password, string $confirm_password, string $security_answer = null ) {
		$success_message = '';
		$error_message   = '';

		// Use the authentication service to reset the password.
		$cyclos_service  = new Cyclos4\AuthService( $this->conf );
		$cyclos_response = $cyclos_service->forgotten_password( $principal, $code, $new_password, $confirm_password, $security_answer );

		// Set the error message, depending on whether we have an error situation or not.
		if ( is_wp_error( $cyclos_response ) ) {
			$error_message = $this->handle_error( $cyclos_response );
		} else {
			$success_message = __( 'Your password has been reset', 'cyclos' );
		}

		return array(
			'successMessage' => $success_message,
			'errorMessage'   => $error_message,
		);
	}

	/**
	 * Returns configuration data for searching Cyclos users. Used for the user directory (map/list).
	 *
	 * @return array|\WP_Error     Array with user metadata or a WP_Error object on failure.
	 */
	public function get_user_metadata() {
		// Use the users service to request the user metadata.
		$cyclos_service  = new Cyclos4\UsersService( $this->conf );
		$cyclos_response = $cyclos_service->get_data_for_search();

		// If the request failed, return a WP_Error object with the error message.
		if ( is_wp_error( $cyclos_response ) ) {
			$message = $this->handle_error( $cyclos_response );
			return new \WP_Error( 'CYCLOS_EXCEPTION', $message );
		}

		// If we have no error, return the response containing the user metadata array.
		return $cyclos_response;
	}

	/**
	 * Returns data of Cyclos users. Used for the user directory (map/list).
	 *
	 * @return array|\WP_Error     Array with user data or a WP_Error object on failure.
	 */
	public function get_user_data() {
		// Use the users service to request the user data.
		$cyclos_service  = new Cyclos4\UsersService( $this->conf );
		$group           = $this->conf->get_user_group();
		$order_by        = $this->conf->get_user_data_sort( false );
		$cyclos_response = $cyclos_service->search_user_directory( $group, $order_by );

		// If the request failed, return a WP_Error object with the error message.
		if ( is_wp_error( $cyclos_response ) ) {
			$message = $this->handle_error( $cyclos_response );
			return new \WP_Error( 'CYCLOS_EXCEPTION', $message );
		}

		// If we have no error, return the response containing the user data array.
		return $cyclos_response;
	}

	/**
	 * Return a custom error message that describes what went wrong with the request to the Cyclos API.
	 *
	 * @param \WP_Error $error   The WP_Error object with information about the error.
	 * @return string            A message indicating what went wrong.
	 */
	protected function handle_error( \WP_Error $error ) {
		$code    = $error->get_error_code();
		$message = $error->get_error_message();

		if ( 'CYCLOS_EXCEPTION' === $code ) {
			// Use a translation message for each of the possible 401/403 error situations, according to the schema of the REST API.
			// Skipped values that are irrelevant for our situation: devicePinRemoved, invalidDeviceActivationCode, invalidDeviceConfirmation.
			$error_codes = array(
				'blockedAccessClient'           => __( 'The access client used for access is blocked', 'cyclos' ),
				'invalidAccessClient'           => __( 'The access client used for access is invalid', 'cyclos' ),
				'invalidAccessToken'            => __( 'The OAuth2 / OpenID Connect access token used for access is invalid', 'cyclos' ),
				'invalidChannelUsage'           => __( 'Attempt to login on a stateless-only channel, or use stateless in a stateful-only channel, or invoke as guest in a channel configuration which is only for users', 'cyclos' ),
				'invalidNetwork'                => __( 'Attempt to access a network that has been disabled', 'cyclos' ),
				'loggedOut'                     => __( 'The session token used for access is invalid', 'cyclos' ),
				'login'                         => __( 'The username / password combination is not correct', 'cyclos' ),
				'missingAuthorization'          => __( 'Attempt to access an operation as guest, but the operation requires authentication', 'cyclos' ),
				'remoteAddressBlocked'          => __( 'Your IP address is blocked by exceeding invalid login attempts', 'cyclos' ),
				'unauthorizedAddress'           => __( 'Your IP address is not white-listed', 'cyclos' ),
				'unauthorizedUrl'               => __( 'Access from this URL is not allowed', 'cyclos' ),
				'expiredPassword'               => __( 'Your password has expired', 'cyclos' ),
				'illegalAction'                 => __( 'This action is not allowed on this context', 'cyclos' ),
				'inaccessibleChannel'           => __( 'You don\'t have access to the main channel', 'cyclos' ),
				'inaccessiblePrincipal'         => __( 'The used identification method (principal type) cannot be used in this channel', 'cyclos' ),
				'indefinitelyBlocked'           => __( 'Your password was indefinitely blocked by exceeding the allowed attempts', 'cyclos' ),
				'invalidPassword'               => __( 'Your password is invalid', 'cyclos' ),
				'operatorWithPendingAgreements' => __( 'You cannot access because your owner member has pending agreements', 'cyclos' ),
				'pendingAgreements'             => __( 'There is at least one agreement which needs to be accepted in order to access the system', 'cyclos' ),
				'permissionDenied'              => __( 'The operation was denied because a required permission was not granted', 'cyclos' ),
				'resetPassword'                 => __( 'Your password was manually reset', 'cyclos' ),
				'temporarilyBlocked'            => __( 'Your access is temporarily blocked by exceeding the allowed attempts', 'cyclos' ),
				'user-blocked'                  => __( 'Your account is blocked', 'cyclos' ),
				'user-disabled'                 => __( 'Your account is disabled', 'cyclos' ),
				'user-pending'                  => __( 'Your account is pending', 'cyclos' ),
				'user-purged'                   => __( 'Your account is purged', 'cyclos' ),
				'user-removed'                  => __( 'Your account is removed', 'cyclos' ),
				'pw-disabled'                   => __( 'Your password is disabled', 'cyclos' ),
				'pw-expired'                    => __( 'Your password is expired', 'cyclos' ),
				'pw-indefinitelyBlocked'        => __( 'Your password is indefinitely blocked', 'cyclos' ),
				'pw-neverCreated'               => __( 'Your password was not created', 'cyclos' ),
				'pw-pending'                    => __( 'Your password is pending', 'cyclos' ),
				'pw-reset'                      => __( 'Your password was reset', 'cyclos' ),
				'pw-temporarilyBlocked'         => __( 'Your password is temporarily blocked by exceeding invalid login attempts', 'cyclos' ),
				'keyInvalidated'                => __( 'The code received on the forgotten password reset request was invalidated because the maximum number of tries was reached', 'cyclos' ),
				'invalidSecurityAnswer'         => __( 'The answer for the security question was incorrect', 'cyclos' ),
				'unexpected'                    => __( 'An unexpected error has occurred', 'cyclos' ),
			);

			// Check the error information from Cyclos. The message contains the HTTP status code, the data contains the response.
			$status_code = $message;
			$response    = $error->get_error_data();

			// Parse the response data to retrieve the error message. The structure of the response differs per status code.
			// Note: the variables in the json we receive from Cyclos. So disable the coding standard for snake case here.
			// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
			switch ( $status_code ) {
				case '401':
				case '403':
					// The response contains a "code" property.
					$code = $response->code ?? '';

					if ( 'login' === $code ) {
						// The response might contain more information on the exact problem with the login in the userStatus property.
						$user_status = $response->userStatus ?? 'active';
						if ( 'active' !== $user_status ) {
							$code = 'user-' . $user_status;
						}
						// The response might contain more information on the exact problem with the login in the passwordStatus property.
						$password_status = $response->passwordStatus ?? 'active';
						if ( 'active' !== $password_status ) {
							$code = 'pw-' . $password_status;
						}
					}
					// Look up the corresponding message in our error codes table.
					$message = $error_codes[ $code ] ?? '';
					break;
				case '404':
					// The response contains an "entityType" and "key" property.
					if ( isset( $response->entityType ) && isset( $response->key ) ) {
						$message = sprintf(
							/* translators: 1: The name of the entity being attempted, but not found. 2: The identifier used to attempt to find the entity. */
							__( 'The %1$s "%2$s" is not found', 'cyclos' ),
							$response->entityType,
							$response->key
						);
					} else {
						// Somehow the response does not contain information on the entity that was not found. Set a more general error message then.
						$message = __( 'The requested information was not found in Cyclos', 'cyclos' );
					}
					break;
				case '422':
					$errors = array();
					// The response might contain a "generalErrors" property.
					if ( isset( $response->generalErrors ) ) {
						$errors[] = implode( ', ', $response->generalErrors );
					}
					// The response might contain a "properties" and "propertyErrors" property.
					if ( isset( $response->properties ) && isset( $response->propertyErrors ) ) {
						// Loop through the properties and find the corresponding error(s) for each.
						foreach ( $response->properties as $key ) {
							$errors[] = implode( ', ', $response->propertyErrors->$key );
						}
					}
					if ( empty( $errors ) ) {
						$message = __( 'The request could not be processed', 'cyclos' );
					} else {
						$message = implode( ', ', $errors );
					}
					break;
				case '500':
					// The response contains a "kind" property.
					$code = $response->kind ?? '';

					// Handle security question errors differentely.
					if ( 'forgottenPassword' === $code ) {
						// Check the code property in the response.
						$forgotten_pw_error = $response->code ?? 'unexpected';
						if ( 'invalidSecurityAnswer' === $forgotten_pw_error ) {
							// Check if the key/code for requesting a password reset is invalid due to reaching the max nr of tries.
							$is_invalid_key = $response->keyInvalidated ?? false;
						}
						// Use the proper code to look up the message in our error_codes table above.
						$code = $is_invalid_key ? 'keyInvalidated' : $forgotten_pw_error;
					}
					// Look up the corresponding message in our error codes table.
					$message = $error_codes[ $code ] ?? '';
					break;
				default:
					$message = __( 'Unknown error', 'cyclos' );
					break;
			}
			if ( empty( $message ) ) {
				$message = sprintf(
					/* translators: 1: statuscode 2: internal errorcode */
					__( 'Unexpected error: %1$s %2$s', 'cyclos' ),
					$status_code,
					$code
				);
			}
			// phpcs:enable
		}
		return $message;
	}
}
