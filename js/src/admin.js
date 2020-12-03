/* global jQuery, ajaxurl */
/**
 * The script to handle our admin settings screen.
 */

jQuery( document ).ready( function ( $ ) {
	// Show the settings sections in tabs.

	/**
	 * Activates the section belonging to the tab passed as a parameter.
	 * To find the section elements that belong to a tab, the header text should contain the tab text.
	 * For example, for a header 'General Settings', the tab text could be 'General'.
	 *
	 * @param {Object} tab The active tab.
	 */
	function activateSection( tab ) {
		const section = tab.text();
		$( '.wrap' )
			.find( "h2:contains('" + section + "')" )
			.each( function () {
				$( this ).addClass( 'active' );
				$( this )
					.nextUntil( 'h2', ':not(.submit)' )
					.addClass( 'active' );
			} );
	}

	// Make the first tab active initially.
	const firstTab = $( '.nav-tab-wrapper a' ).first();
	firstTab.addClass( 'nav-tab-active' );
	activateSection( firstTab );

	// Handle click on a tab to activate the corresponding section.
	$( '.nav-tab-wrapper a' ).click( function () {
		// Remove the active class from all tab links and all section elements.
		$( '.nav-tab-wrapper a' ).removeClass( 'nav-tab-active' );
		$( '.wrap h2, .wrap .intro, .wrap .form-table' ).removeClass(
			'active'
		);

		// Add the active class to the clicked tab link and the section elements that belong to it.
		$( this ).addClass( 'nav-tab-active' );
		activateSection( $( this ) );
	} );

	// Handle click on the user data refresh button on the User Directory tab.
	$( '#cyclos-user-data-refresh' ).click( function () {
		$( '.cyclos-user-data-info' ).html(
			'<span class="dashicons dashicons-update"></span>'
		);
		// Make an AJAX call to the UserDirectory component to refresh the Cyclos user data.
		// Note: since we are in the admin, we can use the global WP variable ajaxurl.
		const data = {
			_ajax_nonce: $( this ).parents( 'form' ).find( '#_wpnonce' ).val(),
			action: 'cyclos_refresh_user_data',
		};
		$.post( ajaxurl, data )
			.done( function ( response ) {
				// Show the result.
				response = response || '';
				$( '.cyclos-user-data-info' ).html( response );
			} )
			.fail( function () {
				// Something went wrong, show an error message.
				$( '.cyclos-user-data-info' ).html( 'Something went wrong.' );
			} );
	} );
} );
