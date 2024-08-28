module.exports = {
	data: function() {
		return {
			message: {
				subject: null,
				text: null,
			},

			loading: false
		}
	},

	methods: {
		validateData() {
			if (tinyMCE.get('text').getBody().textContent != '') {
				this.message.text = tinyMCE.get('text').getContent();
			} else {
				this.message.text = null;
			}

			this.$validator.validateAll().then(successsValidate => {
				if (successsValidate) {
					this.submit();
				}
			}).catch(error => {
				console.log(error);
			});
		},

		submit() {
			this.loading = true;

			axios.post('/admin/newsletter/send', this.message).then(({data}) => {
				if (data.status == true) {
					window.location.reload();
				} else {
					Notification.error(data.message);
				}

				this.loading = false;
			}).catch(error => {
				if (error.response.status == 401 || error.response.status == 419) {
					window.location.href = '/admin/login';
				} else {
					Notification.error('Error occured while creating data.');
				}

				this.loading = false;
			});
		}
	},

	mounted() {
		tinymce.init({
			selector: "textarea",
			height: 300,
			plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen link template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
			toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
		});
	}
}