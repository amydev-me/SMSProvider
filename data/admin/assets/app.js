require('./bootstrap');

window.Vue = require('vue');

window.Notification = require('./core/VueNotification');
window.Helper = require('./core/Helper');

Vue.component('package', resolve => require(['./components/package'], resolve));
Vue.component('order', resolve => require(['./components/order'], resolve));
Vue.component('payg-order', resolve => require(['./components/payg-order'], resolve));
Vue.component('user', resolve => require(['./components/user'], resolve));
Vue.component('user-detail', resolve => require(['./components/user-detail'], resolve));
Vue.component('user-order', resolve => require(['./components/user-order'], resolve));

Vue.component('admin', resolve => require(['./components/admin'], resolve));
Vue.component('operator-log', resolve => require(['./components/operator-log'], resolve));
Vue.component('purchase', resolve => require(['./components/purchase'], resolve));
Vue.component('intl-purchase', resolve => require(['./components/intl-purchase'], resolve));
Vue.component('newsletter', resolve => require(['./components/newsletter'], resolve));
Vue.component('api-key', resolve => require(['./components/api-key'], resolve));
Vue.component('compose', resolve => require(['./components/compose'], resolve));

Vue.component('operator-chart', resolve => require(['./components/dashboard/operator-chart'], resolve));
Vue.component('delivery-chart', resolve => require(['./components/dashboard/delivery-chart'], resolve));
Vue.component('package-chart', resolve => require(['./components/dashboard/package-chart'], resolve));
Vue.component('registration-chart', resolve => require(['./components/dashboard/registration-chart'], resolve));
Vue.component('package-bar-chart', resolve => require(['./components/dashboard/package-bar-chart'], resolve));

Vue.component('country', resolve => require(['./components/country'], resolve));
Vue.component('operator', resolve => require(['./components/operator'], resolve));
Vue.component('sender', resolve => require(['./components/sender'], resolve));
Vue.component('sender-detail', resolve => require(['./components/sender-detail'], resolve));

Vue.component('user-sender', resolve => require(['./components/user-sender'], resolve));

Vue.component('manage-telecom', resolve => require(['./components/telecom'], resolve));
Vue.component('manage-gateway', resolve => require(['./components/gateway'], resolve));
Vue.component('manage-endpoint', resolve => require(['./components/manage-default-endpoint'], resolve));
Vue.component('manage-article', resolve => require(['./components/article'], resolve));
Vue.component('create-article', resolve => require(['./components/create-article'], resolve));
Vue.component('default-setting', resolve => require(['./components/default-setting'], resolve));

Vue.component('user-logs', resolve => require(['./components/logs/user-logs'], resolve));
Vue.component('user-operator', resolve => require(['./components/logs/user-operator'], resolve));
Vue.component('search-sms', resolve => require(['./components/logs/search-sms'], resolve));

Vue.use(require('vee-validate'));

import ToggleButton from 'vue-js-toggle-button';
Vue.use(ToggleButton);

const app = new Vue({
	el: '#app',

	methods: {
		getNotifications() {
			axios.get('/admin/order/notifications').then(({data}) => {
				if (data.notification_count > 0) {
					$('.quantity').show();
				} else {
					$('.quantity').hide();
				}

				$('.notification-quantity').text(data.notification_count);

				var i = 0;
				var noti = '';
				var color = ['primary', 'success', 'danger', 'warning', 'info', 'dark'];

				$.each( data.packages, function( key, value ) {
					noti += '<a href="/admin/order/index/' + value.package_id + '"><div class="notifi__item" style="padding: 19px 10px 14px;"><div class="content"><span class="badge badge-' + color[i++] + '">' + value.total_packages + '</span> <p style="padding-top: 0px; display: inline;"> You have ' + value.total_packages + ' orders of ' + key + ' packages</p></div></div></a>';

					$('#package-notify').html(noti);

					if (i == 6) {
						i = 0;
					}
				});
			});
		},

		cleanData() {
			$('#old_password').val('');
			$('#old_password_error').val('');
			$('#new_password').val('');
			$('#new_password_error').val('');
			$('#confirm_password').val('');
			$('#confirm_password_error').val('');
		},

		validatePasswords() {
			var old_password = $('#old_password').val();
			var new_password = $('#new_password').val();
			var confirm_password = $('#confirm_password').val();
			var error = false;

			if (old_password == null || old_password == '') {
				$('#old_password_error').text('Old Password is required');
				$('#old_password_error').show();
				error = true;
			} else {
				$.ajax({
					type: "POST",
					url: '/admin/password/check',
					data: {old_password: old_password},
					dataType: 'json',
					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
					async: false,

					success: function(data) {
						if (data.status != true) {
							$('#old_password_error').text('Old Password is wrong');
							$('#old_password_error').show();
							error = true;
						} else {
							$('#old_password_error').hide();
						}
					}
				});
			}

			if (new_password == null || new_password == '') {
				$('#new_password_error').text('New Password is required');
				$('#new_password_error').show();
				error = true;
			} else {
				if (old_password == new_password) {
					$('#new_password_error').text('Old Password and New Password must not be same');
					$('#new_password_error').show();
					error = true;
				} else {
					$('#new_password_error').hide();
				}
			}

			if (confirm_password == null || confirm_password == '') {
				$('#confirm_password_error').text('Confirm Password is required');
				$('#confirm_password_error').show();
				error = true;
			} else {
				if (new_password != confirm_password) {
					$('#confirm_password_error').text('New Password and Confirm Password must be same');
					$('#confirm_password_error').show();
					error = true;
				} else {
					$('#confirm_password_error').hide();
				}
				
			}

			if (error) {
				return false;
			} else {
				return true;
			}
		},

		saveNewPassword() {
			var passwords = {
				old_password: $('#old_password').val(),
				new_password: $('#new_password').val(),
				confirm_password: $('#confirm_password').val()
			};

			axios.post('/admin/password/change', passwords).then(({data}) => {
				if (data.status == true) {
					$('#change_password_modal').modal('hide');
					Notification.success('Password changed successfully! Please login again');
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
					window.location.href = '/admin/login';
				} else {
					Notification.error('Error occured while creating data.');
				}
			});;
		}
	},

	mounted() {
		var uri = window.location.pathname;
		var res = uri.split("/");
		
		if (res[1] == 'admin')  {
			this.getNotifications();

			var pusher = new Pusher('a5eab64bb5e6af5ac31d', {
				cluster: 'ap1',
				encrypted: true
			});

			var channel = pusher.subscribe('order-notifications');

			var that = this;
			
			channel.bind('order-received', function(data) {
				that.getNotifications();
			});
		}

		$('#change_password').click(function() {
			that.cleanData();
			$('#change_password_modal').modal('show');
		});

		$('#save_new_password').click(function(e) {
			e.preventDefault();

			if ( that.validatePasswords() ) {
				that.saveNewPassword();
			} else {
				return false;
			}
		});
	}
});