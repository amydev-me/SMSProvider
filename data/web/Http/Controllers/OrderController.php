<?php

namespace Web\Http\Controllers;

use App\Models\UserPackage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Mail;
use User\Http\Controllers\Order\UserOrder;
use Illuminate\Http\Request;

class OrderController extends Controller
{

    use UserOrder;

    public function orderConfirmationView(Request $request)
    {

        $package = $this->getOrderByPackageId($request->package_id);

        if ($package) {

            return $this->redirectWebCheckOut($package);
        }
        return redirect()->back();

    }

    public function packageOrder(Request $request)
    {
        $package = $this->getOrderByPackageId($request->package_id);


        if ($package) {
            $order= $this->createOrder($package);
            if($order){
                return $this->redirectSuccessOrderToWeb();
            }
        }

        //TODO::send sms to admin

        //TODO::send noti to admin

        //TODO::send email to admin


        return redirect()->back();
    }

//
//    /**
//     * User Package Confirm By Admin
//     * When Confirm Order Send Bank Mail
//     * When They Paid Send Invoice
//     */
    public function userPackageConfirm(Request $request)
    {
//        $invoice = UserPackage::findUserPackage(1)->first();
//        if ($invoice) {
//            $invoice->update(['is_confirm' => true]);
//            //TODO::Need To Change Mail Queue
//            Mail::send('bank-mail', [], function ($message) {
//                $message->from('info@triplesms.com');
//                $message->to('amy@lfuturedev.com');
//
//            });

        return response()->json((new MailMessage())
            ->from('info@triplesms.com')
            ->replyTo('amy@lfuturedev.com', 'Amy Pyae Phyo Naing')
            ->line("To complete your TripleSMS registration, please click the button below.")
            ->action('Verify my email address', route('register.confirm', ['token' => '65465465465464646546546']))
            ->line("Thank you, TripleSMS Support Team"));
//        }
//
//        return 'no found';

    }

//    public function paidUserPackage(Request $request){
//        $userpackage = UserPackage::findUserPackage($request->user_package_id)->first();
//        if ($userpackage) {
//            $userpackage->update(['is_confirm' => true]);
//        }
//
//        //TODO::Need To Change Mail Queue
//
//        $filepath = storage_path() . '/app/tmp/';
//        $filename = $filepath . $userpackage->invoice_no;
//        $pdf = PDF::loadView('invoice', compact('invoice'))->setPaper('a5')->save($filename);
//        Mail::queue(new InvoiceMail($filename));
//        if (!Mail::failures()) {
//            unlink($filename);
//        }
//    }
}
