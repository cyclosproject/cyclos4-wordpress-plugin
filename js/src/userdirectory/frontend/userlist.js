/* global cyclosUserObj */
/**
 * UserList class representing a frontend userlist.
 */
import {
	UserData,
	prepareUsersForRender,
	generateVisibleSortOptions,
} from '../data';
import { renderUser } from './templates';

export default class UserList {
	constructor( container, userData ) {
		// If there are no users, show a message instead of a list.
		if ( userData?.users.length <= 0 ) {
			container.innerHTML = cyclosUserObj.l10n?.noUsers;
			return;
		}

		this.container = container;
		/** @type { UserData} */
		this.userData = userData;
		this.initProps();
		this.initState();
		this.renderList();
		if ( this.props.showFilter ) {
			this.renderFilterElement();
		}
		if ( this.props.showSort && this.props.visibleSortOptions.length > 0 ) {
			this.renderSortElement();
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
			showFilter:
				'cyclosShowFilter' in props &&
				'false' !== props.cyclosShowFilter,
			showSort:
				'cyclosShowOrderby' in props &&
				'false' !== props.cyclosShowOrderby,
			visibleSortOptions: props.cyclosSortOptions ?? '',
		};
	}

	/**
	 * Set the state variables to their initial values. State variables are anything that can change in the frontend.
	 */
	initState() {
		this.state = {
			currentFilter: this.props.initialFilter,
			currentSort: this.props.initialSort,
		};
	}

	/**
	 * Render the user list, using the current sort and filter.
	 */
	renderList() {
		// Make sure we have a list element.
		let userList = this.container.querySelector( '.user-list' );
		if ( ! userList ) {
			this.container.innerHTML = '<div class="user-list"></div>';
			userList = this.container.querySelector( '.user-list' );
		}

		// Empty the list element, in case this is a re-render.
		userList.innerHTML = '';

		// Get the users we should show.
		const preparedUsers = prepareUsersForRender(
			this.userData,
			this.state.currentSort,
			this.state.currentFilter
		);

		// Add a user element to the list for each user.
		preparedUsers.forEach( ( user ) =>
			renderUser( userList, user, this.userData.fields )
		);
	}

	/**
	 * Render the filter select and put a change event handler on it.
	 */
	renderFilterElement() {
		// Add a filter element to the container.
		const filter = this.filterElement();
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
	 */
	renderSortElement() {
		// Add a sort element to the container.
		this.container.insertAdjacentHTML( 'afterbegin', this.sortElement() );

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

	/**
	 * Build up the HTML for a dropdown the visitor can use to filter the list.
	 */
	filterElement() {
		const catList = this.userData.filterOptions;
		if ( catList.length <= 0 ) {
			return '';
		}
		const currentFilter = this.state.currentFilter;
		let dropdown = '<div class="filter">';
		dropdown += `<label>${ cyclosUserObj.l10n?.filterLabel }:</label>`;
		dropdown += '<select>';
		catList.forEach( ( { value, label } ) => {
			const selected = currentFilter === value ? ' selected' : '';
			dropdown += `<option value="${ value }"${ selected }>${ label }</option>`;
		} );
		dropdown += '</select>';
		dropdown += '</div>';
		return dropdown;
	}

	/**
	 * Build up the HTML for a dropdown the visitor can use to sort the list.
	 *
	 * The visibleSortOptions are for example: name-asc, name-desc, rating-desc.
	 * This should lead to a select with options: name-asc (Name ASC), name-desc (Name DESC), rating-desc (Rating).
	 * The initial sort property (for example name-asc) is used to make the corresponding option selected initially.
	 */
	sortElement() {
		const visibleSortOptions = this.props.visibleSortOptions;
		if ( visibleSortOptions.length <= 0 ) {
			return '';
		}
		let dropdown = '<div class="orderby">';
		dropdown += `<label>${ cyclosUserObj.l10n?.sortLabel }:</label>`;
		dropdown += `<select>`;
		const optionList = generateVisibleSortOptions(
			this.userData,
			this.props.initialSort,
			visibleSortOptions
		);
		optionList.forEach( ( { value, label, disabled } ) => {
			const selectedAttr =
				this.props.initialSort === value ? ' selected' : '';
			const disabledAttr = disabled ? ' disabled' : '';
			dropdown += `<option value="${ value }"${ selectedAttr }${ disabledAttr }>${ label }</option>`;
		} );
		dropdown += '</select>';
		dropdown += '</div>';
		return dropdown;
	}
}
