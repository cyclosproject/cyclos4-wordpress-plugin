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
	 */
	public function handle_users_shortcode() {
		return $this->render_userdirectory();
	}

	/**
	 * Render the user directory.
	 */
	public function render_userdirectory() {
		// During development, just render some minimal data.
		return 'The Cyclos userdirectory will be shown here.';
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
