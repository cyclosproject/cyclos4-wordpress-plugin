/**
 * The user templates.
 */
import { getPropByPath } from '../utils';

/**
 * Renders a user element on the given container element.
 * The user element contains by default the name and logo of the user and, on click, a modal window with all user fields.
 * Maybe in a future version the webmaster would be able to override the card and onclick handler, to provide a custom template.
 *
 * @param { HTMLElement } userList The list element to render the user on.
 * @param { Object } user The user object to render.
 * @param { Map } fields A Map of fields to show for this user.
 */
export const renderUser = ( userList, user, fields ) => {
	// Render the user card at the end of the userList container.
	userList.insertAdjacentHTML( 'beforeend', card( user ) );

	// Add the trigger to show the detail-info of a user when a visitor clicks on the user card.
	userList.lastElementChild.onclick = () => showInfoWindow( user, fields );
};

/**
 * Returns an HTML string with all fields we should show for this user.
 *
 * @param { Object } user The user object.
 * @param { Map } fields The Map of fields to show.
 * @return { string } The html string with the info of the given user.
 */
export const userDetails = ( user, fields ) => {
	let userInfo = '';
	for ( const [ id, field ] of fields ) {
		// The logo and address field need special treatment.
		if ( 'logo' === field.type ) {
			userInfo += logo( user );
			continue;
		}
		if ( 'address' === field.type ) {
			userInfo += address( user );
			continue;
		}

		// For the other fields their value determines whether we need to show them.
		const value = getPropByPath( user, id );
		if ( ! value ) {
			continue;
		}

		// Call the render method that fits the field type.
		switch ( field.type ) {
			case 'image':
				userInfo += image( id, value );
				break;
			case 'url':
				userInfo += url( id, value );
				break;
			case 'singleSelection':
				userInfo += selection( id, value, field.possibleValues );
				break;
			default:
				userInfo += defaultField( id, value, field.type );
		}
	}
	return userInfo;
};

/**
 * Creates a modal window with all fields we should show for this user.
 *
 * @param { Object } user The user object.
 * @param { Map } fields The Map of fields to show.
 */
const showInfoWindow = ( user, fields ) => {
	const userInfo = userDetails( user, fields );
	Modal.open( userInfo );
};

/**
 * Returns a div with some basic info for the given user.
 *
 * @param { Object } user The user object.
 */
const card = ( user ) => {
	let nameValue = getPropByPath( user, 'name' );
	if ( ! nameValue ) {
		// If there is no 'name' field, the user name may be in the 'display' field.
		nameValue = getPropByPath( user, 'display' );
	}

	// Create the html for the basic info of the user, being name and logo.
	const userName = defaultField( 'name', nameValue, 'text' );
	const userLogo = logo( user );

	// Return the user div with the basic info.
	return `
	<div class="cyclos-user">
		<div class="cyclos-user-item">
			${ userName }
			${ userLogo }
		</div>
	</div>`;
};

const logo = ( user ) => {
	const image = user?.image;
	if ( ! image?.url ) {
		// This user has no logo, so return an empty string.
		return '';
	}
	const width = image.width ?? '';
	const height = image.height ?? '';
	const alt = image.name ?? '';
	return `<img class="cyclos-user-logo" src="${ image.url }" width="${ width }" height="${ height }" alt="${ alt }" />`;
};

const address = ( user ) => {
	const addressVal = user?.address;
	if ( ! addressVal ) {
		// This user has no address, so return an empty string.
		return '';
	}
	const addressLine1 = addressVal.addressLine1
		? `<p>${ addressVal.addressLine1 }</p>`
		: '';
	const zipCityLine =
		addressVal.zip || addressVal.city
			? `<p>${ addressVal.zip ?? '' } ${ addressVal.city ?? '' }</p>`
			: '';
	const country = addressVal.country ? `<p>${ addressVal.country }</p>` : '';
	return `<div class="cyclos-user-address">${ addressLine1 }${ zipCityLine }${ country }</div>`;
};

const image = ( id, value ) => {
	return `<img class="${ id }" src="${ value }" />`;
};

const url = ( id, value ) => {
	return `<div class="${ id } cyclos-user-url"><a href="${ value }">${ value }</a></div>`;
};

const selection = ( id, value, possibleValues ) => {
	// Try to pull the display name of the selected value from the possibleValues array.
	const selectedValue = possibleValues.find(
		( option ) => value === option.internalName
	);
	// Show the selected value, or if we can not find it, fall back to showing the orginal value (i.e. the internalName).
	const valueName = selectedValue?.value ?? value;
	return `<div class="${ id } cyclos-user-selection cyclos-value-${ value }">${ valueName }</div>`;
};

const defaultField = ( id, value, type ) => {
	return `<div class="${ id } cyclos-user-${ type }">${ value }</div>`;
};

class Modal {
	static open( content ) {
		this.create();
		this.modal.querySelector( '.cyclos-modal-content' ).innerHTML = content;
		this.modal.style.display = 'block';
		document.body.style.overflowY = 'hidden'; // Hide the scrollbar on the document behind our modal.
	}

	static close() {
		this.modal.style.display = 'none';
		this.modal.querySelector( '.cyclos-modal-content' ).innerHTML = '';
		document.body.style.overflowY = this.overflow;
	}

	static create() {
		// Only use one modal, even if we have more than one UserList on the screen.
		if ( this.modal ) {
			return;
		}

		// Store the original document overflowY property, so we can return to this when closing the modal.
		this.overflow = document.body.style.overflowY;

		// Add the modal element.
		const modal = document.createElement( 'div' );
		modal.className = 'cyclos-user-info-modal';
		this.modal = document.body.appendChild( modal );

		// Add a close button and a content slot in the modal element.
		const modalContents = `
			<div class="cyclos-modal-header">
				<button class="cyclos-modal-close">&times;</button>
			</div>
			<div class="cyclos-modal-content"></div>
		`;
		this.modal.innerHTML = modalContents;

		// Add the trigger to close and empty the modal whenever the visitor clicks the close button.
		this.modal.querySelector( '.cyclos-modal-close' ).onclick = () =>
			this.close();

		// Add the trigger to close and empty the modal whenever the visitor clicks outside of it.
		window.onclick = ( e ) => {
			if ( e.target === this.modal ) {
				this.close();
			}
		};

		// Add the CSS for the modal.
		const styles = `
		.cyclos-user-info-modal {
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			/* The leaflet pane has a fixed z-index of 400. So we must use a higher z-index on our modal in case it overlaps a map. */
			/* Even worse, the leaflet-top and leaflet-bottom elements even have a z-index 1000. */
			z-index: 1001;
			width: 100%;
			height: 100%;
			overflow: auto;
			background-color: #555; /* Fallback color */
			background-color: rgba(0,0,0,0.4); /* Black with opacity */
		}
		.cyclos-modal-header,
		.cyclos-modal-content {
			width: 600px;
			max-width: 80%;
			background-color: #fff;
			margin: 0 auto;
		}
		.cyclos-modal-header {
			margin-top: 2%;
			padding: 1em;
		}
		.cyclos-modal-content {
			margin-bottom: 2%;
			padding: 2em;
		}
		.cyclos-modal-close {
			line-height: 1;
			color: #aaa;
			font-size: 1.2em;
			background: none;
			padding: 0;
			margin: 0;
			float: right;
		}
		.cyclos-modal-close:hover {
			color: #000;
			background: none;
		}
		.cyclos-user-info {
			display: none;
		}
		.cyclos-user-logo {
			max-width: 250px;
			max-height: 250px;
			width: auto;
			height: auto;
		}
		`;
		const styleSheet = document.createElement( 'style' );
		styleSheet.type = 'text/css';
		styleSheet.innerText = styles;
		document.head.appendChild( styleSheet );
	}
}
