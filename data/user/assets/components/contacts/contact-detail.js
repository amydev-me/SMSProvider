const phoneUtil = require('google-libphonenumber').PhoneNumberUtil.getInstance();
const datePicker = resolve => require(['vue-bootstrap-datetimepicker'], resolve);
const DeleteModal = resolve => require(['../../core/DeleteModal'], resolve);

module.exports = {
	components: { DeleteModal, datePicker },

	data: function () {
		return {
			country: 'MM',
			isedit: false,
			removeUrl: '/contact/delete/',

			contact: {
				id: null,
				contactName: null,
				email: null,
				mobile: null,
				work: null,
				companyName: null,
				address: null,
				birthdate: null,
				user_id: null
			},

			groups: [],
			contact_id: null,
			selected_groups: [],

			options: {
				format: 'YYYY-MM-DD',
				useCurrent: false,
			},
		}
	},

	methods: {
		getUrlParam() {
			var contact_id = Helper.getUrlParameter('contact_id');
			if (contact_id) {
				this.contact_id = contact_id;
				this.getContactDetail();
			}
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

			axios.post('/contact/edit', temp).then(({data}) => {
				if (data.status == true) {
					this.isedit = false;
					Notification.success('Success');
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
					Notification.error('Error occured while creating data.');
				}
			});

		},

		asyncGroup() {
			axios.get('/list/async-group').then(({data}) => {
				this.groups = data;
			});
		},

		getContactDetail() {
			axios.get('/contact/detail/' + this.contact_id).then(({data}) => {
				if (data.status == true) {
					let contact = data.contact;
					this.contact.id = contact.id;
					this.contact.group_id = contact.group_id;
					this.contact.contactName = contact.contactName;
					this.contact.email = contact.email;
					this.contact.mobile = contact.mobile;
					this.contact.work = contact.work;
					this.contact.companyName = contact.companyName;
					this.contact.address = contact.address;
					this.contact.birthdate =contact.birthdate != null?Helper.formatDate(contact.birthdate):null;
					this.contact.user_id = contact.user_id;
					$("#phone").intlTelInput("setNumber", contact.mobile);
					// $('#phone').intlTelInput('selectCountry', 'MM');
					if (data.groups) {
						this.selected_groups = data.groups;
					}
				}
			});
		},

		showDeleteModal() {
			$('#deleteModal').modal('show');
		},

		successdelete() {
			window.location.href = '/group/detail?group_id=' + this.contact.group_id;
		},

		clickedit() {
			this.isedit = true;
		}
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

		this.asyncGroup();
		this.getUrlParam();
	},

	created() {
		this.$validator.extend('verify_phone', {
			getMessage: field => `Invalid phone number`,

			validate: value => new Promise((resolve) => {
				var number = $("#phone").intlTelInput("getNumber");
				if (!number) {
					number = that.contact.mobile;
				}

				var isvalid = false;

				try {
					const _number = phoneUtil.parseAndKeepRawInput(number, this.country);
					var isvalid = phoneUtil.isValidNumber(_number);
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