@extends('layouts.app-master')

@section('title', 'Checkout')

@section('content')
	<div class="page-content--bge5">
		<div class="container">
			<div class="row">
				<div class="col-sm-12">
					<div class="card" style="margin-top: 40px;">
						<div class="card-body">
							<h3>Please Confirm Your Purchase</h3>

							<div class="outer-table-wrap">
								<div class="inner-table-wrap">
									<table class="table table-hover">
										<thead>
											<tr>
												<th class="col col-sm-4">Package</th>
												<th class="col col-sm-3 text-right">Total SMS</th>
												<th class="col col-sm-3 text-right">Price</th>
											</tr>
										</thead>

										<tbody>
											<tr>
												<td class="">
													<div class="media">
														<div class="media-body">
															<p class="media-heading"><a href="#">{{$package->packageName}}</a></p>
															<p class="media-heading"> </p>
															<span> </span><span class="text-warning"></span>
														</div>
													</div>
												</td>

												<td class="text-right"><strong>{{number_format($package->total_sms)}} SMSs</strong></td>

												<td class="text-right"><strong> {{number_format($package->cost)}} </strong></td>
											</tr>
										</tbody>

										<tfoot>
											<tr>
												<td> &nbsp; </td>
												<td class="text-right"><h3>Total</h3></td>
												<td class="text-right"><h3>MMK {{number_format($package->cost)}} </h3></td>
											</tr>

											<tr>
												<td> &nbsp; </td>

												<td class="text-right">
													<button type="button" class="btn btn-light">
													   Cancel
													</button>
												</td>

												<td class="text-right">
													<form action="{{route('order')}}" method="post">
														@csrf

														<input type="hidden" name="package_id" value="{{$package->id}}">
														<button type="submit" class="btn btn-success">
															Checkout
														</button>
													</form>
												</td>
											</tr>
										</tfoot>
									</table>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

@endsection