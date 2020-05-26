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
	 * Search the user directory (map) in Cyclos.
	 *
	 * @param string $group          (Optional) The user group to search in.
	 * @return object|\WP_Error      The body from the Cyclos server response or a WP_Error object on failure.
	 */
	public function search_user_directory( string $group = null ) {
		$this->method = 'GET';
		$this->route  = '/users/map?fields=name&fields=display&fields=image&fields=address&fields=phone&fields=customValues';
		// Note: even though Cyclos allows the groups argument to be an array of multiple groups, we never pass more than one group.
		if ( $group ) {
			$this->route .= "&groups=$group";
		}
		return $this->run();
	}
}
