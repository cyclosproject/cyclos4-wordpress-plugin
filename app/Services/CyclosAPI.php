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
			// If one of the required permissions is not set correctly, set the status to 'warning' and set the error message to indicate the problem.
			if ( ! $can_login ) {
				/* translators: 'Login users via web services' is a string in Cyclos. Leave as-is or use the default translation for USERS.PRODUCTS.loginUsers from the Cyclos crowdin project. This text may have a custom translation in Cyclos however. */
				$message = __( "The Cyclos user needs permission to login other users. Please correct the configuration of the user group in Cyclos: set the 'Login users via web services' permission to 'Yes'.", 'cyclos' );
				$status  = 'warning';
			} elseif ( ! $can_view_profile ) {
				/* translators: 'Accessible user groups' and 'All groups' are strings in Cyclos. Leave as-is or use the default translation for USERS.PRODUCTS.userGroupAccessibility and USERS.PRODUCTS.userGroupAccessibility.ALL from the Cyclos crowdin project. These texts may have a custom translation in Cyclos however. */
				$message = __( "The Cyclos user needs permission to access user groups. Please correct the configuration of the user group in Cyclos: set the 'Accessible user groups' to 'All groups'.", 'cyclos' );
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
	 * Returns an array indicating whether the forgot password functionality and the captcha are enabled in Cyclos.
	 *
	 * @return array {
	 *     @type boolean $is_forgot_password_enabled  Whether the forgotten password functionality is enabled in Cyclos or not.
	 *     @type boolean $is_captcha_enabled          Whether the captcha functionality is enabled in Cyclos or not.
	 * }
	 */
	public function login_configuration() {
		// Use the AuthService to get the data for login, containing information on the forgotPasswordMediums if available.
		$cyclos_service  = new Cyclos4\AuthService( $this->conf );
		$cyclos_response = $cyclos_service->get_data_for_login();

		// If the request indicates an error, return false.
		if ( is_wp_error( $cyclos_response ) ) {
			return array();
		}

		// If we have no error, return whether the forgotPasswordMediums information is filled. If disabled, this is an empty array.
		// If the captcha is enabled, the forgotPasswordCaptchaProvider is not null.
		// Note: the variables in the json we receive from Cyclos. So disable the coding standard for snake case on this line.
		// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		return array(
			'is_forgot_password_enabled' => ! empty( $cyclos_response->forgotPasswordMediums ),
			'is_captcha_enabled'         => isset( $cyclos_response->forgotPasswordCaptchaProvider ),
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
