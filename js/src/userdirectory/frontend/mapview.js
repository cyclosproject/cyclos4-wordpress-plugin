/* global cyclosUserObj, L, Image */
/**
 * UserMap class representing a frontend usermap.
 */
import { userDetails, userNameValue } from './templates';
import { getPropByPath, getPropsByPath } from '../utils';
import View from './view';

export default class MapView extends View {
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
		defaults.catField = cyclosSettings.mapDirectoryField ?? '';

		// Retrieve the specific map properties we need from the container dataset.
		const props = this.container.dataset;
		const mapProps = {
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

		// Add the specific map properties to the default properties from our parent View class.
		super.initProps();
		Object.assign( this.props, mapProps );

		// Never show sort on maps.
		this.props.showSort = false;

		// Store some other props we need later on.
		this.props.catField = 'customValues.' + defaults.catField;

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
	setupView() {
		// Create the usermap element.
		this.initializeView();

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
		this.userMap.style.width = this.props.width;
		this.userMap.style.height = this.props.height;
		this.map = L.map( this.userMap, {
			zoomControl: false,
			tap: false,
		} );
		// Add a custom zoom control so we can pass translated hover texts.
		L.control
			.zoom( {
				zoomInTitle: cyclosUserObj.l10n.zoomInTitle,
				zoomOutTitle: cyclosUserObj.l10n.zoomOutTitle,
			} )
			.addTo( this.map );
		// Add the tiles layer.
		L.tileLayer( this.props.tilesURLTemplate, {
			maxZoom: this.props.maxZoom,
			attribution: this.props.copyright,
		} ).addTo( this.map );

		// Prepare the marker icons.
		// As a fallback, setup a default leaflet icon with no specific className.
		this.defaultIcon = new L.Icon.Default();
		this.catIcons = {};
		// If the icon image is known, set up a custom icon as prepared in the load event of the icon image in loadMap().
		if ( this.iconOptions ) {
			// For users with no category, use the icon with no specific className.
			this.defaultIcon = L.icon( this.iconOptions );
			// Prepare an icon for each category option with its internal name as the className.
			const cats = this.userData.filterOptions;
			cats.forEach( ( cat ) => {
				if ( cat.value ) {
					this.iconOptions.className = cat.value ?? '';
					this.catIcons[ cat.value ] = L.icon( this.iconOptions );
				}
			} );
		}

		// Create a marker for each user, showing a popup with user details when clicked.
		this.markers = [];
		this.maxPopupW = this.userMap.clientHeight - 50;
		this.maxPopupH = Math.min( this.userMap.clientWidth - 100, 300 );
		this.renderUsers();

		// Either show the map so all markers are visible, or use the given home and zoom.
		this.clusters = L.markerClusterGroup().addLayers( this.markers );
		if ( this.props.fit ) {
			this.fitMap();
		} else {
			this.map.setView(
				{ lon: this.props.lon, lat: this.props.lat },
				this.props.zoom
			);
		}
		this.map.addLayer( this.clusters );

		// We are done loading the map, so remove the loader.
		const loader = this.container.querySelector( '.cyclos-loader' );
		if ( loader ) {
			this.container.removeChild( loader );
		}

		// Let the popup update itself after the image it might contain is loaded, because we don't know the dimensions in advance.
		// This corrects the popup dimensions so it does not go outside the map area the first time a new image is loaded.
		this.userMap.querySelector( '.leaflet-popup-pane' ).addEventListener(
			'load',
			( event ) => {
				const tagName = event.target.tagName;
				const popup = this.map._popup; // Last open Popup.

				if ( tagName === 'IMG' && popup && ! popup._updated ) {
					popup._updated = true; // Assumes only 1 image per Popup.
					popup.update();
				}
			},
			true // Capture the load event, because it does not bubble.
		);
	}

	/**
	 * Adjust the zoom and bounds of the map so all markers fit on it.
	 * @param { number } timeout The number of milliseconds to wait before fitting the map bounds.
	 */
	fitMap( timeout = 1000 ) {
		const bounds = this.clusters.getBounds();
		const map = this.map;
		const space = this.iconOptions?.iconSize ?? [ 50, 50 ];
		if ( Object.keys( bounds ).length > 0 ) {
			setTimeout( function () {
				map.fitBounds( bounds, {
					paddingTopLeft: space,
					paddingBottomRight: [ 50, 50 ],
				} );
			}, timeout );
		}
	}

	/**
	 * Initialize the user map.
	 */
	initializeView() {
		// Make sure we have a map element.
		if ( ! this.userMap ) {
			this.userMap = document.createElement( 'div' );
			this.userMap.className = 'user-map';
			this.container.append( this.userMap );
		}
	}

	/**
	 * Empty the view, before rendering the users again.
	 */
	emptyView() {
		this.clusters.clearLayers();
		this.markers = [];
	}

	/**
	 * Re-render the users.
	 */
	reRenderView() {
		super.reRenderView();
		this.clusters.addLayers( this.markers );
		this.fitMap( 100 );
	}

	/**
	 * Creates a Marker object for the given user and adds it to the markers array for rendering.
	 * @param { Object } user The user object.
	 */
	renderUser( user ) {
		// If the user already has a marker, push it to the markers array.
		if ( user.marker ) {
			this.markers.push( user.marker );
			return;
		}

		// The user does not have a marker already, so create it, if the user has lat/lon coordinates.
		const lat = getPropByPath( user, 'address.location.latitude' );
		const lon = getPropByPath( user, 'address.location.longitude' );
		if ( lat && lon ) {
			// For the title we use the users' name plus some extra fields that can be changed by the webmaster.
			const title =
				userNameValue( user ) +
				getPropsByPath( user, cyclosUserObj.map_marker_title );
			const userInfo = userDetails( user, this.userData.fields );
			const userCat = getPropByPath( user, this.props.catField );
			const marker = L.marker(
				{ lon, lat },
				{
					title,
					icon: this.catIcons[ userCat ] ?? this.defaultIcon,
				}
			).bindPopup( userInfo, {
				maxHeight: this.maxPopupW,
				minWidth: this.maxPopupH,
				className: cyclosUserObj.design,
				autoPanPadding: [ 50, 50 ], // Open the popup always at least 50px from the left/top border to prevent the controls overlapping it.
			} );

			// Store the marker in the user object and push it to the array of markers to show.
			user.marker = marker;
			this.markers.push( user.marker );
		}
	}
}
