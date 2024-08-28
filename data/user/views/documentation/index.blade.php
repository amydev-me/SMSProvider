@extends('layouts.user-master')

@section('title', 'Documentation')
@section('documentation','active')

@section('style')
<style>
	pre {
		font: 12px / 18px Consolas, monospace, serif;
		background: #263238;
		color: #bde052;
		overflow: auto;
		-moz-tab-size: 4;
		-o-tab-size: 4;
		tab-size: 4;
	}

	h2, h4 {
		margin-top: .5rem;
		margin-bottom: .3rem;
	}

	.container.user {
		margin-top: 0px;
	}
</style>
@endsection

@section('content')
<h2 class="center" style="line-height: 1.7;">TripleSMS REST API</h2>

<div class="container user">
	<h2>Send Messages</h2>

	URL:
	<pre><code>
		POST https://triplesms.com/api/v1/message
	</code></pre>

	<h4>HTTP Headers</h4>
	You can get your API key <a href="https://triplesms.com/rest_api/keys" target="_blank">Here</a>.
	<pre><code>
		Content-Type: application/json,
		Authorization: Bearer { your API key },
	</code></pre>

	<h4>Request Parameters</h4>
	You can register your sender ID by sending your email to info@triplesms.com.
	<pre><code>
		{
			"sender": "Info",  // Optional
			"to": "+959xxxxxxxx",
			"body": "Hello World!"
		}
	</code></pre>

	<h4>Response Parameters</h4>
	<pre><code>
		{
			"code": 200,
			"message": "Success",
			"description": "Success",
			"status": "success",
			"batchId": "1531800242",
			"encoding": "Plain Text",
			"message_content": "Hello World!",
			"total_characters": 21,
			"message_parts": 1,
			"total_sms": 1,
			"source": "Api",
			"data": [
				{
					"messageId": "c1ec23d6-792b-11e8-a7e4-b06ebf2cef50",
					"recipient": "+959xxxxxxxx",
					"operator": "MPT",
					"status": "Delivered",
					"send_at": "14 Jun 2018 17:01",
				}
			]
		}
	</code></pre>

	<p>A message, to multiple recipients:</p>
	<pre><code>
		{
			"sender": "Info",  // Optional
			"to": ["+959xxxxxxxx", "+959xxxxxxxx", "+959xxxxxxxx"],
			"body": "မဂၤလာပါ"
		}
	</code></pre>

	<p>Response:</p>
	<pre><code>
		{
			"code": 200,
			"message": "Success",
			"description": "Success",
			"status": "success",
			"batchId": "1531800242",
			"encoding": "Plain Text",
			"message_content": "Hello World!",
			"total_characters": 21,
			"message_parts": 1,
			"total_sms": 3,
			"source": "Api",
			"data": [
				{
					"messageId": "c1ec23d6-792b-11e8-a7e4-b06ebf2cef51",
					"recipient": "+959xxxxxxxx",
					"operator": "MPT",
					"status": "Delivered",
					"send_at": "14 Jun 2018 17:01",
				},
				{
					"messageId": "c1ec23d6-792b-11e8-a7e4-b06ebf2cef52",
					"recipient": "+959xxxxxxxx",
					"operator": "MPT",
					"status": "Delivered",
					"send_at": "14 Jun 2018 17:01",
				},
				{
					"messageId": "c1ec23d6-792b-11e8-a7e4-b06ebf2cef53",
					"recipient": "+959xxxxxxxx",
					"operator": "MPT",
					"status": "Delivered",
					"send_at": "14 Jun 2018 17:01",
				}
			]
		}
	</code></pre>

	<h2>HTTP response codes</h2>

	<table class="table table-top-campaign  table-hover">
		<thead>
			<tr>
				<th scope="col">Code</th>
				<th scope="col">Message</th>
				<th scope="col">Description</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td>401</td>
				<td>Unauthorized</td>
				<td>Unauthorized Token is invalid.</td>
			</tr>

			<tr>
				<td>01</td>
				<td>Invalid Request</td>
				<td>A request containing invalid parameters or invalid data.</td>
			</tr>

			<tr>
				<td>02</td>
				<td>Not Allowed</td>
				<td>Operator only allowed 6SMSs.</td>
			</tr>

			<tr>
				<td>03</td>
				<td>Insufficient  Balance</td>
				<td>You need to purchase more Packages before you can create message.</td>
			</tr>

			<tr>
				<td>04</td>
				<td>Account Verification</td>
				<td>Verify your email address first.</td>
			</tr>
		</tbody>
	</table>

	<h2>Get all Contacts</h2>

	<p>To retrieve all contacts, use:</p>

	<pre><code>
		GET https://triplesms.com/api/v1/contacts
	</code></pre>

	<table class="table table-top-campaign  table-hover">
		<thead>
			<tr>
				<th scope="col">Name</th>
				<th scope="col">Required?</th>
				<th scope="col">Example</th>
				<th scope="col">Description</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td>page</td>
				<td>No</td>
				<td>3</td>
				<td>Fetch the specified results page. The default is 1.</td>
			</tr>

			<tr>
				<td>limit</td>
				<td>No</td>
				<td>25</td>
				<td>The number of results per page. The default is 10.</td>
			</tr>
		</tbody>
	</table>

	<h2>Get all Messages</h2>

	<p>To retrieve all messages, use:</p>

	<pre><code>
		GET https://triplesms.com/api/v1/messages
	</code></pre>

	<table class="table table-top-campaign  table-hover">
		<thead>
			<tr>
				<th scope="col">Name</th>
				<th scope="col">Required?</th>
				<th scope="col">Example</th>
				<th scope="col">Description</th>
			</tr>
		</thead>

		<tbody>
			<tr>
				<td>page</td>
				<td>No</td>
				<td>3</td>
				<td>Fetch the specified results page. The default is 1.</td>
			</tr>
			
			<tr>
				<td>limit</td>
				<td>No</td>
				<td>25</td>
				<td>The number of results per page. The default is 10.</td>
			</tr>
		</tbody>
	</table>

	<h2>Check Balance</h2>

	<p>To check remaining balance, use:</p>

	<pre><code>
		GET https://triplesms.com/api/v1/balance
	</code></pre>
</div>
@endsection