/* global cyclosUserObj, L */
/**
 * UserMap class representing a frontend usermap.
 */
import { UserData } from '../data';
import { userDetails } from './templates';
import { getPropByPath } from '../utils';

export default class UserMap {
	constructor( container, userData ) {
		// If there are no users, show a message instead of a map.
		if ( userData?.users.length <= 0 ) {
			container.textContent = cyclosUserObj.l10n?.noUsers;
			return;
		}

		this.container = container;
		/** @type { UserData} */
		this.userData = userData;
		this.renderMap();
	}

	/**
	 * Render the map.
	 */
	renderMap() {
		// Initialize the map.
		this.container.style.height = '500px';
		this.container.style.width = '100%';
		const home = { lon: 5.11653, lat: 52.095262 };
		const zoom = 10;
		const map = L.map( this.container ).setView( home, zoom );
		L.tileLayer( 'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
			maxZoom: 19,
			attribution:
				'&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>',
		} ).addTo( map );

		// Add a marker to the map for each user, showing a popup with user details when clicked.
		const maxPopupWidth = this.container.clientWidth - 50;
		const minPopupWidth = this.container.clientWidth / 2;
		const maxPopupHeight = this.container.clientHeight / 2;
		this.userData.users.forEach( ( user ) => {
			const lat = getPropByPath( user, 'address.location.latitude' );
			const lon = getPropByPath( user, 'address.location.longitude' );
			if ( lat && lon ) {
				const userInfo = userDetails( user, this.userData.fields );
				L.marker( { lon, lat } )
					.bindPopup( userInfo, {
						maxHeight: maxPopupHeight,
						maxWidth: maxPopupWidth,
						minWidth: minPopupWidth,
					} )
					.addTo( map );
			}
		} );

		// We are done loading the map, so remove the loader.
		const loader = this.container.querySelector( '.cyclos-loader' );
		if ( loader ) {
			this.container.removeChild( loader );
		}

		// Let the popup update itself after the image it might contain is loaded, because we don't know the dimensions in advance.
		// This corrects the popup dimensions so it does not go outside the map area the first time a new image is loaded.
		this.container.querySelector( '.leaflet-popup-pane' ).addEventListener(
			'load',
			( event ) => {
				const tagName = event.target.tagName;
				const popup = map._popup; // Last open Popup.

				if ( tagName === 'IMG' && popup && ! popup._updated ) {
					popup._updated = true; // Assumes only 1 image per Popup.
					popup.update();
				}
			},
			true // Capture the load event, because it does not bubble.
		);
	}
}
