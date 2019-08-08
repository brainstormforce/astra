const request = require("request");

beforeEach(() => {
	cleanupWP();
});

afterEach(() => {
	cleanupWP();
});

const cleanupWP = async () => {
	return new Promise((resolve, reject) => {
		var options = {
			uri:
                `${ process.env.ASTRA_TESTS_URL }/wp-admin/admin-ajax.php?action=clean_site`
		};

		request(options, function(error, response, body) {
			if (!error && response.statusCode == 200) {
				resolve(body);
			} else {
				console.log(response.statusCode);
				console.log(response.statusMessage);
				reject(response);
			}
		});
	});
};
