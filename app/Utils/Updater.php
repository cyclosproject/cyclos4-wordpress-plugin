<?php
/**
 * Updater class to update things when needed.
 * For example migrating option records when the plugin is updated from an older version.
 *
 * @package Cyclos
 */

namespace Cyclos\Utils;

/**
 * Updater class.
 */
class Updater {

	/**
	 * Constructor.
	 *
	 * @param string $db_version   The version of the plugin in the database.
	 */
	public function __construct( string $db_version ) {
		// Do the specific updates for each version that requires it.
		if ( version_compare( $db_version, '2.0.0', '<' ) ) {
			$this->update_200();
		}
	}

	/**
	 * Changes when updating to version 2.0.0.:
	 * - Move option records into one condensed option record.
	 * - Default to use styling on the loginform when the plugin is updated from an older version.
	 */
	protected function update_200() {
		// If the database contains old-style option records for the plugin, condense them into one.
		// Also change the default value of using styles on the login form to true if we update from an older version.
		$cyclos_url      = get_option( 'cyclos_url' );
		$cyclos_admin    = get_option( 'cyclos_adminuser' );
		$cyclos_token    = get_option( 'cyclos_token' );
		$cyclos_redirect = get_option( 'cyclos_redirectUrl' ); // Note: this option was never in an official release but it was in the trunk, so people might have it.

		// If the option records are not found, the plugin was newly installed and we don't need to do anything.
		// If the option records are found, we do the updating we need.
		if ( $cyclos_url || $cyclos_admin || $cyclos_token || $cyclos_redirect ) {
			// The plugin was updated from an older version. Migrate the options now.
			// Note: while it is quite unlikely an option record new-style exists, we do retrieve and update it.
			// The update_option() call later on will create the option record if it does not exist yet.
			$option = get_option( Configuration::CYCLOS_OPTION_NAME, array() );
			if ( $cyclos_url ) {
				$option['cyclos_url'] = $cyclos_url;
			}
			if ( $cyclos_admin ) {
				$option['username'] = $cyclos_admin;
			}
			if ( $cyclos_token ) {
				$option['accessclient_token'] = $cyclos_token;
			}
			if ( $cyclos_redirect ) {
				$option['custom_cyclos_url'] = $cyclos_redirect;
			}
			// Set the loginform styling to true by default.
			$option['style_loginform'] = true;

			update_option( Configuration::CYCLOS_OPTION_NAME, $option );

			// Delete the old options.
			delete_option( 'cyclos_url' );
			delete_option( 'cyclos_adminuser' );
			delete_option( 'cyclos_token' );
			delete_option( 'cyclos_redirectUrl' );
		}
	}

}
