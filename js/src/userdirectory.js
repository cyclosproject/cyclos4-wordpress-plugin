/* global cyclosUserObj */
/**
 * Internal dependencies
 */
import { initUsers, aggregateUsers, UserData } from './userdirectory/data';
import UserList from './userdirectory/frontend/userlist';
import UserMap from './userdirectory/frontend/usermap';

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
			// For each map div on the page, show a user map.
			userMaps.forEach(
				( mapElement ) => new UserMap( mapElement, userData )
			);

			// Before passing the userData to userLists, aggregate users with more than one address to be seen as one user.
			if ( userLists.length > 0 ) {
				// To avoid changing the data used in UserMap, we make a new UserData object.
				const aggrData = new UserData(
					aggregateUsers( userData.users ),
					userData.userMeta
				);
				// For each list div on the page, show a user list.
				userLists.forEach(
					( listElement ) => new UserList( listElement, aggrData )
				);
			}
		} )
		.catch( () => {
			const errMsg = cyclosUserObj.l10n?.setupMessage;
			userLists.forEach( ( listEl ) => ( listEl.textContent = errMsg ) );
			userMaps.forEach( ( mapEl ) => ( mapEl.textContent = errMsg ) );
		} );
};

frontEnd();
