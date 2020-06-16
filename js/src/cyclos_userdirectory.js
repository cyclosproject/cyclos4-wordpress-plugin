import buildUserList from './cyclos_userlist';

// Build the userlist.
document.querySelectorAll( '.cyclos-user-list' ).forEach( ( list ) => {
	buildUserList( list );
} );
