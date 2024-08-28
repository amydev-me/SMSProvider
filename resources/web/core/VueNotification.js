module.exports = {
	success (notitext) {
		return $.Notification.autoHideNotify('success', 'top-right', '', notitext);
	},

	info (notitext) {
		return $.Notification.autoHideNotify('info', 'top-right', '', notitext);
	},

	error (notitext) {
		return $.Notification.autoHideNotify('error', 'top-right', '', notitext);
	},

	warning (notitext) {
		return $.Notification.autoHideNotify('warning', 'top-right', '', notitext);
	},
}