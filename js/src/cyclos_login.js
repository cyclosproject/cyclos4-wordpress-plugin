/* global jQuery, cyclosLoginObj */
/**
 * The Login module script.
 */

jQuery( document ).ready( function ( $ ) {
	cyclosLoginObj = cyclosLoginObj || {};
	const invalidDataMessage = cyclosLoginObj.l10n.invalidDataMessage;
	const loginFormSetupMessage = cyclosLoginObj.l10n.loginFormSetupMessage;
	const captchaSetupMessage = cyclosLoginObj.l10n.captchaSetupMessage;

	// Handle submit on the login form.
	$( '.cyclos-login-form' ).submit( function ( event ) {
		event.preventDefault();
		// Find the form this link belongs to. And from there, find the other elements relative to this form.
		const loginForm = this;
		const box = $( loginForm ).parents( '.cyclos-form-box' );
		const notice = $( box ).find( '.notice' );
		const data = { _ajax_nonce: cyclosLoginObj.id, action: 'cyclos_login' };
		data.principal = $( loginForm )
			.find( 'input[name="principal"]' )
			.val()
			.trim();
		data.password = $( loginForm )
			.find( 'input[name="password"]' )
			.val()
			.trim();
		data.returnTo = $( loginForm )
			.find( 'input[name="return-to"]' )
			.val()
			.trim();

		$( notice ).hide();
		$.post( cyclosLoginObj.ajax_url, data )
			.done( function ( response ) {
				response = response || {};
				if ( response.redirectUrl ) {
					window.location.href = response.redirectUrl;
				} else {
					$( notice )
						.html(
							`${ response.errorMessage || invalidDataMessage }.`
						)
						.show();
				}
			} )
			.fail( function () {
				$( notice ).html( `${ loginFormSetupMessage }.` ).show();
			} )
			.always( function () {
				// Remove focus from the submit button.
				$( loginForm ).find( 'input[type="submit"]' ).blur();
			} );
	} );

	// Handle click on the forgot password link.
	$( '.cyclos-forgot-link' ).click( function ( event ) {
		event.preventDefault();
		// Find the form this link belongs to. And from there, find the other elements relative to this form.
		const loginForm = $( this ).parents( 'form' );
		const box = $( loginForm ).parents( '.cyclos-form-box' );
		const forgotForm = $( box ).find( '.cyclos-forgotpassword-form' );
		const notice = $( box ).find( '.notice' );

		// Hide the loginform and possible error message, and show the forgot password form.
		$( loginForm ).hide();
		$( notice ).hide();
		$( forgotForm ).show();

		// Only do captcha things when the captcha field is there; it might not be when captcha is disabled in Cyclos.
		if ( $( forgotForm ).find( '.cyclos-captcha' ).length ) {
			// Check if the form already has a captcha ID. Only generate a captcha if we don't have one already for this form.
			if ( $( forgotForm ).data( 'captchaID' ) === undefined ) {
				newCaptcha( forgotForm, notice );
			}
		}
	} );

	// Handle click on the forgot password cancel link.
	$( '.cyclos-forgot-cancel' ).click( function ( event ) {
		event.preventDefault();
		// Find the form this link belongs to. And from there, find the other elements relative to this form.
		const forgotForm = $( this ).parents( 'form' );
		const box = $( forgotForm ).parents( '.cyclos-form-box' );
		const loginForm = $( box ).find( '.cyclos-login-form' );
		const notice = $( box ).find( '.notice' );

		// Hide the forgot password form and possible error message, and show the loginform.
		$( forgotForm ).hide();
		$( notice ).hide();
		$( loginForm ).show();
	} );

	// Handle click on the new captcha link.
	$( '.cyclos-newcaptcha' ).click( function ( event ) {
		event.preventDefault();
		// Find the form this link belongs to. And from there, find the other elements relative to this form.
		const forgotForm = $( this ).parents( 'form' );
		const box = $( forgotForm ).parents( '.cyclos-form-box' );
		const notice = $( box ).find( '.notice' );
		const captchaAnswer = $( forgotForm ).find( 'input[name="captcha"]' );

		$( notice ).hide();
		// Generate a new captcha image.
		newCaptcha( forgotForm, notice );

		// Empty the captcha code input field and give it focus.
		// Quirck: Toggle the required html5 attribute to avoid getting an html5 browser error on this field.
		$( captchaAnswer ).attr( 'required', false ).val( '' );
		$( captchaAnswer ).focus().attr( 'required', true );
	} );

	// Handle submit on the forgot password form.
	$( '.cyclos-forgotpassword-form' ).submit( function ( event ) {
		event.preventDefault();

		// Find the form this link belongs to. And from there, find the other elements relative to this form.
		const forgotForm = this;
		const box = $( forgotForm ).parents( '.cyclos-form-box' );
		const loginForm = $( box ).find( '.cyclos-login-form' );
		const notice = $( box ).find( '.notice' );

		// Determine which version of the forgot password we should use.
		if ( $( forgotForm ).find( '.cyclos-wizard-step' ) ) {
			forgotPWRequestWizard( forgotForm, loginForm, notice );
		} else {
			forgotPWRequestSimple( forgotForm, loginForm, notice );
		}
	} );

	// The forgotten password wizard, using newer Cyclos versions (4.13 or newer).
	function forgotPWRequestWizard( forgotForm, loginForm, notice ) {
		cyclosLoginObj = cyclosLoginObj || {};
		const data = {
			_ajax_nonce: cyclosLoginObj.id,
			action: 'cyclos_forgot_password_wizard',
		};
		$( notice ).hide();

		// Find the step we are in and add the relevant information to the data we will post.
		const stepField = $( forgotForm ).find(
			'input[name="cyclos-wizard-step"]'
		);
		const step = stepField?.val();

		// Always pass the step and the principal.
		data.step = step;
		data.principal = $( forgotForm )
			.find( 'input[name="principal"]' )
			?.val()
			.trim();

		// For each step, put the data we need to post in our data object.
		switch ( step ) {
			case 'request':
				// Only do captcha things when the captcha field is there; it might not be when captcha is disabled in Cyclos.
				if ( $( forgotForm ).find( '.cyclos-captcha' ).length ) {
					data.captcha_id = $( forgotForm ).data( 'captchaID' );
					data.captcha_response = $( forgotForm )
						.find( 'input[name="captcha"]' )
						.val()
						.trim();
				}
				data.send_medium = $( forgotForm )
					.find( 'input[name="send-medium"]' )
					?.val();
				break;
			case 'code':
				data.code = $( forgotForm ).find( 'input[name="code"]' )?.val();
				break;
			case 'change':
				data.code = $( forgotForm ).find( 'input[name="code"]' )?.val();
				// Only pass the answer on the security question if it is visible.
				const securityQuestionP = $( forgotForm ).find(
					'.cyclos-security-question'
				);
				if ( securityQuestionP?.is( ':visible' ) ) {
					data.security_answer = $( forgotForm )
						.find( 'input[name="security-answer"]' )
						?.val();
				}
				data.new_password = $( forgotForm )
					.find( 'input[name="new-password"]' )
					?.val();
				data.confirm_password = $( forgotForm )
					.find( 'input[name="confirm-password"]' )
					?.val();
				break;
		}

		// Post the data to WP.
		$.post( cyclosLoginObj.ajax_url, data )
			.done( function ( response ) {
				response = response || {};
				if ( response.errorMessage ) {
					showForgotPWError(
						notice,
						forgotForm,
						response.errorMessage
					);
					return;
				}
				switch ( step ) {
					case 'request':
						// Go to the next step.
						stepField.val( 'code' );
						$( forgotForm )
							.find( '.cyclos-wizard-step-request' )
							?.hide();
						$( forgotForm )
							.find( '.cyclos-wizard-step-code' )
							?.show();
						$( notice )
							.html( `${ response.successMessage }.` )
							.show();
						break;
					case 'code':
						// Go to the next step.
						stepField.val( 'change' );
						$( forgotForm )
							.find( '.cyclos-wizard-step-code' )
							?.hide();
						$( forgotForm )
							.find( '.cyclos-wizard-step-change' )
							?.show();
						if ( response.securityQuestion ) {
							// Show the security question.
							const securityQuestionP = $( forgotForm ).find(
								'.cyclos-security-question'
							);
							securityQuestionP
								.find( '.cyclos-question' )
								.text( `${ response.securityQuestion }` );
							securityQuestionP.show();
						}
						break;
					case 'change':
						// We just completed the last step.
						// Empty the fields in the forgot pw form steps and reset the hidden step field to the first step.
						$( forgotForm ).hide();
						$( forgotForm )
							.find( 'div input[type!="hidden"]' ) // The sendMedium field may be hidden, if so don't empty it.
							.val( '' );
						$( forgotForm ).find( 'div' ).hide();
						$( forgotForm )
							.find( '.cyclos-wizard-step-request' )
							?.show();
						stepField.val( 'request' );
						newCaptcha( forgotForm, notice );
						// Show the success message and show the login form again.
						$( notice )
							.html( `${ response.successMessage }.` )
							.show();
						$( loginForm ).show();
						break;
					default:
						// There can not be another step than the steps we handled above. So this is an error.
						showForgotPWError(
							notice,
							forgotForm,
							loginFormSetupMessage
						);
				}
			} )
			.fail( function () {
				showForgotPWError( notice, forgotForm, loginFormSetupMessage );
			} );
	}

	// The simple request for forgotten passwords, using older Cyclos versions (4.12 or older).
	function forgotPWRequestSimple( forgotForm, loginForm, notice ) {
		cyclosLoginObj = cyclosLoginObj || {};
		const data = {
			_ajax_nonce: cyclosLoginObj.id,
			action: 'cyclos_forgot_password',
		};
		data.principal = $( forgotForm )
			.find( 'input[name="principal"]' )
			.val()
			.trim();
		// Only do captcha things when the captcha field is there; it might not be when captcha is disabled in Cyclos.
		if ( $( forgotForm ).find( '.cyclos-captcha' ).length ) {
			data.captcha_id = $( forgotForm ).data( 'captchaID' );
			data.captcha_response = $( forgotForm )
				.find( 'input[name="captcha"]' )
				.val()
				.trim();
		}

		$( notice ).hide();
		$.post( cyclosLoginObj.ajax_url, data )
			.done( function ( response ) {
				response = response || {};
				if ( response.successMessage ) {
					// Show the success message and show the login form again.
					$( notice ).html( `${ response.successMessage }.` ).show();
					$( forgotForm ).hide();
					$( loginForm ).show();
				} else {
					$( notice )
						.html(
							`${ response.errorMessage || invalidDataMessage }.`
						)
						.show();
					// Remove focus from the submit button.
					$( forgotForm ).find( 'input[type="submit"]' ).blur();
				}
			} )
			.fail( function () {
				$( notice ).html( `${ loginFormSetupMessage }.` ).show();
				// Remove focus from the submit button.
				$( forgotForm ).find( 'input[type="submit"]' ).blur();
			} );
	}

	function showForgotPWError( notice, forgotForm, msg ) {
		$( notice ).html( `${ msg }` ).show();
		// Remove focus from the submit button.
		$( forgotForm ).find( 'input[type="submit"]' ).blur();
	}

	// Generates a new captcha image and puts it on the captcha element of the given form.
	// The ID of the captcha is stored on the form as a jQuery data value.
	// If there is an error, it is shown in the given notice element.
	function newCaptcha( forgotForm, notice ) {
		const captcha = $( forgotForm ).find( '.cyclos-captcha' );
		cyclosLoginObj = cyclosLoginObj || {};
		const data = {
			_ajax_nonce: cyclosLoginObj.id,
			action: 'cyclos_captcha',
		};
		$.post( cyclosLoginObj.ajax_url, data )
			.done( function ( response ) {
				response = response || {};
				if ( response.id && response.content ) {
					// Store the captcha ID on the form and show its contents as an image.
					$( forgotForm ).data( 'captchaID', response.id );
					$( captcha )
						.attr(
							'src',
							'data:image/png;base64,' + response.content
						)
						.show();
				} else {
					$( captcha ).hide();
					$( notice )
						.html(
							`${ response.errorMessage || invalidDataMessage }.`
						)
						.show();
				}
			} )
			.fail( function () {
				$( captcha ).hide();
				$( notice ).html( `${ captchaSetupMessage }.` ).show();
			} );
	}
} );
