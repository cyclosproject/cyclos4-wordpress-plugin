<?php
/**
 * Main plugin file
 *
 * @package Cyclos
 *
 * @wordpress-plugin
 * Plugin Name:       Cyclos
 * Plugin URI:        https://www.cyclos.org/wordpress-plugins/
 * Description:       Integrates the Cyclos login form into your WordPress blog.
 * Version:           2.0.0-dev
 * Requires at least: 5.0
 * Requires PHP:      5.6
 * Author:            The Cyclos team
 * Author URI:        https://www.cyclos.org
 * Text domain:       cyclos
 * License:           GPL v2 or later
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

/*
	Copyright 2015 Cyclos (www.cyclos.org)

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

namespace Cyclos;

// Block people to access the script directly (against malicious attempts).
defined( 'ABSPATH' ) || exit;

define( 'Cyclos\\PLUGIN_VERSION', '2.0.0' );
define( 'Cyclos\\MINIMUM_PHP_REQUIRED', '5.6' );
define( 'Cyclos\\MINIMUM_WP_REQUIRED', '5.0' );
define( 'Cyclos\\PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'Cyclos\\PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'Cyclos\\PLUGIN_FILE', __FILE__ );

// Using composer autoload.
require_once 'vendor/autoload.php';

// Load the necessary parts of the plugin in the proper action hook - so others can remove them if needed.
add_action( 'plugins_loaded', __NAMESPACE__ . '\\load_plugin_parts' );

/**
 * Load the parts of the plugin.
 */
function load_plugin_parts() {
	// Check if we need to run plugin updates.
	$db_version = get_option( 'cyclos_version', '1' );
	if ( version_compare( $db_version, PLUGIN_VERSION, '<' ) ) {
		// The plugin version in the database is older than the current code.
		// Call the plugin updater that will do updates where needed.
		new Utils\Updater( $db_version );
	}

	// Load the helper classes.
	$config     = Configuration::get_instance();
	$cyclos_api = new Services\CyclosAPI( $config );

	// Load the admin component.
	$admin = new Components\Admin( $config );
	$admin->init();

	// Load the login component.
	$login_component = new Components\LoginComponent( $config, $cyclos_api );
	$login_component->init();

}

// Include the template functions.
require_once 'template-functions.php';

/**
 * At plugin activation, register the uninstall hook.
 */
function plugin_activate() {
	// Register the uninstall hook.
	register_uninstall_hook( __FILE__, __NAMESPACE__ . '\\plugin_uninstall' );
}
register_activation_hook( __FILE__, __NAMESPACE__ . '\\plugin_activate' );

/**
 * At plugin uninstall, remove all plugin data.
 */
function plugin_uninstall() {
	// Delete any instances of our widget from any sidebar.
	// First unregister the widget, then use retrieve_widgets() that removes 'lost' widgets for us.
	unregister_widget( Widgets\LoginWidget::class );
	retrieve_widgets();

	// Delete the widget options record.
	delete_option( 'widget_' . Widgets\LoginWidget::ID_BASE );

	// Delete the plugin options records.
	delete_option( Configuration::CYCLOS_OPTION_NAME );
	delete_option( 'cyclos_version' );
}
