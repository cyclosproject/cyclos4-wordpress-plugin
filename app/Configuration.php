<?php
/**
 * Configuration class taking care of the plugin settings.
 *
 * @package Cyclos
 */

namespace Cyclos;

/**
 * The Configuration class.
 */
class Configuration {

	/**
	 * The unique key for our option record.
	 */
	const CYCLOS_OPTION_NAME = 'cyclos';

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
			self::$sections = array(
				'general'    => array(
					'label' => __( 'General Settings', 'cyclos' ),
					'intro' => __( 'Configure the connection to your Cyclos instance with the fields below. Please read the <a href="https://wiki.cyclos.org" target="_blank">instructions</a> in our Wiki for a detailed explanation.', 'cyclos' ),
				),
				'login_form' => array(
					'label' => 'Login Form',
					'intro' => 'You can put a login form on your website using the Cyclos widget or using the <code>[cycloslogin]</code> shortcode.<br>Below you can configure your own label texts for the Login Form. If you are fine with the default texts, you can leave any field empty here.<br>You can also choose if you want to use the Cyclos styling of the login form. If you disable this, the login form will look just like other forms in your theme.<br>To completely change the login form look and feel, you could create your own template. Please see the instructions in the <a href="#">wiki</a> on how to do this.',
				),
			);
			self::$settings = array(
				'connection_status'    => new Setting( 'general', __( 'Connection status', 'cyclos' ), 'connection_status' ),
				'cyclos_url'           => new Setting( 'general', __( 'Cyclos URL', 'cyclos' ), 'url', true, 'https://demo.cyclos.org', __( 'The Cyclos URL. This is were the REST API should be available.', 'cyclos' ) ),
				'username'             => new Setting( 'general', __( 'Username', 'cyclos' ), 'text', true, 'wp_admin_user', __( 'The Cyclos user to connect with.', 'cyclos' ) ),
				'password'             => new Setting( 'general', __( 'Password', 'cyclos' ), 'password', true, '', __( 'The password of the Cyclos user to connect with.', 'cyclos' ) ),
				'activation_code'      => new Setting( 'general', __( 'Activation code', 'cyclos' ), 'text', true, '', __( 'One-time 4-digit activation code generated by Cyclos.', 'cyclos' ) ),
				'custom_cyclos_url'    => new Setting( 'general', __( 'Custom Cyclos frontend URL', 'cyclos' ), 'url', true, 'https://demo-ui.cyclos.org%p?sessionToken=%s', __( 'Custom frontend URL. Please leave blank unless you use a custom Cyclos frontend. You can use %%s and/or %%p for the token and return path variables.', 'cyclos' ) ),
				'style_loginform'      => new Setting( 'login_form', __( 'Use Cyclos styling for the loginform?', 'cyclos' ), 'checkbox', false, false ),
				'name_label'           => new Setting( 'login_form', __( 'Username field', 'cyclos' ), 'text', false, __( 'User', 'cyclos' ), __( 'The placeholder in the username field', 'cyclos' ) ),
				'password_label'       => new Setting( 'login_form', __( 'Password field', 'cyclos' ), 'text', false, __( 'Password', 'cyclos' ), __( 'The placeholder in the password field', 'cyclos' ) ),
				'submit_button'        => new Setting( 'login_form', __( 'Submit button', 'cyclos' ), 'text', false, __( 'Login', 'cyclos' ), __( 'The text on the submit button', 'cyclos' ) ),
				'forgot_pw_linktext'   => new Setting( 'login_form', __( 'Forgotten password link', 'cyclos' ), 'text', false, __( 'Forgot your password?', 'cyclos' ), __( 'The text for the forgotten password link', 'cyclos' ) ),
				'forgot_pw_email'      => new Setting( 'login_form', __( 'Forgotten password user', 'cyclos' ), 'text', false, __( 'User', 'cyclos' ), __( 'The placeholder in the username field in the forgotten password form', 'cyclos' ) ),
				'forgot_pw_captcha'    => new Setting( 'login_form', __( 'Forgotten password captcha', 'cyclos' ), 'text', false, __( 'Visual validation', 'cyclos' ), __( 'The placeholder in the captcha field in the forgotten password form', 'cyclos' ) ),
				'forgot_pw_newcaptcha' => new Setting( 'login_form', __( 'Forgotten password new captcha', 'cyclos' ), 'text', false, __( 'New code', 'cyclos' ), __( 'The text for the new captcha link in the forgotten password form', 'cyclos' ) ),
				'forgot_pw_submit'     => new Setting( 'login_form', __( 'Forgotten password submit', 'cyclos' ), 'text', false, __( 'Submit', 'cyclos' ), __( 'The text on the submit button in the forgotten password form', 'cyclos' ) ),
				'forgot_pw_cancel'     => new Setting( 'login_form', __( 'Forgotten password cancel', 'cyclos' ), 'text', false, __( 'Cancel', 'cyclos' ), __( 'The text for the cancel link in the forgotten password form', 'cyclos' ) ),
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
		if ( $current_url === $fields['cyclos_url'] && $current_username === $fields['username'] ) {
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
		);
	}

}