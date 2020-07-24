/* global cyclosUserObj */
/**
 * UserData class containing current Cyclos user data and metadata.
 */
export default class UserData {
	// Use getters so the data is lazy loaded.
	get users() {
		if ( ! this._users ) {
			this._users = this.fetchUsers();
		}
		return this._users;
	}

	get userMeta() {
		if ( ! this._userMeta ) {
			this._userMeta = this.fetchUserMeta();
		}
		return this._userMeta;
	}

	get filterOptions() {
		if ( ! this._filterOptions ) {
			this._filterOptions = this.generateFilterOptions();
		}
		return this._filterOptions;
	}

	get sortOptions() {
		if ( ! this._sortOptions ) {
			this._sortOptions = this.generateSortOptions();
		}
		return this._sortOptions;
	}

	get fields() {
		if ( ! this._fields ) {
			this._fields = this.generateFieldMap();
		}
		return this._fields;
	}

	fetchUsers() {
		// This would fetch the userdata from WP using the action 'cyclos_userdata'.
		// For now, just return some hardcoded users.
		/* eslint-disable prettier/prettier */
		return [
			{ id: 120, name: 'Piet', customValues: { rating: 3, featured: '1', category: 'bakeries' }, address: { id: '7762070814178009663', name: 'Address 1', addressLine1: 'Boulevard of broken dreams', city: 'Paris', country: 'FR', location: { latitude: 48.856614, longitude: 2.352222 } } },
			{ id: 123, name: 'Tester', customValues: { rating: 3, featured: '1', category: 'bakeries' }, address: { id: '7762070814178009663', name: 'Address 1', addressLine1: 'Boulevard of broken dreams', city: 'Paris', country: 'FR', location: { latitude: 48.856614, longitude: 2.352222 } } },
			{ id: 124, name: 'Other Tester', customValues: { rating: 5, featured: '0', category: 'restaurants' }, address: { id: '7762070814178009919', name: 'Home', addressLine1: 'Diagonal 433', city: 'Barcelona', region: 'Barcelona', country: 'ES', location: { latitude: 41.394194, longitude: 2.151278 } } },
			{ id: 125, name: 'Pierre', customValues: { rating: 5, featured: '1', category: 'bakeries' }, address: { id: '7762070814178009663', name: 'Address 1', addressLine1: 'Boulevard of broken dreams', city: 'Paris', country: 'FR', location: { latitude: 48.856614, longitude: 2.352222 } } },
			{ id: 126, name: 'Mister X', customValues: { rating: 2, featured: '0', category: 'hairdressers' }, address: { id: '7762070814178009919', name: 'Home', addressLine1: 'Diagonal 433', city: 'Barcelona', region: 'Barcelona', country: 'ES', location: { latitude: 41.394194, longitude: 2.151278 } } },
			{ id: 127, name: 'Bonnie Ocean', customValues: { rating: 4, featured: '1', category: 'hairdressers' }, address: { id: '7762070814178009663', name: 'Address 1', addressLine1: 'Boulevard of broken dreams', city: 'Paris', country: 'FR', location: { latitude: 48.856614, longitude: 2.352222 } } },
			{ id: 128, name: 'Amazing Stroopwaffle', customValues: { rating: 4, featured: '1', category: 'bakeries' }, address: { id: '7762070814178009919', name: 'Home', addressLine1: 'Diagonal 433', city: 'Barcelona', region: 'Barcelona', country: 'ES', location: { latitude: 41.394194, longitude: 2.151278 } } },
			{ id: 130, name: 'Weird empty person', address: { id: '7762070814178009919', name: 'Home', addressLine1: 'Diagonal 433', city: 'Barcelona', region: 'Barcelona', country: 'ES', location: { latitude: 41.394194, longitude: 2.151278 } } },
		];
		/* eslint-enable prettier/prettier */
	}

	fetchUserMeta() {
		// This would fetch the userdata from WP using the action 'cyclos_usermetadata'.
		// For now, just return some hardcoded metadata.
		// Basic fields can be only: [ accountNumber, address, email, image, name, phone, username ].
		// Note: there is always only one 'phone' field per user, even when the user has both mobile and landline phones set in Cyclos.
		// Address fields can be: addressLine1, addressLine2, street, buildingNumber, complement, city, country, neighborhood, poBox, region, zip.
		// Image fields can be: id, name, contentType, length, url, width, height.
		return {
			// userFields: [
			// 	{ id: 'name', name: 'Name', type: 'string' },
			// 	{ id: 'image', name: 'Logo', type: 'image' },
			// 	{ id: 'address.addressLine1', name: 'Address Line 1', type: 'string' },
			// 	{ id: 'address.zip', name: 'Zip code', type: 'string' },
			// 	{ id: 'address.city', name: 'City', type: 'string' },
			// 	{ id: 'address.country', name: 'Country', type: 'string' },
			// 	{ id: 'address.latitude', name: 'Latitude', type: 'decimal' },
			// 	{ id: 'address.longitude', name: 'Longitude', type: 'decimal' },
			// 	{ id: 'phone', name: 'Phone', type: 'string' },
			// 	{ id: 'customValues.website', name: 'Website', type: 'url' },
			// 	{ id: 'customValues.category', name: 'Sector', type: 'singleSelection' },
			// 	{ id: 'customValues.description', name: 'Description', type: 'richText' },
			// 	{ id: 'customValues.featured', name: 'Featured', type: 'boolean' },
			// 	{ id: 'customValues.rating', name: 'Rating', type: 'integer' },
			// ],
			customFields: [
				{ id: 'website', name: 'Website', type: 'url' },
				{ id: 'description', name: 'Description', type: 'richText' },
				{ id: 'category', name: 'Sector', type: 'singleSelection' },
				{ id: 'featured', name: 'Prominent in WP', type: 'boolean' },
				{ id: 'rating', name: 'Rating', type: 'integer' },
			],
			defaultMapLocation: {
				latitude: 52.095066,
				longitude: 5.119164,
			},
			defaultMapZoomMobile: 7,
			defaultMapZoomWeb: 7,
		};
	}

	/**
	 * Creates an array of unique categories from the given list of users.
	 */
	generateFilterOptions() {
		// Create a set of internal names of unique categories.
		const catField = cyclosUserObj.fields?.category;
		const categories = this.users.reduce( ( cats, user ) => {
			if ( user.customValues ) {
				cats.add( user.customValues[ catField ] );
			}
			return cats;
		}, new Set() );

		// Create an array of the unique categories, with a value and label each.
		const catList = [ ...categories ].map( ( cat ) => ( {
			value: cat,
			label: cat,
		} ) );

		// Add an option to show all users.
		catList.unshift( {
			value: '',
			label: cyclosUserObj.l10n?.noFilterOption,
		} );

		return catList;
	}

	/**
	 * Creates an array of all possible sort options. Includes all custom userfields plus Name, each ascending and descending and an empty Default option.
	 *
	 * For example:
	 * [
	 * 	{ value: '', label: 'Default' },
	 * 	{ value: name-asc, label: 'Name ASC' },
	 * 	{ value: name-desc, label: 'Name DESC' },
	 * 	{ value: rating-asc, label: 'Rating ASC' },
	 * 	{ value: rating-desc, label: 'Rating DESC' },
	 * 	{ value: website-asc, label: 'Website ASC' },
	 * 	{ value: website-desc, label: 'Website DESC' },
	 * ]
	 */
	generateSortOptions() {
		const val = { asc: '-asc', desc: '-desc' };
		const label = { asc: ' ASC', desc: ' DESC' };
		// If we want arrows instead of ASC/DESC in the labels, we could use:
		// const labels = { asc: ' &#8595;', desc: ' &#8593;' };
		const optList = [];
		this.fields.forEach( ( name, id ) => {
			optList.push( { value: id + val.asc, label: name + label.asc } );
			optList.push( { value: id + val.desc, label: name + label.desc } );
		} );

		// Add an empty option.
		optList.unshift( {
			value: '',
			label: cyclosUserObj.l10n?.noSortOption,
		} );

		return optList;
	}

	/**
	 * Creates a map of userfields, mapping their internal name to their display name.
	 *
	 * For example:
	 * 	name -> Name
	 * 	customValues.rating -> Rating
	 * 	customValues.website -> Website
	 */
	generateFieldMap() {
		const fields = new Map();

		// Add the Name field.
		// Just hardcoded for now, should be retrieved from dynamic data along with other userfields like image and address.
		fields.set( 'name', 'Name' );

		// Add the custom fields.
		const customValue = 'customValues.';
		this.userMeta.customFields.forEach( ( field ) =>
			fields.set( customValue + field.id, field.name )
		);

		return fields;
	}

	/**
	 * Creates an array of value/label objects, given an array of option keys and the current sort value.
	 *
	 * If the current sort value is not in the given array of option keys, an empty disabled option is added at the top.
	 *
	 * For example:
	 *  [
	 * 	  { value: '', label: '', disabled: true },
	 * 	  { value: 'name-asc', label: 'Name ASC' },
	 * 	  { value: 'name-desc', label: 'Name DESC' },
	 * 	  { value: 'rating-desc', label: 'Rating DESC' },
	 *  ]
	 *
	 * @param { string } initialSort The initial sorting, for example: name-asc.
	 * @param { string } visibleSortOptions The visible sort options, for example: name-asc, name-desc, rating-desc.
	 * @return { Array } The array of value/label objects.
	 */
	generateVisibleSortOptions( initialSort, visibleSortOptions ) {
		const visibleSortOptionsArray = visibleSortOptions.split( ',' );

		// Filter the sortOptions so we only show the ones that should be visible.
		const sortList = this.sortOptions.filter( ( option ) =>
			visibleSortOptionsArray.includes( option.value )
		);

		// Add an empty disabled value at the top when the webmaster sorts on a field that is not in the visitor sort list.
		if ( visibleSortOptionsArray.indexOf( initialSort ) === -1 ) {
			sortList.unshift( {
				value: initialSort,
				label: cyclosUserObj.l10n?.noSortOption,
				disabled: true,
			} );
		}

		return sortList;
	}
}
