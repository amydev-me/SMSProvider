@extends('admin-layouts.company')

@section('title', 'Terms & Conditions')
@section('terms', 'active')

@section('stylesheet')
<link rel="stylesheet" type="text/css" href="{{ asset('css/pretty-checkbox.min.css') }}">

<style>
	@media (max-width: 330px) {
		.col {
			max-width: 34%;
		}
	}

	.breadcrumb {
		padding: 8px 10px;
		margin-bottom: 0px;
	}
</style>
@endsection

@section('content')
<div class="row form-inline">
	<div class="col">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb float-right">
				<li class="breadcrumb-item mb-0" aria-current="page"><a href="{{ route('admin.terms') }}">Terms & Conditions</a></li>
				<li class="breadcrumb-item active mb-0" aria-current="page">Update</li>
			</ol>
		</nav>
	</div>
</div>

<div class="top-campaign mt-3">
	<form role="form" method="POST" action="{{ route('admin.terms.update') }}" autocomplete="off">
		{{ csrf_field() }}

		<div class="form-group">
			<label>Terms <span class="text-danger">*</span></label>

			<textarea class="mceEditor" name="text">{{ $term ? old('text', $term->text) : '' }}</textarea>
			<div class="text-danger">{{ $errors->post->first('text') }}</div>
		</div>

		<div class="form-group">
			<label>Expire Date <small> - If expire date is added, users need to accept terms before expiration date or their services will be stopped.</small></label>

			<input type="text" class="form-control" name="expire_at" id="expire_at">
			<div class="text-danger">{{ $errors->post->first('expire_at') }}</div>
		</div>

		<div class="form-group">
			<div class="pretty p-svg p-smooth p-bigger" style="margin-right: 10px;">
				<input type="checkbox" name="email" value="checked" id="checkbox"/>

				<div class="state p-primary">
					<svg class="svg svg-icon" viewBox="0 0 20 20">
						<path d="M7.629,14.566c0.125,0.125,0.291,0.188,0.456,0.188c0.164,0,0.329-0.062,0.456-0.188l8.219-8.221c0.252-0.252,0.252-0.659,0-0.911c-0.252-0.252-0.659-0.252-0.911,0l-7.764,7.763L4.152,9.267c-0.252-0.251-0.66-0.251-0.911,0c-0.252,0.252-0.252,0.66,0,0.911L7.629,14.566z" style="stroke: white; fill: white;"></path>
					</svg>
					<label>Email Notify</label>
				</div>
			</div>
		</div>

		<div class="form-group email-group" style="display: none;">
			<label>Email Subject</label>

			<input type="text" class="form-control" name="subject">
			<div class="text-danger">{{ $errors->post->first('subject') }}</div>
		</div>

		<div class="form-group email-group" style="display: none;">
			<label>Email Body</label>

			<textarea class="mceEditor" name="body">{{ $term ? old('body', $term->body) : '' }}</textarea>
			<div class="text-danger">{{ $errors->post->first('body') }}</div>
		</div>

		<a href="{{ route('admin.terms') }}" class="btn btn-secondary">Back</a>
		<button type="submit" class="btn btn-primary" id="submit">Save</button>
	</form>
</div>
@endsection

@section('script')
<script src="{{ asset('tinymce/tinymce.min.js') }}"></script>

<script>
	tinymce.init({
		selector: "textarea",
		height: 300,
		plugins: 'print preview fullpage searchreplace autolink directionality visualblocks visualchars fullscreen link template codesample table charmap hr pagebreak nonbreaking anchor toc insertdatetime advlist lists textcolor wordcount imagetools contextmenu colorpicker textpattern help',
		toolbar: 'formatselect | bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat',
	});

	$('#expire_at').datepicker({
		dateFormat: "yy-mm-dd"
	});

	$('#checkbox').click(function() {
		if (document.getElementById("checkbox").checked) {
			$('.email-group').show();
		} else {
			$('.email-group').hide();
		}
	});

	@if ($errors->post->first('subject'))
		$('#checkbox').prop('checked', true);
		$('.email-group').show();
	@endif

	$('#submit').click(function() {
		$(this).css('pointer-events', 'none');
	});
</script>
@endsection