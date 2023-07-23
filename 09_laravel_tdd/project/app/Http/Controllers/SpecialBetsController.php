<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\BetEvent;
use App\Models\BlikCode;
use App\Models\Event;
use App\Models\Odds;
use App\Models\Premium;
use App\Models\SpecialEvent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SpecialBetsController extends Controller
{
    public function store(Request $request): RedirectResponse | View
    {
        $user = Auth::user();

        $sum = 0;
        for ($i=0; $i<strlen(strval($request["bet_code"])); $i++) {
            $option = strval($request["bet_code"])[$i];
            $sum += intval($option);
        }
        if ($sum == 0) {
            return back()->withErrors(["empty" => "Nie możesz obstawić pustego kuponu."]);
        }
        if ($request["amount"] <= 0) {
            return back()->withErrors(["amount" => "Podaj kwotę kuponu."]);
        }

        if (!$user) {
            return redirect('betcode')->with('status', 'Wygenerowany kod: '.$request["bet_code"]);
        }

        if ($user->deposit < $request["amount"]) {
            return redirect()->route('account.balance')->with('status', "Brak wystarczających środków na koncie.");
        }

        //everything checks out - lets get to work
        $user->update(['deposit' => $user->deposit - $request["amount"]]);
        $day = getdate();
        $m = strval($day['mon']);
        if (strlen($m) == 1) {
            $m = '0'.$m;
        }
        $d = strval($day['mday']);
        if (strlen($d) == 1) {
            $d = '0'.$d;
        }
        $array = [ $day['year'] , $m, $d];

        $upcomingMatches = SpecialEvent::all()->sortBy('id');
        if (strlen(strval($request["bet_code"])) > count($upcomingMatches)) {
            return back()->withErrors(["empty" => "Kod kuponu jest nieprawidłowy."]);
        }
        $bet_code = $request["bet_code"].str_repeat('0', count($upcomingMatches) - strlen(strval($request["bet_code"])));
        $i = -1;
        $totalOdd = 1;
        foreach ($upcomingMatches as $um) {
            $i += 1;
            if ($bet_code[$i] == '0') {
                continue;
            }
            $odds = Odds::where('event_id', $um->id)->first();
            if ($odds) {
                switch ($bet_code[$i]) {
                    case '1':
                        $totalOdd *= $odds->win_op_1;
                        break;
                    default:
                        $totalOdd *= $odds->win_op_2;
                }

                $odds->update(['sum' => $odds->sum + $request["amount"]]);
                $odds->update(['win_op_1' => $odds->win_op_1 - 0.1]);
                $odds->update(['win_op_2' => $odds->win_op_2 - 0.1]);
            }
        }

        $totalOdd = floor($totalOdd*100)/100;
        $bet = Bet::create([
            'user_id' => $user->id,
            'date' => implode("-", $array),
            "stake" => $request["amount"],
            "total_odd" => $totalOdd,
        "status" => 0, //1 - skończony, 0 - trwa
            "bet_result" => 1, //0 - przegrany, 1 w trakcie, 2 - wygrany
            'win_price' => $totalOdd * $request["amount"]
        ]);
        $i = -1;
        foreach ($upcomingMatches as $um) {
            $i += 1;
            if ($bet_code[$i] == '0') {
                continue;
            }
            switch ($bet_code[$i]) {
                case '1':
                    $ans = $um->answer_1;
                    break;
                default:
                    $ans = $um->answer_2;
            }
            BetEvent::create([
                'bet_id' => $bet->id,
                'event_id' => $um->id,
                "answer" => $ans
            ]);
        }
        return redirect('/')->with('status', "Kupon postawiony");
    }
}
