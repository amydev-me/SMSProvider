@extends('layouts.user-master')

@section('title', 'Resend Mail')

@section('style')
<link href="{{URL::asset('css/animate.css')}}" rel="stylesheet">

<style>
	.font-light {
		font-weight: 300;
	}

	.ex-page-content h1 {
		font-size: 150px;
		line-height: 150px;
		font-weight: 700;
		color: #252932;
		text-shadow: rgba(61, 61, 61, 0.3) 1px 1px, rgba(61, 61, 61, 0.2) 2px 2px, rgba(61, 61, 61, 0.3) 3px 3px;
	}

	.wrapper-page {
		width: 380px;
		margin: 200px auto 200px;
	}
</style>
@endsection

@section('content')
<div class="wrapper-page animated fadeInDown">
	<div class="ex-page-content animated flipInX text-center">

		<h2 class="font-light">{{ $message }}</h2><br>
	</div>
</div>
@endsection