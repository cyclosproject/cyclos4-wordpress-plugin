import retrieveUsers from './cyclos_userdata';

const showFilter = () => {
	// For now, just fill the dropdown with some fake data - to be implemented later.
	return '<select name="category"><option value="bakeries">Bakeries</option><option value="bike_shops">Bike shops</option><option value="groceries">Grocery Stores</option></select>';
};

const showListItem = ( user ) => {
	const display = user.display ? user.display : '';
	const name = user.name ? user.name : display;
	const logo = user.image;
	const address = user.address;
	const website = user.customValues?.website;
	const phone = user.phone;
	let html = '<li>';
	html += `<h2>${ name }</h2>`;
	html += logo
		? `<img class="logo" src="${ logo.url }" alt="${ logo.name }" width="${ logo.width }" height="${ logo.height }">`
		: '';
	if ( address ) {
		html += address.addressLine1 ? `<p>${ address.addressLine1 }</p>` : '';
		html += `<p>${ address.city } ${ address.country }</p>`;
	}
	html += website ? `<p><a href="${ website }">${ website }</a></p>` : '';
	html += phone ? `<p>${ phone }</a></p>` : '';
	html += '</li>';
	return html;
};

async function buildUserList( listElement ) {
	const props = listElement.dataset;
	const users = await retrieveUsers();
	let userList = '';
	userList += props.cyclosShowFilter ? showFilter() : '';
	userList += '<ul>';
	users.forEach( ( user ) => {
		userList += showListItem( user );
	} );
	userList += '</ul>';
	listElement.innerHTML = userList;
}

export default buildUserList;
