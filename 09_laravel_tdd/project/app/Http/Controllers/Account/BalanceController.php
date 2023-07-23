<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\BlikCode;
use App\Models\Premium;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BalanceController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        return view('account.balance')->with('user', $user);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'amount' => ['required', 'numeric']
        ]);

        $code = BlikCode::where('code', $request['blikCode'])->first();
        if (!$code) {
            return back()->withErrors(['blikCode' => 'Doładowanie nie powiodło się, zły kod blik.']);
        } else {
            $user = Auth::user();
            if ($user instanceof User) {
                if ($request['amount'] < 0) {
                    return back()->withErrors(['amount' => 'Doładowanie nie powiodło się, kwota musi być większa od 0']);
                }
                $user->update(['deposit' => $user->deposit + $request['amount']]);
            }
        }
        return redirect('account')->with('status', 'Twoje konto zostało doładowane!');
    }
}
