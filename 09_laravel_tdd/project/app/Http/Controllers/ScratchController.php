<?php

namespace App\Http\Controllers;

use App\Models\Premium;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ScratchController extends Controller
{
    public function index(): RedirectResponse|View
    {
        $user = Auth::user();
        if ($user) {
            if (!$user->premium) {
                return redirect('/account/premium');
            }
            $premium = Premium::where('user_id', $user->id)->first();

            $didWin = 1;
            if ($premium) {
                if ($premium->scratches_left == 0) {
                    $didWin = 0;
                    if ($this->didTheyWin($premium->scratchcard_id)) {
                        $didWin = 2;
                    }
                }
            }

            return view('scratchcard')->with('user', $user)->with('premium', $premium)->with('didWin', $didWin);
        } else {
            return back();
        }
    }

    public function store(Request $request): RedirectResponse
    {
        $lastID = -1;
        $user = Auth::user();
        if ($user && isset($request["id"])) {
            $premium = Premium::where('user_id', $user->id)->first();
            if ($premium) {
                $new_s_cnt = ($premium->scratches_left) - 1;
                if ($new_s_cnt < 0) {
                    return back()->withErrors(['enough' => 'Na dziś wystraczy. Wróć jutro!']);
                }
                $char = substr($premium->scratchcard_id, $request["id"]-1, 1);
                $char = strval(intval($char) + 5);
                $new_s_id = substr_replace($premium->scratchcard_id, $char, $request["id"]-1, 1);
                $up_1 = Premium::where('id', $premium->id)->first();
                if ($up_1) {
                    $up_1->update(['scratches_left' => $new_s_cnt]);
                }
                $up_2 = Premium::where('id', $premium->id)->first();
                if ($up_2) {
                    $up_2->update(['scratchcard_id' => $new_s_id]);
                }
                $lastID = $request["id"];
                if ($new_s_cnt == 0 and $this->didTheyWin($new_s_id)) {
                    $up_3 = User::where('id', $user->id)->first();
                    if ($up_3) {
                        $up_3->update(['deposit' => $user->deposit + 5]);
                    }
                }
            }
        }
        return redirect('scratchcard');
    }

    private function didTheyWin(String $sid): bool
    {
        $sum = [];
        $numbers = str_split($sid);
        foreach ($numbers as $number) {
            if (intval($number) >= 5) {
                $sum[] = intval($number);
            }
        }
        if ($sum[0] == $sum[1] and $sum[0] == $sum[2]) {
            return true;
        }
        return false;
    }
}
