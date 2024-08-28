@extends('admin-layouts.company')

@section('title', 'Terms & Conditions')
@section('terms', 'active')

@section('stylesheet')
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

	h4 {
		margin-top: 2rem;
	}

	ul, ol {
		padding-left: 50px;
	}

	ol li {
		margin-bottom: 0.5rem;
	}
</style>
@endsection

@section('content')
<div class="row form-inline">
	<div class="col-auto">
		<a href="{{ route('admin.terms.edit') }}" class="btn btn-primary">
			<i class="fas fa-edit"></i>&nbsp; Update
		</a>
	</div>

	<div class="col">
		<nav aria-label="breadcrumb">
			<ol class="breadcrumb float-right">
				<li class="breadcrumb-item active mb-0" aria-current="page">Terms & Conditions</li>
			</ol>
		</nav>
	</div>
</div>

<div class="top-campaign mt-3">
	@if ($term)

		@if ($term->expire_at != NULL)

		<p class="text-right">Expire Date to Accept Terms - <strong>{{ \Carbon\Carbon::parse($term->expire_at)->format('d M Y') }}</strong></p>

		@endif

	{!! $term->text !!}

	@endif
</div>
@endsection