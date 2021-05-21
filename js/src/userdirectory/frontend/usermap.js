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
		this.initProps();
		this.renderMap();
	}

	/**
	 * Initialize the properties needed for the map.
	 */
	initProps() {
		// Use default fallback values from Cyclos if available.
		const cyclosSettings = this.userData.userMeta;
		const defaults = {};
		defaults.lon = cyclosSettings.defaultMapLocation?.longitude ?? 0;
		defaults.lat = cyclosSettings.defaultMapLocation?.latitude ?? 0;
		defaults.zoom = cyclosSettings.defaultMapZoomWeb ?? 1;

		// Retrieve the properties we need from the container dataset.
		const props = this.container.dataset;
		this.props = {
			width: props.cyclosWidth ?? '100%',
			height: props.cyclosHeight ?? '500px',
			// The boolean attributes might be put in without a value (indicating true) or with a value "true"/"false".
			// So check if they exist and if so with a value that is not false (so either empty or "true").
			fit: 'cyclosFitUsers' in props && 'false' !== props.cyclosFitUsers,
			lon: props.cyclosLon ?? defaults.lon,
			lat: props.cyclosLat ?? defaults.lat,
			zoom: props.cyclosZoom ?? defaults.zoom,
			maxZoom: props.cyclosMaxZoom ?? 19,
		};

		// Add the tile provider and its copyright string. Maybe we will add other providers in the future, but for now just use openstreetmap.
		this.props.tilesURLTemplate =
			'https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png';
		this.props.copyright =
			'&copy; <a href="https://openstreetmap.org/copyright">OpenStreetMap contributors</a>';
	}

	/**
	 * Render the map.
	 */
	renderMap() {
		// Initialize the map.
		this.container.style.width = this.props.width;
		this.container.style.height = this.props.height;
		const map = L.map( this.container );
		L.tileLayer( this.props.tilesURLTemplate, {
			maxZoom: this.props.maxZoom,
			attribution: this.props.copyright,
		} ).addTo( map );

		// Add a marker to the map for each user, showing a popup with user details when clicked.
		const maxPopupWidth = this.container.clientWidth - 50;
		const minPopupWidth = this.container.clientWidth / 2;
		const maxPopupHeight = this.container.clientHeight / 2;
		const markers = [];
		this.userData.users.forEach( ( user ) => {
			const lat = getPropByPath( user, 'address.location.latitude' );
			const lon = getPropByPath( user, 'address.location.longitude' );
			if ( lat && lon ) {
				const userInfo = userDetails( user, this.userData.fields );
				markers.push(
					L.marker( { lon, lat } ).bindPopup( userInfo, {
						maxHeight: maxPopupHeight,
						maxWidth: maxPopupWidth,
						minWidth: minPopupWidth,
					} )
				);
			}
		} );

		// Either show the map so all markers are visible, or use the given home and zoom.
		const group = L.featureGroup( markers ).addTo( map );
		if ( this.props.fit ) {
			setTimeout( function () {
				map.fitBounds( group.getBounds() );
			}, 1000 );
		} else {
			map.setView(
				{ lon: this.props.lon, lat: this.props.lat },
				this.props.zoom
			);
		}

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
