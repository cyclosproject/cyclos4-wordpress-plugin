/**
 * ListView class representing a frontend userlist.
 */
import { renderUser } from './templates';
import View from './view';

export default class ListView extends View {
	/**
	 * Initialize the user list.
	 */
	initializeView() {
		// Make sure we have a list element.
		if ( ! this.userList ) {
			this.userList = document.createElement( 'div' );
			this.userList.className = 'user-list';
			this.container.textContent = '';
			this.container.append( this.userList );
		}
	}

	/**
	 * Empty the view, before rendering the users again.
	 */
	emptyView() {
		// Empty the list element, in case this is a re-render.
		this.userList.textContent = '';
	}

	/**
	 * Render one of the users.
	 * @param { Object } user The user object to render.
	 */
	renderUser( user ) {
		renderUser( this.userList, user, this.userData.fields );
	}
}
