<?php
/**
 * Updater class to update things when needed.
 * For example migrating option records when the plugin is updated from an older version.
 *
 * @package Cyclos
 */

namespace Cyclos\Utils;

use Cyclos\Configuration;

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
	 * - Default to use styling on the loginform because the old version included styling on the form.
	 */
	protected function update_200() {
		// Retrieve the old-style option record values.
		$cyclos_url      = get_option( 'cyclos_url' );
		$cyclos_admin    = get_option( 'cyclos_adminuser' );
		$cyclos_token    = get_option( 'cyclos_token' );
		$cyclos_redirect = get_option( 'cyclos_redirectUrl' ); // Note: this option was never in an official release but it was in the trunk, so people might have it.

		// If the option records are not found, the plugin was newly installed or never used and we don't need to do anything.
		// If the option records are found, we do the updating we need.
		if ( $cyclos_url || $cyclos_admin || $cyclos_token || $cyclos_redirect ) {
			// The plugin was updated from an older version. Migrate the options now.
			// Note: while it is quite unlikely an option record new-style exists, we do retrieve and update it.
			// The update_option() call later on will create the option record if it does not exist yet.
			$option                       = get_option( Configuration::CYCLOS_OPTION_NAME, array() );
			$option['cyclos_url']         = $cyclos_url;
			$option['username']           = $cyclos_admin;
			$option['accessclient_token'] = $cyclos_token;
			$option['custom_cyclos_url']  = $cyclos_redirect;

			// The old version of the plugin might have label texts configured in option records. Migrate them as well.
			$option['name_label']           = get_option( 'cyclos_t_loginName' );
			$option['password_label']       = get_option( 'cyclos_t_loginPassword' );
			$option['submit_button']        = get_option( 'cyclos_t_loginSubmit' );
			$option['forgot_pw_linktext']   = get_option( 'cyclos_t_forgotLink' );
			$option['forgot_pw_email']      = get_option( 'cyclos_t_forgotEmail' );
			$option['forgot_pw_captcha']    = get_option( 'cyclos_t_forgotCaptcha' );
			$option['forgot_pw_newcaptcha'] = get_option( 'cyclos_t_forgotNewCaptcha' );
			$option['forgot_pw_submit']     = get_option( 'cyclos_t_forgotSubmit' );
			$option['forgot_pw_cancel']     = get_option( 'cyclos_t_forgotCancel' );

			// Remove all keys with an empty value.
			$option = array_filter( $option );

			// Set the loginform styling to true by default.
			$option['style_loginform'] = true;

			update_option( Configuration::CYCLOS_OPTION_NAME, $option );

			// Delete the old options.
			delete_option( 'cyclos_url' );
			delete_option( 'cyclos_adminuser' );
			delete_option( 'cyclos_token' );
			delete_option( 'cyclos_redirectUrl' );
			delete_option( 'cyclos_t_loginName' );
			delete_option( 'cyclos_t_loginPassword' );
			delete_option( 'cyclos_t_loginSubmit' );
			delete_option( 'cyclos_t_forgotLink' );
			delete_option( 'cyclos_t_forgotEmail' );
			delete_option( 'cyclos_t_forgotCaptcha' );
			delete_option( 'cyclos_t_forgotNewCaptcha' );
			delete_option( 'cyclos_t_forgotSubmit' );
			delete_option( 'cyclos_t_forgotCancel' );

			// Also delete old options we no longer use. These are not migrated, just removed.
			delete_option( 'cyclos_t_loginTitle' );
			delete_option( 'cyclos_t_forgotTitle' );
			delete_option( 'cyclos_t_forgotDone' ); // This message is no longer customizable.
			// Note: there may also be texts for error messages, but we don't migrate those. We only remove their option records if they exist.
			// In the new version of the plugin, error messages can no longer be customized, only translated via translate.wordpress.org.
			delete_option( 'cyclos_t_errorLogin' );
			delete_option( 'cyclos_t_errorAddressBlocked' );
			delete_option( 'cyclos_t_errorEmailNotFound' );
			delete_option( 'cyclos_t_errorConnection' );
			delete_option( 'cyclos_t_errorGeneral' );
			// Also delete options that might exist if people used the development version that was never offically released.
			delete_option( 'cyclos_t_errorInaccessibleChannel' );
			delete_option( 'cyclos_t_errorInaccessiblePrincipal' );
			delete_option( 'cyclos_t_errorUserBlocked' );
			delete_option( 'cyclos_t_errorUserDisabled' );
			delete_option( 'cyclos_t_errorUserPending' );
			delete_option( 'cyclos_t_errorPasswordIndefinitelyBlocked' );
			delete_option( 'cyclos_t_errorPasswordTemporarilyBlocked' );
			delete_option( 'cyclos_t_errorInvalidPassword' );
			delete_option( 'cyclos_t_errorInvalidAccessClient' );
			delete_option( 'cyclos_t_errorOperatorWithPendingAgreements' );
			delete_option( 'cyclos_t_errorEntityNotFound' );
			delete_option( 'cyclos_t_errorEntityNotFoundUser' );
			delete_option( 'cyclos_t_errorEntityNotFoundAccessClient' );
		}
	}

}
