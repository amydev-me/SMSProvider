const VueTagsInput = resolve => require(['@johmun/vue-tags-input'], resolve);
const datePicker = resolve => require(['vue-bootstrap-datetimepicker'], resolve);

// const PNF = require('google-libphonenumber').PhoneNumberFormat;
const moment = require('moment');
// const phoneUtil = require('google-libphonenumber').PhoneNumberUtil.getInstance();
const Country = require('../core/Country');
const SmsLength = require('../core/CalculateSmsLength');

const Timezone = require('../core/Timezone');
Vue.component('multiselect', require('vue-multiselect').default);

const parsePhoneNumberFromString = require('libphonenumber-js').parsePhoneNumberFromString;

$.extend(true, $.fn.datetimepicker.defaults, {
	icons: {
		time: 'far fa-clock',
		date: 'far fa-calendar',
		up: 'fas fa-arrow-up',
		down: 'fas fa-arrow-down',
		previous: 'fas fa-chevron-left',
		next: 'fas fa-chevron-right',
		today: 'fas fa-calendar-check',
		clear: 'far fa-trash-alt',
		close: 'far fa-times-circle'
	}
});

module.exports = {
	components: { VueTagsInput, datePicker },

	data: function() {
		return {
			country: 'MM',

			count: {
				encoding: null,
				length: 0,
				per_message: 0,
				remaining: 0,
				char_type: null,
				messages: 0,
				text: '',
				max: 918
			},

			body: "",
			to: [],
			tag: '',
			tags: [],
			autocompleteItems: [],
			debounce: null,

			sendIcon: 'fa-paper-plane',
			send: 'Send Message',
			scheduleMessage: 'Schedule Message',
			schedule: false,
			date: moment(new Date()).format("DD MMM YYYY h:mm A"),

			options: {
				format: 'DD MMM YYYY h:mm A',
				useCurrent: false,
				showClear: true,
				showClose: true,
			},

			log_id:null,
			loading: false,

			countries: [],
			sender_name: "",

			timezones: [],
			timezone: null,
			selected_timezone: null,
		}
	},

	methods: {
		getCountries() {
			axios.get('/admin/country/async').then(({data}) => {
				this.countries = data;
			});
		},

		getUrlParameter() {
			var log_id = Helper.getUrlParameter('log_id');

			if (log_id) {
				this.log_id = log_id;

				axios.get('/admin/log/detail?log_id=' + this.log_id).then(({data}) => {
					this.tags = data.log_details.map(a => {
						return {
							label: null,
							text: a.recipient,
							tiClasses: [
								'valid'
							]
						};
					});

					this.body = data.message_content;
				});
			}
		},

		toggleSchedule: function (event) {
			this.schedule = !this.schedule;

			if (this.schedule === true) {
				this.send = 'Schedule Message';
				this.sendIcon = 'fa-calendar';
				this.scheduleMessage = 'Cancel Schedule';
			} else {
				this.send = 'Send Message';
				this.sendIcon = 'fa-paper-plane';
				this.scheduleMessage = 'Schedule Message';
			}
		},

		calculateSmsLength(value) {
			this.count = SmsLength.getSmsLength(value);
		},

		update(newTags) {
			this.autocompleteItems = [];
			this.tags = newTags;
		},

		validateTag(obj) {
			if (obj.tag.type == 'list' || obj.tag.type == 'contact') {
				if (obj.tag.type == 'list') {
					if (obj.tag.label == '0') {
						return;
					}
				}

				obj.addTag();
			} else {
				try {
					if ( (obj.tag.text).substring(0, 2) == '09' ) {
						// const number = phoneUtil.parseAndKeepRawInput(obj.tag.text, 'MM');

						// if ( phoneUtil.isValidNumber(number) ) {
						// 	obj.tag.text = phoneUtil.format(number, PNF.E164);
						// 	obj.tag.label = null;
						// 	obj.addTag();

						// 	return false;
						// }

						var phoneNumber = parsePhoneNumberFromString(obj.tag.text, 'MM');

						if (phoneNumber.isValid()) {
							obj.tag.text = phoneNumber.number;
							obj.tag.label = null;
							obj.addTag();

							return false;
						}
					} else {
						$.each(this.countries, function(key, value) {
							var num = (obj.tag.text).substring(0, 5);

							if ( num.indexOf(value.prefix) != -1 ) {
								// const number = phoneUtil.parseAndKeepRawInput(obj.tag.text, value.code);

								// if ( phoneUtil.isValidNumber(number) ) {
								// 	obj.tag.text = phoneUtil.format(number, PNF.E164);
								// 	obj.tag.label = null;
								// 	obj.addTag();

								// 	return false;
								// }

								var phoneNumber = parsePhoneNumberFromString(obj.tag.text, value.code);

								if (phoneNumber.isValid()) {
									obj.tag.text = phoneNumber.number;
									obj.tag.label = null;
									obj.addTag();

									return false;
								}
							}
						});
					}
				} catch (err) {
					console.log(err);
				}
			}
		},

		validateData() {
			this.$validator.validateAll().then(successsValidate => {
				if (successsValidate) {
					this.submit();
				}
			}).catch(error => {
				Notification.warning('Opps! Something went wrong.');
			});
		},

		submit() {
			this.loading = true;

			var _data = Object.assign({}, this.count);
			var smsdata = new Object();

			smsdata.id = null;
			smsdata.body = this.body;
			smsdata.to = JSON.stringify(this.tags);
			smsdata.encoding = _data.encoding == "UTF16" ? 'Unicode' : 'Plain text';
			smsdata.total_characters = _data.length;
			smsdata.source = "Web App";
			smsdata.message_parts = _data.messages;
			smsdata.send_at = this.date;
			smsdata.sender_name = this.sender_name;

			var total = 0;

			this.tags.map(function (el) {
				if (el.type == 'list') {
					total += el.label;
				} else {
					total++;
				}
			});

			smsdata.total_sms = total * _data.messages;

			if (this.schedule) {
				smsdata.timezone = this.selected_timezone.timezone;
			}

			let url = this.schedule ? '/admin/schedule-message' : '/admin/message';
			
			axios.post(url, smsdata).then(({data}) => {
				if (data.status == 'success') {
					window.location.href = '/admin/compose';
				} else {
					Notification.error(data.description);
					this.loading = false;
				}
			}).catch(error => {
				if (error.response.status == 401 || error.response.status == 419) {
					window.location.href = '/admin/login';
				} else {
					Notification.error(error.response.data.description);
				}

				this.loading = false;
			});
		},

		getTimezones() {
			var obj = Timezone.getTimezones();
			var timezones = [];
			
			$.each( obj, function( key, value ) {
				var timezone = {
					'timezone' : key,
					'city' : value
				}

				timezones.push(timezone);
			});

			this.timezones = timezones;
		},

		insertTag(field) {
			this.body += '{' + field + '}';
		}
	},

	computed: {
		totalvalue: {
			get: function() {
				var total = 0;

				this.tags.map(function (el) {
					if (el.type == 'list') {
						total += el.label;
					} else {
						total++;
					}
				});

				return total * this.count.messages;
			}
		},

		receipients: function() {
			let total = 0;

			for (let i = 0; i < this.results.length; i++) {
				total += parseInt(this.results[i].marks);
			}

			return total;
		}
	},

	mounted() {
		this.getCountries();

		this.getUrlParameter();
		this.total_sms = this.totalvalue;

		this.getTimezones();
	}
}