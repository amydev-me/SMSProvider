@extends('layouts.app-master')

@section('documentation', 'active')
@section('description', "API Documentation for Developers.")
@section('title', "Documentation | TripleSMS")

@section('style')
<style>
	h1 {
		margin-top: 5rem;
	}

	h2, h4 {
		margin-top: .5rem;
		margin-bottom: .3rem;
	}

	pre {
		font: 12px / 18px Consolas,monospace,serif;
		background: #263238;
		color: #bde052;
		overflow: auto;
		-moz-tab-size: 4;
		-o-tab-size: 4;
		tab-size: 4;
	}

	.container.user {
		margin-top: 0px;
	}
</style>
@endsection

@section('content')
<h1 class="text-center">API Documentation</h1>

<div class="container user">
	<h2>Send Messages</h2>

	URL:
	<pre><code>
		POST https://triplesms.com/api/v1/message
	</code></pre>

	<h4>HTTP Headers</h4>
	All API methods expect requests to supply a Content-Type header with the value application/json. All responses will have the Content-Type header set to application/json.
	<br/><br/>

	<p>Probably the most simple example:</p>
	<pre><code>
		{
			"to": "+959xxxxxxxx",
			"body": "Hello World!"
		}
	</code></pre>

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

	<p>A message, to multiple recipients</p>
	<pre><code>
		{
			"to": ["+959xxxxxxxx", "+959xxxxxxxx", "+959xxxxxxxx"],
			"body": "မဂၤလာပါ"
		}
	</code></pre>

	<p>200 An array of the messages that were created from the request</p>
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

	<h3>HTTP response codes</h3>

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
				<td>Invalid request</td>
				<td>A request containing invalid parameters or invalid data.</td>
			</tr>

			<tr>
				<td>02</td>
				<td>Not Allowed</td>
				<td>Operator only allowed 6SMSs.</td>
			</tr>

			<tr>
				<td>03</td>
				<td>Insufficient balance</td>
				<td>You need to purchase more SMS before you send message.</td>
			</tr>

			<tr>
				<td>04</td>
				<td>Account Verification</td>
				<td>Verify your email address first.</td>
			</tr>
		</tbody>
	</table>

	<h4>Get all contacts</h4>
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

	<h4>Get all messages</h4>
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