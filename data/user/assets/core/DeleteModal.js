module.exports = {
	props:
		{
			message: {
				default:'Are you sure to delete?'
			},

			id: {
				default: 'deleteModal',
			},

			inputid: {
				default: null
			},

			inputurl: {
				default: null
			}
		}
	,

	template: `
		<div class="modal fade" :id="id" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
			<div class="modal-dialog modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLongTitle">Confirm Delete</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<span aria-hidden="true">&times;</span>
						</button>
					</div>

					<div class="modal-body">
						{{this.message}}
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-light" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-primary" v-on:click.prevent="performdelete">Delete</button>
					</div>
				</div>
			</div>
		</div>`,

	methods: {
		performdelete() {
			axios.get(this.inputurl + this.inputid).then(response => {
				if (response.data.status) {
					this.$emit('input');
				} else {
					Notification.error('Opps!Something went wrong.');
				}
			}).catch(error => {
				if (error.response.status == 401 || error.response.status == 419) {
					// window.location.href = '/login';
				} else {

				}
			});
		}
	}
}