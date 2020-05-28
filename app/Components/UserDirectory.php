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
		add_shortcode( 'cyclosusers', array( $this, 'handle_users_shortcode' ) );
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
		$atts = shortcode_atts(
			array(
				'view' => 'list',
			),
			$atts,
			$tag
		);
		return $this->render_userdirectory( $atts['view'] );
	}

	/**
	 * Render the user directory. Depending on the given view, the data is returned as a map or a list.
	 *
	 * @param string $view   The view to show the user data. Can be either 'map' or 'list'.
	 * @return string        The rendered user data or an error message if something went wrong.
	 */
	public function render_userdirectory( $view ) {
		// Retrieve the Cyclos user data.
		$user_data = $this->get_cyclos_user_data();

		// If something went wrong, return the error message instead of rendering the view.
		if ( is_wp_error( $user_data ) ) {
			return $user_data->get_error_message();
		}

		// We have an array with user data. Pass it to the render method for the relevant view.
		switch ( $view ) {
			case 'map':
				return $this->render_user_map( $user_data );
			case 'list':
				return $this->render_user_list( $user_data );
			default:
				return __( 'The user directory must use either a map or a list view. No other options are available.', 'cyclos' );
		}
	}

	/**
	 * Return the Cyclos user data, using a transient to limit the number of calls to the Cyclos server.
	 */
	protected function get_cyclos_user_data() {
		// If we can use the data from our transient, use that.
		$user_data = get_transient( Configuration::USER_DATA_TRANSIENT );
		if ( false === $user_data ) {
			// The transient is not there or not valid anymore, so retrieve the data from Cyclos.
			$user_data = $this->cyclos->get_user_data( $this->conf->get_user_group() );
			// Store the data in the transient, but only if it is not an error.
			if ( ! is_wp_error( $user_data ) ) {
				set_transient( Configuration::USER_DATA_TRANSIENT, $user_data, $this->conf->get_user_data_expiration_time() * MINUTE_IN_SECONDS );
			}
		}
		return $user_data;
	}

	/**
	 * Render the user directory list view.
	 *
	 * @param array $user_data  Array with the user data.
	 * @return string           The rendered list with the user data.
	 */
	public function render_user_list( $user_data ) {
		// Find out which template we should use.
		// There might be a template override in the theme, in: {theme-directory}/cyclos/user-list.php.
		$template_file = locate_template( 'cyclos/user-list.php' );
		if ( empty( $template_file ) ) {
			// If the theme does not contain a template override, use the default template in our plugin.
			$template_file = \Cyclos\PLUGIN_DIR . 'templates/user-list.php';
		}

		// Allow other plugins to change the template location.
		$template_file = apply_filters( 'cyclos_userlist_template', $template_file );

		// Pass variables to the template.
		set_query_var( 'cyclos_user_data', $user_data );

		// Load the template and return its contents.
		// Make sure the template can load more than once, in case the shortcode is on the screen more than once.
		$require_once = false;
		ob_start();
		load_template( $template_file, $require_once );
		return ob_get_clean();
	}

	/**
	 * Render the user directory map view.
	 *
	 * @param array $user_data  Array with the user data.
	 * @return string           The rendered map with the user data.
	 */
	public function render_user_map( $user_data ) {
		return sprintf( 'There are %1$u Cyclos users, but the map view is not implemented yet. Please use the list view for now.', count( $user_data ) );
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
}
