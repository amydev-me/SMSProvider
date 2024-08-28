@extends('layouts.app-master')

@section('title', "Confirm Order | TripleSMS")

@section('style')
	<link href="{{ URL::asset('css/animate.css') }}" rel="stylesheet">

	<style>
		.font-light {
			font-weight: 300;
		}

		.ex-page-content h1 {
			font-size: 150px;
			line-height: 150px;
			font-weight: 700;
			color: green;
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
		<div class="ex-page-content animated fadeInDown text-center">
			<h1><i class="fas fa-check-circle"></i></h1>
			<h2 class="font-light">Thank you!</h2><br>
			<p>YOUR ORDER HAS BEEN RECEIVED</p>
			<p>We will be contacting you soon!</p>
			<a class="btn btn-purple m-t-20" href="{{route('dashboard.index')}}"><i class="fa fa-angle-left"></i> Back to Dashboard</a>
		</div>
	</div>
@endsection