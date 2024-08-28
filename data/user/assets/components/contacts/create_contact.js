const phoneUtil = require('google-libphonenumber').PhoneNumberUtil.getInstance();
const datePicker = resolve => require(['vue-bootstrap-datetimepicker'], resolve);

module.exports = {
	components: { datePicker },

	data: function () {
		return {
			country: 'MM',

			contact: {
				id: null,
				groups: [],
				contactName: null,
				email: null,
				mobile: null,
				work: '',
				companyName: null,
				address: null,
				birthdate: null,
				user_id: null
			},

			groups: [],
			selected_groups: [],
			phone: '',
			isnotvalid: false,
			group_id: null,

			options: {
				format: 'YYYY-MM-DD',
				useCurrent: false,
			},
		}
	},

	methods: {
		getUrlParam() {
			group_id = Helper.getUrlParameter('group_id');
			if (group_id) {
				this.group_id = group_id;
				var data = this.groups.find(function (el) {
					return el.id == group_id;
				});
				this.selected_groups.push(data);
			}
		},

		asyncGroup() {
			return axios.get('/list/async-group').then(({data}) => {
				this.groups = data;
			});
		},

		validateData() {
			this.$validator.validateAll().then(successsValidate => {
				if (successsValidate) {
					this.submit();
				}
			}).catch(error => {
				Notification.warning('Opps!Something went wrong.');
			});
		},

		submit() {
			let _groups = [];
			var temp = this.selected_groups.map(function (e) {
				_groups.push(e.id);
			});
			this.contact.groups = _groups;
			var temp = Object.assign({}, this.contact);
			temp.mobile = $("#phone").intlTelInput("getNumber");

			axios.post('/contact/create', temp).then(({data}) => {
				if (data.status == true) {
					window.location = document.referrer;
				} else {
					var error_messages = '';

					for (var key in data.message) {
						if (data.message.hasOwnProperty(key)) {
							error_messages += data.message[key] + '<br/>';
						}
					}

					Notification.error(error_messages);
				}
			}).catch(error => {
				if (error.response.status == 401 || error.response.status == 419) {
					window.location.href = '/login';
				} else {
					Notification.error('Error occured while inserting data.');
				}
			});
		},
	},

	mounted() {
		$("#phone").intlTelInput({
			defaultCountry: "mm",
			preferredCountries: ["mm"],

			geoIpLookup: function (callback) {
				$.get('https://ipinfo.io', function () {
				}, "jsonp").always(function (resp) {
					var countryCode = (resp && resp.country) ? resp.country : "";
					callback(countryCode);
				});
			},

			utilsScript: "../../js/utils.js"
		});

		this.asyncGroup().then(x => {
			this.getUrlParam();
		});
	},

	created() {
		this.$validator.extend('verify_phone', {
			getMessage: field => `Invalid phone number`,

			validate: value => new Promise((resolve) => {
				var number = $("#phone").intlTelInput("getNumber");
				var isvalid = false;
				try {
					const _number = phoneUtil.parse(number, this.country);
					isvalid = phoneUtil.isValidNumber(_number);
				} catch (e) {

				}
				setTimeout(() => {
					resolve({
						valid: isvalid
					});
				}, 200);
			})
		});

		this.$validator.extend('verify_group', {
			getMessage: field => `The group field is required`,

			validate: value => new Promise((resolve) => {
				var isvalid = false;

				if (this.selected_groups.length > 0) {
					$('.multiselect__tags').css('border-color', '#e5e5e5');
					isvalid = true;
				} else {
					$('.multiselect__tags').css('border-color', '#dc3545');
					isvalid = false;
				}

				resolve({
					valid: isvalid
				});
			})
		});
	},

	computed: {
		groupTab() {
			return {
				tabIndex: 0,
			}
		},
	}
}