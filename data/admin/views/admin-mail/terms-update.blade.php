<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<!-- We have updated our terms & conditions - availble <a href="https://triplesms.com/terms">Here</a>.
	<br/><br/>

	@if ($request['expire_at'] != NULL)

	Please accept Terms & Conditions before <strong>{{ \Carbon\Carbon::parse($request['expire_at'])->format('d M Y') }}</strong> or your services will be stopped until you accept updated Terms & Conditions.

	@endif -->

	{!! $request['body'] !!}
</body>
</html>