<?php
namespace User\Http\Controllers;

use App\Models\PaygInvoice;
use App\Models\UserPackage;
use App\Models\UserCredit;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;

class InvoiceController extends Controller
{
	public function getInvoiceList()
	{
		if (Auth::guard('web')->user()->sms_type == 'Package') {
			$invoices = UserPackage::with('package')
									->where('user_id', Auth::guard('web')->user()->id)
									->where('status', 'paid')
									->whereHas('package', function($q) {
										$q->where('packageName', '<>', 'Free');
									})
									->orderByDesc('order_date')
									->get();
		} elseif (Auth::guard('web')->user()->sms_type == 'PAYG') {
			$invoices = PaygInvoice::where('user_id', Auth::guard('web')->user()->id)
									->orderByDesc('invoice_date')
									->get();
		} else {			
			$invoices = UserPackage::where('user_id', Auth::guard('web')->user()->id)
									->where('package_id', NULL)
									->where('total_usd', '>', 0)
									->where('status', 'paid')
									->orderByDesc('order_date')
									->get();
		}

		return view('invoice-list', compact('invoices'));
	}

	public function downloadInvoice($invoice_no)
	{
		$filepath = storage_path() . '/app/tmp/';

		$invoice = UserPackage::with('package')
								->where('user_id', Auth::guard('web')->user()->id)
								->where('invoice_no', $invoice_no)
								->where('status', 'paid')
								->first();

		$filename = $filepath . $invoice->invoice_no . '.pdf';

		if ($invoice) {
			PDF::loadView('invoice', compact('invoice'))->setPaper('a5')->save($filename);
			return response()->download($filename)->deleteFileAfterSend(true);
		}
	}

	public function downloadPaygInvoice($invoice_no)
	{
		$filepath = storage_path() . '/app/tmp/';

		$invoice = PaygInvoice::where('user_id', Auth::guard('web')->user()->id)
							->where('invoice_no', $invoice_no)
							->first();

		$filename = $filepath . $invoice->invoice_no . '.pdf';

		if ($invoice) {
			PDF::loadView('invoice', compact('invoice'))->setPaper('a5')->save($filename);
			return response()->download($filename)->deleteFileAfterSend(true);
		}
	}
}