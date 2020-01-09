<?php
/**
 * The template for displaying the login form.
 *
 * You can override this template in your theme by copying it to {your-theme-directory}/cyclos/login-form.php.
 *
 * Available variables:
 * $cyclos_is_forgot_password_enabled indicates whether the functionality to recover a forgotten password is enabled in Cyclos.
 * $cyclos_is_captcha_enabled indicates whether Cyclos requires a captcha to request a forgotten password reset.
 * $cyclos_return_to contains the Cyclos request parameter indicating where to go within Cyclos after logging in. Can be empty.
 * $cyclos_error contains the error message in case of a severe error with the Cyclos server, for example if the server can not be reached. Can be empty.
 *
 * @package Cyclos
 */

?>
<div class='cyclos-form-box'>

	<div class="notice"><?php echo isset( $cyclos_error ) ? esc_html( $cyclos_error ) : ''; ?></div>

	<?php if ( empty( $cyclos_error ) ) : ?>

		<?php if ( $cyclos_is_forgot_password_enabled ) : ?>
			<form class='cyclos-forgotpassword-form' action='#' method='post' style='display:none'>
				<p><input placeholder="<?php cyclos_loginform_label( 'forgot_principal' ); ?>" name='principal' type='text' required></p>
				<?php if ( $cyclos_is_captcha_enabled ) : ?>
					<p class="cyclos-line">
						<input placeholder="<?php cyclos_loginform_label( 'forgot_captcha' ); ?>" name='captcha' type='text' required>
						<a id="cyclos-newcaptcha" href='#'><?php cyclos_loginform_label( 'forgot_newcaptcha' ); ?></a>
					</p>
					<p><img id="cyclos-captcha" alt='captcha' style='display:none'></p>
				<?php endif; ?>
				<p><input type='submit' value=<?php cyclos_loginform_label( 'forgot_submit' ); ?>></p>
				<p><a id="cyclos-forgot-cancel" href='#'><?php cyclos_loginform_label( 'forgot_cancel' ); ?></a></p>
			</form>
		<?php endif; ?>

		<form class='cyclos-login-form' action='#' method='post'>
			<input name='return-to' value='<?php echo esc_attr( $cyclos_return_to ); ?>' type='hidden'>
			<p><input placeholder="<?php cyclos_loginform_label( 'principal' ); ?>" name='principal' type='text' required></p>
			<p><input placeholder="<?php cyclos_loginform_label( 'password' ); ?>" name='password' type='password' required></p>
			<p><input type='submit' value="<?php cyclos_loginform_label( 'submit' ); ?>"></p>
			<?php if ( $cyclos_is_forgot_password_enabled ) : ?>
				<p><a id="cyclos-forgot-link" href='#'><?php cyclos_loginform_label( 'forgot_link' ); ?></a></p>
			<?php endif; ?>
		</form>

	<?php endif; ?>
</div>
