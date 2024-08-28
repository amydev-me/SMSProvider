<?php

namespace User\Http\Controllers;

use Illuminate\Http\Request;

use function foo\func;

use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Response;
use Propaganistas\LaravelPhone\PhoneNumber;

use Illuminate\Validation\Rule;

use App\Models\Contact;
use App\Models\LogDetail;
use App\Models\SmsLog;
use App\Models\Country;

use Carbon\Carbon;
use Validator;
use Auth;
use DB;

class ExportFileController extends Controller
{
	/**
	 * Export Excel/CSV File
	 */
	public function exportContactByGroup(Request $request)
	{
		$group_id = $request->group_id;

		if (!isset($request->group_id)) {
			return back();
		}

		try {
			$contacts = Contact::where('user_id', Auth::guard('web')->user()->id)
								->whereHas('groups', function ($q) use ($group_id) {
									$q->where('id', $group_id);
								})
								->select('id', 'contactName as Name', 'companyName as Company', 'Mobile', 'address as Address', 'birthdate', 'email as Email'
								)
								->get()
								->makeHidden(['id']);

			Excel::create('contacts', function ($excel) use ($contacts) {
				$excel->setTitle('Contact List');
				$excel->setCreator("TripleSMS")
					->setCompany("TripleSMS");

				$excel->sheet('Sheet1', function ($sheet) use ($contacts) {
					$sheet->fromArray($contacts);
				});
			})->download('xlsx');

		} catch (\Exception $e) {

		}

		return back();
	}

	public function importContactsFile(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'import_file' => 'required|mimes:xls,xlsx,csv,txt'
		]);

		if ($validator->fails()) {
			return redirect()->back()->withErrors($validator->errors());
		}

		if ($request->hasFile('import_file')) {

			$path = $request->file('import_file')->getRealPath();

			try {
				$data = Excel::load($path)->get();

				if ($data->count()) {
					$arr = array();

					$country_codes = $this->getCountryCodes();

					foreach ($data as $key => $value) {

						$phone = $value->mobile;

						if ( substr($phone, 0, 1) != '+' ) {
							$phone = '+' . $phone;
						}

						if ( substr($phone, 0, 1) == '+' ) {
							$validator = Validator::make(['phone' => $phone], [
								'phone' => Rule::phone()->country( $country_codes )
							]);

							if (!$validator->fails()) {
								$formattedNumber = PhoneNumber::make($phone);
								$dob = $value->dateofbirth ? Carbon::parse($value['dateofbirth'])->format('Y-m-d') : null;

								$contact = Contact::create([
									'contactName' => $value->name,
									'companyName' => $value->company,
									'email' => $value->email,
									'mobile' => $formattedNumber,
									'address' => $value->address,
									'birthdate' => $dob,
									'user_id' => Auth::guard('web')->user()->id
								]);

								if ($contact) {
									if ($request->groups) {
										$groups = explode(',', $request->groups);
										$contact->groups()->attach($groups);
									}
								}
							}
						}
					}

					DB::table('contacts')->insert($arr);

					return redirect()->route('contact.index');
				}

				return back();

			} catch (\Exception $e) {

				return back();
			}
		}
	}

	public function exportSmsLogDetails()
	{
		$user_id = Auth::guard('web')->user()->id;

		if ($user_id) {
			try {
				$log_details = LogDetail::with(['sms_log' => function ($q) {
											$q->select('id', 'message_content', 'encoding');
										}])
										->with('country')
										->whereHas('sms_log', function ($q) use ($user_id) {
											$q->where('user_id', $user_id);
										})
										->select(['id', 'sms_log_id', 'recipient as To', 'country_id', 'source as Source', 'status as Status', 'total_usage as Total Usage', 'send_at'])
										->get()
										->makeHidden(['id', 'sms_log_id']);

				$log_details->map(function ($item, $key) {
					$item->Country = $item->country->name;
					$item->Encoding = $item->sms_log->encoding;
					$item->Message = $item->sms_log->message_content;
					$item->SendTime = $item->send_at;
					unset($item->send_at);
					unset($item->sms_log);
				});

				Excel::create('sms_logs', function ($excel) use ($log_details) {
					$excel->setTitle('SMS LOGS');
					$excel->setCreator("TripleSMS")
						->setCompany("TripleSMS");

					$excel->sheet('Sheet1', function ($sheet) use ($log_details) {
						$sheet->fromArray($log_details);
					});
				})->download('xlsx');

			} catch (\Exception $e) {
				return back();
			}
		}
	}

	private function getCountryCodes()
	{
		$countries = Country::where('status', '1')
							->where('rate', '>', '0')
							->pluck('code')
							->toArray();

		return $countries;
	}
}