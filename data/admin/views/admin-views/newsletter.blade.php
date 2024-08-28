@extends('admin-layouts.company')

@section('title', 'Newsletter')
@section('newsletter', 'active')

@section('stylesheet')
<style>
	.fr-box {
		margin-bottom: 1rem;
	}

	[v-cloak] > * { display:none; }
	[v-cloak]::before { content: "Loading..."; }
</style>

<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>

@endsection

@section('content')

<newsletter inline-template>
	<div v-cloak>
		<div class="row form-inline">
			<div class="col">
				<nav aria-label="breadcrumb">
					<ol class="breadcrumb float-right">
						<li class="breadcrumb-item active" aria-current="page">Newsletter</li>
					</ol>
				</nav>
			</div>
		</div>

		<form role="form" @submit.prevent="validateData" autocomplete="off">
			<div class="form-group">
				<label>Subject</label>
				<input class="form-control" placeholder="Email Subject" name="subject" v-model="message.subject" v-validate="'required'"/>

				<div class="text-danger" v-if="errors.has('subject')">@{{ errors.first('subject') }}</div>
			</div>

			<div class="form-group">
				<label>Body</label>
				<textarea class="mceEditor" id="text"></textarea>

				<input type="hidden" name="text" v-model="message.text" v-validate="'required'"/>
				<div class="text-danger" v-if="errors.has('text')">@{{ errors.first('text') }}</div>
			</div>

			<button type="submit" class="btn btn-primary"><i v-if="loading" class="fas fa-spinner fa-spin"></i> Send</button>
		</form>
	</div>
</newsletter>

@endsection