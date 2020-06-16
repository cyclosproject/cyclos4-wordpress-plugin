/* global fetch, cyclosUserDirectoryObj */

async function retrieveUsers() {
	const result = await fetch(
		`${ cyclosUserDirectoryObj.ajax_url }?_ajax_nonce=${ cyclosUserDirectoryObj.id }&action=cyclos_userdata`
	);
	return result.ok ? await result.json() : 'Error retrieving users.';
}

export default retrieveUsers;
