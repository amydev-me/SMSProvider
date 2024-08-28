@extends('admin-layouts.company')

@section('title', 'Compose')
@section('compose', 'active')

@section('stylesheet')
<style>
	.dropdown li {
		border-bottom: 1px solid rgba(112, 128, 144, 0.1)
	}

	.dropdown li:last-child {
		border-bottom: none;
	}

	.dropdown li a {
		padding: 10px 20px;
		display: flex;
		width: 100%;
		align-items: center;
		font-size: 1.25em;
	}

	.dropdown li a .fa {
		padding-right: 0.5em;
	}

	.vue-tags-input {
		max-width: 100% !important;
		position: relative;
		background-color: #fff;
	}

	.tag {
		background-color: #007bff !important;
	}

	.tags-input .fa-address-book:before {
		content: "\f2b9";
		font-size: 18px;
	}

	.tags-input .input {
		min-height: 44px;
	}

	.tags-input .tag.tag {
		background-color: #F2F2F2;
		color: white;
	}

	.tags-input .tag-right {
		margin-right: 2px;
		width: 24px;
	}

	.tags-input .my-item, .tags-input  .my-tag-right {
		align-items: center;
	}

	.tags-input  .my-item i {
		margin-right: 5px;
	}

	.tags-input ul {
		float: left;
		width: 100%;
		max-height: 150px !important;
		min-height: 15px !important;
		overflow-y: auto;
	}

	.tags-input ul::-webkit-scrollbar-track {
		border: 1px solid #9e9e9e;
		padding: 2px 0;
		background-color: #9e9e9e;
	}

	.tags-input ul::-webkit-scrollbar {
		width: 5px;
	}

	.tags-input ul::-webkit-scrollbar-thumb {
		border-radius: 10px;
		box-shadow: inset 0 0 6px rgba(0,0,0,.3);
		background-color: #fafafa;
		border: 1px solid #9e9e9e;
	}

	@media (max-width: 373px) {
		.scheduleBtn{
			margin-left: 80px;
			margin-bottom: 10px;
		}
	}
</style>
@endsection

@section('content')
<compose inline-template>
	<div class="row" v-cloak>
		<div class="col">
			<div class="card">
				<div class="card-header">
					<h5 class="mb-0">New Text Message</h5>
				</div>

				<div class="card-body">
					<form role="form" @submit.prevent="validateData">
						<div class="row">
							<div class="form-group col-auto">
								<label>Sender Name</label>
								<input type="text" name="sender" v-model="sender_name" class="form-control">
							</div>
						</div>

						<div class="form-group">
							<label>To</label>

							<vue-tags-input v-model="tag" :autocomplete-items="autocompleteItems" :tags="tags" @tags-changed="update" @before-adding-tag="validateTag" name="recipient">
								<div slot="autocompleteItem" class="my-item" slot-scope="props" @click="props.performAdd(props.item)">
									<i class="fas fa-address-book">
										@{{ props.item.text }}
									</i>
									(@{{ props.item.label }})
								</div>

								<div slot="tagRight" class="my-tag-right" slot-scope="props" @click="props.performOpenEdit(props.index)">
									<span v-if="props.tag.label != null"> (@{{ props.tag.label }})</span>
								</div>
							</vue-tags-input>

							<div class="text-danger" v-if="errors.has('recipient')">@{{ errors.first('recipient') }}</div>
						</div>

						<div class="form-group">
							<label>Message</label>

							<textarea class="form-control" placeholder="Type Your Message Here" name="msg" @input="calculateSmsLength($event.target.value)" v-model="body" v-validate="'required'"></textarea>

							<div class="text-danger" v-if="errors.has('msg')">Please enter a message.</div>

							<small v-if="count.text != ''">@{{ count.length }} characters used
								<span> ,@{{ count.remaining }} left</span> | Parts:<span :class="count.messages>6 ? 'text-danger' : 'text-default'">@{{ count.messages }}/6</span>

								<div class="badge badge-orange" v-if="count.messages>1">@{{ count.messages }} SMSs</div>
								<div class="badge badge-orange" v-if="count.char_type != ''">Unicode</div>
								<div class="badge badge-orange" v-if="count.messages>1"> Long Message</div>
								<span class="text-success">| Total: @{{ totalvalue }}</span>
							</small>
						</div>

						<div class="form-group" v-if="schedule">
							<label>Timezone</label>
							<multiselect
								placeholder="Select Timezone"
								label="city"
								name="timezone"
								track-by="timezone"
								data-vv-name="timezone"
								v-model="selected_timezone"
								v-validate="'required'"
								:options="timezones">
							</multiselect>

							<div class="text-danger" v-if="errors.has('timezone')">Select Timezone</div>
						</div>

						<div class="form-group" v-if="schedule">
							<label>Schedule Message</label>
							<date-picker name="date" v-model="date" :config="options"></date-picker>
						</div>

						<div class="row">
							<div class="col">
								<div class="float-left scheduleBtn">
									<button type="button" class="btn btn-light btn-sm" v-on:click="toggleSchedule">
										<i class="fa fa-calendar"></i>&nbsp; @{{ scheduleMessage }}
									</button>
								</div>

								<div class="float-right">
									<button type="submit" class="btn btn-primary btn-sm" :disabled="tags.length <= 0">
										<i v-if="loading" class="fas fa-spinner fa-spin"></i>
										<i v-if="loading == false" class="fa" :class="sendIcon"></i>&nbsp;@{{ send }}
									</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</compose>
@endsection