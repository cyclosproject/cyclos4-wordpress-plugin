/* global cyclosUserObj */
/**
 * UserList class representing a frontend userlist.
 */
import {
	UserData,
	prepareUsersForRender,
	generateVisibleSortOptions,
} from '../data';
import { renderUser, searchElement, filterElement, sortElement } from './templates';

export default class UserList {
	constructor( container, userData ) {
		// If there are no users, show a message instead of a list.
		if ( userData?.users.length <= 0 ) {
			container.textContent = cyclosUserObj.l10n?.noUsers;
			return;
		}

		this.container = container;
		/** @type { UserData} */
		this.userData = userData;
		this.initProps();
		this.initState();
		this.renderList();
		if ( this.props.visibleSortOptions.length > 0 ) {
			this.renderSortElement();
		}
		if ( this.props.showFilter ) {
			this.renderFilterElement();
		}
		if ( this.props.showSearch ) {
			this.renderSearchElement();
		}
	}

	/**
	 * Retrieve the properties from the container dataset.
	 */
	initProps() {
		// Retrieve the properties we need from the container dataset.
		const props = this.container.dataset;
		this.props = {
			initialSort: props.cyclosOrderby ?? '',
			initialFilter: props.cyclosFilter ?? '',
			// The boolean attributes might be put in without a value (indicating true) or with a value "true"/"false".
			// So check if they exist and if so with a value that is not false (so either empty or "true").
			showSearch:
				'cyclosShowSearch' in props &&
				'false' !== props.cyclosShowSearch,
			showFilter:
				'cyclosShowFilter' in props &&
				'false' !== props.cyclosShowFilter,
			visibleSortOptions: props.cyclosSortOptions ?? '',
		};
	}

	/**
	 * Set the state variables to their initial values. State variables are anything that can change in the frontend.
	 */
	initState() {
		this.state = {
			currentSearch: '',
			currentFilter: this.props.initialFilter,
			currentSort: this.props.initialSort,
		};
	}

	/**
	 * Render the user list, using the current sort and filter.
	 */
	renderList() {
		// Make sure we have a list element.
		if ( ! this.userList ) {
			this.userList = document.createElement( 'div' );
			this.userList.className = 'user-list';
			this.container.textContent = '';
			this.container.append( this.userList );
		}

		// Empty the list element, in case this is a re-render.
		this.userList.textContent = '';

		// Get the users we should show.
		const preparedUsers = prepareUsersForRender(
			this.userData,
			this.state.currentSearch,
			this.state.currentSort,
			this.state.currentFilter
		);

		// Add a user element to the list for each user.
		preparedUsers.forEach( ( user ) =>
			renderUser( this.userList, user, this.userData.fields )
		);
	}

	/**
	 * Render the search select and put a change event handler on it.
	 */
	renderSearchElement() {
		// Add a search element to the container.
		const search = searchElement();
		this.container.insertAdjacentHTML( 'afterbegin', search );

		// Add the trigger to search the userlist whenever the visitor leaves the search field.
		this.container.querySelector( '.search input' ).onchange = (
			event
		) => {
			this.handleChangeSearch( event.target.value );
		};

		// Add the trigger to search the userlist whenever the visitor clicks the search button.
		// Note: actually, this is not needed, because the search input onchange above already should have triggered the search.
		this.container.querySelector( '.search button' ).onclick = () => {
			const searchKeywords =
				this.container.querySelector( '.search input' ).value;
			this.handleChangeSearch( searchKeywords );
		};
	}

	/**
	 * Render the filter select and put a change event handler on it.
	 */
	renderFilterElement() {
		// Add a filter element to the container.
		const filter = filterElement(
			this.userData.filterOptions,
			this.state.currentFilter
		);
		// If the filter is empty, don't render anything. This happens when Cyclos has no 'Default filter for map directory' filter field set.
		if ( ! filter ) {
			return;
		}
		this.container.insertAdjacentHTML( 'afterbegin', filter );

		// Add the trigger to filter the userlist whenever the filter option changes.
		this.container.querySelector( '.filter select' ).onchange = (
			event
		) => {
			this.handleChangeFilter( event.target.value );
		};
	}

	/**
	 * Render the sort select and put a change event handler on it.
	 * The visibleSortOptions are for example: name-asc, name-desc, customValues.rating-desc.
	 * This should lead to a select with options: name-asc (Name ASC), name-desc (Name DESC), customValues.rating-desc (Rating).
	 */
	renderSortElement() {
		// Add a sort element to the container.
		const visibleSortOptions = this.props.visibleSortOptions;
		if ( visibleSortOptions.length <= 0 ) {
			return;
		}
		const optionList = generateVisibleSortOptions(
			this.userData,
			this.props.initialSort,
			visibleSortOptions
		);
		this.container.insertAdjacentHTML(
			'afterbegin',
			sortElement( optionList, this.props.initialSort )
		);

		// Add the trigger to sort the userlist whenever the orderby option changes.
		this.container.querySelector( '.orderby select' ).onchange = (
			event
		) => {
			this.handleChangeSort( event.target.value );
			// If there is a disabled initial option, remove it from the select element as soon as the visitor has chosen another option.
			event.target
				.querySelectorAll( 'option[disabled]' )
				.forEach( ( el ) => el.remove() );
		};
	}

	/**
	 * Change event handler for the search.
	 *
	 * @param { string } newSearch The new search value.
	 */
	handleChangeSearch( newSearch ) {
		// Set the currentSearch in our state to the chosen search value.
		this.state.currentSearch = newSearch;
		// Re-build the list of users.
		this.renderList();
	}

	/**
	 * Change event handler for the filter.
	 *
	 * @param { string } newFilter The new filter value.
	 */
	handleChangeFilter( newFilter ) {
		// Set the currentFilter in our state to the chosen filter value.
		this.state.currentFilter = newFilter;
		// Re-build the list of users.
		this.renderList();
	}

	/**
	 * Change event handler for the sort.
	 *
	 * @param { string } newSort The new sort value.
	 */
	handleChangeSort( newSort ) {
		// Set the currentSort in our state to the chosen order value.
		this.state.currentSort = newSort;
		// Re-build the list of users.
		this.renderList();
	}
}
