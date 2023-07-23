<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\BlikCode;
use App\Models\Premium;
use App\Models\User;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BuyPremiumController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        if ($user) {
            return view('account.premium')->with('user', $user)->with('top_up', false);
        }
        return view('auth.login');
    }

    /**
     * @throws \Exception
     */
    public function store(Request $request): RedirectResponse
    {
        if (!$request['statute']) {
            return back()->withErrors(['statute' => 'Aby kontynuować zaakceptuj regulamin']);
        }
        if ($request['deposit'] < 20) {
            return redirect()->route('account.balance')->with('status', 'Brak środków do zakupu konta premium.');
        }

        $user_id = Auth::user();
        if ($user_id) {
            $this->create_premium($user_id->id);
            $user = User::where('id', $user_id->id)->first();
            if ($user instanceof User) {
                $user->update(['premium' => 1, 'deposit' => ($user->deposit - 20)]);

                return redirect()->route('account')->with('status', 'Gratulujemy zostania użytkownikiem premium ' . ($user)->name . '!');
            }
        }
        return redirect()->route('account');
    }


    private function create_premium(int $user_id): Premium
    {
        $si = '';
        for ($j = 0; $j<9; $j++) {
            $si .= random_int(1, 4);
        }
        $day = getdate();
        if ($day['mon'] == 12) {
            $array = [$day['mday'], 1 , $day['year'] + 1];
        } else {
            $array = [$day['mday'], $day['mon'] + 1, $day['year']];
        }
        $day = implode('/', $array);
        $premium = Premium::create([
            'scratches_left' => 3,
            'scratchcard_id' => $si,
            'user_id' => $user_id,
            'expiration_date' => $day,
            'harakiried' => 0,
        ]);

        return $premium;
    }
}
