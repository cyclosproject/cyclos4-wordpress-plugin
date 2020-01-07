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
 * Requires at least: 4.3
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

// Block people to access the script directly (against malicious attempts).
defined( 'ABSPATH' ) || exit;

/**
 * At plugin activation, check the PHP version.
 */
function cyclos_plugin_activate() {
	if ( version_compare( PHP_VERSION, '5.6.0', '<' ) ) {
		wp_die( 'The Cyclos plugin requires at least PHP version 5.6. You have ' . PHP_VERSION );
		deactivate_plugins( basename( __FILE__ ) );
	}
}
register_activation_hook( __FILE__, 'cyclos_plugin_activate' );

if ( version_compare( PHP_VERSION, '5.6.0', '>=' ) ) {
	include_once 'cyclos-common.php';
	include_once 'cyclos-admin.php';
	include_once 'cyclos-public.php';
}
