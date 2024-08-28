<?php
namespace Admin\Http\Controllers;

use Admin\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Mail;
use PDF;

use App\Models\PaygInvoice;
use App\Models\User;

class PaygOrderController extends Controller
{
	public function getOrders()
	{
		$orders = PaygInvoice::with('user')
							->whereHas('user', function($query) {
								$query->where('sms_type', 'PAYG');
							})
							->orderBy('id', 'desc')
							->paginate(10);
		return response()->json($orders);
	}

	public function filter(Request $request)
	{
		$search = $request->search;

		$orders = PaygInvoice::with('user')
							->whereHas('user', function($query) {
								$query->where('sms_type', 'PAYG');
							});

		if ($search != NULL || $search != '') {
			$orders->where('id', 'LIKE', '%' . $search . '%')
				->orWhere('invoice_no', 'LIKE', '%' . $search . '%')
				->orWhere('cost', 'LIKE', '%' . $search . '%')
				->orWhere('total_credit', 'LIKE', '%' . $search . '%')
				->orWhereHas('user', function($query) use ($search) {
					$query->where('username', 'LIKE', '%' . $search . '%');
				});
		}

		if ($request->status != 'all') {
			$orders->where('status', $request->status);
		}

		if ($request->invoice_date != NULL) {
			$orders->whereDate('invoice_date', Carbon::parse($request->invoice_date)->format('Y-m-d'));
		}

		return response()->json($orders->orderBy('id', 'desc')->paginate($request->page_size));
	}

	public function updateOrder(Request $request)
	{
		$order = PaygInvoice::where('id', $request->order_id)->first();

		if ($order) {
			$order->update(['status' => $request->status]);

			if ($request->status == 'paid') {
				$order->user()->update(['account_type' => 'Premium']);
				$order->update(['payment_date' => Carbon::now()]);
			}

			$this->sendOrderEmail($order);

			return response()->json(['status' => true], 200);
		}

		return response()->json(['status' => false, 'message' => "Can't access data."], 403);
	}

	private function sendOrderEmail($invoice)
	{
		if ($invoice->status == 'paid') {
			$subject = 'Your payment of MMK ' . $invoice->cost . ' is received.';
			$template = 'admin-mail.payg-blank';
			$email = $invoice->user->email;

			$filepath = storage_path() . '/app/tmp/';
			$filename = $filepath . $invoice->invoice_no . '.pdf';
			PDF::loadView('admin-mail.invoice', compact('invoice'))->setPaper('a5')->save($filename);

			Mail::send($template, compact('invoice'), function($message) use ($email, $subject, $filename) {
				$message->to($email)
						->subject($subject)
						->attach($filename);
			});

			unlink($filename);
		}
	}
}