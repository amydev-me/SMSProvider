<?php
namespace User\Http\Controllers\Order;

use App\Models\User;
use App\Models\Package;
use App\Models\UserPackage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

trait UserOrder
{
    protected function getOrderByPackageId($package_id)
    {
        return Package::with(['promotions' => function($query) {
                            $query->where('active', '1');
                        }])
                        ->where('id', $package_id)
                        ->first();
    }

    protected function redirectUserCheckOut($package)
    {
        $is_verify = User::CurrentUser()->where('verified', true)->exists();
        return view('user-checkout', compact('package', 'is_verify'));
    }

    protected function redirectWebCheckOut($package)
    {
        return view('checkout', compact('package'));
    }

    protected function redirectSuccessOrderToUser()
    {
        return redirect()->to('order-success');
    }

    protected function redirectSuccessOrderToWeb()
    {
        return redirect()->to('order-confirmation');
    }

    protected function createOrder($package)
    {
        return UserPackage::create([
            'invoice_no' => Carbon::now()->timestamp,
            'user_id' => Auth::guard('web')->user()->id,
            'package_id' => $package->id,
            'credit' => $package->credit,
            'total_credit' => $package->credit,
            'cost' => $package->cost,
            'payment_method' => 'Bank',
            'status' => 'pending',
            'order_date' => Carbon::now()
        ]);
    }
}