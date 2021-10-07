/* global cyclosUserObj, L, Image */
/**
 * UserMap class representing a frontend usermap.
 */
import { UserData } from '../data';
import { userDetails, userNameValue } from './templates';
import { getPropByPath, getPropsByPath } from '../utils';

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
		this.loadMap();
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
	 * Load the map: render the map in the load event of the marker image.
	 * This way, we can adjust the marker options to the image dimensions (unless loading fails).
	 */
	loadMap() {
		// Use the dimensions of the icon image to determine the iconSize, iconAnchor and popupAnchor.
		const iconUrl = cyclosUserObj.map_icon;
		this.iconOptions = { iconUrl };
		const markerIcon = new Image();
		markerIcon.onload = () => {
			const iconW = markerIcon.naturalWidth;
			const iconH = markerIcon.naturalHeight;
			if ( iconW && iconH ) {
				this.iconOptions.iconSize = [ iconW, iconH ];
				this.iconOptions.iconAnchor = [ iconW / 2, iconH ];
				this.iconOptions.popupAnchor = [ 1, -( iconH - 10 ) ];
			}
			this.renderMap();
		};
		markerIcon.onerror = () => {
			this.iconOptions = null;
			this.renderMap();
		};
		markerIcon.src = iconUrl;
	}

	/**
	 * Render the map.
	 */
	renderMap() {
		// Initialize the map.
		this.container.style.width = this.props.width;
		this.container.style.height = this.props.height;
		const map = L.map( this.container, {
			zoomControl: false,
			tap: false,
		} );
		// Add a custom zoom control so we can pass translated hover texts.
		L.control
			.zoom( {
				zoomInTitle: cyclosUserObj.l10n.zoomInTitle,
				zoomOutTitle: cyclosUserObj.l10n.zoomOutTitle,
			} )
			.addTo( map );
		// Add a fullscreen control if the device supports it (no iPhone).
		if ( window.screenfull ) {
			L.control
				.fullscreen( {
					title: cyclosUserObj.l10n.fullScreen,
					titleCancel: cyclosUserObj.l10n.exitFullscreen,
				} )
				.addTo( map );
		}
		// Add the tiles layer.
		L.tileLayer( this.props.tilesURLTemplate, {
			maxZoom: this.props.maxZoom,
			attribution: this.props.copyright,
		} ).addTo( map );

		// Prepare the marker icons.
		// As a fallback, setup a default leaflet icon with no specific className.
		let defaultIcon = new L.Icon.Default();
		const catIcons = {};
		// If the icon image is known, set up a custom icon as prepared in the load event of the icon image in loadMap().
		if ( this.iconOptions ) {
			// For users with no category, use the icon with no specific className.
			defaultIcon = L.icon( this.iconOptions );
			// Prepare an icon for each category option with its internal name as the className.
			const cats = this.userData.filterOptions;
			cats.forEach( ( cat ) => {
				if ( cat.value ) {
					this.iconOptions.className = cat.value ?? '';
					catIcons[ cat.value ] = L.icon( this.iconOptions );
				}
			} );
		}

		// Add a marker to the map for each user, showing a popup with user details when clicked.
		const markers = [];
		const catField =
			'customValues.' + this.userData.userMeta?.mapDirectoryField;
		const maxPopupW = this.container.clientHeight - 50;
		const maxPopupH = Math.min( this.container.clientWidth - 100, 300 );
		this.userData.users.forEach( ( user ) => {
			const lat = getPropByPath( user, 'address.location.latitude' );
			const lon = getPropByPath( user, 'address.location.longitude' );
			if ( lat && lon ) {
				// For the title we use the users' name plus some extra fields that can be changed by the webmaster.
				// This way, users with multiple addresses turn up with their different addresses in the search control.
				const title =
					userNameValue( user ) +
					getPropsByPath( user, cyclosUserObj.map_marker_title );
				const userInfo = userDetails( user, this.userData.fields );
				const userCat = getPropByPath( user, catField );
				markers.push(
					L.marker(
						{ lon, lat },
						{
							title,
							icon: catIcons[ userCat ] ?? defaultIcon,
						}
					).bindPopup( userInfo, {
						maxHeight: maxPopupW,
						minWidth: maxPopupH,
						className: cyclosUserObj.design,
						autoPanPadding: [ 50, 50 ], // Open the popup always at least 50px from the left/top border to prevent the controls overlapping it.
					} )
				);
			}
		} );

		// Either show the map so all markers are visible, or use the given home and zoom.
		const group = L.featureGroup( markers );
		if ( this.props.fit ) {
			const bounds = group.getBounds();
			if ( Object.keys( bounds ).length > 0 ) {
				setTimeout( function () {
					map.fitBounds( bounds );
				}, 1000 );
			}
		} else {
			map.setView(
				{ lon: this.props.lon, lat: this.props.lat },
				this.props.zoom
			);
		}
		const clusters = L.markerClusterGroup().addLayer( group );
		map.addLayer( clusters );

		// Add the search control.
		const searchControl = new L.Control.Search( {
			layer: clusters,
			initial: false, // Also find letters in the middle of a word, not just from the beginning.
			marker: false, // Hide the red circle around a hit.
			textErr: cyclosUserObj.l10n?.noUsers,
			textCancel: cyclosUserObj.l10n?.cancel,
			textPlaceholder: cyclosUserObj.l10n?.search,
		} );
		searchControl.on( 'search:locationfound', ( e ) => {
			// When a user is found, close the search control and open the user popup.
			searchControl.collapse();
			clusters.zoomToShowLayer( e.layer, () => {
				e.layer.openPopup();
			} );
		} );
		map.addControl( searchControl );

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
