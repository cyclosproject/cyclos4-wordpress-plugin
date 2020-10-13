<?php
/**
 * The template for displaying the login form.
 *
 * You can override this template in your theme by copying it to {your-theme-directory}/cyclos/login-form.php.
 * If you do, make sure you keep the classnames in tact. The javascript needs these to let the login form work.
 *
 * Available variables:
 * $cyclos_is_forgot_password_enabled indicates whether the functionality to recover a forgotten password is enabled in Cyclos.
 * $cyclos_is_captcha_enabled indicates whether Cyclos requires a captcha to request a forgotten password reset.
 * $cyclos_use_forgot_password_wizard whether we should handle forgotten password requests via a wizard, or via a simple request (for older Cyclos versions).
 * $cyclos_forgot_password_mediums contains an array of the mediums the user can choose to receive the confirmation key or code. Empty when forgot password is disabled.
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

				<?php if ( $cyclos_use_forgot_password_wizard ) : ?>
					<input type='hidden' name='cyclos-wizard-step' value='request'>

					<?php // Show the forgot password wizard, containing three steps. ?>

					<div class='cyclos-wizard-step-request'>

						<?php // 1. Request step: principal, captcha, sendMedium. ?>

						<p><input placeholder="<?php cyclos_loginform_label( 'forgot_principal' ); ?>" name='principal' type='text' autocomplete="username"></p>

						<?php if ( $cyclos_is_captcha_enabled ) : ?>
							<p class="cyclos-line">
								<input placeholder="<?php cyclos_loginform_label( 'forgot_captcha' ); ?>" name='captcha' type='text'>
								<a class="cyclos-newcaptcha" href='#'><?php cyclos_loginform_label( 'forgot_newcaptcha' ); ?></a>
							</p>
							<p><img class="cyclos-captcha" alt='captcha' style='display:none'></p>
						<?php endif; ?>

						<?php if ( count( $cyclos_forgot_password_mediums ) > 1 ) : ?>
							<label><?php cyclos_loginform_label( 'forgot_medium' ); ?>:
								<select name='send-medium'>
									<option value='email'><?php cyclos_loginform_label( 'forgot_email' ); ?></option>
									<option value='sms'><?php cyclos_loginform_label( 'forgot_sms' ); ?></option>
								</select>
							</label>
						<?php else : ?>
							<input name='send-medium' value='<?php echo esc_attr( $cyclos_forgot_password_mediums[0] ); ?>' type='hidden'>
						<?php endif; ?>

					</div>

					<div class='cyclos-wizard-step-code' style='display:none'>

						<?php // 2. Code step: principal (from request step), verification code. ?>

						<p><input placeholder="<?php cyclos_loginform_label( 'forgot_code' ); ?>" name='code' type='text'></p>

					</div>

					<div class='cyclos-wizard-step-change' style='display:none'>

						<?php // 3. Change step: principal (from request step), verification code (from code step), security question, new password + confirmation. ?>

						<p class='cyclos-security-question' style='display:none'>
							<label><span class='cyclos-question'></span>
								<input placeholder="<?php cyclos_loginform_label( 'forgot_security' ); ?>" name='security-answer' type='text'>
							</label>
						</p>

						<p class='cyclos-password-hint' style='display:none'></p>
						<p><input placeholder="<?php cyclos_loginform_label( 'forgot_new_pw' ); ?>" name='new-password' type='password'></p>
						<p><input placeholder="<?php cyclos_loginform_label( 'forgot_confirm_pw' ); ?>" name='confirm-password' type='password'></p>
					</div>

				<?php else : ?>

					<?php // Show the simple forgot password form elements. ?>

					<p><input placeholder="<?php cyclos_loginform_label( 'forgot_principal' ); ?>" name='principal' type='text' autocomplete="username" required></p>
					<?php if ( $cyclos_is_captcha_enabled ) : ?>
						<p class="cyclos-line">
							<input placeholder="<?php cyclos_loginform_label( 'forgot_captcha' ); ?>" name='captcha' type='text' required>
							<a class="cyclos-newcaptcha" href='#'><?php cyclos_loginform_label( 'forgot_newcaptcha' ); ?></a>
						</p>
						<p><img class="cyclos-captcha" alt='captcha' style='display:none'></p>
					<?php endif; ?>

				<?php endif; ?>

				<p><input type='submit' value=<?php cyclos_loginform_label( 'forgot_submit' ); ?>></p>
				<p><a class="cyclos-forgot-cancel" href='#'><?php cyclos_loginform_label( 'forgot_cancel' ); ?></a></p>

			</form>

		<?php endif; ?>

		<form class='cyclos-login-form' action='#' method='post'>
			<input name='return-to' value='<?php echo esc_attr( $cyclos_return_to ); ?>' type='hidden'>
			<p><input placeholder="<?php cyclos_loginform_label( 'principal' ); ?>" name='principal' type='text' autocomplete="username" required></p>
			<p><input placeholder="<?php cyclos_loginform_label( 'password' ); ?>" name='password' type='password' autocomplete="current-password" required></p>
			<p><input type='submit' value="<?php cyclos_loginform_label( 'submit' ); ?>"></p>
			<?php if ( $cyclos_is_forgot_password_enabled ) : ?>
				<p><a class="cyclos-forgot-link" href='#'><?php cyclos_loginform_label( 'forgot_link' ); ?></a></p>
			<?php endif; ?>
		</form>

	<?php endif; ?>
</div>
