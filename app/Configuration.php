<?php
/**
 * Configuration class taking care of the plugin settings.
 *
 * @package Cyclos
 */

namespace Cyclos;

use Cyclos\Components\Admin;
use Cyclos\Components\LoginForm;
use Cyclos\Components\UserDirectory;
use Cyclos\Services\CyclosAPI;

/**
 * The Configuration class.
 */
class Configuration {

	/**
	 * The unique key for our option record.
	 */
	const CYCLOS_OPTION_NAME = 'cyclos';

	/**
	 * The unique keys for our user data transient records.
	 */
	const USER_METADATA_TRANSIENT = 'cyclos_user_metadata';
	const USER_DATA_TRANSIENT     = 'cyclos_user_data';

	/**
	 * The unique instance of this class.
	 *
	 * @var Configuration $instance
	 */
	protected static $instance;

	/**
	 * The option record for our plugin settings.
	 *
	 * @var array $plugin_option The plugin option record.
	 */
	protected static $plugin_option;

	/**
	 * Array containing the sections of the plugin settings.
	 *
	 * @var [] $sections Array of sections, each containing an id, title and optional info text.
	 */
	protected static $sections;

	/**
	 * Array containing the plugin settings.
	 *
	 * @var Setting[] $settings Array of plugin settings.
	 */
	protected static $settings;

	/**
	 * Array containing the plugin components a webmaster can turn on/off. This never includes the Admin component.
	 *
	 * @var Setting[] $settings Array of plugin components.
	 */
	protected static $components;

	/**
	 * Returns the unique instance of this class.
	 */
	public static function get_instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Initializes the settings.
	 */
	protected function initialize_settings() {
		if ( ! isset( self::$settings ) ) {

			// Initialize the components a webmaster can turn on/off.
			self::$components = array(
				'login_form'     => LoginForm::class,
				'user_directory' => UserDirectory::class,
			);

			// Initialize the sections (= tabs). These are the general and connection tabs and a tab for each of the optional components.
			self::$sections = array(
				'general'    => array(
					'label' => __( 'General', 'cyclos' ),
					'intro' => sprintf(
						/* translators: 1: Start-tag of hyperlink to Cyclos wiki 2: End-tag of hyperlink. */
						__( 'With the Cyclos plugin you can let your WordPress website communicate with your Cyclos server. Please read the %1$sinstructions%2$s in our Wiki for a detailed explanation or if you encounter problems using the plugin.', 'cyclos' ),
						'<a href="https://wiki.cyclos.org/index.php/WordPress" target="_blank">',
						'</a>'
					),
				),
				'connection' => array(
					'label' => __( 'Connection', 'cyclos' ),
					'intro' => sprintf(
						/* translators: 1: Start-tag of hyperlink to Cyclos wiki 2: End-tag of hyperlink. */
						__( 'Configure the connection to your Cyclos server with the fields below. Please read the %1$sinstructions%2$s in our Wiki for a detailed explanation.<br><br><strong>IMPORTANT:<br>Create a dedicated Cyclos user for connecting your WordPress site and make absolutely sure that this user has only the permissions in Cyclos that are needed for the plugin to work, as described in the wiki, and no other permissions. If ever your WordPress site might get hacked, the hacker could not do much harm within Cyclos this way.</strong>', 'cyclos' ),
						'<a href="https://wiki.cyclos.org/index.php/WordPress" target="_blank">',
						'</a>'
					),
				),
			);
			// Add a section for each of the optional components.
			$components = $this->get_components();
			foreach ( $components as $id => $component_class ) {
				self::$sections[ $id ] = array(
					'label' => $component_class::get_component_info()['tab'],
					'intro' => $component_class::get_component_info()['intro'],
				);
			}

			// Initialize the settings. They are divided per section (= tab).
			/* translators: The %s and %p are placeholders, please leave them in. */
			$custom_url_description = __( 'Custom frontend URL. Please leave blank unless you use a custom Cyclos frontend. You can use %s and/or %p for the token and return path variables.', 'cyclos' );
			self::$settings         = array(
				'connection_status'    => new Setting( 'general', __( 'Connection status', 'cyclos' ), 'connection_status' ),
				'read_cyclos_url'      => new Setting( 'general', __( 'Cyclos URL', 'cyclos' ), 'read_field', false, __( '(not configured yet)', 'cyclos' ), __( 'The URL of your Cyclos server', 'cyclos' ) ),
				'read_username'        => new Setting( 'general', __( 'Cyclos user', 'cyclos' ), 'read_field', false, __( '(not configured yet)', 'cyclos' ), __( 'The Cyclos user used to communicate with your Cyclos server', 'cyclos' ) ),
				'active_components'    => new Setting( 'general', __( 'Active plugin parts', 'cyclos' ), 'components', false, false, __( 'The functionality you would like to use. You can use the plugin to create a Login Form, a User Directory (a map or list with Cyclos Members), or both if you like.', 'cyclos' ) ),
				'cyclos_url'           => new Setting( 'connection', __( 'Cyclos URL', 'cyclos' ), 'url', true, '', __( 'The Cyclos URL. The required REST API methods via /api/* are always enabled in Cyclos, but please make sure to control access in the Cyclos Web services channel according to the instructions.', 'cyclos' ), 'https://demo.cyclos.org' ),
				'username'             => new Setting( 'connection', __( 'Username', 'cyclos' ), 'text', true, '', __( 'The Cyclos user to connect with. Important: Always use a dedicated Cyclos user, with only the permissions needed for the Cyclos plugin to work!', 'cyclos' ), 'wp_admin_user' ),
				'password'             => new Setting( 'connection', __( 'Password', 'cyclos' ), 'password', true, '', __( 'The password of the Cyclos user to connect with. The password is not stored anywhere. It is only used once, together with the activation code below, to activate the accessclient for you. The accessclient token is used for all further requests to your Cyclos server by this plugin.', 'cyclos' ) ),
				'activation_code'      => new Setting( 'connection', __( 'Activation code', 'cyclos' ), 'text', true, '', __( 'One-time 4-digit activation code generated by Cyclos.', 'cyclos' ) ),
				'style_loginform'      => new Setting( 'login_form', __( 'Use Cyclos styling?', 'cyclos' ), 'checkbox', false, false ),
				'custom_cyclos_url'    => new Setting( 'login_form', __( 'Custom Cyclos frontend URL', 'cyclos' ), 'url', true, '', $custom_url_description, 'https://demo-ui.cyclos.org%p?sessionToken=%s' ),
				'name_label'           => new Setting( 'login_form', __( 'Username field', 'cyclos' ), 'text', false, __( 'User', 'cyclos' ), __( 'The placeholder in the username field', 'cyclos' ) ),
				'password_label'       => new Setting( 'login_form', __( 'Password field', 'cyclos' ), 'text', false, __( 'Password', 'cyclos' ), __( 'The placeholder in the password field', 'cyclos' ) ),
				'submit_button'        => new Setting( 'login_form', __( 'Submit button', 'cyclos' ), 'text', false, __( 'Login', 'cyclos' ), __( 'The text on the submit button', 'cyclos' ) ),
				'forgot_pw_linktext'   => new Setting( 'login_form', __( 'Forgotten password link', 'cyclos' ), 'text', false, __( 'Forgot your password?', 'cyclos' ), __( 'The text for the forgotten password link', 'cyclos' ) ),
				'forgot_pw_email'      => new Setting( 'login_form', __( 'Forgotten password user', 'cyclos' ), 'text', false, __( 'User', 'cyclos' ), __( 'The placeholder in the username field in the forgotten password form', 'cyclos' ) ),
				'forgot_pw_captcha'    => new Setting( 'login_form', __( 'Forgotten password captcha', 'cyclos' ), 'text', false, __( 'Visual validation', 'cyclos' ), __( 'The placeholder in the captcha field in the forgotten password form', 'cyclos' ) ),
				'forgot_pw_newcaptcha' => new Setting( 'login_form', __( 'Forgotten password new captcha', 'cyclos' ), 'text', false, __( 'New code', 'cyclos' ), __( 'The text for the new captcha link in the forgotten password form', 'cyclos' ) ),
				'forgot_pw_medium'     => new Setting( 'login_form', __( 'Forgotten password send medium label', 'cyclos' ), 'text', false, __( 'Send verification code by', 'cyclos' ), __( 'The label near the (optional) send medium choice in the forgotten password form', 'cyclos' ) ),
				'forgot_pw_mail'       => new Setting( 'login_form', __( 'Forgotten password e-mail', 'cyclos' ), 'text', false, __( 'E-mail', 'cyclos' ), __( 'The (optional) e-mail send medium choice in the forgotten password form', 'cyclos' ) ),
				'forgot_pw_sms'        => new Setting( 'login_form', __( 'Forgotten password SMS', 'cyclos' ), 'text', false, __( 'SMS', 'cyclos' ), __( 'The (optional) SMS send medium choice in the forgotten password form', 'cyclos' ) ),
				'forgot_pw_code'       => new Setting( 'login_form', __( 'Forgotten password code', 'cyclos' ), 'text', false, __( 'Verification code', 'cyclos' ), __( 'The placeholder in the verification code step in the forgotten password form', 'cyclos' ) ),
				'forgot_pw_sec_answer' => new Setting( 'login_form', __( 'Forgotten password security answer', 'cyclos' ), 'text', false, __( 'Your answer', 'cyclos' ), __( 'The placeholder in the answer to the security question field in the forgotten password form', 'cyclos' ) ),
				'forgot_pw_new_pw'     => new Setting( 'login_form', __( 'Forgotten password new password', 'cyclos' ), 'text', false, __( 'New password', 'cyclos' ), __( 'The placeholder in the new password field in the forgotten password form', 'cyclos' ) ),
				'forgot_pw_confirm_pw' => new Setting( 'login_form', __( 'Forgotten password confirm password', 'cyclos' ), 'text', false, __( 'Confirm new password', 'cyclos' ), __( 'The placeholder in the confirmation field of the new password in the forgotten password form', 'cyclos' ) ),
				'forgot_pw_submit'     => new Setting( 'login_form', __( 'Forgotten password submit', 'cyclos' ), 'text', false, __( 'Submit', 'cyclos' ), __( 'The text on the submit button in the forgotten password form', 'cyclos' ) ),
				'forgot_pw_cancel'     => new Setting( 'login_form', __( 'Forgotten password cancel', 'cyclos' ), 'text', false, __( 'Cancel', 'cyclos' ), __( 'The text for the cancel link in the forgotten password form', 'cyclos' ) ),
				'user_style'           => new Setting( 'user_directory', __( 'Choose style', 'cyclos' ), 'user_style', false, 'ocean', __( 'You can choose different designs for the user directory. If you prefer to use your own custom style, choose \'None\' here and adjust your theme\'s CSS however you like.', 'cyclos' ) ),
				'map_icon'             => new Setting( 'user_directory', __( 'Custom map icon', 'cyclos' ), 'url', false, '', __( 'If you would like to use a custom icon image as a marker on the map, fill in the full URL to this image here.', 'cyclos' ) ),
				'user_search_label'    => new Setting( 'user_directory', __( 'Search label', 'cyclos' ), 'text', false, __( 'Keywords', 'cyclos' ), __( 'The label of the search field', 'cyclos' ) ),
				'user_filter_label'    => new Setting( 'user_directory', __( 'Filter label', 'cyclos' ), 'text', false, __( 'Filter', 'cyclos' ), __( 'The label of the filter dropdown', 'cyclos' ) ),
				'user_sort_label'      => new Setting( 'user_directory', __( 'Sort label', 'cyclos' ), 'text', false, __( 'Sort', 'cyclos' ), __( 'The label of the sort dropdown', 'cyclos' ) ),
				'user_nofilter_option' => new Setting( 'user_directory', __( 'Unfiltered option', 'cyclos' ), 'text', false, __( 'All users', 'cyclos' ), __( 'The filter option when users are shown without filtering', 'cyclos' ) ),
				'user_nosort_option'   => new Setting( 'user_directory', __( 'Initial sort option when invisible', 'cyclos' ), 'text', false, __( 'Default', 'cyclos' ), __( 'The sort option when users are sorted initially on a non-visible sorting option', 'cyclos' ) ),
				'user_sort_asc'        => new Setting( 'user_directory', __( 'Ascending sort option indicator', 'cyclos' ), 'text', false, __( 'ASC', 'cyclos' ), __( 'The indicator for ascending sorting', 'cyclos' ) ),
				'user_sort_desc'       => new Setting( 'user_directory', __( 'Descending sort option indicator', 'cyclos' ), 'text', false, __( 'DESC', 'cyclos' ), __( 'The indicator for descending sorting', 'cyclos' ) ),
				'user_data_sort'       => new Setting( 'user_directory', __( 'Cyclos user data ordering', 'cyclos' ), 'user_data_sort', false, 'creationDate', __( 'Use this if you would like to retrieve the user data from Cyclos ordered in a specific way.', 'cyclos' ) ),
				'user_group'           => new Setting( 'user_directory', __( 'Cyclos user group', 'cyclos' ), 'text', false, '', __( 'The internal name of the Cyclos group to filter the users to show. Use this if you only want to show users from a certain group instead of all Cyclos users in the network.', 'cyclos' ) ),
				'user_expiration'      => new Setting( 'user_directory', __( 'Expiration time of user data', 'cyclos' ), 'number', false, 30, __( 'The number of minutes to keep user data in cache. By default, user data is only retrieved from Cyclos if the current data is older than 30 minutes. If you like, you can change this here.', 'cyclos' ) ),
				'user_data_info'       => new Setting( 'user_directory', __( 'Current user data', 'cyclos' ), 'user_data_transient', false, null ),
			);
		}
	}

	/**
	 * Returns the sections array.
	 */
	public function get_sections() {
		if ( ! isset( self::$sections ) ) {
			$this->initialize_settings();
		}
		return self::$sections;
	}

	/**
	 * Returns the settings array. This is an array of Setting objects.
	 */
	public function get_settings() {
		if ( ! isset( self::$settings ) ) {
			$this->initialize_settings();
		}
		return self::$settings;
	}

	/**
	 * Returns the array of components a webmaster can turn on/off.
	 */
	public function get_components() {
		if ( ! isset( self::$components ) ) {
			$this->initialize_settings();
		}
		return self::$components;
	}

	/**
	 * Returns the value of the plugin option record. This is a serialized array.
	 */
	protected function get_plugin_option() {
		if ( ! isset( self::$plugin_option ) ) {
			self::$plugin_option = get_option( self::CYCLOS_OPTION_NAME );
		}
		return self::$plugin_option;
	}

	/**
	 * Returns the value of the given setting, either from the option record or its default value.
	 *
	 * @param string $setting_key The setting identifier.
	 * @param bool   $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_setting( string $setting_key, bool $use_default = true ) {
		$settings = $this->get_settings();
		if ( ! array_key_exists( $setting_key, $settings ) ) {
			// The given setting is unknown.
			return '';
		}
		$setting  = $settings[ $setting_key ];
		$fallback = $use_default ? $setting->get_default() : '';
		return $this->get_plugin_option()[ $setting_key ] ?? $fallback;
	}

	/**
	 * Validates the input.
	 *
	 * @param array $fields Array of fields, containing key-value pairs.
	 */
	public function validate( array $fields ) {
		$settings = $this->get_settings();
		foreach ( $fields as $field => $value ) {
			$validation_message = $this->validator( $field, $value );
			if ( ! empty( $validation_message ) ) {
				$old_value = $this->get_setting( $field );
				$setting   = $settings[ $field ];
				add_settings_error(
					Admin::SETTINGS_PAGE,
					$field,
					sprintf(
						/* translators: 1: Field Label 2: Incorrect field value 3: The validation message. */
						__( '%1$s: %2$s is not valid. %3$s.', 'cyclos' ),
						$setting->get_label(),
						$value,
						$validation_message
					)
				);
				$fields[ $field ] = $old_value;
			}
		}
		$this->validate_userdata( $fields );
		return $this->validate_connection_fields( $fields );
	}

	/**
	 * Checks whether the given value is valid for the given setting.
	 *
	 * @param string $field A string that is a key in the settings array.
	 * @param mixed  $value The value to check.
	 * @return string The validation error message or an empty string if the value is valid.
	 */
	protected function validator( $field, $value ) {
		switch ( $field ) {
			case 'user_expiration':
				if ( $value < 0 ) {
					return __( 'The expiration time must be 0 or higher', 'cyclos' );
				}
				break;
			case 'latitude':
				if ( -90 > $value || $value > 90 ) {
					return __( 'The latitude must be between -90 and 90', 'cyclos' );
				}
				break;
			case 'longitude':
				if ( -180 > $value || $value > 180 ) {
					return __( 'The longitude must be between -180 and 180', 'cyclos' );
				}
				break;
			case 'zoom':
				if ( 1 > $value || $value > 20 ) {
					return __( 'The zoom level must be between 1 and 20', 'cyclos' );
				}
				break;
			default:
				break;
		}
		return '';
	}

	/**
	 * Validates the userdata transients when fields that effect the userdata are being changed.
	 * Note: we are not actually validating the input fields themselves here. But the validation phase is
	 * a handy place to check whether the userdata should be refreshed.
	 *
	 * @param array $fields Array of fields, containing key-value pairs.
	 */
	protected function validate_userdata( array $fields ) {
		// The fields that should trigger a data refresh when changed.
		$relevant_fields = array( 'user_data_sort', 'user_group', 'user_expiration' );
		foreach ( $relevant_fields as $field ) {
			// Check if the field value is being changed.
			$old_value = $this->get_setting( $field );
			$new_value = $fields[ $field ] ?? null;
			if ( $old_value !== $new_value ) {
				// Delete the user data, so next time it will be fetched from Cyclos.
				delete_transient( self::USER_DATA_TRANSIENT );
				delete_transient( self::USER_METADATA_TRANSIENT );

				// The transients are deleted, no need to check the other fields for changes.
				return;
			}
		}
	}

	/**
	 * Validates the fields that concern the Cyclos connection.
	 * We always keep the URL and username, but we remove the password and activation code.
	 * Instead, we add the accessclient token, which is generated whenever needed by calling the Cyclos API.
	 *
	 * @param array $fields Array of fields, containing key-value pairs.
	 */
	protected function validate_connection_fields( array $fields ) {
		$error_message = '';
		$new_token     = '';

		// Get the input values for the connection fields.
		$url             = $fields['cyclos_url'] ?? '';
		$username        = $fields['username'] ?? '';
		$password        = $fields['password'] ?? '';
		$activation_code = $fields['activation_code'] ?? '';

		// Retrieve the current values of the connection fields.
		$current_url      = $this->get_cyclos_url( false );
		$current_username = $this->get_username( false );
		$current_token    = $this->get_accessclient_token();

		// Check if we have new connection information.
		if ( $current_url === $url && $current_username === $username ) {
			// Keep the original token, because the url and username have not been changed.
			// If the password and activation code are filled in, we will re-generate a new token later on.
			$new_token = $current_token;
		} else {
			// Empty the token, because the url and/or the username have changed.
			// Even if no password and activation code are filled in, the original token is no longer valid.
			$new_token = '';
		}

		// Check if we should try to generate a new token. We need all four input fields for this.
		if ( ! empty( $url ) && ! empty( $username ) && ! empty( $password ) && ! empty( $activation_code ) ) {
			$cyclos    = new CyclosAPI( $this );
			$new_token = $cyclos->generate_token( $url, $username, $password, $activation_code );
			if ( is_wp_error( $new_token ) ) {
				// Something went wrong, prepare the error message and empty the token.
				$error_message = sprintf(
					/* translators: 1: The error message. */
					__( 'The connection information is not correct: %1$s', 'cyclos' ),
					$new_token->get_error_message()
				);
				$new_token = '';
			}
		}

		// If we don't already have an error message, check if we should add one.
		if ( empty( $error_message ) ) {
			// If we don't have a token at this moment, set an error to inform the user the connection input is not complete.
			if ( empty( $new_token ) ) {
				$error_message = __( 'The connection information is not complete. Please fill in all four connection fields to set the connection.', 'cyclos' );
			} else {
				// If we have a token from earlier, but the user tried to change it with incomplete information, inform the user.
				if ( $new_token === $current_token && ( ! empty( $password ) || ! empty( $activation_code ) ) ) {
					$error_message = __( 'The connection information is not complete. Please fill in all four connection fields to reset the connection.', 'cyclos' );
				}
			}
		}

		// If the error message is filled, inform the user.
		if ( ! empty( $error_message ) ) {
			add_settings_error( Admin::SETTINGS_PAGE, 'token_activation', $error_message );
		}

		// Adjust the fields array, so the correct information will be stored in our option record.
		// Put the new token in the fields array and remove the password and activation code from the fields array.
		$fields['accessclient_token'] = $new_token;
		unset( $fields['password'] );
		unset( $fields['activation_code'] );

		// Return the fields.
		return $fields;
	}

	/**
	 * Returns the Cyclos URL.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_cyclos_url( bool $use_default = true ) {
		return $this->get_setting( 'cyclos_url', $use_default );
	}

	/**
	 * Returns the username used to connect with Cyclos.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_username( bool $use_default = true ) {
		return $this->get_setting( 'username', $use_default );
	}

	/**
	 * Returns the accessclient token or an empty string if no token exists.
	 */
	public function get_accessclient_token() {
		// Note: we don't use get_setting() in this case, because the token is not a normal setting field.
		// Call the setting option record directly instead.
		return $this->get_plugin_option()['accessclient_token'] ?? '';
	}

	/**
	 * Returns whether the given component is active or not.
	 *
	 * @param string $component_id The id of the component.
	 */
	public function is_active( string $component_id ) {
		return $this->get_setting( 'active_components' )[ $component_id ] ?? false;
	}

	/**
	 * Returns the Custom Cyclos frontend URL.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_custom_cyclos_frontend_url( bool $use_default = true ) {
		return $this->get_setting( 'custom_cyclos_url', $use_default );
	}

	/**
	 * Whether to put styling on the loginform or not.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 * @return bool
	 */
	public function add_styles_to_loginform( bool $use_default = true ) {
		return $this->get_setting( 'style_loginform', $use_default );
	}

	/**
	 * Returns all the texts used on the login form.
	 *
	 * @return array
	 */
	public function get_loginform_labels() {
		return array(
			'principal'         => $this->get_setting( 'name_label' ),
			'password'          => $this->get_setting( 'password_label' ),
			'submit'            => $this->get_setting( 'submit_button' ),
			'forgot_link'       => $this->get_setting( 'forgot_pw_linktext' ),
			'forgot_principal'  => $this->get_setting( 'forgot_pw_email' ),
			'forgot_captcha'    => $this->get_setting( 'forgot_pw_captcha' ),
			'forgot_newcaptcha' => $this->get_setting( 'forgot_pw_newcaptcha' ),
			'forgot_submit'     => $this->get_setting( 'forgot_pw_submit' ),
			'forgot_cancel'     => $this->get_setting( 'forgot_pw_cancel' ),
			'forgot_medium'     => $this->get_setting( 'forgot_pw_medium' ),
			'forgot_email'      => $this->get_setting( 'forgot_pw_mail' ),
			'forgot_sms'        => $this->get_setting( 'forgot_pw_sms' ),
			'forgot_code'       => $this->get_setting( 'forgot_pw_code' ),
			'forgot_security'   => $this->get_setting( 'forgot_pw_sec_answer' ),
			'forgot_new_pw'     => $this->get_setting( 'forgot_pw_new_pw' ),
			'forgot_confirm_pw' => $this->get_setting( 'forgot_pw_confirm_pw' ),
		);
	}

	/**
	 * Returns the user group to filter the user data.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_user_group( bool $use_default = true ) {
		return $this->get_setting( 'user_group', $use_default );
	}

	/**
	 * Returns the expiration time for user data in minutes.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_user_data_expiration_time( bool $use_default = true ) {
		return $this->get_setting( 'user_expiration', $use_default );
	}

	/**
	 * Returns the field to use as orderby when retrieving the Cyclos user data.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_user_data_sort( bool $use_default = true ) {
		return $this->get_setting( 'user_data_sort', $use_default );
	}

	/**
	 * Returns the style to use in the user directory.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_user_style( bool $use_default = true ) {
		return $this->get_setting( 'user_style', $use_default );
	}

	/**
	 * Returns the map icon to use on the user map.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_map_icon( bool $use_default = true ) {
		return $this->get_setting( 'map_icon', $use_default );
	}

	/**
	 * Returns the label to use near the search input in the user directory.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_user_search_label( bool $use_default = true ) {
		return $this->get_setting( 'user_search_label', $use_default );
	}

	/**
	 * Returns the label to use near the filter dropdown in the user directory.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_user_filter_label( bool $use_default = true ) {
		return $this->get_setting( 'user_filter_label', $use_default );
	}

	/**
	 * Returns the label to use near the sort dropdown in the user directory.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_user_sort_label( bool $use_default = true ) {
		return $this->get_setting( 'user_sort_label', $use_default );
	}

	/**
	 * Returns the non-filtering option in the filter dropdown in the user directory.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_user_nofilter_option( bool $use_default = true ) {
		return $this->get_setting( 'user_nofilter_option', $use_default );
	}

	/**
	 * Returns the initial invisible option in the sort dropdown in the user directory.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_user_nosort_option( bool $use_default = true ) {
		return $this->get_setting( 'user_nosort_option', $use_default );
	}

	/**
	 * Returns the ascending sort indicator in the sort dropdown in the user directory.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_user_sort_asc( bool $use_default = true ) {
		return $this->get_setting( 'user_sort_asc', $use_default );
	}

	/**
	 * Returns the descending sort indicator in the sort dropdown in the user directory.
	 *
	 * @param bool $use_default (optional) Whether to return the default value if the setting is not set. Defaults to true.
	 */
	public function get_user_sort_desc( bool $use_default = true ) {
		return $this->get_setting( 'user_sort_desc', $use_default );
	}

}
