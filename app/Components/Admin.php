<?php
/**
 * The class for the admin screen.
 *
 * @package cyclos
 */

namespace Cyclos\Components;

use Cyclos\Configuration;
use Cyclos\Services\CyclosAPI;

/**
 * The Admin class.
 */
class Admin {

	const SETTINGS_PAGE = 'cyclos-options';

	/**
	 * The configuration.
	 *
	 * @var Configuration $conf The configuration.
	 */
	private $conf;

	/**
	 * Constructor.
	 *
	 * @param Configuration $conf     The configuration.
	 */
	public function __construct( Configuration $conf ) {
		$this->conf = $conf;
	}

	/**
	 * Initialize the admin hooks.
	 */
	public function init() {
		add_action( 'admin_menu', array( $this, 'add_cyclos_settings_submenu' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_admin_scripts' ) );
	}

	/**
	 * Initialize the plugin options by registering the fields, sections and settings.
	 *
	 * Uses functions from the Settings API:
	 * register_setting( string $option_group, string $option_name, array $args = array() )
	 * add_settings_section( string $id, string $title, callable $callback, string $page )
	 * add_settings_field( string $id, string $title, callable $callback, string $page, string $section = 'default', array $args = array() )
	 */
	public function register_settings() {
		// Parse the settings from the configuration to build the settings screens. We will make a tab for each section.

		// Add the sections.
		$sections = $this->conf->get_sections();
		foreach ( $sections as $section_id => $section ) {
			$callback = null;
			if ( isset( $section['intro'] ) ) {
				$callback = function() use ( $section ) {
					// Note: we need to output the description without escaping it, to allow the description to contain a hyperlink for example.
					// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
					printf( '<p class="intro">%s</p>', $section['intro'] );
					// phpcs:enable
				};
			}
			add_settings_section( $section_id, $section['label'], $callback, self::SETTINGS_PAGE );
		}

		// Add the fields.
		$plugin_settings = $this->conf->get_settings();
		foreach ( $plugin_settings as $key => $setting_info ) {
			// Add the field with the correct render method, depending on the field type.
			$type = $setting_info->get_type();
			// If a method for the field type exists, use that, otherwise use the text render method.
			$render_method = 'render_text';
			if ( is_callable( array( $this, "render_{$type}" ) ) ) {
				$render_method = "render_{$type}";
			}
			$section = $setting_info->get_section();
			$args    = array(
				'label_for'    => $key,
				'setting_info' => $setting_info,
			);
			add_settings_field( $key, $setting_info->get_label(), array( $this, $render_method ), self::SETTINGS_PAGE, $section, $args );

			// Add the validation hook for this field type if not already added before.
			$hook      = "cyclos_sanitize_{$type}";
			$validator = array( $this, 'sanitize_' . $type );
			if ( ! has_filter( $hook, $validator ) && is_callable( $validator ) ) {
				add_filter( $hook, $validator, 10, 4 );
			}
		}

		// Register the option that holds all our settings.
		register_setting(
			self::SETTINGS_PAGE,
			$this->conf::CYCLOS_OPTION_NAME,
			array(
				'sanitize_callback' => array( $this, 'sanitize_settings' ),
			)
		);
	}

	/**
	 * Enqueue the assets we need in the admin screen.
	 *
	 * @param string $hook The admin page we are on.
	 */
	public function enqueue_admin_scripts( $hook ) {
		// Only add our assets on our own settings page.
		// phpcs:disable Squiz.Commenting.InlineComment.InvalidEndChar
		// Note:
		// Using hardcoded 'settings_page_' here.
		// We could also store the result of our add_options_page() call in add_cyclos_settings_submenu() in a class property and use that.
		// It is built up from:
		// - 'settings' (because we created a submenu under Settings),
		// - '_page' (hardcoded in https://developer.wordpress.org/reference/functions/get_plugin_page_hookname/),
		// - and our own settings_page constant we used as menu_slug in our add_options_page() call.
		// phpcs:enable
		if ( 'settings_page_' . self::SETTINGS_PAGE !== $hook ) {
			return;
		}

		// Enqueue our admin script.
		$js_file   = 'js/dist/admin.js';
		$js_handle = 'cyclos-settings';
		$version   = \Cyclos\PLUGIN_VERSION . '-' . filemtime( \Cyclos\PLUGIN_DIR . $js_file );
		$file_url  = \Cyclos\PLUGIN_URL . $js_file;
		$deps      = array( 'jquery' );
		$in_footer = true;
		wp_enqueue_script( $js_handle, $file_url, $deps, $version, $in_footer );

		// Add our css inline - it is too little to put in a file and enqueue that for now.
		$custom_css = '
			.wrap h2, .wrap .intro, .wrap .form-table { display: none; }
			.wrap h2.active, .wrap .intro.active, .wrap .form-table.active { display: block; }
			.form-table td p.dashicons { font-size: 24px; }
			.dashicons-minus { color: gray; }
			.dashicons-warning { color: orange; }
			.dashicons-no { color: red; }
			.dashicons-yes { color: green; }
		';
		wp_add_inline_style( 'wp-admin', $custom_css );
	}

	/**
	 * Add a custom submenu to the WordPress Settings admin menu-item.
	 */
	public function add_cyclos_settings_submenu() {
		// add_options_page params: string $page_title, string $menu_title, string $capability, string $menu_slug, callable $function = ''.
		add_options_page( 'Cyclos Plugin Settings', 'Cyclos', 'manage_options', self::SETTINGS_PAGE, array( $this, 'cyclos_settings_page' ) );
	}

	/**
	 * Builds the settings page.
	 * Note: we don't have to call settings_errors() here, because admin screens within the WP Settings automatically show errors.
	 */
	public function cyclos_settings_page() {
		?>
		<div class="wrap">
			<h1>Cyclos Settings</h1>
			<form method="post" action="options.php">

				<nav class="nav-tab-wrapper">
					<?php
					foreach ( $this->conf->get_sections() as $section ) {
						printf( '<a href="#" class="nav-tab">%1$s</a>', esc_html( $section['label'] ) );
					}
					?>
				</nav>

				<?php
					settings_fields( self::SETTINGS_PAGE );
					do_settings_sections( self::SETTINGS_PAGE );
					submit_button();
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render the setting field for fields of type text.
	 *
	 * @param array $args Contains the field id and Settings object of the field to render.
	 */
	public function render_text( $args ) {
		$field_id = $args['label_for'];
		$setting  = $args['setting_info'];
		$value    = $this->conf->get_setting( $field_id, false );
		$name     = $this->conf::CYCLOS_OPTION_NAME . '[' . $field_id . ']';
		printf( '<input type="text" name="%s" id="%s" class="regular-text" value="%s" placeholder="%s" />', esc_html( $name ), esc_html( $field_id ), esc_attr( $value ), esc_html( $setting->get_default() ) );
		if ( ! empty( $setting->get_description() ) ) {
			printf( '<p class="description">%s</p>', esc_html( $setting->get_description() ) );
		}
	}

	/**
	 * Render the setting field for fields of type password.
	 *
	 * @param array $args Contains the field id and Settings object of the field to render.
	 */
	public function render_password( $args ) {
		$field_id = $args['label_for'];
		$setting  = $args['setting_info'];
		$value    = $this->conf->get_setting( $field_id, false );
		$name     = $this->conf::CYCLOS_OPTION_NAME . '[' . $field_id . ']';
		printf( '<input type="password" name="%s" id="%s" class="regular-text" value="%s" placeholder="%s" />', esc_html( $name ), esc_html( $field_id ), esc_attr( $value ), esc_html( $setting->get_default() ) );
		if ( ! empty( $setting->get_description() ) ) {
			printf( '<p class="description">%s</p>', esc_html( $setting->get_description() ) );
		}
	}

	/**
	 * Render the setting field for fields of type checkbox.
	 *
	 * @param array $args Contains the key and Settings object of the field to render.
	 */
	public function render_checkbox( $args ) {
		$field_id = $args['label_for'];
		$setting  = $args['setting_info'];
		$value    = $this->conf->get_setting( $field_id, false );
		$name     = $this->conf::CYCLOS_OPTION_NAME . '[' . $field_id . ']';
		printf( '<input type="checkbox" name="%s" id="%s" value="1" %s />', esc_html( $name ), esc_html( $field_id ), checked( 1, esc_attr( $value ), false ) );
		if ( ! empty( $setting->get_description() ) ) {
			printf( '<p class="description">%s</p>', esc_html( $setting->get_description() ) );
		}
	}

	/**
	 * Render the setting field for fields of type info.
	 * Note: don't use this field type; it does not work correctly.
	 *
	 * @param array $args Contains the key and Settings object of the field to render.
	 */
	public function render_info( $args ) {
		$setting = $args['setting_info'];
		if ( ! empty( $setting->get_description() ) ) {
			// If we would do simply do: echo $setting->get_description() the text would come next to the label.
			// If we put the text in a colspan like below, it gets under the label (which is not what we want) and also with invalid html.
			// Note: we need to output the description without escaping it, to allow the description to contain a hyperlink for example.
			// phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $setting->get_description();
			printf( '<tr valign="top"><td colspan="2">%s</td></tr>', $setting->get_description() );
			// phpcs:enable
		}
	}

	/**
	 * Render the connection status setting field.
	 * This is not an input field, but shows the result of the Cyclos API with the currently configured connection information.
	 */
	public function render_connection_status() {
		$message = __( 'Status unknown. The connection has not been setup yet. Please make sure you fill in all connection fields.', 'cyclos' );
		$status  = 'none';

		// Only try to retrieve the connection status if we have a token, since without it, the connection will fail anyway.
		if ( ! empty( $this->conf->get_accessclient_token() ) ) {
			$cyclos          = new CyclosAPI( $this->conf );
			$cyclos_response = $cyclos->get_connection();
			$status          = $cyclos_response['status'];
			$message         = $cyclos_response['message'];
		}

		switch ( $status ) {
			case 'none':
				$css_dashicon = 'dashicons-minus';
				break;
			case 'warning':
				$css_dashicon = 'dashicons-warning';
				break;
			case 'error':
				$css_dashicon = 'dashicons-no';
				break;
			default:
				$css_dashicon = 'dashicons-yes';
				break;
		}

		printf( '<p class="dashicons %1$s"></p><p>%2$s</p>', esc_html( $css_dashicon ), esc_html( $message ) );
	}

	/**
	 * Sanitize the input of the settings.
	 *
	 * @param array $input Array of unsanitized field values.
	 * @return array Array of sanitized field values.
	 */
	public function sanitize_settings( $input ) {
		$settings = $this->conf->get_settings();
		foreach ( $input as $field => $value ) {
			if ( empty( $value ) ) {
				// Don't store empty values. This way the default value is used if needed.
				unset( $input[ $field ] );
				continue;
			}
			$setting         = $settings[ $field ];
			$old_value       = $this->conf->get_setting( $field, false );
			$input[ $field ] = apply_filters( "cyclos_sanitize_{$setting->get_type()}", $value, $field, $setting, $old_value );
		}
		return $this->conf->validate( $input );
	}

	/**
	 * Returns the sanitized value of a text field.
	 *
	 * @param string $value The text value to sanitize.
	 */
	public function sanitize_text( $value ) {
		return sanitize_text_field( $value );
	}

	/**
	 * Returns the sanitized value of a password field.
	 *
	 * @param string $value The password value to sanitize.
	 */
	public function sanitize_password( $value ) {
		return sanitize_text_field( $value );
	}

	/**
	 * Returns the sanitized value of a number field.
	 *
	 * @param string $value The number value to sanitize.
	 */
	public function sanitize_number( $value ) {
		return is_numeric( $value ) ? $value + 0 : 0;
	}

	/**
	 * Returns the sanitized value of a URL field or the old value if the new is not valid.
	 *
	 * @param string  $value     The URL value to sanitize.
	 * @param string  $field     The field id.
	 * @param Setting $setting   The setting.
	 * @param string  $old_value The previous value of the field.
	 */
	public function sanitize_url( $value, $field, $setting, $old_value ) {
		$sanitized_url = esc_url_raw( $value );
		if ( empty( $sanitized_url ) ) {
			add_settings_error(
				self::SETTINGS_PAGE,
				$field,
				sprintf(
					/* translators: 1: Field Label 2: Incorrect field value. */
					__( '%1$s: %2$s does not seem to be a valid URL.', 'cyclos' ),
					$setting->get_label(),
					$value
				)
			);
			return $old_value;
		}
		return $sanitized_url;
	}
}