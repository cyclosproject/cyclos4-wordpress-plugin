<?php
/*
Plugin Name: Cyclos
Plugin URI: http://www.cyclos.org/worpressplugin/
Description: Integrates the Cyclos login form into your WordPress blog.
Version: 1.0
Author: The Cyclos team
Author URI: http://www.cyclos.org
*/

/*  Copyright 2015 Cyclos (www.cyclos.org)

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

// Block people to access the script directly (against malicious attempts)
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

function cyclos_plugin_activate() {
    if (version_compare(PHP_VERSION, '5.3.0', '<')) {
        wp_die('The Cyclos plugin requires at least PHP version 5.3. You have ' . PHP_VERSION);
        deactivate_plugins( basename( __FILE__ ) );
    }
}
register_activation_hook( __FILE__, 'cyclos_plugin_activate' );

if (version_compare(PHP_VERSION, '5.3.0', '>=')) {
    include_once 'cyclos-common.php';
    include_once 'cyclos-admin.php';
    include_once 'cyclos-public.php';
}
?>
