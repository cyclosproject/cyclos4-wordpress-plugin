/* global cyclosUserObj */
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
			// Analogous code for when we start implementing the user map functionality.
			// userMaps.forEach(
			// 	( mapElement ) => new UserMap( mapElement, userData )
			// );

			// Before passing the userData to userLists, aggregate users with more than one address to be seen as one user.
			userData.aggregateUsers();
			userLists.forEach(
				( listElement ) => new UserList( listElement, userData )
			);
		} )
		.catch( () => {
			const errMsg = cyclosUserObj.l10n?.setupMessage;
			userLists.forEach( ( listEl ) => ( listEl.textContent = errMsg ) );
			userMaps.forEach( ( mapEl ) => ( mapEl.textContent = errMsg ) );
		} );
};

frontEnd();
