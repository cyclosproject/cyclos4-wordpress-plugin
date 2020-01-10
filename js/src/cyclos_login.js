/* global jQuery, cyclosLoginObj */
/**
 * The Login module script.
 */

jQuery( document ).ready( function( $ ) {
	let captchaID = null;

	// Handle submit on the login form.
	$( '.cyclos-login-form' ).submit( function( event ) {
		event.preventDefault();
		cyclosLoginObj = cyclosLoginObj || {};
		const loginForm = this;
		const data = { _ajax_nonce: cyclosLoginObj.id, action: 'cyclos_login' };
		data.principal = $( loginForm ).find( 'input[name="principal"]' ).val().trim();
		data.password = $( loginForm ).find( 'input[name="password"]' ).val().trim();
		data.returnTo = $( loginForm ).find( 'input[name="return-to"]' ).val().trim();
		$( '.cyclos-form-box .notice' ).hide();
		$.post( cyclosLoginObj.ajax_url, data )
			.done( function( response ) {
				response = response || {};
				if ( response.redirectUrl ) {
					window.location.href = response.redirectUrl;
				} else {
					$( '.cyclos-form-box .notice' ).html( `${ response.errorMessage || 'Invalid data received from server' }.` ).show();
				}
			} )
			.fail( function() {
				$( '.cyclos-form-box .notice' ).html( `Something is wrong with the login form setup.` ).show();
			} )
			.always( function() {
				// Remove focus from the submit button.
				$( loginForm ).find( 'input[type="submit"]' ).blur();
			} );
	} );

	// Handle click on the forgot password link.
	$( '#cyclos-forgot-link' ).click( function( event ) {
		event.preventDefault();
		// Hide the loginform and possible error message, and show the forgot password form.
		$( '.cyclos-login-form' ).hide();
		$( '.cyclos-form-box .notice' ).hide();
		$( '.cyclos-forgotpassword-form' ).show();

		// Only do captcha things when the captcha field is there; it might not be when captcha is disabled in Cyclos.
		if ( $( '.cyclos-forgotpassword-form #cyclos-captcha' ).length ) {
			// If we don't have a captcha yet, make one.
			if ( captchaID === null ) {
				newCaptcha();
			}
		}
	} );

	// Handle click on the forgot password cancel link.
	$( '#cyclos-forgot-cancel' ).click( function( event ) {
		event.preventDefault();
		// Hide the forgot password form and possible error message, and show the loginform.
		$( '.cyclos-forgotpassword-form' ).hide();
		$( '.cyclos-form-box .notice' ).hide();
		$( '.cyclos-login-form' ).show();
	} );

	// Handle click on the new captcha link.
	$( '#cyclos-newcaptcha' ).click( function( event ) {
		event.preventDefault();
		// Generate a new captcha image.
		newCaptcha();
		// Empty the captcha code input field and give it focus.
		// Toggle the required html5 attribute to avoid getting an html5 browser error on this field.
		$( '.cyclos-forgotpassword-form input[name="captcha"]' ).attr( 'required', false ).val( '' );
		$( '.cyclos-forgotpassword-form input[name="captcha"]' ).focus().attr( 'required', true );
	} );

	// Handle submit on the forgot password form.
	$( '.cyclos-forgotpassword-form' ).submit( function( event ) {
		event.preventDefault();
		cyclosLoginObj = cyclosLoginObj || {};
		const forgotpasswordForm = this;
		const data = { _ajax_nonce: cyclosLoginObj.id, action: 'cyclos_forgot_password' };
		data.principal = $( forgotpasswordForm ).find( 'input[name="principal"]' ).val().trim();
		data.captcha_id = captchaID;
		data.captcha_response = $( forgotpasswordForm ).find( 'input[name="captcha"]' ).val().trim();
		$( '.cyclos-form-box .notice' ).hide();
		$.post( cyclosLoginObj.ajax_url, data )
			.done( function( response ) {
				response = response || {};
				if ( response.successMessage ) {
					// Show the success message and show the login form again.
					$( '.cyclos-form-box .notice' ).html( `${ response.successMessage }.` ).show();
					$( forgotpasswordForm ).hide();
					$( '.cyclos-login-form' ).show();
				} else {
					$( '.cyclos-form-box .notice' ).html( `${ response.errorMessage || 'Invalid data received from server' }.` ).show();
					// Remove focus from the submit button.
					$( forgotpasswordForm ).find( 'input[type="submit"]' ).blur();
				}
			} )
			.fail( function() {
				$( '.cyclos-form-box .notice' ).html( `Something is wrong with the login form setup.` ).show();
				// Remove focus from the submit button.
				$( forgotpasswordForm ).find( 'input[type="submit"]' ).blur();
			} );
	} );

	// Generate a new captcha image.
	function newCaptcha() {
		cyclosLoginObj = cyclosLoginObj || {};
		const data = { _ajax_nonce: cyclosLoginObj.id, action: 'cyclos_captcha' };
		$.post( cyclosLoginObj.ajax_url, data )
			.done( function( response ) {
				response = response || {};
				if ( response.id && response.content ) {
					// Store the captcha ID and show its contents as an image.
					captchaID = response.id;
					$( '.cyclos-forgotpassword-form #cyclos-captcha' ).attr( 'src', 'data:image/png;base64,' + response.content ).show();
				} else {
					$( '.cyclos-forgotpassword-form #cyclos-captcha' ).hide();
					$( '.cyclos-form-box .notice' ).html( `${ response.errorMessage || 'Invalid data received from server' }.` ).show();
				}
			} )
			.fail( function() {
				$( '.cyclos-forgotpassword-form #cyclos-captcha' ).hide();
				$( '.cyclos-form-box .notice' ).html( `Something is wrong with the captcha function.` ).show();
			} );
	}
} );
