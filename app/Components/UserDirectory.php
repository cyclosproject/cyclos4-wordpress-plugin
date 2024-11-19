<?php
/**
 * The UserDirectory component enables the Cyclos users map/list.
 *
 * @package Cyclos
 */

namespace Cyclos\Components;

use Cyclos\Configuration;
use Cyclos\Services\CyclosAPI;

/**
 * UserDirectory class
 */
class UserDirectory {

	/**
	 * Leaflet map asset sources.
	 */
	const LEAFLET_VERSION = '1.7.1';
	const LEAFLET_CSS     = 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css';
	const LEAFLET_JS      = 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.js';
	const LEAFLET_ICON    = 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-icon.png';

	/**
	 * Leaflet markercluster plugin asset sources.
	 */
	const LEAFLET_CLUSTER_VERSION  = '1.5.0';
	const LEAFLET_CLUSTER_CSS      = 'https://unpkg.com/leaflet.markercluster@1.5.0/dist/MarkerCluster.css';
	const LEAFLET_CLUSTER_ICON_CSS = 'https://unpkg.com/leaflet.markercluster@1.5.0/dist/MarkerCluster.Default.css';
	const LEAFLET_CLUSTER_JS       = 'https://unpkg.com/leaflet.markercluster@1.5.0/dist/leaflet.markercluster.js';

	/**
	 * Leaflet search plugin asset sources.
	 */
	const LEAFLET_SEARCH_VERSION = '2.9.9';
	const LEAFLET_SEARCH_CSS     = 'https://unpkg.com/leaflet-search@2.9.9/dist/leaflet-search.min.css';
	const LEAFLET_SEARCH_JS      = 'https://unpkg.com/leaflet-search@2.9.9/dist/leaflet-search.min.js';

	/**
	 * The Cyclos API.
	 *
	 * @var CyclosAPI $cyclos The Cyclos API.
	 */
	private $cyclos;

	/**
	 * The configuration.
	 *
	 * @var Configuration $conf The configuration.
	 */
	private $conf;

	/**
	 * Keep track of wether we have prepared the scripts for a map already or not.
	 *
	 * @var boolean $has_map Whether the userdirectory is shown in a map or not (i.e. in a list only).
	 */
	private $has_map;

	/**
	 * Constructor.
	 *
	 * @param Configuration $conf   The configuration.
	 * @param CyclosAPI     $cyclos The Cyclos API.
	 */
	public function __construct( Configuration $conf, CyclosAPI $cyclos ) {
		$this->conf   = $conf;
		$this->cyclos = $cyclos;
	}

	/**
	 * Init function to setup hooks.
	 */
	public function init() {
		add_action( 'init', array( $this, 'register_stuff' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_style' ) );
		add_action( 'wp_footer', array( $this, 'localize_script' ) );
		add_shortcode( 'cyclosusers', array( $this, 'handle_users_shortcode' ) );
		add_filter( 'cyclos_render_setting', array( $this, 'render_user_settings' ), 10, 2 );
		add_action( 'wp_ajax_cyclos_refresh_user_data', array( $this, 'handle_refresh_user_data_ajax_request' ) );
		// Note: we don't need a 'wp_ajax_nopriv_cyclos_refresh_user_data' because the refresh data action can only be done by admins.
		add_action( 'wp_ajax_cyclos_userdata', array( $this, 'handle_retrieve_userdata_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_cyclos_userdata', array( $this, 'handle_retrieve_userdata_ajax_request' ) );
		add_action( 'wp_ajax_cyclos_usermetadata', array( $this, 'handle_retrieve_usermetadata_ajax_request' ) );
		add_action( 'wp_ajax_nopriv_cyclos_usermetadata', array( $this, 'handle_retrieve_usermetadata_ajax_request' ) );
	}

	/**
	 * First register the scripts and styles, then register the Gutenberg blocks.
	 * This way, the scripts are always registered before we register the blocks (that depend on the scripts).
	 * Note: the userdirectory is only available as a shortcode at this moment, not as a Gutenberg block.
	 */
	public function register_stuff() {
		$this->register_assets();
	}

	/**
	 * Shortcode handler for the Cyclos user directory.
	 *
	 * @param array  $atts     The shortcode attributes.
	 * @param string $content  The content of the shortcode.
	 * @param string $tag      The shortcode tag.
	 * @return string          The rendered user data.
	 */
	public function handle_users_shortcode( $atts = array(), $content = null, $tag = '' ) {
		$atts = shortcode_atts(
			array(
				'views'                => 'list',
				'show_search'          => true,
				'filter_category'      => '',
				'show_filter'          => true,
				'order_field'          => '',
				'sort_order'           => 'asc',
				'visible_sort_options' => '',
				'map_width'            => '',
				'map_height'           => '',
				'fit_users'            => true,
				'home_longitude'       => '',
				'home_latitude'        => '',
				'zoom'                 => '',
				'max_zoom'             => '',
			),
			$atts,
			$tag
		);
		// Make sure the views attribute is an array.
		$views = explode( ',', $atts['views'] );

		// Build up the output.
		$output = '';
		foreach ( $views as $view ) {
			switch ( trim( $view ) ) {
				case 'map':
					$this->enqueue_script( 'map' );
					$output .= $this->render_user_map( $atts );
					break;
				case 'list':
					$this->enqueue_script( 'list' );
					$output .= $this->render_user_list( $atts );
					break;
				default:
					$output .= __( 'The user directory must use either a map or a list view. No other options are available.', 'cyclos' );
			}
		}
		return $output;
	}

	/**
	 * Render the user directory list view.
	 *
	 * @param array $atts     The shortcode attributes relevant for list views.
	 * @return string         The rendered list with the user data.
	 */
	public function render_user_list( $atts ) {
		// For fields used in sorting, we must add the customValues prefix for custom fields.
		// For example, if the webmaster uses 'rating' in one of the sort args, we must convert this to 'customValues.rating'.
		$order_field          = $this->prefix_customfield_names( $atts['order_field'] );
		$visible_sort_options = $this->prefix_customfield_names( $atts['visible_sort_options'] );

		// Prepare the selector to use on the div, based on the selected style.
		$selector = $this->conf->get_user_style();
		if ( 'plain' === $selector || 'none' === $selector ) {
			// The plain and empty styles don't need a specific selector.
			$selector = '';
		}

		// Return a user-list div with the proper data attributes. Also set the selected style as an extra CSS class on the div.
		return sprintf(
			'<div class="cyclos-user-list %s"%s%s%s%s%s><div class="cyclos-loader">%s...</div></div>',
			$selector,
			$this->make_data_attribute( 'show-search', $atts['show_search'], 'boolean' ),
			$this->make_data_attribute( 'filter', $atts['filter_category'] ),
			$this->make_data_attribute( 'show-filter', $atts['show_filter'], 'boolean' ),
			$order_field ? $this->make_data_attribute( 'orderby', $order_field . '-' . $atts['sort_order'] ) : '',
			$this->make_data_attribute( 'sort-options', $visible_sort_options ),
			esc_html__( 'Loading the users, this might take a couple of seconds', 'cyclos' )
		);
	}

	/**
	 * Render the user directory map view.
	 *
	 * @param array $atts       The shortcode attributes relevant for list views.
	 * @return string           The rendered map with the user data.
	 */
	public function render_user_map( $atts ) {
		return sprintf(
			'<div class="cyclos-user-map"%s%s%s%s%s%s%s><div class="cyclos-loader">%s...</div></div>',
			$this->make_data_attribute( 'width', $atts['map_width'] ),
			$this->make_data_attribute( 'height', $atts['map_height'] ),
			$this->make_data_attribute( 'fit-users', $atts['fit_users'], 'boolean' ),
			$this->make_data_attribute( 'lon', $atts['home_longitude'] ),
			$this->make_data_attribute( 'lat', $atts['home_latitude'] ),
			$this->make_data_attribute( 'zoom', $atts['zoom'] ),
			$this->make_data_attribute( 'max-zoom', $atts['max_zoom'] ),
			esc_html__( 'Loading the users, this might take a couple of seconds', 'cyclos' )
		);
	}

	/**
	 * Build an HTML data attribute with the given value.
	 * For string attributes: when non-empty, the data attribute is added and filled with the value; when empty, the data attribute is left out.
	 * For boolean attributes: when truthy, the data attribute is added without any value; when false, the data attribute is left out.
	 *
	 * @param string $name    The id to use in the name of the data attribute. Used after the 'data-cyclos-' prefix.
	 * @param string $value   The value.
	 * @param string $type    (Optional) The type of the value. Can be string or boolean. Default: string.
	 * @return string          The HTML of the data attribute.
	 */
	protected function make_data_attribute( string $name, string $value, string $type = 'string' ) {
		$html = '';
		switch ( $type ) {
			case 'string':
				// String attributes are filled with their value if the value is not empty.
				if ( ! empty( $value ) ) {
					$html .= sprintf( ' data-cyclos-%s="%s"', esc_attr( $name ), esc_attr( $value ) );
				}
				break;
			case 'boolean':
				// Boolean attributes are simply put in without value if the value is true.
				// To check whether the given value is truthy, use PHP's filter_var function.
				// This way it does not matter if the value is passed as 0/1, '0'/'1', 'true'/'false', 'TRUE'/'FALSE'.
				if ( filter_var( $value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE ) ) {
					$html .= sprintf( ' data-cyclos-%s', esc_attr( $name ) );
				}
				break;
		}
		return $html;
	}

	/**
	 * Convert customfield internalNames in the input string so the are prefixed with 'customValues.'.
	 *
	 * @param string $input   The string containing possible field names to convert.
	 * @return string         The string with any custom field names converted to have the proper prefix.
	 */
	protected function prefix_customfield_names( $input ) {
		// If the input is empty, there is nothing to do.
		if ( empty( $input ) ) {
			return $input;
		}

		// Retrieve the user metadata, since this contains the internalNames of all possible customfields.
		$user_metadata = $this->get_cyclos_user_metadata();
		// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		$custom_fields = array_map(
			function ( $field ) {
				return $field->internalName ?? '';
			},
			$user_metadata->customFields ?? array()
		);
		// phpcs:enable

		// For each possible customfield, replace its internalName in the input with the prefixed internalName.
		foreach ( $custom_fields as $field ) {
			if ( empty( $field ) ) {
				continue;
			}
			$input = str_replace( $field, 'customValues.' . $field, $input );
		}
		return $input;
	}

	/**
	 * Register the frontend assets for the userdirectory.
	 */
	public function register_assets() {
		// Stop if we are not on the frontend.
		if ( is_admin() ) {
			return;
		}

		// Register the leaflet map styles and scripts.
		wp_register_style( 'leaflet-style', self::LEAFLET_CSS, array(), self::LEAFLET_VERSION );
		wp_register_style( 'leaflet-cluster-style', self::LEAFLET_CLUSTER_CSS, array( 'leaflet-style' ), self::LEAFLET_CLUSTER_VERSION );
		wp_register_style( 'leaflet-cluster-icon-style', self::LEAFLET_CLUSTER_ICON_CSS, array( 'leaflet-cluster-style' ), self::LEAFLET_CLUSTER_VERSION );
		wp_register_style( 'leaflet-search-style', self::LEAFLET_SEARCH_CSS, array( 'leaflet-style' ), self::LEAFLET_SEARCH_VERSION );
		wp_register_script( 'leaflet-script', self::LEAFLET_JS, array(), self::LEAFLET_VERSION, true );
		wp_register_script( 'leaflet-cluster-script', self::LEAFLET_CLUSTER_JS, array( 'leaflet-script' ), self::LEAFLET_CLUSTER_VERSION, true );
		wp_register_script( 'leaflet-search-script', self::LEAFLET_SEARCH_JS, array( 'leaflet-script' ), self::LEAFLET_SEARCH_VERSION, true );

		// Register the userdirectory script variant for the list.
		$file      = 'js/dist/userdirectory.js';
		$asset     = include \Cyclos\PLUGIN_DIR . 'js/dist/userdirectory.asset.php';
		$handle    = 'cyclos-userdirectory';
		$file_url  = \Cyclos\PLUGIN_URL . $file;
		$deps      = $asset['dependencies'];
		$version   = $asset['version'];
		$in_footer = true;
		wp_register_script( $handle, $file_url, $deps, $version, $in_footer );
		// Register the userdirectory script variant for the map (including leaflet deps).
		$handle = 'cyclos-userdirectory-map';
		$deps   = array_merge( $asset['dependencies'], array( 'leaflet-script', 'leaflet-cluster-script', 'leaflet-search-script' ) );
		wp_register_script( $handle, $file_url, $deps, $version, $in_footer );

		// Register the userdirectory style.
		$file     = 'css/dist/userdirectory.min.css';
		$handle   = 'cyclos-userdirectory-style';
		$version  = \Cyclos\PLUGIN_VERSION . '-' . filemtime( \Cyclos\PLUGIN_DIR . $file );
		$file_url = \Cyclos\PLUGIN_URL . $file;
		$deps     = array( 'leaflet-style', 'leaflet-cluster-icon-style', 'leaflet-search-style' );
		wp_register_style( $handle, $file_url, $deps, $version );
	}

	/**
	 * Enqueue frontend stylesheet for the userdirectory.
	 * Note: we always enqueue the userdirectory stylesheet, regardless of whether the userdirectory is on the screen.
	 * This could be improved by checking if the screen actually contains the userdirectory shortcode.
	 * But this is not trivial (for example the content may be in an intro text on a category screen).
	 * Note: since adding the map functionality, we always load the leaflet stylesheet, even when the webmaster is only showing
	 * a user list and no user map. The webmaster can dequeue these styles on all pages or on all pages except where they show the map.
	 */
	public function enqueue_style() {
		if ( 'none' === $this->conf->get_user_style() ) {
			// The webmaster choose to not include our userdirectory CSS. Still we should load the leaflet CSS which would otherwise load as a dependency of our CSS.
			wp_enqueue_style( 'leaflet-cluster-icon-style' );
			wp_enqueue_style( 'leaflet-search-style' );
		} else {
			// Load our userdirectory CSS, which includes a dependency to the leaflet CSS.
			wp_enqueue_style( 'cyclos-userdirectory-style' );
		}
	}

	/**
	 * Enqueue frontend javascript for the userdirectory.
	 *
	 * @param string $view   The view to enqueue scripts for, being either 'list' or 'map'.
	 */
	public function enqueue_script( $view ) {
		if ( $this->has_map ) {
			// We have already enqueued the map script. This contains list functionality as well, so there is nothing left to do.
			return;
		}
		switch ( $view ) {
			case 'map':
				// First remove the list script that might be enqueued.
				wp_dequeue_script( 'cyclos-userdirectory' );
				// Next, enqueue the map script, which contains list functionality as well.
				wp_enqueue_script( 'cyclos-userdirectory-map' );
				// Keep track of the fact that we enqueued the map script.
				$this->has_map = true;
				break;
			case 'list':
				wp_enqueue_script( 'cyclos-userdirectory' );
				break;
		}
	}

	/**
	 * Localize frontend javascript for the userdirectory.
	 */
	public function localize_script() {
		// Pass the necessary information to the userdirectory script.
		$map_icon = $this->conf->get_map_icon();
		if ( empty( $map_icon ) ) {
			$map_icon = self::LEAFLET_ICON;
		}
		$handle = $this->has_map ? 'cyclos-userdirectory-map' : 'cyclos-userdirectory';
		wp_localize_script(
			$handle,
			'cyclosUserObj',
			array(
				'ajax_url'         => admin_url( 'admin-ajax.php' ),
				'id'               => wp_create_nonce( 'cyclos_userdirectory_nonce' ),
				'design'           => $this->conf->get_user_style(),
				'map_icon'         => $map_icon,
				'map_marker_title' => apply_filters( 'cyclos_map_marker_title', 'address.addressLine1 + address.city' ),
				'l10n'             => array(
					'setupMessage'   => __( 'There was an error retrieving the user data from the server. Please ask your website administrator if this problem persists.', 'cyclos' ),
					'noUsers'        => __( 'No users found', 'cyclos' ),
					'cancel'         => __( 'Cancel', 'cyclos' ),
					'search'         => __( 'Search', 'cyclos' ),
					'zoomInTitle'    => __( 'Zoom in', 'cyclos' ),
					'zoomOutTitle'   => __( 'Zoom out', 'cyclos' ),
					'fullScreen'     => __( 'Full Screen', 'cyclos' ),
					'exitFullscreen' => __( 'Exit Full Screen', 'cyclos' ),
					'searchLabel'    => $this->conf->get_user_search_label(),
					'filterLabel'    => $this->conf->get_user_filter_label(),
					'noFilterOption' => $this->conf->get_user_nofilter_option(),
					'sortLabel'      => $this->conf->get_user_sort_label(),
					'noSortOption'   => $this->conf->get_user_nosort_option(),
					'asc'            => $this->conf->get_user_sort_asc(),
					'desc'           => $this->conf->get_user_sort_desc(),
				),
			)
		);
	}

	/**
	 * Return information about this component.
	 */
	public static function get_component_info() {
		return array(
			'tab'   => __( 'User Directory', 'cyclos' ),
			'intro' => __( 'You can put a user directory (map/list) on your website using the <code>[cyclosusers]</code> shortcode on one of your Posts or Pages.', 'cyclos' ),
		);
	}

	/**
	 * Render custom setting types for this component.
	 *
	 * @param callable $callback  The original function to call to render the setting.
	 * @param string   $type      The setting type.
	 * @return callable           The new function to call to render the setting.
	 */
	public function render_user_settings( $callback, $type ) {
		switch ( $type ) {
			case 'user_data_transient':
				return array( $this, 'render_user_data_transient' );
			case 'user_data_sort':
				return array( $this, 'render_user_data_sort' );
			case 'user_style':
				return array( $this, 'render_user_style' );
			default:
				return $callback;
		}
	}

	/**
	 * Render a user data transient setting field.
	 * This is not an input field, but information on the current data and a button to refresh the data.
	 *
	 * @param array $args Contains the key and Settings object of the field to render.
	 */
	public function render_user_data_transient( $args ) {
		$setting       = $args['setting_info'];
		$user_data     = $this->get_cyclos_user_data();
		$user_metadata = $this->get_cyclos_user_metadata();
		$userdata_info = $this->cyclos_userdata_info( $user_data, $user_metadata );
		$class         = $userdata_info['is_error'] ? 'error' : '';
		$note          = $userdata_info['note'] ?? '';
		printf( '<p class="cyclos-user-data-info %s">%s</p>', esc_attr( $class ), esc_html( $userdata_info['message'] ) );
		printf( '<p class="description %s">%s</p>', esc_attr( $class ), $note ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		printf(
			'<p><button class="button" type="button" id="cyclos-user-data-refresh">%s</button></p><p class="description">%s</p>',
			esc_html__( 'Refresh current user data', 'cyclos' ),
			esc_html__( 'Use this if you would like to retrieve fresh user data from Cyclos before the expiration time is over.', 'cyclos' )
		);
	}

	/**
	 * Render a user data sort setting field.
	 * This is a dropdown list, with options according to what the REST API /users/map can receive as 'orderBy' parameter.
	 *
	 * @param array $args Contains the key and Settings object of the field to render.
	 */
	public function render_user_data_sort( $args ) {
		$field_id = $args['label_for'];
		$setting  = $args['setting_info'];
		$value    = $this->conf->get_setting( $field_id );
		$name     = $this->conf::CYCLOS_OPTION_NAME . '[' . $field_id . ']';
		// Select the options the webmaster can choose to sort the data.
		// These are the options for orderBy as defined by the REST API, except some options that don't make sense here:
		// - distance - since there is no location.
		// - random - this breaks the address aggregate algorithm in the JS which assumes items belonging to one user are next to eachother.
		// - relevance - since there is no search field.
		$options = array(
			'alphabeticallyAsc'  => __( 'Alphabetically Ascending [a-z]', 'cyclos' ),
			'alphabeticallyDesc' => __( 'Alphabetically Descending [z-a]', 'cyclos' ),
			'creationDate'       => __( 'By Creation Date (newest first)', 'cyclos' ),
		);
		printf( '<select name="%s" id="%s">', esc_html( $name ), esc_html( $field_id ) );
		foreach ( $options as $option => $name ) {
			printf( '<option value="%s" %s>%s</option>', esc_attr( $option ), ( $value === $option ? 'selected' : '' ), esc_html( $name ) );
		}
		print( '</select>' );
		if ( ! empty( $setting->get_description() ) ) {
			printf( '<p class="description">%s</p>', esc_html( $setting->get_description() ) );
		}
	}

	/**
	 * Render a user style setting field.
	 * This is a dropdown list, with options according to the different designs in the userdirectory CSS.
	 *
	 * @param array $args Contains the key and Settings object of the field to render.
	 */
	public function render_user_style( $args ) {
		$field_id = $args['label_for'];
		$setting  = $args['setting_info'];
		$value    = $this->conf->get_setting( $field_id );
		$name     = $this->conf::CYCLOS_OPTION_NAME . '[' . $field_id . ']';
		$options  = array(
			/* translators: 'Ocean' is used as the name of one of the design styles. You might leave it as-is. */
			'ocean'    => __( 'Ocean', 'cyclos' ),
			/* translators: 'Material' is used as the name of one of the design styles and refers to Google's 'Material design'. You might leave it as-is. */
			'material' => __( 'Material', 'cyclos' ),
			'plain'    => __( 'Plain', 'cyclos' ),
			'none'     => __( 'None', 'cyclos' ),
		);
		printf( '<select name="%s" id="%s">', esc_html( $name ), esc_html( $field_id ) );
		foreach ( $options as $option => $name ) {
			printf( '<option value="%s" %s>%s</option>', esc_attr( $option ), ( $value === $option ? 'selected' : '' ), esc_html( $name ) );
		}
		print( '</select>' );
		if ( ! empty( $setting->get_description() ) ) {
			printf( '<p class="description">%s</p>', esc_html( $setting->get_description() ) );
		}
	}

	/**
	 * Returns a status information text about the userdata, given the userdata object.
	 *
	 * @param array|WP_Error $user_data      Array with user data or a WP_Error object on failure.
	 * @param array|WP_Error $user_metadata  Array with user metadata or a WP_Error object on failure.
	 * @return array                         Array with status message and error flag.
	 */
	protected function cyclos_userdata_info( $user_data, $user_metadata ) {
		$message  = '';
		$is_error = false;
		$note     = '';
		if ( is_wp_error( $user_data ) || is_wp_error( $user_metadata ) ) {
			$message  = is_wp_error( $user_data ) ? $user_data->get_error_message() : $user_metadata->get_error_message();
			$is_error = true;
		} else {
			$message = sprintf( '%s %s', count( $user_data ), __( 'Cyclos users (addresses)', 'cyclos' ) );

			// Add notes for specific situations.
			$notes = array();

			// When there are users (not zero) explain that the number of users may differ from that in Cyclos because we don't aggregate addresses here.
			if ( count( $user_data ) > 0 ) {
				$notes[] = __( 'If your Cyclos users can have more than one address, the number above indicates the number of addresses of your users, not the number of unique users. The user map will show each address as a separate pointer. The user list will only show each user once.', 'cyclos' );
			}

			// The properties in metadata follow the REST API names, which do not always adhere to the WP naming conventions.
			// phpcs:disable WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase

			// When no filter field is configured, explain that filtering is not possible.
			$filter_field = $user_metadata->mapDirectoryField ?? '';
			if ( empty( $filter_field ) ) {
				$notes[] = __( 'You can not use the filter functionality, because the \'Default filter for map directory\' setting in your Cyclos configuration is currently not set to a selection field.', 'cyclos' );
			}

			// When the users in our dataset are only a subset of the users that exist in Cyclos, explain that filtering/sorting the subset may not be useful.
			$max_nr_of_users = $user_metadata->query->pageSize ?? null;
			if ( count( $user_data ) === $max_nr_of_users ) {
				/* translators: 1: The maximum number of users to show on maps, as set in the Cyclos configuration. */
				$notes[] = sprintf( __( 'There are more users (addresses) in Cyclos than the maximum to show on a user map/list. The Cyclos setting \'Maximum users / advertisements on map\' in your Cyclos configuration is currently set to %1$s. Therefore, the users shown in your WordPress site are only a subset of the total set of Cyclos users. Please note that using a filter or sort within this subset may not be useful. Also, if your users can have more than one address, it might even mean that for the last user not all addresses are shown, when the plugin just reached the maximum to show.', 'cyclos' ), $max_nr_of_users );
			}

			// phpcs:enable

			// If there are notes, show them, each on a separate line.
			if ( count( $notes ) > 0 ) {
				$separator = '<br />* ';
				$note      = sprintf( '%1$s:%2$s%3$s', __( 'Note', 'cyclos' ), $separator, implode( $separator, $notes ) );
			}
		}
		$response = array(
			'message'  => $message,
			'note'     => $note,
			'is_error' => $is_error,
		);
		return $response;
	}

	/**
	 * Handle the AJAX request to refresh the Cyclos user data.
	 */
	public function handle_refresh_user_data_ajax_request() {
		// Die if the nonce is incorrect.
		// Note: In Admin we call settings_fields( self::SETTINGS_PAGE ), which creates a nonce for an action of the form: {$option_group}-options.
		// So, to check the nonce here, we must use this same action. In admin.js we get the nonce value from the _wpnonce form field.
		// See https://developer.wordpress.org/reference/functions/settings_fields/.
		check_ajax_referer( Admin::SETTINGS_PAGE . '-options' );

		// Get the Cyclos user data, forcing new retrieval from Cyclos.
		$user_data     = $this->get_cyclos_user_data( true );
		$user_metadata = $this->get_cyclos_user_metadata();
		$response      = $this->cyclos_userdata_info( $user_data, $user_metadata );

		wp_send_json( $response );
	}

	/**
	 * Handle the AJAX request for retrieving the Cyclos users.
	 */
	public function handle_retrieve_userdata_ajax_request() {
		$error_message = '';
		$users         = array();

		// Die if the nonce is incorrect.
		check_ajax_referer( 'cyclos_userdirectory_nonce' );

		// Get the Cyclos user data.
		$user_data = $this->get_cyclos_user_data();

		// Return the users found, and an error message if something is wrong.
		if ( is_wp_error( $user_data ) ) {
			$error_message = $user_data->get_error_message();
		} else {
			$users = $user_data;
		}
		$response = array(
			'data'  => $users,
			'error' => $error_message,
		);

		wp_send_json( $response );
	}

	/**
	 * Handle the AJAX request for retrieving the Cyclos user metadata.
	 */
	public function handle_retrieve_usermetadata_ajax_request() {
		$error_message = '';
		$metadata      = array();

		// Die if the nonce is incorrect.
		check_ajax_referer( 'cyclos_userdirectory_nonce' );

		// Get the Cyclos user metadata.
		$user_metadata = $this->get_cyclos_user_metadata();

		// Return the user metadata found, and an error message if something is wrong.
		if ( is_wp_error( $user_metadata ) ) {
			$error_message = $user_metadata->get_error_message();
		} else {
			$metadata = $user_metadata;
		}
		$response = array(
			'data'  => $metadata,
			'error' => $error_message,
		);

		wp_send_json( $response );
	}

	/**
	 * Return the Cyclos user data, using a transient to limit the number of calls to the Cyclos server.
	 *
	 * @param  bool $force_new  (Optional) Whether the data should always be retrieved from Cyclos. Defaults to false.
	 * @return array|\WP_Error  Array with user data or a WP_Error object on failure.
	 */
	protected function get_cyclos_user_data( bool $force_new = false ) {
		// If we can use the data from our transient, use that.
		$user_data = get_transient( Configuration::USER_DATA_TRANSIENT );
		if ( $force_new || false === $user_data ) {
			// The transient is not there or not valid anymore, so retrieve the data from Cyclos.
			$user_data = $this->cyclos->get_user_data();
			// Store the data in the transient, but only if it is not an error.
			if ( ! is_wp_error( $user_data ) ) {
				set_transient( Configuration::USER_DATA_TRANSIENT, $user_data, $this->conf->get_user_data_expiration_time() * MINUTE_IN_SECONDS );
				// Also refresh the metadata, because both data belong together.
				$this->get_cyclos_user_metadata( true );
			}
		}
		return $user_data;
	}

	/**
	 * Return the Cyclos user metadata, using a transient to limit the number of calls to the Cyclos server.
	 *
	 * @param  bool $force_new  (Optional) Whether the data should always be retrieved from Cyclos. Defaults to false.
	 * @return array|\WP_Error  Array with user metadata or a WP_Error object on failure.
	 */
	protected function get_cyclos_user_metadata( bool $force_new = false ) {
		// If we can use the metadata from our transient, use that.
		$user_metadata = get_transient( Configuration::USER_METADATA_TRANSIENT );
		if ( $force_new || false === $user_metadata ) {
			// The transient is not there or not valid anymore, so retrieve the data from Cyclos.
			$user_metadata = $this->cyclos->get_user_metadata();
			// Store the data in the transient, but only if it is not an error.
			if ( ! is_wp_error( $user_metadata ) ) {
				$user_metadata = $this->decorate_user_metadata( $user_metadata );
				set_transient( Configuration::USER_METADATA_TRANSIENT, $user_metadata, $this->conf->get_user_data_expiration_time() * MINUTE_IN_SECONDS );
			}
		}
		return $user_metadata;
	}

	/**
	 * Add relevant information to the metadata, like data-type and (WP-translatable) label for basic fields.
	 *
	 * @param Object $user_metadata  Undecorated user metadata.
	 * @return Object                Decorated user metadata.
	 */
	protected function decorate_user_metadata( $user_metadata ) {
		// Build up the lists of possible basic fields, each with their visible name and type.
		// Note: if we would need address fields in the future, we could add them here in a sub-array for 'address'.
		// phpcs:disable WordPress.Arrays.ArrayDeclarationSpacing.AssociativeArrayFound
		$basic_fields = array(
			'display'       => array( 'name' => __( 'Display', 'cyclos' ), 'type' => 'text' ),
			'name'          => array( 'name' => __( 'Name', 'cyclos' ), 'type' => 'text' ),
			'image'         => array( 'name' => __( 'Logo', 'cyclos' ), 'type' => 'logo' ),
			'username'      => array( 'name' => __( 'Username', 'cyclos' ), 'type' => 'text' ),
			'accountNumber' => array( 'name' => __( 'Account Number', 'cyclos' ), 'type' => 'text' ),
			'address'       => array( 'name' => __( 'Address', 'cyclos' ), 'type' => 'address' ),
			'email'         => array( 'name' => __( 'E-mail', 'cyclos' ), 'type' => 'email' ),
			'phone'         => array( 'name' => __( 'Phone', 'cyclos' ), 'type' => 'phone' ),
		);
		// phpcs:enable WordPress.Arrays.ArrayDeclarationSpacing.AssociativeArrayFound

		// Only keep basic fields that are enabled in Cyclos.
		$enabled_fields = $user_metadata->fieldsInList; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		// The fieldsInList property does not contain all possible fields, so include the ones Cyclos leaves out.
		array_push( $enabled_fields, 'display', 'image', 'address' );
		// Filter the basic fields so only enabled fields remain.
		$basic_fields = array_intersect_key( $basic_fields, array_flip( $enabled_fields ) );

		// Decorate the user metadata with the basic fields information.
		$user_metadata->basicFields = $basic_fields; // phpcs:ignore WordPress.NamingConventions.ValidVariableName.UsedPropertyNotSnakeCase
		return $user_metadata;
	}
}
