/* global jQuery, cyclosLoginObj, grecaptcha */
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

		// Reset and hide the forgot password form and possible error message, and show the loginform.
		resetForgotForm( forgotForm );
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
		const data = { _ajax_nonce: cyclosLoginObj.id };
		let grecaptchav2ID;

		// Find the step we are in and add the relevant information to the data we will post.
		const stepField = $( forgotForm ).find(
			'input[name="cyclos-wizard-step"]'
		);
		let step = stepField?.val();
		if ( step ) {
			data.action = 'cyclos_forgot_password_wizard';
			data.step = step;
		} else {
			// This is the old simple form, without wizard.
			data.action = 'cyclos_forgot_password';
			step = 'simple';
		}

		// Always pass the principal.
		data.principal = fieldVal( forgotForm, 'principal' );

		// For each step, put the data we need to post in our data object.
		switch ( step ) {
			case 'request':
			case 'simple':
				// Only do captcha things when the captcha field is there; it might not be when captcha is disabled in Cyclos.
				if ( $( forgotForm ).find( '.cyclos-captcha' ).length ) {
					data.captcha_id = $( forgotForm ).data( 'captchaID' );
					data.captcha_response = fieldVal( forgotForm, 'captcha' );
				}
				grecaptchav2ID = $( forgotForm ).data( 'grecaptchav2' );
				if ( undefined !== grecaptchav2ID ) {
					// Google recaptchaV2 is used as a captcha provider. Set its response in the data object.
					data.captcha_response =
						grecaptcha.getResponse( grecaptchav2ID );
				}
				// The medium must not be sent in the simple form, so first check if it is there.
				const sendMedium = fieldVal( forgotForm, 'send-medium' );
				if ( sendMedium ) {
					data.send_medium = sendMedium;
				}
				break;
			case 'code':
				data.code = fieldVal( forgotForm, 'code' );
				break;
			case 'change':
				data.code = fieldVal( forgotForm, 'code' );
				// Only pass the answer on the security question if it is visible.
				const securityQuestionP = $( forgotForm ).find(
					'.cyclos-security-question'
				);
				if ( securityQuestionP?.is( ':visible' ) ) {
					data.sec_answer = fieldVal( forgotForm, 'security-answer' );
				}
				data.new_pw = fieldVal( forgotForm, 'new-password' );
				data.confirm_pw = fieldVal( forgotForm, 'confirm-password' );
				break;
		}

		$( notice ).hide();

		// Post the data to WP.
		$.post( cyclosLoginObj.ajax_url, data )
			.done( function ( response ) {
				response = response || {};
				if ( response.errorMessage ) {
					showMsg( notice, response.errorMessage );
					return;
				}
				switch ( step ) {
					case 'request':
						// Go to the next step.
						showNextStep( forgotForm, step, stepField );
						if ( undefined !== grecaptchav2ID ) {
							// Reset the recaptchaV2.
							grecaptcha.reset( grecaptchav2ID );
						}

						// Show where the verification code is sent to.
						showMsg( notice, response.successMessage );
						break;
					case 'code':
						// Go to the next step.
						showNextStep( forgotForm, step, stepField );

						// Show the security question (if enabled).
						if ( response.securityQuestion ) {
							showSecurityQuestion(
								forgotForm,
								response.securityQuestion
							);
						}

						// Show the password hint (if available).
						if ( response.passwordHint ) {
							showPasswordHint(
								forgotForm,
								response.passwordHint
							);
						}
						break;
					case 'change':
					case 'simple':
						// We just completed the last step. Reset the forgot form.
						resetForgotForm( forgotForm );

						// Show the success message, hide the forgot form and show the login form again.
						showMsg( notice, response.successMessage );
						$( forgotForm ).hide();
						$( loginForm ).show();
						break;
					default:
						// There can not be another step than the steps we handled above. So this is an error.
						showMsg( notice, loginFormSetupMessage );
				}
			} )
			.fail( function () {
				showMsg( notice, loginFormSetupMessage );
			} )
			.always( function () {
				// Remove focus from the submit button.
				$( forgotForm ).find( 'input[type="submit"]' ).blur();
			} );
	} );

	function fieldVal( forgotForm, fieldName ) {
		return $( forgotForm )
			.find( 'input[name="' + fieldName + '"]' )
			?.val()
			?.trim();
	}

	function showMsg( notice, msg ) {
		// End the message with a period (.) if it doesn't already.
		if ( '.' !== msg.slice( -1 ) ) {
			msg = `${ msg }.`;
		}
		// Show the message in the notice element.
		$( notice ).html( msg ).show();
	}

	function showNextStep( forgotForm, step, stepField ) {
		// Determine the next step, which is always from: 'request' to 'code' to 'change'.
		const next = 'request' === step ? 'code' : 'change';

		// Set the next step value in the step field.
		stepField?.val( next );

		// Hide the old step and show the new step.
		const oldDiv = '.cyclos-wizard-step-' + step;
		const newDiv = '.cyclos-wizard-step-' + next;
		$( forgotForm ).find( oldDiv )?.hide();
		$( forgotForm ).find( newDiv )?.show();
	}

	function showSecurityQuestion( forgotForm, question ) {
		const securityQuestionP = $( forgotForm ).find(
			'.cyclos-security-question'
		);
		securityQuestionP.find( '.cyclos-question' ).text( question );
		securityQuestionP.show();
	}

	function showPasswordHint( forgotForm, hint ) {
		const passwordHintP = $( forgotForm ).find( '.cyclos-password-hint' );
		passwordHintP.text( hint );
		passwordHintP.show();
	}

	function resetForgotForm( forgotForm ) {
		// Empty the input fields in the forgot pw form.
		$( forgotForm )
			.find( 'input[type!="hidden"]' ) // The sendMedium field may be hidden, if so don't empty it.
			.not( 'input[type="submit"]' ) // Skip the submit button.
			?.val( '' );

		// Reset the captcha field.
		$( forgotForm ).removeData( 'captchaID' );

		// Hide all step divs except the first step div.
		$( forgotForm ).children( 'div' )?.hide();
		$( forgotForm ).children( '.cyclos-wizard-step-request' )?.show();

		// Reset the hidden step field to the first step, if this field exists.
		const stepField = $( forgotForm ).find(
			'input[name="cyclos-wizard-step"]'
		);
		stepField?.val( 'request' );
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

/**
 * Callback for Google's recaptcha.
 * Renders a recaptchaV2 widget on each element that needs it, then stores the widget ID in the parent form.
 */
function onloadCallback() {
	cyclosLoginObj = cyclosLoginObj || {};
	// Render Google's captcha on each recaptchav2 element we have.
	document
		.querySelectorAll( '.cyclos-google-recaptchav2' )
		.forEach( ( el ) => {
			const widgetID = grecaptcha.render( el, {
				sitekey: cyclosLoginObj.sitekey,
				size: 'compact',
			} );
			// Store the widget id in the form data, so we can later determine which response to retrieve (in case there are several login forms on the screen).
			el.closest( 'form' ).dataset.grecaptchav2 = widgetID;
		} );
}
// Make our callback function available in the global window object, otherwise the external Google api JS can not call it.
window.cyclos_grecaptchav2_callback = onloadCallback;
