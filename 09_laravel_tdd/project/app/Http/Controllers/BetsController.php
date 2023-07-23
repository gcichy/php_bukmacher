<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bet;
use App\Models\BetEvent;
use App\Models\BlikCode;
use App\Models\Event;
use App\Models\Odds;
use App\Models\Premium;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BetsController extends Controller
{
    public function store(Request $request): RedirectResponse | View
    {
        $user = Auth::user();

        $sum = 0;
        for ($i=0; $i<strlen(strval($request["bet_code"])); $i++) {
            if (strval($request["bet_code"])[$i] == '0') {
                continue;
            }
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
        $user->update(['deposit' => floor(($user->deposit - $request["amount"])*100)/100]);
        $day = getdate();
        $m = strval($day['mon']);
        if (strlen($m) == 1) {
            $m = '0'.$m;
        }
        $d = strval($day['mday']);
        if (strlen($d) == 1) {
            $d = '0'.$d;
        }
        $array = [strval($day['year']), $m, $d];

        $upcomingMatches = Event::where('status', 'upcoming')->orderBy('id')->get();
        if (strlen(strval($request["bet_code"])) > count($upcomingMatches)) {
            return back()->withErrors(["empty" => "Błędny kod kuponu."]);
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
                $draw = 1 / $odds->win_op_1 + 1 / $odds->win_op_2;
                $draw = floor((1 - $draw) * 100) / 100;
                if ($draw <= 0) {
                    $draw = 0.05;
                }
                $draw = 1 / $draw;
                $draw = floor($draw * 100) / 100;
                switch ($bet_code[$i]) {
                    case '1':
                        $totalOdd *= $odds->win_op_1;
                        break;
                    case '2':
                        $totalOdd *= $draw;
                        break;
                    case'3':
                        $totalOdd *= $odds->win_op_2;
                        break;
                    case'4':
                        $odds1X = 1 - 1 / $odds->win_op_2;
                        if ($odds1X <= 0) {
                            $odds1X = 0.05;
                        }
                        $odds1X = 1 / $odds1X;
                        $odds1X = floor($odds1X * 100) / 100;
                        $totalOdd *= $odds1X;
                        break;
                    case'5':
                        $notdraw = 1 - 1 / $draw;
                        if ($notdraw <= 0) {
                            $notdraw = 0.05;
                        }
                        $notdraw = 1 / $notdraw;
                        $notdraw = floor($notdraw * 100) / 100;
                        $totalOdd *= $notdraw;
                        break;
                    default:
                        $odds2X = 1 - 1 / $odds->win_op_1;
                        if ($odds2X <= 0) {
                            $odds2X = 0.05;
                        }
                        $odds2X = 1 / $odds2X;
                        $odds2X = floor($odds2X * 100) / 100;
                        $totalOdd *= $odds2X;
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
                    $ans = $um->opponent_1;
                    break;
                case '2':
                    $ans = "X";
                    break;
                case'3':
                    $ans = $um->opponent_2;
                    break;
                case'4':
                    $ans = "1X";
                    break;
                case'5':
                    $ans = "12";
                    break;
                default:
                    $ans = "X2";
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
