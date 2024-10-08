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
 * @param { Object } obj  The object to get the property from.
 * @param { string } path The property path, containing the property names divided by a dot.
 */
export const getPropByPath = ( obj, path ) => {
	let intermediate = '';
	return path.split( '.' ).reduce( ( result, cur ) => {
		( { [ cur ]: intermediate } = result || {} );
		return intermediate;
	}, obj );
};

/**
 * Get multiple properties of an object as a concatenated output string, using paths dividing the property names with a dot, each path separated with a '+'.
 *
 * For example, if the paths string is: 'address.zip + address.city', the string returned is obj['address']['zip'] - obj['address']['city'].
 *
 * @param { Object } obj   The object to get the properties from.
 * @param { string } paths The property paths seperated with a '+' sign.
 * @param { string } sep   (Optional) The separator to use in the output string. Defaults to ' - '.
 */
export const getPropsByPath = ( obj, paths, sep = ' - ' ) => {
	if ( ! paths || paths.length <= 0 ) {
		return '';
	}
	const output = paths.split( ' + ' ).reduce( ( result, path ) => {
		const prop = getPropByPath( obj, path );
		return result + ( prop ? sep + prop : '' );
	}, '' );
	return output;
};
