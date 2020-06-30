/* global cyclosUserObj */

const categories = new Set();

/**
 * Build up the HTML for a dropdown to be used as a category filter.
 */
const buildFilterDropdown = () => {
	if ( categories.size <= 0 ) return '';
	let dropdown = `<select name="${ cyclosUserObj.fields?.category }" class="filter">`;
	dropdown += `<option value="">${ cyclosUserObj.l10n?.noFilterOption }</option>`;
	categories.forEach( ( cat ) => {
		dropdown += `<option value="${ cat }">${ cat }</option>`;
	} );
	dropdown += '</select>';
	return dropdown;
};
/**
 * Add a filter dropdown to a list element, with an onchange event handler to filter the list items whenever the dropdown is changed.
 *
 * @param { HTMLElement } list The list element to add the filter to.
 */
const addFilter = ( list ) => {
	list.insertAdjacentHTML( 'afterbegin', buildFilterDropdown() );
	list.querySelector( '.filter' ).onchange = ( event ) => {
		// Start with showing all items.
		const allItems = 'li';
		list.querySelectorAll( allItems ).forEach( ( el ) => {
			el.style.display = 'block';
		} );
		// If the dropdown is set to show all categories, there is nothing more to do.
		const chosenCategory = event.target.value;
		if ( chosenCategory.length <= 0 ) {
			return;
		}
		// Filter the items by the chosen category: find all items that do not have the chosen category and hide them.
		const redundantItems = `li:not([data-cyclos-category="${ chosenCategory }"])`;
		list.querySelectorAll( redundantItems ).forEach( ( el ) => {
			el.style.display = 'none';
		} );
	};
};

/**
 * Truncate the given input string to 150 characters, putting a hellip (...) in case the string is truncated.
 *
 * Note: we could use lodash _.truncate() as well, but that would introduce a dependency on lodash.
 *
 * @param { string } inputString
 */
const truncate = ( inputString ) => {
	return inputString.length > 150
		? inputString.slice( 0, 145 ) + '&hellip;'
		: inputString;
};

/**
 * Creates a comparator callback function that compares two given users a and b.
 * The callback returns -1 if a should be before b; 1 if b should be before a; 0 otherwise.
 *
 * @param { string } orderBy The property to use for the ordering.
 * @param { string } sortOrder The sort order. Either 'asc' or 'desc'.
 */
const usersComparator = ( orderBy, sortOrder ) => ( a, b ) => {
	// Get the properties to compare for both users, using their path.
	// For example, if the orderBy string is: customValues.rating, the property is in: user['customValues']['rating'].
	// We could have used lodash _.get() function, but this saves another frontend JS include.
	let x = orderBy.split( '.' ).reduce( ( o, i ) => o[ i ], a );
	let y = orderBy.split( '.' ).reduce( ( o, i ) => o[ i ], b );

	// Now, compare the two values.
	let comparison = 0;
	// Check the property type, because this determines the way we should compare the values.
	if ( isNaN( parseInt( x ) ) ) {
		// For string field types, use string comparison. Can also be used for boolean field types, because "true" comes after "false".
		x = x ? x.toLowerCase() : '';
		y = y ? y.toLowerCase() : '';
	} else {
		// For numbers (even if inside quotes) use number comparison.
		x = parseInt( x );
		y = parseInt( y );
	}

	// Put users with an empty orderBy property at the end.
	if ( ! x ) return y ? 1 : 0;
	if ( ! y ) return -1;

	// If both users have the orderBy property, use that to determine their order.
	if ( x < y ) comparison = -1;
	if ( x > y ) comparison = 1;

	// Reverse the order if the requested sortOrder is descending.
	if ( sortOrder === 'desc' ) comparison *= -1;

	// Return the result.
	return comparison;
};

/**
 * Build up the HTML for a user list item.
 *
 * @param { Object } user The user object
 */
const buildListItem = ( user ) => {
	const category = user.customValues[ cyclosUserObj.fields?.category ] ?? '';
	let catData = '';
	if ( category ) {
		categories.add( category ); // Store the category for later use.
		catData = ` data-cyclos-category="${ category }"`; // Create a data attribute with this category, used for filtering.
	}
	const display = user.display ? user.display : '';
	const name = user.name ? user.name : display;
	const logo = user.image;
	const address = user.address;
	const description = user.customValues[ cyclosUserObj.fields?.description ];
	const website = user.customValues[ cyclosUserObj.fields?.website ];
	const phone = user.phone;
	let html = `<li${ catData }>`;
	html += `<h2>${ name }</h2>`;
	html += logo
		? `<img class="logo" src="${ logo.url }" alt="${ logo.name }" width="${ logo.width }" height="${ logo.height }">`
		: '';
	if ( address ) {
		html += address.addressLine1 ? `<p>${ address.addressLine1 }</p>` : '';
		html += `<p>${ address.city } ${ address.country }</p>`;
	}
	html += website ? `<p><a href="${ website }">${ website }</a></p>` : '';
	html += phone ? `<p>${ phone }</p>` : '';
	html += description ? `<div>${ truncate( description ) }</div>` : '';
	html += category ? `<p class="${ category }">${ category }</p>` : '';
	html += '</li>';
	return html;
};

/**
 * Build a list of the given users and put it on the given list element.
 *
 * @param { HTMLElement } listElement The list element we should put the users on.
 * @param { Array } users The array of users.
 */
function buildUserList( listElement, users ) {
	// If there are no users, show a message instead of a list.
	if ( users.length <= 0 ) {
		listElement.innerHTML = cyclosUserObj.l10n?.noUsers;
		return;
	}

	const props = listElement.dataset;

	// Do an initial sort on the user list if the data attribute tells us to.
	const orderBy = props.cyclosOrderby ?? '';
	if ( orderBy ) {
		const sortOrder = 'cyclosOrderDesc' in props ? 'desc' : 'asc';
		users.sort( usersComparator( orderBy, sortOrder ) );
	}

	// Build up the list of users and put it in the list element.
	let userList = '<ul>';
	users.forEach( ( user ) => {
		userList += buildListItem( user );
	} );
	userList += '</ul>';
	listElement.innerHTML = userList;

	// If we are to show a filter, add it to the list element.
	if ( 'cyclosShowFilter' in props ) {
		addFilter( listElement );
	}
}

export default buildUserList;
