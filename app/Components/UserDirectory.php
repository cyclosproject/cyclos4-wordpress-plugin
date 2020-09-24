<?php
/**
 * The UserDirectory component enables the Cyclos users map/list.
 *
 * @package Cyclos
 */

namespace Cyclos\Components;

use Cyclos\Configuration;
use Cyclos\Services\CyclosAPI;

/**
 * UserDirectory class
 */
class UserDirectory {

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
		add_shortcode( 'cyclosusers', array( $this, 'handle_users_shortcode' ) );
		add_filter( 'cyclos_render_setting', array( $this, 'render_user_settings' ), 10, 2 );
		add_action( 'wp_ajax_cyclos_refresh_user_data', array( $this, 'handle_refresh_user_data_ajax_request' ) );
		// Note: we don't need a 'wp_ajax_nopriv_cyclos_refresh_user_data' because the refresh data action can only be done by admins.
		add_action( 'wp_ajax_cyclos_userdata', array( $this, 'handle_retrieve_userdata_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_cyclos_userdata', array( $this, 'handle_retrieve_userdata_ajax_request' ) );
		add_action( 'wp_ajax_cyclos_usermetadata', array( $this, 'handle_retrieve_usermetadata_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_cyclos_usermetadata', array( $this, 'handle_retrieve_usermetadata_ajax_request' ) );
	}

	/**
	 * First register the scripts and styles, then register the Gutenberg blocks.
	 * This way, the scripts are always registered before we register the blocks (that depend on the scripts).
	 * Note: the userdirectory is only available as a shortcode at this moment, not as a Gutenberg block.
	 */
	public function register_stuff() {
		$this->register_assets();
	}

	/**
	 * Shortcode handler for the Cyclos user directory.
	 *
	 * @param array  $atts     The shortcode attributes.
	 * @param string $content  The content of the shortcode.
	 * @param string $tag      The shortcode tag.
	 * @return string          The rendered user data.
	 */
	public function handle_users_shortcode( $atts = array(), $content = null, $tag = '' ) {
		$this->enqueue_script();

		$atts = shortcode_atts(
			array(
				'views'           => 'list',
				'filter_category' => '',
				'show_filter'     => true,
				'order_field'     => '',
				'sort_order'      => 'asc',
				'show_orderby'    => true,
				'sort_options'    => '',
			),
			$atts,
			$tag
		);
		// Make sure the views attribute is an array.
		$views = explode( ',', $atts['views'] );

		// Build up the output.
		$output = '';
		foreach ( $views as $view ) {
			switch ( trim( $view ) ) {
				case 'map':
					$output .= $this->render_user_map( $atts );
					break;
				case 'list':
					$output .= $this->render_user_list( $atts );
					break;
				default:
					$output .= __( 'The user directory must use either a map or a list view. No other options are available.', 'cyclos' );
			}
		}
		return $output;
	}

	/**
	 * Render the user directory list view.
	 *
	 * @param array $atts     The shortcode attributes relevant for list views.
	 * @return string         The rendered list with the user data.
	 */
	public function render_user_list( $atts ) {
		return sprintf(
			'<div class="cyclos-user-list"%s%s%s%s%s></div>',
			$this->make_data_attribute( 'filter', $atts['filter_category'] ),
			$this->make_data_attribute( 'show-filter', $atts['show_filter'], 'boolean' ),
			$this->make_data_attribute( 'orderby', $atts['order_field'] . '-' . $atts['sort_order'] ),
			$this->make_data_attribute( 'show-orderby', $atts['show_orderby'], 'boolean' ),
			$this->make_data_attribute( 'sort-options', $atts['sort_options'] )
		);
	}

	/**
	 * Render the user directory map view.
	 *
	 * @return string           The rendered map with the user data.
	 */
	public function render_user_map() {
		return '<div class="cyclos-user-map">The map view is not implemented yet. Please use the list view for now.</div>';
	}

	/**
	 * Build an HTML data attribute with the given value.
	 * For string attributes: when non-empty, the data attribute is added and filled with the value; when empty, the data attribute is left out.
	 * For boolean attributes: when truthy, the data attribute is added without any value; when false, the data attribute is left out.
	 *
	 * @param string $name    The id to use in the name of the data attribute. Used after the 'data-cyclos-' prefix.
	 * @param string $value   The value.
	 * @param string $type    (Optional) The type of the value. Can be string or boolean. Default: string.
	 * @return $html          The HTML of the data attribute.
	 */
	protected function make_data_attribute( string $name, string $value, string $type = 'string' ) {
		$html = '';
		switch ( $type ) {
			case 'string':
				// String attributes are filled with their value if the value is not empty.
				if ( ! empty( $value ) ) {
					$html .= sprintf( ' data-cyclos-%s="%s"', esc_attr( $name ), esc_attr( $value ) );
				}
				break;
			case 'boolean':
				// Boolean attributes are simply put in without value if the value is true.
				// To check whether the given value is truthy, use PHP's filter_var function.
				// This way it does not matter if the value is passed as 0/1, '0'/'1', 'true'/'false', 'TRUE'/'FALSE'.
				if ( filter_var( $value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) ) {
					$html .= sprintf( ' data-cyclos-%s', esc_attr( $name ) );
				}
				break;
		}
		return $html;
	}

	/**
	 * Register the frontend assets for the userdirectory.
	 */
	public function register_assets() {
		// Stop if we are not on the frontend.
		if ( is_admin() ) {
			return;
		}

		// Register the userdirectory script.
		$file      = 'js/dist/cyclos_userdirectory.js';
		$handle    = 'cyclos-userdirectory';
		$version   = \Cyclos\PLUGIN_VERSION . '-' . filemtime( \Cyclos\PLUGIN_DIR . $file );
		$file_url  = \Cyclos\PLUGIN_URL . $file;
		$deps      = array( 'wp-polyfill' );
		$in_footer = true;
		wp_register_script( $handle, $file_url, $deps, $version, $in_footer );

		// phpcs:disable Squiz.PHP.CommentedOutCode.Found, Squiz.Commenting.InlineComment.SpacingBefore
		// // Register the userdirectory style if this is configured.
		// if ( $this->conf->add_styles_to_userdirectory() ) {
			$file     = 'css/userdirectory.css';
			$handle   = 'cyclos-userdirectory-style';
			$version  = \Cyclos\PLUGIN_VERSION . '-' . filemtime( \Cyclos\PLUGIN_DIR . $file );
			$file_url = \Cyclos\PLUGIN_URL . $file;
			$deps     = array();
			wp_register_style( $handle, $file_url, $deps, $version );
		// }
		// phpcs:enable Squiz.PHP.CommentedOutCode.Found, Squiz.Commenting.InlineComment.SpacingBefore
	}

	/**
	 * Enqueue frontend stylesheet for the userdirectory.
	 * Note: we always enqueue the userdirectory stylesheet, regardless of whether the userdirectory is on the screen.
	 * This could be improved by checking if the screen actually contains the userdirectory shortcode.
	 * But this is not trivial (for example the content may be in an intro text on a category screen).
	 */
	public function enqueue_style() {
		// phpcs:disable Squiz.PHP.CommentedOutCode.Found
		// // Enqueue the userdirectory stylesheet if this is configured.
		// if ( $this->conf->add_styles_to_userdirectory() ) {
			wp_enqueue_style( 'cyclos-userdirectory-style' );
		// }
		// phpcs:enable Squiz.PHP.CommentedOutCode.Found
	}

	/**
	 * Enqueue frontend javascript for the userdirectory.
	 */
	public function enqueue_script() {
		// Keep track of wether we have prepared the scripts already or not.
		// If we call wp_localize_script more than once, the resulting javascript object is also put on the html several times.
		static $scripts_ready = false;
		if ( $scripts_ready ) {
			// We have done the enqueue and localize script work already before, so just return.
			return;
		}
		// Enqueue the userdirectory script.
		wp_enqueue_script( 'cyclos-userdirectory' );

		// Pass the necessary information to the userdirectory script.
		wp_localize_script(
			'cyclos-userdirectory',
			'cyclosUserObj',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'id'       => wp_create_nonce( 'cyclos_userdirectory_nonce' ),
				'l10n'     => array(
					'invalidDataMessage' => __( 'Invalid data received from server', 'cyclos' ),
					'setupMessage'       => __( 'Something is wrong with the user directory setup', 'cyclos' ),
					'noUsers'            => __( 'No users found', 'cyclos' ),
					'filterLabel'        => __( 'Filter', 'cyclos' ),
					'noFilterOption'     => __( 'All users', 'cyclos' ),
					'sortLabel'          => __( 'Sort', 'cyclos' ),
					'noSortOption'       => __( 'Default', 'cyclos' ),
				),
			)
		);
		// Set the indicator the scripts are ready, so next time we kan skip the enqueue and localize script work.
		$scripts_ready = true;
	}

	/**
	 * Return information about this component.
	 */
	public static function get_component_info() {
		return array(
			'tab'   => __( 'User Directory', 'cyclos' ),
			'intro' => __( 'You can put a user directory (map/list) on your website using the <code>[cyclosusers]</code> shortcode on one of your Posts or Pages.', 'cyclos' ),
		);
	}

	/**
	 * Render custom setting types for this component.
	 *
	 * @param callable $callback  The original function to call to render the setting.
	 * @param string   $type      The setting type.
	 * @return callable           The new function to call to render the setting.
	 */
	public function render_user_settings( $callback, $type ) {
		switch ( $type ) {
			case 'user_data_transient':
				return array( $this, 'render_user_data_transient' );
			case 'user_data_sort':
				return array( $this, 'render_user_data_sort' );
			default:
				return $callback;
		}
	}

	/**
	 * Render a user data transient setting field.
	 * This is not an input field, but information on the current data and a button to refresh the data.
	 *
	 * @param array $args Contains the key and Settings object of the field to render.
	 */
	public function render_user_data_transient( $args ) {
		$setting   = $args['setting_info'];
		$user_data = $this->get_cyclos_user_data();
		if ( is_wp_error( $user_data ) ) {
			echo esc_html( $user_data->get_error_message() );
			return;
		}
		printf(
			'<p class="cyclos-user-data-info">%s %s</p><p><button class="button" type="button" id="cyclos-user-data-refresh">%s</button></p>',
			esc_html( count( $user_data ) ),
			esc_html__( 'Cyclos users', 'cyclos' ),
			esc_html__( 'Refresh current user data', 'cyclos' )
		);
		if ( ! empty( $setting->get_description() ) ) {
			printf( '<p class="description">%s</p>', esc_html( $setting->get_description() ) );
		}
	}

	/**
	 * Render a user data sort setting field.
	 * This is a dropdown list, with options according to what the REST API /users/map can receive as 'orderBy' parameter.
	 *
	 * @param array $args Contains the key and Settings object of the field to render.
	 */
	public function render_user_data_sort( $args ) {
		$field_id = $args['label_for'];
		$setting  = $args['setting_info'];
		$value    = $this->conf->get_setting( $field_id );
		$name     = $this->conf::CYCLOS_OPTION_NAME . '[' . $field_id . ']';
		$options  = array(
			'alphabeticallyAsc'  => __( 'Alphabetically Ascending [a-z]', 'cyclos' ),
			'alphabeticallyDesc' => __( 'Alphabetically Descending [z-a]', 'cyclos' ),
			'creationDate'       => __( 'By Creation Date (newest first)', 'cyclos' ),
			'random'             => __( 'Random', 'cyclos' ),
		);
		printf( '<select name="%s" id="%s">', esc_html( $name ), esc_html( $field_id ) );
		foreach ( $options as $option => $name ) {
			printf( '<option value="%s" %s>%s</option>', esc_attr( $option ), ( $value === $option ? 'selected' : '' ), esc_html( $name ) );
		}
		print( '</select>' );
		if ( ! empty( $setting->get_description() ) ) {
			printf( '<p class="description">%s</p>', esc_html( $setting->get_description() ) );
		}
	}

	/**
	 * Handle the AJAX request to refresh the Cyclos user data.
	 */
	public function handle_refresh_user_data_ajax_request() {
		// Die if the nonce is incorrect.
		// Note: In Admin we call settings_fields( self::SETTINGS_PAGE ), which creates a nonce for an action of the form: {$option_group}-options.
		// So, to check the nonce here, we must use this same action. In admin.js we get the nonce value from the _wpnonce form field.
		// See https://developer.wordpress.org/reference/functions/settings_fields/.
		check_ajax_referer( Admin::SETTINGS_PAGE . '-options' );

		// Get the Cyclos user data, forcing new retrieval from Cyclos.
		$user_data = $this->get_cyclos_user_data( true );
		$response  = '';
		if ( is_wp_error( $user_data ) ) {
			$response = $user_data->get_error_message();
		} else {
			// Also refresh the metadata, because both data belong together.
			$user_metadata = $this->get_cyclos_user_metadata( true );

			// Return either the number of users found, or an error message if something is wrong.
			if ( is_wp_error( $user_metadata ) ) {
				$response = $user_metadata->get_error_message();
			} else {
				$response = sprintf( '%s %s', count( $user_data ), __( 'Cyclos users', 'cyclos' ) );
			}
		}
		wp_send_json( $response );
	}

	/**
	 * Handle the AJAX request for retrieving the Cyclos users.
	 */
	public function handle_retrieve_userdata_ajax_request() {
		$error_message = '';
		$users         = array();

		// Die if the nonce is incorrect.
		check_ajax_referer( 'cyclos_userdirectory_nonce' );

		// Get the Cyclos user data.
		$user_data = $this->get_cyclos_user_data();

		// Return the users found, and an error message if something is wrong.
		if ( is_wp_error( $user_data ) ) {
			$error_message = $user_data->get_error_message();
		} else {
			$users = $user_data;
		}
		$response = array(
			'data'  => $users,
			'error' => $error_message,
		);

		wp_send_json( $response );
	}

	/**
	 * Handle the AJAX request for retrieving the Cyclos user metadata.
	 */
	public function handle_retrieve_usermetadata_ajax_request() {
		$error_message = '';
		$metadata      = array();

		// Die if the nonce is incorrect.
		check_ajax_referer( 'cyclos_userdirectory_nonce' );

		// Get the Cyclos user metadata.
		$user_metadata = $this->get_cyclos_user_metadata();

		// Return the user metadata found, and an error message if something is wrong.
		if ( is_wp_error( $user_metadata ) ) {
			$error_message = $user_metadata->get_error_message();
		} else {
			$metadata = $user_metadata;
		}
		$response = array(
			'data'  => $metadata,
			'error' => $error_message,
		);

		wp_send_json( $response );
	}

	/**
	 * Return the Cyclos user data, using a transient to limit the number of calls to the Cyclos server.
	 *
	 * @param  bool $force_new  (Optional) Whether the data should always be retrieved from Cyclos. Defaults to false.
	 * @return array|\WP_Error  Array with user data or a WP_Error object on failure.
	 */
	protected function get_cyclos_user_data( bool $force_new = false ) {
		// If we can use the data from our transient, use that.
		$user_data = get_transient( Configuration::USER_DATA_TRANSIENT );
		if ( $force_new || false === $user_data ) {
			// The transient is not there or not valid anymore, so retrieve the data from Cyclos.
			$user_data = $this->cyclos->get_user_data();
			// Store the data in the transient, but only if it is not an error.
			if ( ! is_wp_error( $user_data ) ) {
				set_transient( Configuration::USER_DATA_TRANSIENT, $user_data, $this->conf->get_user_data_expiration_time() * MINUTE_IN_SECONDS );
			}
		}
		return $user_data;
	}

	/**
	 * Return the Cyclos user metadata, using a transient to limit the number of calls to the Cyclos server.
	 *
	 * @param  bool $force_new  (Optional) Whether the data should always be retrieved from Cyclos. Defaults to false.
	 * @return array|\WP_Error  Array with user data or a WP_Error object on failure.
	 */
	protected function get_cyclos_user_metadata( bool $force_new = false ) {
		// If we can use the metadata from our transient, use that.
		$user_metadata = get_transient( Configuration::USER_METADATA_TRANSIENT );
		if ( $force_new || false === $user_metadata ) {
			// The transient is not there or not valid anymore, so retrieve the data from Cyclos.
			$user_metadata = $this->cyclos->get_user_metadata();
			// Store the data in the transient, but only if it is not an error.
			if ( ! is_wp_error( $user_metadata ) ) {
				set_transient( Configuration::USER_METADATA_TRANSIENT, $user_metadata, $this->conf->get_user_data_expiration_time() * MINUTE_IN_SECONDS );
			}
		}
		return $user_metadata;
	}

}
