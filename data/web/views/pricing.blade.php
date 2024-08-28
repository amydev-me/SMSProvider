@extends('layouts.app-master')

@section('pricing', 'active')
@section('title', "Pricing | TripleSMS")
@section('description', "Buy Affordable SMS Packages at TripleSMS for your Business Communications.")

@section('style')
<link href="{{ asset('css/pricing.min.css' )}}" media="all" rel="stylesheet" type="text/css"/>

<style>
.pagination__navigation--disabled {
	opacity: .6;
	pointer-events: none;
}

.pagination__more {
	pointer-events: none;
}
</style>
@endsection

@section('content')
<div class="slantedDivA">
	<h1 class="title">Affordable & Reliable Pricing</h1>

	<hr style="border-top: 1px solid white;"/>

	<div style="text-align: center;">
		TripleSMS offers the most reliable and cheapest SMS packages for our customers.<br/>
		See prices for the countries we support below.
	</div>
</div>

<pricing inline-template>
	<div class="container all_container">
		<div class="white_card">
			<div class="row">

				@foreach($packages as $package)

				<div class="col-sm-4 col-lg-2">
					<div class="package_container">
						<div class="container">
							<div class="package">
								@if (count($package->promotions) == 1)

								<div class="ribbon ribbon-top-left">
									<span>
										{{ $package->promotions[0]->promo_status }}
									</span>
								</div>

								@endif

								<div class="circle"></div>

								<div class="package_name">
									{{ $package->packageName }}
								</div>

								<div class="package_price">
									<span class="price">{{ $package->packageName == 'Free' ? 'Free' : number_format($package->cost) }}</span><br/>
									<span class="price_unit">{{ $package->packageName == 'Free' ? '-' : 'MMK' }}</span>
								</div>

								<hr/>

								<div class="package_credit">
									{{ number_format($package->credit) }}<br>
									<span class="price_unit">Credit</span>
								</div>

								@if (count($package->promotions) == 1)

								<div class="extra_credit">
									<div class="sm_divider"></div>
									<span class="extra_no">+{{ $package->promotions[0]->promo_credit }}</span><br><span class="extra_unit">Credit</span>
									<div class="sm_divider"></div>
								</div>

								@else

								<div class="divider"></div>

								@endif

								<div class="package_btn">
									@if ($package->packageName == 'Free')
										<a title="Create an Account" href="{{ route('register') }}"><button>Try Free</button></a>
									@else
										@if(Auth::user())
											<a href="{{ route('checkout',['package_id'=>$package->id]) }}"><button>Order Now</button></a>
										@else
											<a href="{{ route('login') }}"><button>Order Now</button></a>
										@endif
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>

				@endforeach

			</div>
		</div>

		<div class="table_title_container">
			<div class="table_title">
				We also offer international SMS
			</div>

			<hr/>

			<div class="table_title_content">
				The following table shows the prices of each country.
			</div>
		</div>

		<div class="table_container">
			<div class="container">
				<div class="form-group float-left">
					<select v-model="page_size" class="form-control" @change="searchClick">
						<option value="2">2</option>
						<option value="5">5</option>
						<option value="10">10</option>
						<option value="25">25</option>
						<option value="50">50</option>
					</select>
				</div>

				<div class="form-group search-container float-right">
					<div class="input-group">
						<input class="form-control" type="text" placeholder="Search" v-model="search" v-on:keyup.enter="searchClick"/>

						<button type="submit" @click="searchClick">
							<span class="fa fa-search"></span>
						</button>
					</div>
				</div>

				<table id="customers">
					<thead>
						<tr class="table_header">
							<th>Country</th>
							<th>Operator</th>
							<th>Credit</th>
						</tr>
					</thead>

					<tbody v-cloak>
						<tr v-for="country in countries">
							<td>@{{ country.name }}</td>

							<td style="padding: 0px">
								<div class="list-group list-group-flush">
									<div class="list-group-item" v-for="operator in country.operators">@{{ operator.name }}</div>
								</div>
							</td>

							<td>@{{ country.rate }}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<br/>

		<div class="row">
			<div class="col-sm-12 text-center">
				<vue-pagination :length.number="pagination.last_page" v-model="pagination.current_page" @input="filter"></vue-pagination>
			</div>
		</div>

		<br/>
	</div>
</pricing>
@endsection