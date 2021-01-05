/**
 * Utils for the Cyclos userdirectory.
 */

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
 * Get the property of an object, using a path dividing the property names with a dot.
 *
 * For example, if the path string is: customValues.rating, the property returned is: obj['customValues']['rating'].
 * We could have used lodash _.get() function, but this saves another frontend JS include.
 *
 * @param { Object } obj The object to get the property from.
 * @param { string } path The property path, containing the property names divided by a dot.
 */
export const getPropByPath = ( obj, path ) => {
	let intermediate = '';
	return path.split( '.' ).reduce( ( result, cur ) => {
		( { [ cur ]: intermediate } = result || {} );
		return intermediate;
	}, obj );
};
