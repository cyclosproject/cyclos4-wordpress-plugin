/* global jQuery, cyclosLoginObj */
/**
 * The Login module script.
 */

import { __ } from '@wordpress/i18n';

const invalidDataMessage = __( 'Invalid data received from server', 'cyclos' );
const loginFormSetupMessage = __( 'Something is wrong with the login form setup', 'cyclos' );
const captchaSetupMessage = __( 'Something is wrong with the captcha function', 'cyclos' );

jQuery( document ).ready( function( $ ) {
	// Handle submit on the login form.
	$( '.cyclos-login-form' ).submit( function( event ) {
		event.preventDefault();
		cyclosLoginObj = cyclosLoginObj || {};
		// Find the form this link belongs to. And from there, find the other elements relative to this form.
		const loginForm = this;
		const box = $( loginForm ).parents( '.cyclos-form-box' );
		const notice = $( box ).find( '.notice' );
		const data = { _ajax_nonce: cyclosLoginObj.id, action: 'cyclos_login' };
		data.principal = $( loginForm ).find( 'input[name="principal"]' ).val().trim();
		data.password = $( loginForm ).find( 'input[name="password"]' ).val().trim();
		data.returnTo = $( loginForm ).find( 'input[name="return-to"]' ).val().trim();

		$( notice ).hide();
		$.post( cyclosLoginObj.ajax_url, data )
			.done( function( response ) {
				response = response || {};
				if ( response.redirectUrl ) {
					window.location.href = response.redirectUrl;
				} else {
					$( notice ).html( `${ response.errorMessage || invalidDataMessage }.` ).show();
				}
			} )
			.fail( function() {
				$( notice ).html( `${ loginFormSetupMessage }.` ).show();
			} )
			.always( function() {
				// Remove focus from the submit button.
				$( loginForm ).find( 'input[type="submit"]' ).blur();
			} );
	} );

	// Handle click on the forgot password link.
	$( '.cyclos-forgot-link' ).click( function( event ) {
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
	$( '.cyclos-forgot-cancel' ).click( function( event ) {
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
	$( '.cyclos-newcaptcha' ).click( function( event ) {
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
	$( '.cyclos-forgotpassword-form' ).submit( function( event ) {
		event.preventDefault();
		cyclosLoginObj = cyclosLoginObj || {};
		// Find the form this link belongs to. And from there, find the other elements relative to this form.
		const forgotForm = this;
		const box = $( forgotForm ).parents( '.cyclos-form-box' );
		const loginForm = $( box ).find( '.cyclos-login-form' );
		const notice = $( box ).find( '.notice' );
		const data = { _ajax_nonce: cyclosLoginObj.id, action: 'cyclos_forgot_password' };
		data.principal = $( forgotForm ).find( 'input[name="principal"]' ).val().trim();
		// Only do captcha things when the captcha field is there; it might not be when captcha is disabled in Cyclos.
		if ( $( forgotForm ).find( '.cyclos-captcha' ).length ) {
			data.captcha_id = $( forgotForm ).data( 'captchaID' );
			data.captcha_response = $( forgotForm ).find( 'input[name="captcha"]' ).val().trim();
		}

		$( notice ).hide();
		$.post( cyclosLoginObj.ajax_url, data )
			.done( function( response ) {
				response = response || {};
				if ( response.successMessage ) {
					// Show the success message and show the login form again.
					$( notice ).html( `${ response.successMessage }.` ).show();
					$( forgotForm ).hide();
					$( loginForm ).show();
				} else {
					$( notice ).html( `${ response.errorMessage || invalidDataMessage }.` ).show();
					// Remove focus from the submit button.
					$( forgotForm ).find( 'input[type="submit"]' ).blur();
				}
			} )
			.fail( function() {
				$( notice ).html( `${ loginFormSetupMessage }.` ).show();
				// Remove focus from the submit button.
				$( forgotForm ).find( 'input[type="submit"]' ).blur();
			} );
	} );

	// Generates a new captcha image and puts it on the captcha element of the given form.
	// The ID of the captcha is stored on the form as a jQuery data value.
	// If there is an error, it is shown in the given notice element.
	function newCaptcha( forgotForm, notice ) {
		const captcha = $( forgotForm ).find( '.cyclos-captcha' );
		cyclosLoginObj = cyclosLoginObj || {};
		const data = { _ajax_nonce: cyclosLoginObj.id, action: 'cyclos_captcha' };
		$.post( cyclosLoginObj.ajax_url, data )
			.done( function( response ) {
				response = response || {};
				if ( response.id && response.content ) {
					// Store the captcha ID on the form and show its contents as an image.
					$( forgotForm ).data( 'captchaID', response.id );
					$( captcha ).attr( 'src', 'data:image/png;base64,' + response.content ).show();
				} else {
					$( captcha ).hide();
					$( notice ).html( `${ response.errorMessage || invalidDataMessage }.` ).show();
				}
			} )
			.fail( function() {
				$( captcha ).hide();
				$( notice ).html( `${ captchaSetupMessage }.` ).show();
			} );
	}
} );
