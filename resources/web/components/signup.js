const { isValidNumber } = require('libphonenumber-js');
var ServerError = require('../core/ServerErrors');

const dict = {
	custom: {
		username: {
			required: 'Please enter a username',
			min: 'Username must be at least 3 characters.'
		},

		email: {
			required: 'Please enter an email address',
			email: 'Please enter a valid email address'
		},

		full_name: {
			required: 'Please enter your full name',
			alpha: 'Full Name may only contain alphabetic characters.'
		},

		mobile: {
			required: 'Please enter your mobile number'
		},

		confirm_code: {
			required: 'Please enter the code sent to your phone'
		},

		password: {
			required: 'Please provide a password'
		},

		accept_terms: {
			required: 'Please indicate that you have read and agree to our Terms and Conditions.'
		}
	}
};

module.exports = {
	data: function () {
		return {
			user:{
				userName: null,
				fullName: null,
				emailAddress: null,
				mobileNo: null,
				confirmCode: null,
				companyName: null,
				userAddress: null,
				userPassword: null,
				acceptTerms: false
			},

			togglePassword: 'fa fa-fw fa-eye field-icon',
			passwordFieldType: 'password',
			user_errors: new ServerError(),

			captcha: null,
		}
	},

	methods: {
		hasErrors() {
			return this.user_errors.any();
		},

		validateData() {
			// if (grecaptcha.getResponse().length == 0) {
			// 	this.captcha = null;
			// } else {
			// 	this.captcha = true;
			// }

			this.$validator.validateAll().then(successsValidate => {
				if (successsValidate) {
					$('#phone').val( $("#phone").intlTelInput("getNumber") );

					axios.post('/check-confirmation', {mobile: $('#phone').val(), confirm_code: this.user.confirmCode}).then(({data}) => {
						if (data.status == true) {
							this.submit();
						} else {
							var error_messages = '';

							for (var key in data.message) {
								if (data.message.hasOwnProperty(key)) {
									error_messages += data.message[key] + '<br/>';
								}
							}

							Notification.error(error_messages);
						}
					});
				}
			});
		},

		switchVisibility() {
			if (this.passwordFieldType === 'password') {
				this.passwordFieldType = 'text';
				this.togglePassword = 'fa fa-fw fa-eye-slash field-icon';
			} else {
				this.passwordFieldType = 'password';
				this.togglePassword = 'fa fa-fw fa-eye field-icon';
			}
		},

		sendConfirmationCode() {
			this.$validator.validate('mobile').then(successsValidate => {
				if (successsValidate) {
					$('#phone').val( $("#phone").intlTelInput("getNumber") );

					// if ( this.getCookie('mobile') != null ) {
					// 	Notification.error('Please wait 15 minutes to send another code.');
					// 	return false;
					// }

					axios.post('/send-confirmation', {mobile: $('#phone').val()}).then(({data}) => {
						if (data.status == true) {
							// this.setCookie('mobile', $('#phone').val(), 15);
							Notification.success('A confirmation code has been sent to your mobile.');
						} else {
							var error_messages = '';

							for (var key in data.message) {
								if (data.message.hasOwnProperty(key)) {
									error_messages += data.message[key] + '<br/>';
								}
							}

							Notification.error(error_messages);
						}
					});
				}
			});
		},

		// setCookie(name, value, minutes) {
		// 	var expires = "";

		// 	if (minutes) {
		// 		var date = new Date();
		// 		date.setTime(date.getTime() + (minutes * 60 * 1000));
		// 		expires = "; expires=" + date.toUTCString();
		// 	}

		// 	document.cookie = name + "=" + (value || "") + expires + "; path=/";
		// },

		// getCookie(name) {
		// 	var nameEQ = name + "=";
		// 	var ca = document.cookie.split(';');

		// 	for (var i = 0; i < ca.length; i++) {
		// 		var c = ca[i];

		// 		while (c.charAt(0) == ' ') {
		// 			c = c.substring(1,c.length);
		// 		}

		// 		if (c.indexOf(nameEQ) == 0) {
		// 			return c.substring(nameEQ.length,c.length);
		// 		}
		// 	}

		// 	return null;
		// },

		// eraseCookie(name) {	 
		// 	document.cookie = name + '=; Max-Age=-99999999;';
		// },

		submit() {
			this.$refs.form.submit()
		},
	},

	mounted() {
		$("#phone").intlTelInput({
			defaultCountry: "mm",
			preferredCountries: ["mm"],
			geoIpLookup: function (callback) {
				$.get('https://ipinfo.io', function () {}, "jsonp").always(function (resp) {
						var countryCode = (resp && resp.country) ? resp.country : "";
						callback(countryCode);
				});
			},
			utilsScript: "../../js/utils.js"
		});
	},

	created() {
		this.$validator.extend('verify_phone', {
			getMessage: field => `Invalid phone number.`,
			validate: value => new Promise((resolve) => {
				var number = $("#phone").intlTelInput("getNumber");
				let validUser = isValidNumber(number);
				setTimeout(() => {
					resolve({
						valid: validUser
					});
				}, 200);
			})
		});

		this.$validator.extend('verify_user', {
			getMessage: field => `Username has already been taken.`,
			validate: value => new Promise((resolve) => {
				let validUser = true;
				setTimeout(() => {
					axios.get('/api/validate?field=username&q=' + value).then(response => {
						validUser = response.data.valid;
					}).then(response => {
						resolve({
							valid: validUser == true ? true : false
						});
					});
				}, 200);
			})
		});

		this.$validator.extend('verify_email', {
			getMessage: field => `Email has already been taken.`,
			validate: value => new Promise((resolve) => {
				let validEmail = true;
				setTimeout(() => {
					axios.get('/api/validate?field=email&q=' + value).then(response => {
						validEmail = response.data.valid;
					}).then(response => {
						resolve({
							valid: validEmail == true ? true : false,
						});
					});
				}, 200);
			})
		});

		this.$validator.localize('en', dict);
	}
}