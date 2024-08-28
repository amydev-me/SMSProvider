const moment = require('moment');

module.exports = {
	getUrlParameter(name, url) {
		if (!url) {
			url = window.location.href;
		}

		name = name.replace(/[\[\]]/g, "\\$&");

		var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
			results = regex.exec(url);

		if (!results) {
			return null;
		}

		if (!results[2]) {
			return '';
		}

		return decodeURIComponent(results[2].replace(/\+/g, " "));
	},

	formatDate(date) {
		return moment(date).format("YYYY-MM-DD");
	},

	formatDateTime(date) {
		return moment(date).format("YYYY-MM-DD HH:mm:ss");
	},

	formatPrettyDate(date) {
		return moment(date).format("DD MMM YYYY");
	},

	formatPrettyDateTime(date) {
		return moment(date).format("DD MMM YYYY hh:mm:ss A");
	},

	formatPrettyDateMonth(date) {
		return moment(date).format("DD MMM");
	}
}