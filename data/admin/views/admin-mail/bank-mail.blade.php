<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<div class="flex-center position-ref full-height">
	<div class="content">
		<p>Thank you for using our TripleSMS gateway service. Your order has been confirmed.</p><br/>

		You can payable to the following bank accounts.<br/><br/>
		Please reply to this email with bank slip. (OR)<br/>
		If you pay with mobile banking, kindly attach the screenshot of payment.
		<br/><br/>

		Package Name - <strong>{{ $invoice->package->packageName }}</strong><br/>
		Amount - <strong>{{ number_format($invoice->package->cost) }} MMK</strong>
		<br/><br/>

		Account Name - <strong>HEIN CHIT HLEIN</strong>
		<br/><br/>

		Bank Name - <strong>KBZ</strong><br/>
		Account No. - <strong>9993 0706 6015 97201</strong>
		<br/><br/>

		Bank Name - <strong>CB</strong><br/>
		Account No. - <strong>0031 6005 0005 6968</strong>
		<br/><br/>

		Bank Name - <strong>AYA</strong><br/>
		Account No. - <strong>0104 2010 1008 4134</strong>
		<br/><br/>

		Bank Name - <strong>Yoma</strong><br/>
		Account No. - <strong>005 410 140 001 250</strong>
		<br/><br/>

		Bank Name - <strong>AGD</strong><br/>
		Account No. - <strong>6234 3301 0083 7372</strong>
	</div>
</div>
</body>
</html>