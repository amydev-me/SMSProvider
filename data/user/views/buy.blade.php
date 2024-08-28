@extends('layouts.user-master')

@section('title', 'Pircing')
@section('pricing','active')

@section('style')
<link href="{{ asset('css/pricing.min.css' )}}" media="all" rel="stylesheet" type="text/css"/>
<style>
	#generic_price_table{
		background-color: transparent;
	}

	@media (min-width: 576px) and (max-width: 1199px){
		.pushMargin {
			margin-left: 117px;
		}
	}

	.cl_title_line {
		background: url("/img/title_line10.png") center top no-repeat;
	}

	.cl_title_line {
		height: 3px;
		padding: 0px;
		margin: 15px auto 20px;
		text-align: center;
		background: url('/img/title_line10.png') center top no-repeat;
	}
</style>
@endsection

@section('content')
<buy inline-template>
	<div id="generic_price_table">
		<section>
			<div class="container">
				<div class="row">
					<div class="col-md-12">
						<div class="price-heading clearfix">
							<h1>Affordable & Reliable Pricing</h1>
							<div class="cl_title_line"></div>
						</div>
					</div>
				</div>
			</div>

			<div class="container">
				<div class="row price-list">

					@foreach($packages as $package)

					<div class="col-sm-4 col-lg-2">
						<div class="package_container">
							<div class="container">
								<div class="package">
									@if (count($package->promotions) == 1 && $package->remaining_promo > 0)

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
										<span class="price">{{ number_format($package->cost) }}</span><br/>
										<span class="price_unit">{{ $package->packageName == 'Free' ? '-' : 'MMK' }}</span>
									</div>

									<hr/>

									<div class="package_credit">
										{{ number_format($package->credit) }}<br>
										<span class="price_unit">Credit</span>
									</div>

									@if (count($package->promotions) == 1 && $package->remaining_promo > 0)

									<div class="extra_credit">
										<div class="sm_divider"></div>
										<span class="extra_no">+{{ $package->promotions[0]->promo_credit }}</span><br><span class="extra_unit">Credit</span>
										<div class="sm_divider"></div>
									</div>

									@else

									<div class="divider"></div>

									@endif

									<div class="package_btn">
										<a href="{{ route('user-checkout', ['package_id' => $package->id]) }}"><button>Order Now</button></a>
									</div>
								</div>
							</div>
						</div>
					</div>

					@endforeach

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

					<br/>

					<div class="row">
						<div class="col-sm-12 text-center">
							<vue-pagination :length.number="pagination.last_page" v-model="pagination.current_page" @input="filter"></vue-pagination>
						</div>
					</div>
				</div>
			</div>
		</section>
	</div>
</buy>
@endsection