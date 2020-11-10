/* global fetch, cyclosUserObj */
import { getPropByPath } from './utils';

/**
 * UserData class containing current Cyclos user data and metadata.
 */
export class UserData {
	constructor( users, userMeta ) {
		this.users = users;
		this.userMeta = userMeta;
		if ( users && userMeta ) {
			// Only initialize our derived properties when we have user data.
			this.init();
		}
	}

	init() {
		this.generateFieldMap();
		this.generateFilterOptions();
		this.generateSortOptions();
	}

	/**
	 * Creates an array of unique categories from the given list of users.
	 */
	generateFilterOptions() {
		const catFieldId = this.userMeta.mapDirectoryField;
		if ( ! catFieldId ) {
			return null;
		}
		// Create a set of internal names of unique categories.
		const categories = this.users.reduce( ( cats, user ) => {
			if ( user.customValues ) {
				cats.add( user.customValues[ catFieldId ] );
			}
			return cats;
		}, new Set() );

		// Remove a possible undefined category, which may exist when there are users with no category or even no customValues at all.
		categories.delete( undefined );

		// Pull the display names of the possible values of the category field out of the userMeta.
		const catField = this.userMeta.customFields.find(
			( field ) => catFieldId === field.internalName
		);
		const catValues = catField?.possibleValues;
		// Map the cat value to the cat display name.
		const catLabels = new Map();
		catValues.forEach( ( cat ) => {
			catLabels.set( cat.internalName, cat.value );
		} );

		// Create an array of the unique categories, with a value and label each.
		const catList = [ ...categories ].map( ( cat ) => ( {
			value: cat,
			label: catLabels.get( cat ) ?? cat,
		} ) );

		// Sort the categories by their label.
		catList.sort( ( a, b ) => a.label.localeCompare( b.label ) );

		// Add an option to show all users.
		catList.unshift( {
			value: '',
			label: cyclosUserObj.l10n?.noFilterOption,
		} );

		// Store the array of categories.
		this.filterOptions = catList;
	}

	/**
	 * Creates an array of all possible sort options. Includes all custom userfields plus Name, each ascending and descending and an empty Default option.
	 *
	 * For example:
	 * [
	 * 	{ value: '', label: 'Default' },
	 * 	{ value: name-asc, label: 'Name ASC' },
	 * 	{ value: name-desc, label: 'Name DESC' },
	 * 	{ value: customValues.rating-asc, label: 'Rating ASC' },
	 * 	{ value: customValues.rating-desc, label: 'Rating DESC' },
	 * 	{ value: customValues.website-asc, label: 'Website ASC' },
	 * 	{ value: customValues.website-desc, label: 'Website DESC' },
	 * ]
	 */
	generateSortOptions() {
		const val = { asc: '-asc', desc: '-desc' };
		const label = {
			asc: ' ' + cyclosUserObj.l10n?.asc?.toUpperCase(),
			desc: ' ' + cyclosUserObj.l10n?.desc?.toUpperCase(),
		};
		const optList = [];
		this.fields.forEach( ( field, id ) => {
			if ( id === 'image' || id === 'address' ) {
				// The logo and address will not be a sort option, so skip those.
				return;
			}
			optList.push( {
				value: id + val.asc,
				label: field.name + label.asc,
			} );
			optList.push( {
				value: id + val.desc,
				label: field.name + label.desc,
			} );
		} );

		// Add an empty option.
		optList.unshift( {
			value: '',
			label: cyclosUserObj.l10n?.noSortOption,
		} );

		// Store the array of sort options.
		this.sortOptions = optList;
	}

	/**
	 * Generates a map of userfields, mapping their internal name to their field information.
	 * The field information can contain things like display name, field type and extra information like possibleValues.
	 *
	 * For example:
	 * 	name -> { name: 'Name', type: 'text' }
	 * 	email -> { name: 'E-mail', type: 'email' }
	 * 	customValues.rating -> { name: 'Rating', type: 'integer'}
	 * 	customValues.website -> { name: 'Website', type: 'url'}
	 * 	customValues.category -> { name: 'Sector', type: 'singleSelection', possibleValues: [
	 * 		{ value: 'Bakeries', internalName: 'bakeries' },
	 * 		{ value: 'Bike shops', internalName: 'bikes' },
	 * 		{ value: 'Restaurants and pubs', internalName: 'restaurants' },
	 * ] }
	 */
	generateFieldMap() {
		const fields = new Map();

		// Add the basic fields.
		const basicFieldInfo = this.userMeta.basicFields;
		for ( const key in basicFieldInfo ) {
			fields.set( key, basicFieldInfo[ key ] );
		}

		// Add the custom fields.
		const customValue = 'customValues.';
		this.userMeta.customFields.forEach( ( field ) => {
			fields.set( customValue + field.internalName, {
				name: field.name,
				type: field.type,
				possibleValues: field.possibleValues,
			} );
		} );

		// Store the generated map.
		this.fields = fields;
	}

	/**
	 * Aggregate the users so users with multiple addresses are condensed to one user.
	 */
	aggregateUsers() {
		this.users = this.users.reduce( ( aggregatedData, item ) => {
			// Check if the current item belongs to the same user as the previous item we stored.
			const prevUser = aggregatedData[ aggregatedData.length - 1 ] ?? {};
			const prevUserId = prevUser?.id ?? '';
			const curUserId = item?.id ?? '';
			if ( prevUserId && prevUserId === curUserId ) {
				// The current item belongs to the same user as the previous, so add the address of the current item to the previous,
				// instead of pushing the current item itself to the aggregated data.
				( prevUser.addresses = prevUser.addresses || [] ).push(
					item.address
				);
			} else {
				// The current item belongs to another user than the previous, so simply push the current item to the aggregated data.
				aggregatedData.push( item );
			}
			return aggregatedData;
		}, [] );
	}
}

export async function initUsers() {
	// Prepare the URL.
	const url = `${ cyclosUserObj.ajax_url }?_ajax_nonce=${ cyclosUserObj.id }`;

	// First get the user metadata.
	let response = await fetch( `${ url }&action=cyclos_usermetadata` );
	if ( ! response.ok ) {
		throw new Error(
			'Retrieving metadata from ' +
				response.url +
				': ' +
				response.statusText
		);
	}
	let result = await response.json();
	if ( result.error ) {
		throw new Error( 'Retrieving metadata: ' + result.error );
	}
	const userMeta = result.data;

	// Next, get the users data.
	response = await fetch( `${ url }&action=cyclos_userdata` );
	if ( ! response.ok ) {
		throw new Error(
			'Retrieving userdata from ' +
				response.url +
				': ' +
				response.statusText
		);
	}
	result = await response.json();
	if ( result.error ) {
		throw new Error( 'Retrieving userdata: ' + result.error );
	}
	const users = result.data;

	// Now we have both users and metadata, we can prepare a UserData object.
	return new UserData( users, userMeta );
}

/**
 * Filters the sortOptions from userData to return an array of only the sortOptions that should be visible.
 *
 * If the current sort value is not in the given array of option keys, an empty disabled option is added at the top.
 *
 * For example:
 *  [
 * 	  { value: '', label: '', disabled: true },
 * 	  { value: 'name-asc', label: 'Name ASC' },
 * 	  { value: 'name-desc', label: 'Name DESC' },
 * 	  { value: 'customValues.rating-desc', label: 'Rating DESC' },
 *  ]
 *
 * @param { UserData } userData The userData object containing the array of all possible sortOptions.
 * @param { string } initialSort The initial sorting, for example: name-asc.
 * @param { string } visibleSortOptions The visible sort options, for example: name-asc, name-desc, customValues.rating-desc.
 * @return { Array } The array of value/label objects.
 */
export const generateVisibleSortOptions = (
	userData,
	initialSort,
	visibleSortOptions
) => {
	const visibleSortOptionsArray = visibleSortOptions
		.split( ',' )
		.map( ( item ) => item.trim() );

	// Create a list of sort options to show, containing the labels from the sortOptions in userData.
	const sortList = visibleSortOptionsArray.reduce( ( list, option ) => {
		// Find the option in the userData sortOptions (which contains the labels).
		const sortOption = userData.sortOptions.find(
			( item ) => item.value === option
		);
		// If the option is found, add it to the list, with its label (if not, the webmaster specified an non-existing fieldName).
		if ( sortOption ) {
			list.push( {
				value: option,
				label: sortOption.label,
			} );
		}
		return list;
	}, [] );

	// Add an empty disabled value at the top when the webmaster sorts on a field that is not in the visitor sort list.
	if ( visibleSortOptionsArray.indexOf( initialSort ) === -1 ) {
		sortList.unshift( {
			value: initialSort,
			label: cyclosUserObj.l10n?.noSortOption,
			disabled: true,
		} );
	}

	return sortList;
};

/**
 * Returns the users array filtered on the given filter and order by the given sort.
 *
 * @param { UserData } userData The userData object containing the array of users.
 * @param { string } sort The order to sort by. Contains field and direction separated by a dash, for example: name-asc.
 * @param { string } filter The field to filter on.
 * @return { Array } The filtered and sorted array of users.
 */
export const prepareUsersForRender = ( userData, sort, filter ) => {
	// Create a new local array, so the original users array is not affected by our sorting.
	// This way we can always reset the sort to none if the webmaster wants to.
	// Note: arrays are passed by reference in JavaScript.
	let tempUsers = Array.from( userData?.users );
	const catField = userData.userMeta?.mapDirectoryField;
	if ( '' !== filter && '' !== catField ) {
		tempUsers = doFilter( tempUsers, catField, filter, '' );
	}
	if ( '' !== sort ) {
		const [ orderField, orderDirection ] = sort.split( '-' );
		doSort( tempUsers, orderField, orderDirection );
	}
	return tempUsers;
};

/**
 * Filters the given array of users so we only have users in the requested category.
 *
 * @param { Array } users The array of users that should be filtered.
 * @param { string } catField The internal name of the category field to filter by.
 * @param { string } category The category to filter by.
 */
const doFilter = ( users, catField, category ) => {
	// If there is no category to filter on, or no category field, just return the original users.
	if ( '' === category || ! catField ) {
		return users;
	}

	// Return the users, filtered by category.
	return users.filter(
		( user ) => category === user.customValues?.[ catField ]
	);
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
