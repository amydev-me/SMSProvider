<div class="hero">
	<div class="img2">
		<div class="row" style="height: 100%; flex-direction: row-reverse">
			<div class="col">
				<div style="z-index: 2; text-align: center">
					<h1 style="color: white;" class="heroTitle">Bulk SMS Gateway for Business</h1>
					<p style="color: white;" class="heroContent">
						TripleSMS is one Of bulk SMS service providers in Myanmar.
					</p>
					<p class="pb-3" style="color: white;" class="heroContent">
						Compose and send your text messages online with TripleSMS.
					</p>

					@guest
						<a title="Create an Account" href="{{route('register')}}" class="btn btn-lg btn-pink heroBtn"><b class="tryNowBtn">Try Now</b></a>
					@else
						<a title="Pricing" href="{{route('buy')}}" class="btn btn-lg btn-pink"><b class="buyNowBtn">Buy Now</b></a>
					@endguest
				</div>
			</div>
		</div>
	</div>
</div>