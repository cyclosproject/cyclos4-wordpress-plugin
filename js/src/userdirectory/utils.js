/* global cyclosUserObj */
/**
 * Utils for the Cyclos userdirectory.
 */

export const listItems = ( users, category ) => {
	const catField = cyclosUserObj.fields?.category;
	if ( '' !== category ) {
		// Filter the users so we only have users in the requested category.
		users = users.filter(
			( user ) =>
				category ===
				( user.customValues ? user.customValues[ catField ] : '' )
		);
	}
	const list = users.reduce(
		( userList, user ) =>
			( userList += `<div>${ user.name } ${
				user.customValues ? user.customValues[ catField ] : ''
			}</div>` ),
		''
	);
	return list;
};

/**
 * Truncate the given input string to 150 characters, putting a hellip (...) in case the string is truncated.
 *
 * Note: we could use lodash _.truncate() as well, but that would introduce a dependency on lodash.
 *
 * @param { string } inputString
 */
export const truncate = ( inputString ) => {
	return inputString.length > 150
		? inputString.slice( 0, 145 ) + '&hellip;'
		: inputString;
};

/**
 * Sorts the given array of users.
 *
 * @param { Array } users The array of users that should be sorted.
 * @param { string } orderBy The field to sort by.
 * @param { string } sortOrder The direction to sort by. Either 'asc' or 'desc'.
 */
export const doSort = ( users, orderBy, sortOrder ) => {
	if ( orderBy.length > 0 ) {
		users.sort( usersComparator( orderBy, sortOrder ) );
	}
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
	let x = getPropByPath( a, orderBy );
	let y = getPropByPath( b, orderBy );

	// Now, compare the two values.
	let comparison = 0;
	// Check the property type, because this determines the way we should compare the values. Otherwise "12" would be seen as lower than "3".
	if ( isNaN( parseInt( x, 10 ) ) ) {
		x = x ? x.toLowerCase() : '';
	} else {
		x = parseInt( x, 10 );
	}
	if ( isNaN( parseInt( y, 10 ) ) ) {
		y = y ? y.toLowerCase() : '';
	} else {
		y = parseInt( y, 10 );
	}

	// Put users with an empty orderBy property at the end.
	if ( '' === x ) {
		return y ? 1 : 0;
	}
	if ( '' === y ) {
		return -1;
	}

	// If both users have the orderBy property, use that to determine their order.
	if ( x < y ) {
		comparison = -1;
	}
	if ( x > y ) {
		comparison = 1;
	}

	// Reverse the order if the requested sortOrder is descending.
	if ( sortOrder === 'desc' ) {
		comparison *= -1;
	}

	// Return the result.
	return comparison;
};

/**
 * Get the property of an object, using a path dividing the property names with a dot.
 *
 * For example, if the path string is: customValues.rating, the property returned is: obj['customValues']['rating'].
 * We could have used lodash _.get() function, but this saves another frontend JS include.
 *
 * @param { Object } obj The object to get the property from.
 * @param { string } path The property path, containing the property names divided by a dot.
 */
const getPropByPath = ( obj, path ) => {
	let intermediate = '';
	return path.split( '.' ).reduce( ( result, cur ) => {
		( { [ cur ]: intermediate } = result || {} );
		return intermediate;
	}, obj );
};
