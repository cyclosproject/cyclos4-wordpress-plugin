<?php
/**
 * Updater class to update things when needed.
 * For example migrating option records when the plugin is updated from an older version.
 *
 * @package Cyclos
 */

namespace Cyclos\Utils;

use Cyclos\Configuration;
use Exception;

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
			try {
				$this->update_200();
			} catch ( Exception $e ) {
				// We don't use the exception. It is just to make sure the updater stops doing anything else.
				return;
			}
		}

		// Next, update the version of the plugin in the database.
		// Note: if there was no option record yet, this will add one.
		update_option( 'cyclos_version', \Cyclos\PLUGIN_VERSION );
	}

	/**
	 * Changes when updating to version 2.0.0.:
	 * - Check the minimum requirements for PHP and WordPress that we raised in version 2.0.0.
	 * - Register the uninstall hook, that was newly added in the activation hook of the plugin in version 2.0.0.
	 * - Move option records into one condensed option record.
	 * - Default to use styling on the loginform because the old version included styling on the form.
	 *
	 * @throws Exception When the minimum requirements are not met, this method throws an Exception.
	 */
	protected function update_200() {
		// Check the minimum requirements for the 2.0.0 version of our plugin.
		// Note: we could have used the WP core function validate_plugin_requirements() for this as well.
		// But this was added only in WordPress 5.2, so we would still need a fallback for older systems.
		// And also, it parses the readme.txt and main plugin file, which seems a bit more resource intensive.
		$wp_version       = get_bloginfo( 'version' );
		$php_compatible   = version_compare( PHP_VERSION, \Cyclos\MINIMUM_PHP_REQUIRED, '>=' );
		$wp_compatible    = version_compare( $wp_version, \Cyclos\MINIMUM_WP_REQUIRED, '>=' );
		$incompatible_msg = '';
		if ( ! $php_compatible ) {
			$incompatible_msg = sprintf(
				/* translators: 1: required PHP version 2: current PHP version. */
				__( 'The Cyclos plugin requires PHP version %1$s. Your system has PHP version %2$s. The plugin will not work on your system and was therefore deactivated. Please update your PHP version if you want to use this plugin.', 'cyclos' ),
				\Cyclos\MINIMUM_PHP_REQUIRED,
				PHP_VERSION
			);
		}
		if ( ! $wp_compatible ) {
			$incompatible_msg = sprintf(
				/* translators: 1: required WordPress version 2: current WordPress version. */
				__( 'The Cyclos plugin requires WordPress version %1$s. Your system has WordPress version %2$s. The plugin will not work on your system and was therefore deactivated. Please update your WordPress version if you want to use this plugin.', 'cyclos' ),
				\Cyclos\MINIMUM_WP_REQUIRED,
				$wp_version
			);
		}
		// If the current system is not compatible, show an admin notice, deactivate our plugin and stop the update procedure.
		if ( ! empty( $incompatible_msg ) ) {
			if ( is_admin() ) {
				add_action(
					'admin_notices',
					function () use ( $incompatible_msg ) {
						printf( '<div class="notice notice-error is-dismissible"><p>%s</p></div>', esc_html( $incompatible_msg ) );
					}
				);
			}
			if ( ! function_exists( 'deactivate_plugins' ) ) {
				require_once \ABSPATH . 'wp-admin/includes/plugin.php';
			}
			deactivate_plugins( plugin_basename( \Cyclos\PLUGIN_FILE ) );
			// Unset the query parameter that WordPress uses during plugin activation. This way, the default message 'Plugin activated' is suppressed.
			unset( $_GET['activate'] ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			throw new Exception( 'The Cyclos plugin was deactivated; the system does not meet the minimum requirements for this plugin.' );
		}

		// Register the uninstall hook for our plugin, which the old version did not have yet.
		// For new installs of the 2.0 version, the activate hook does this. But someone updating the plugin without
		// deactivating/activating, would not have the uninstall hook. So register it now, while we update.
		// Register the uninstall hook.
		register_uninstall_hook( \Cyclos\PLUGIN_FILE, '\\Cyclos\\plugin_uninstall' );

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
