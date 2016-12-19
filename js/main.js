function searchSubmit() {
	var input = document.getElementById('search-field').value.replace(/\s/g, '-').replace('\'', '').toLowerCase();

	if (input.length > 0) {
		window.location = '/champion/' + input;
	}

	return false;
}

document.addEventListener('awesomplete-selectcomplete', searchSubmit, false);