<?php
/**
 * The Login component enables the Cyclos login form.
 *
 * @package Cyclos
 */

namespace Cyclos\Components;

use Cyclos\Configuration;
use Cyclos\Services\CyclosAPI;
use Cyclos\Widgets\LoginWidget;

/**
 * LoginComponent class
 */
class LoginComponent {

	/**
	 * The Cyclos API.
	 *
	 * @var CyclosAPI $cyclos The Cyclos API.
	 */
	private $cyclos;

	/**
	 * The configuration.
	 *
	 * @var Configuration $conf The configuration.
	 */
	private $conf;

	/**
	 * Constructor.
	 *
	 * @param Configuration $conf   The configuration.
	 * @param CyclosAPI     $cyclos The Cyclos API.
	 */
	public function __construct( Configuration $conf, CyclosAPI $cyclos ) {
		$this->conf   = $conf;
		$this->cyclos = $cyclos;
	}

	/**
	 * Init function to setup hooks.
	 */
	public function init() {
		add_action( 'init', array( $this, 'register_stuff' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
		add_shortcode( 'cycloslogin', array( $this, 'handle_login_shortcode' ) );
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
		add_action( 'wp_ajax_cyclos_login', array( $this, 'handle_login_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_cyclos_login', array( $this, 'handle_login_ajax_request' ) );
		add_action( 'wp_ajax_cyclos_captcha', array( $this, 'handle_captcha_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_cyclos_captcha', array( $this, 'handle_captcha_ajax_request' ) );
		add_action( 'wp_ajax_cyclos_forgot_password', array( $this, 'handle_forgot_password_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_cyclos_forgot_password', array( $this, 'handle_forgot_password_ajax_request' ) );
		add_action( 'wp_ajax_cyclos_forgot_password_wizard', array( $this, 'handle_forgot_password_wizard_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_cyclos_forgot_password_wizard', array( $this, 'handle_forgot_password_wizard_ajax_request' ) );
	}

	/**
	 * First register the scripts and styles, then register the Gutenberg blocks.
	 * This way, the scripts are always registered before we register the blocks (that depend on the scripts).
	 * Note: the login form is only available as a shortcode and widget at this moment, not as a Gutenberg block.
	 */
	public function register_stuff() {
		$this->register_assets();
	}

	/**
	 * Register the widget for the Cyclos login form.
	 */
	public function register_widget() {
		return register_widget( LoginWidget::class );
	}

	/**
	 * Shortcode handler for the Cyclos login.
	 */
	public function handle_login_shortcode() {
		$this->enqueue_script();
		return $this->render_loginform();
	}

	/**
	 * Render the login form.
	 */
	public function render_loginform() {
		// Find out which template we should use.
		// There might be a template override in the theme, in: {theme-directory}/cyclos/login-form.php.
		$template_file = locate_template( 'cyclos/login-form.php' );
		if ( empty( $template_file ) ) {
			// If the theme does not contain a template override, use the default template in our plugin.
			$template_file = \Cyclos\PLUGIN_DIR . 'templates/login-form.php';
		}

		// Allow other plugins to change the template location.
		$template_file = apply_filters( 'cyclos_loginform_template', $template_file );

		// Pass variables to the template.
		$login_configuration = $this->cyclos->login_configuration();
		// If the login_configuration does not contain the expected elements, something is wrong with the Cyclos server.
		if ( ! isset( $login_configuration['is_forgot_password_enabled'] ) ||
			! isset( $login_configuration['is_captcha_enabled'] ) ) {
			set_query_var( 'cyclos_error', __( 'Something is wrong with the Cyclos server. The login form cannot be used at the moment.', 'cyclos' ) );
			set_query_var( 'cyclos_is_forgot_password_enabled', false );
			set_query_var( 'cyclos_is_captcha_enabled', false );
			set_query_var( 'cyclos_use_forgot_password_wizard', false );
			set_query_var( 'cyclos_forgot_password_mediums', array() );
			set_query_var( 'cyclos_return_to', '' );
		} else {
			// Cyclos can not send us a nonce, so ignore the recommended nonce verification.
			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$return_to = isset( $_GET['returnTo'] ) ? sanitize_text_field( wp_unslash( $_GET['returnTo'] ) ) : '';
			set_query_var( 'cyclos_is_forgot_password_enabled', $login_configuration['is_forgot_password_enabled'] );
			set_query_var( 'cyclos_is_captcha_enabled', $login_configuration['is_captcha_enabled'] );
			set_query_var( 'cyclos_use_forgot_password_wizard', $login_configuration['has_complex_forgot_password'] );
			set_query_var( 'cyclos_forgot_password_mediums', $login_configuration['forgot_password_mediums'] );
			set_query_var( 'cyclos_return_to', $return_to );
		}

		// Load the template and return its contents.
		// Make sure the template can load more than once, in case the shortcode is on the screen more than once.
		$require_once = false;
		ob_start();
		load_template( $template_file, $require_once );
		return ob_get_clean();
	}

	/**
	 * Register the frontend assets for the loginform.
	 */
	public function register_assets() {
		// Stop if we are not on the frontend.
		if ( is_admin() ) {
			return;
		}

		// Register the login script.
		$file      = 'js/dist/cyclos_login.js';
		$handle    = 'cyclos-loginform';
		$version   = \Cyclos\PLUGIN_VERSION . '-' . filemtime( \Cyclos\PLUGIN_DIR . $file );
		$file_url  = \Cyclos\PLUGIN_URL . $file;
		$deps      = array( 'jquery' );
		$in_footer = true;
		wp_register_script( $handle, $file_url, $deps, $version, $in_footer );

		// Register the login style if this is configured.
		if ( $this->conf->add_styles_to_loginform() ) {
			$file     = 'css/login.css';
			$handle   = 'cyclos-loginform-style';
			$version  = \Cyclos\PLUGIN_VERSION . '-' . filemtime( \Cyclos\PLUGIN_DIR . $file );
			$file_url = \Cyclos\PLUGIN_URL . $file;
			$deps     = array();
			wp_register_style( $handle, $file_url, $deps, $version );
		}
	}

	/**
	 * Enqueue frontend stylesheet for the loginform.
	 * Note: we always enqueue the loginform stylesheet, regardless of whether the loginform is on the screen.
	 * This could be improved by checking if the screen actually contains the loginform shortcode or widget.
	 * But this is not trivial (for example checking the content is not enough, the widget may be in a sidebar).
	 */
	public function enqueue_style() {
		// Enqueue the login stylesheet if this is configured.
		if ( $this->conf->add_styles_to_loginform() ) {
			wp_enqueue_style( 'cyclos-loginform-style' );
		}
	}

	/**
	 * Enqueue frontend javascript for the loginform.
	 */
	public function enqueue_script() {
		// Keep track of wether we have prepared the scripts already or not.
		// If we call wp_localize_script more than once, the resulting javascript object is also put on the html several times.
		static $scripts_ready = false;
		if ( $scripts_ready ) {
			// We have done the enqueue and localize script work already before, so just return.
			return;
		}
		// Enqueue the login script.
		wp_enqueue_script( 'cyclos-loginform' );

		// Pass the necessary information to the login script.
		// Note: We also need a few localized strings in the Javascript and instead of using wp-i18n we just pass them here,
		// because using wp-i18n leads to two extra network requests (wp-i18n and wp-polyfill), which is a bit overkill.
		wp_localize_script(
			'cyclos-loginform',
			'cyclosLoginObj',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'id'       => wp_create_nonce( 'cyclos_login_nonce' ),
				'l10n'     => array(
					'invalidDataMessage'    => __( 'Invalid data received from server', 'cyclos' ),
					'loginFormSetupMessage' => __( 'Something is wrong with the login form setup', 'cyclos' ),
					'captchaSetupMessage'   => __( 'Something is wrong with the captcha function', 'cyclos' ),
				),
			)
		);
		// Set the indicator the scripts are ready, so next time we kan skip the enqueue and localize script work.
		$scripts_ready = true;
	}

	/**
	 * Handle the AJAX request from the login form.
	 */
	public function handle_login_ajax_request() {
		// Die if the nonce is incorrect.
		check_ajax_referer( 'cyclos_login_nonce' );

		// Do a remote request to Cyclos.
		$username       = isset( $_POST['principal'] ) ? wp_unslash( $_POST['principal'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$password       = isset( $_POST['password'] ) ? wp_unslash( $_POST['password'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$remote_address = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
		$return_to      = isset( $_POST['returnTo'] ) ? sanitize_text_field( wp_unslash( $_POST['returnTo'] ) ) : '';

		$response = $this->cyclos->login( $username, $password, $remote_address, $return_to );
		wp_send_json( $response );
	}

	/**
	 * Handle the AJAX request for a captcha from the forgot password form.
	 */
	public function handle_captcha_ajax_request() {
		// Die if the nonce is incorrect.
		check_ajax_referer( 'cyclos_login_nonce' );

		// Do a remote request to Cyclos.
		$response = $this->cyclos->get_captcha();
		wp_send_json( $response );
	}

	/**
	 * Handle the AJAX request from the forgot password form.
	 */
	public function handle_forgot_password_ajax_request() {
		// Die if the nonce is incorrect.
		check_ajax_referer( 'cyclos_login_nonce' );

		// Do a remote request to Cyclos.
		$principal        = isset( $_POST['principal'] ) ? wp_unslash( $_POST['principal'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$captcha_id       = isset( $_POST['captcha_id'] ) ? sanitize_text_field( wp_unslash( $_POST['captcha_id'] ) ) : '';
		$captcha_response = isset( $_POST['captcha_response'] ) ? sanitize_text_field( wp_unslash( $_POST['captcha_response'] ) ) : '';

		$response = $this->cyclos->forgot_password( $principal, $captcha_id, $captcha_response );
		wp_send_json( $response );
	}

	/**
	 * Handle the AJAX request from the forgot password wizard.
	 */
	public function handle_forgot_password_wizard_ajax_request() {
		// Die if the nonce is incorrect.
		check_ajax_referer( 'cyclos_login_nonce' );

		// Do a remote request to Cyclos. Check the step field to determine which route we must call.
		$step = isset( $_POST['step'] ) ? sanitize_text_field( wp_unslash( $_POST['step'] ) ) : '';
		switch ( $step ) {
			case 'request':
				$principal        = isset( $_POST['principal'] ) ? wp_unslash( $_POST['principal'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$captcha_id       = isset( $_POST['captcha_id'] ) ? sanitize_text_field( wp_unslash( $_POST['captcha_id'] ) ) : '';
				$captcha_response = isset( $_POST['captcha_response'] ) ? sanitize_text_field( wp_unslash( $_POST['captcha_response'] ) ) : '';
				$send_medium      = isset( $_POST['send_medium'] ) ? sanitize_text_field( wp_unslash( $_POST['send_medium'] ) ) : '';

				$response = $this->cyclos->forgot_password_step_request( $principal, $captcha_id, $captcha_response, $send_medium );
				break;
			case 'code':
				$principal = isset( $_POST['principal'] ) ? wp_unslash( $_POST['principal'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$code      = isset( $_POST['code'] ) ? sanitize_text_field( wp_unslash( $_POST['code'] ) ) : '';

				$response = $this->cyclos->forgot_password_step_code( $principal, $code );
				break;
			case 'change':
				// Note: we can not sanitize the password and principal fields, because they may contain legitimate special characters.
				// phpcs:disable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				$principal        = isset( $_POST['principal'] ) ? wp_unslash( $_POST['principal'] ) : '';
				$code             = isset( $_POST['code'] ) ? sanitize_text_field( wp_unslash( $_POST['code'] ) ) : '';
				$new_password     = isset( $_POST['new_pw'] ) ? wp_unslash( $_POST['new_pw'] ) : '';
				$confirm_password = isset( $_POST['confirm_pw'] ) ? wp_unslash( $_POST['confirm_pw'] ) : '';
				$security_answer  = isset( $_POST['sec_answer'] ) ? sanitize_text_field( wp_unslash( $_POST['sec_answer'] ) ) : '';
				// phpcs:enable WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

				$response = $this->cyclos->forgot_password_step_change( $principal, $code, $new_password, $confirm_password, $security_answer );
				break;
			default:
				$response = array(
					'errorMessage' => __( 'Unidentified wizard step', 'cyclos' ) . ': ' . $step,
				);
		}
		wp_send_json( $response );
	}
}
