/**
 * Internal dependencies
 */
import { initUsers } from './userdirectory/data';
import UserList from './userdirectory/frontend/userlist';

const frontEnd = () => {
	const userLists = document.querySelectorAll( '.cyclos-user-list' );
	const userMaps = document.querySelectorAll( '.cyclos-user-map' );

	// First check if there is at least one userdirectory on the current screen. If not, there is nothing to do.
	if ( userLists.length === 0 && userMaps.length === 0 ) {
		return;
	}

	// Retrieve the data.
	initUsers()
		// Build the proper user view in each div or show an error if something is wrong.
		.then( ( userData ) => {
			userLists.forEach(
				( listElement ) => new UserList( listElement, userData )
			);
			// Analogous code for when we start implementing the user map functionality.
			// userMaps.forEach(
			// 	( mapElement ) => new UserMap( mapElement, userData )
			// );
		} )
		.catch( ( err ) => {
			const errorMsg =
				'There was an error retrieving the userdata from the server. Please ask your website administrator if this problem persists.';
			// eslint-disable-next-line no-console
			console.log( err );
			userLists.forEach( ( listEl ) => ( listEl.innerHTML = errorMsg ) );
			userMaps.forEach( ( mapEl ) => ( mapEl.innerHTML = errorMsg ) );
		} );
};

frontEnd();
