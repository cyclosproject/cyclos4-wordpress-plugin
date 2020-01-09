<?php
/**
 * Functions for use in templates.
 *
 * @package Cyclos
 */

/**
 * Display the label text for use in the login form that belongs to the given id.
 * This is either the label as set in the plugin settings, the default value, or an empty string if the id is unknown.
 *
 * @param string $id Identifies which label is requested.
 */
function cyclos_loginform_label( string $id ) {
	$conf = Cyclos\Configuration::get_instance();
	echo esc_html( $conf->get_loginform_labels()[ $id ] ?? '' );
}
