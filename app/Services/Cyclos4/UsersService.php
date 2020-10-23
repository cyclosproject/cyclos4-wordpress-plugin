<?php
/**
 * UsersService class contains access to the user directory (map).
 *
 * @package Cyclos
 */

namespace Cyclos\Services\Cyclos4;

/**
 * The UsersService class.
 */
class UsersService extends Service {

	/**
	 * Get the configuration data for searching the user directory (map) in Cyclos.
	 *
	 * @return object|\WP_Error      The body from the Cyclos server response or a WP_Error object on failure.
	 */
	public function get_data_for_search() {
		$this->method = 'GET';
		$this->route  = '/users/map/data-for-search';
		return $this->run();
	}

	/**
	 * Search the user directory (map) in Cyclos.
	 *
	 * @param string $group          (Optional) The user group to search in.
	 * @param string $order_by       (Optional) The field to use as orderBy.
	 * @return object|\WP_Error      The body from the Cyclos server response or a WP_Error object on failure.
	 */
	public function search_user_directory( string $group = null, string $order_by = null ) {
		$this->method = 'GET';
		$this->route  = '/users/map';
		// Note: even though Cyclos allows the groups argument to be an array of multiple groups, we never pass more than one group.
		if ( $group ) {
			$this->route .= "&groups=$group";
		}
		if ( $order_by ) {
			// Note: if the given order_by is not one of the possible values, the REST API will simply default to ordering by creationDate.
			// So we don't need to check whether the given order_by value is correct.
			$this->route .= "&orderBy=$order_by";
		}
		return $this->run();
	}
}
