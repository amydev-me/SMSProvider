<?php
namespace Web\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Web\Http\Controllers\Controller;
use App\Http\Controllers\SendSmsWithAdminToken;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use GuzzleHttp\Client;
use Carbon\Carbon;

use App\Events\UserRegistered;
use App\Models\ConfirmationCode;
use App\Models\AdminToken;
use App\Models\User;

class RegisterController extends Controller
{
	use SendSmsWithAdminToken;

	public function __construct()
	{
		$this->middleware('guest');
	}

	public function sign_up(Request $request)
	{
		$this->validator($request->all())->validate();

		$confirm_code = ConfirmationCode::where('mobile', $request->mobile)->orderBy('created_at', 'desc')->first();

		if (!$confirm_code) {
			return redirect()->back()->withErrors(['errors' => 'The conformation code is wrong.']);
		}

		if ($request->confirm_code != $confirm_code->confirmation_code) {
			return redirect()->back()->withErrors(['errors' => 'The conformation code is wrong.']);
		}

		event(new UserRegistered($user = $this->create($request->all())));
		Auth::guard('web')->login($user);

		if (Auth::guard('web')->check()) {
			return redirect()->route('dashboard.index');
		}

		return redirect()->back()->withErrors(['errors'=>'Opps! something went wrong. Please register agian. If already registered your email/username.Please Try to login. Thanks.'])->withInput($request->all());
	}

	protected function validator(array $data)
	{
		$rules = [
			'username' => 'required|alpha_dash|min:3|unique:users',
			'email' => 'required|email|max:100|unique:users',
			'full_name' => 'required',
			'mobile' => 'required',
			'accept_terms' => 'required',
			'password'=>'required',
			// 'g-recaptcha-response' => 'required|captcha'
		];

		// $messages = [
		// 	'g-recaptcha-response.required' => 'Please verify that you are not a robot.',
		// 	'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
		// ];

		return Validator::make($data, $rules);
	}

	private function create(array $data)
	{
		return User::create([
			'username' => $data['username'],
			'email' => $data['email'],
			'password' => Hash::make($data['password']),
			'mobile' => $data['mobile'],
			'full_name' => $data['full_name'],
			'company' => $data['company'],
			'address' => $data['address'],
			'accept_terms' => $data['accept_terms'] == 'on' ? true : false,
			'account_type' => 'Free',
		]);
	}

	public function sendConfirmation(Request $request)
	{
		$validator = Validator::make($request->all(), [
			'mobile' => 'required|unique:users'
		]);

		if ($validator->fails()) {
			return response()->json(['status' => false, 'message' => $validator->errors()], 200);
		}

		$check_number = ConfirmationCode::where('mobile', $request->mobile)->orderBy('created_at', 'desc')->first();

		$confirmation_code = rand(100000, 999999);
		
		if ( $check_number ) {
			$count = $check_number->count + 1;

			if ( $check_number->expire_at->gt( Carbon::now() ) ) {
				return response()->json(['status' => false, 'message' => ['mobile' => 'Please wait 15 minutes to send another code.']], 200);
			}
			
			$check_number->update([
				'confirmation_code' => $confirmation_code,
				'count' => $count,
				'expire_at' => Carbon::now()->addMinutes(15)
			]);
		} else {
			ConfirmationCode::create([
				'mobile' => $request->mobile,
				'confirmation_code' => $confirmation_code,
				'count' => 1,
				'expire_at' => Carbon::now()->addMinutes(15)
			]);
		}

		// $this->sendSms($request->mobile, $confirmation_code);
		$this->sendSmsWithAdminToken($request->mobile, 'Your confirmation code is ' . $confirmation_code . '.');

		return response()->json(['status' => true], 200);
	}

	// private function sendSms($mobile, $confirmation_code)
	// {
	// 	try {
	// 		$token = AdminToken::first()->api_secret;
	// 		$client = new Client();

	// 		$client->request(
	// 			'POST', 'https://triplesms.com/api/send/message', [
	// 				'headers' => [
	// 					'Authorization' => "Bearer {$token}"
	// 				],

	// 				'json' => [
	// 					'sender' => 'TripleSMS',
	// 					'to' => $mobile,
	// 					'body' => 'Your confirmation code is ' . $confirmation_code . '.'
	// 				]
	// 			]
	// 		);
	// 	} catch (RequestException $e) {
	// 		throw $e;
	// 	}
	// }

	public function checkConfirmation(Request $request)
	{
		$confirm_code = ConfirmationCode::where('mobile', $request->mobile)->orderBy('created_at', 'desc')->first();

		try {
			if ($request->confirm_code != $confirm_code->confirmation_code) {
				return response()->json(['status' => false, 'message' => ['confirmation_code' => 'The confirmation code is wrong.']], 200);
			}

			if ( $confirm_code->expire_at->lt( Carbon::now() ) ) {
				return response()->json(['status' => false, 'message' => ['confirmation_code' => 'The confirmation code is expire. Send another code.']], 200);
			}

			return response()->json(['status' => true], 200);

		} catch (\Exception $e) {
			return response()->json(['status' => false, 'message' => ['confirmation_code' => 'The confirmation code is wrong.']], 200);
		}
	}
}