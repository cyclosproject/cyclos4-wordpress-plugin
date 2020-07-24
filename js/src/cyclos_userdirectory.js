/**
 * Internal dependencies
 */
import UserData from './userdirectory/data';
import UserList from './userdirectory/frontend/userlist';

const frontEnd = () => {
	const userLists = document.querySelectorAll( '.cyclos-user-list' );
	const userMaps = document.querySelectorAll( '.cyclos-user-map' );

	// First check if there is at least one userdirectory on the current screen. If not, there is nothing to do.
	if ( userLists.length === 0 && userMaps.length === 0 ) {
		return;
	}

	// Retrieve the data.
	const userData = new UserData();

	// Build the proper user view in each div or show an error if something is wrong.
	if ( userData.error ) {
		userLists.forEach( ( listElement ) => {
			listElement.innerHTML = `${ userData.errorMsg }.`;
		} );
		userMaps.forEach( ( mapElement ) => {
			mapElement.innerHTML = `${ userData.errorMsg }.`;
		} );
	} else {
		userLists.forEach( ( listElement ) => {
			new UserList( listElement, userData );
		} );
		// userMaps.forEach( ( mapElement ) => {
		// 	buildUserMap( mapElement, userData );
		// } );
	}
};

frontEnd();
