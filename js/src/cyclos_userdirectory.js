/* global jQuery, cyclosUserObj */
import buildUserList from './cyclos_userlist';

jQuery( document ).ready( function( $ ) {
	const userLists = document.querySelectorAll( '.cyclos-user-list' );
	const userMaps = document.querySelectorAll( '.cyclos-user-map' );

	// First check if there is at least one userdirectory on the current screen. If not, there is nothing to do.
	if ( userLists.length === 0 && userMaps.length === 0 ) {
		return;
	}

	// Get the userdata. When it's ready, call the function to build up the HTML.
	cyclosUserObj = cyclosUserObj || {};
	const invalidDataMessage = cyclosUserObj.l10n.invalidDataMessage;
	const setupMessage = cyclosUserObj.l10n.setupMessage;
	const data = { _ajax_nonce: cyclosUserObj.id, action: 'cyclos_userdata' };
	$.post( cyclosUserObj.ajax_url, data )
		.done( ( response ) => {
			response = response || {};
			if ( response.userData && Array.isArray( response.userData ) ) {
				buildHTML( response.userData );
			} else {
				showErrorMsg(
					`${ response.errorMessage || invalidDataMessage }.`
				);
			}
		} )
		.fail( () => {
			showErrorMsg( `${ setupMessage }.` );
		} );

	// The function to build the HTML for each view, given the userdata we found in the response.
	const buildHTML = ( userData ) => {
		userLists.forEach( ( listElement ) => {
			buildUserList( listElement, userData );
		} );
		// userMaps.forEach( ( mapElement ) => {
		// 	buildUserMap( mapElement, userData );
		// } );
	};

	// The function to show error information, if the userdata could not be retrieved.
	const showErrorMsg = ( errorMsg ) => {
		userLists.forEach( ( listElement ) => {
			listElement.innerHTML = `${ errorMsg || invalidDataMessage }.`;
		} );
		userMaps.forEach( ( mapElement ) => {
			mapElement.innerHTML = `${ errorMsg || invalidDataMessage }.`;
		} );
	};
} );
