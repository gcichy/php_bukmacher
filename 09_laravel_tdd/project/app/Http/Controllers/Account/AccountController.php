<?php

namespace App\Http\Controllers\Account;

use App\Models\BlikCode;
use App\Models\Premium;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Http\Controllers\BetResultController;

class AccountController
{
    public function index(): View
    {
        BetResultController::update();
        $user = Auth::user();
        if ($user instanceof User) {
            if ($user->premium) {
                $premium = Premium::where('user_id', $user->id)->first();
                if ($premium instanceof Premium) {
                    $premium_expire = 'Data wygaśnięcia konta premium: ' . $premium->expiration_date;
                    return view('account')
                        ->with('user', $user)
                        ->with('type', 'Premium')
                        ->with('premium_expire', $premium_expire);
                }
            }
        }
        return view('account')
            ->with('user', $user)
            ->with('type', 'Standardowe');
    }

//    public function store(Request $request): RedirectResponse
//    {
//        $code = BlikCode::all()->where('code', $request['blikCode'])->first();
//        if (!$code) {
//            return back()->withErrors(['blikCode' => 'Doładowanie nie powiodło się, zły kod blik.']);
//        } else {
//            $user = Auth::user();
//            if ($request['amount'] < 0) {
//                return back()->withErrors(['amount' => 'Doładowanie nie powiodło się, kwota musi być większa od 0']);
//            }
//            User::all()->where('id', $user->id)->first()->update(['deposit' => $user->deposit+$request['amount']]);
//        }
//        return redirect('account');
//    }
}
