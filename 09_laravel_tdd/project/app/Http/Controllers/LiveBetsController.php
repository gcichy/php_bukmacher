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

class LiveBetsController extends Controller
{
    public function index(): View | RedirectResponse
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('home');
        }
        if ($user->premium == 0) {
            return redirect()->route('account.premium');
        }
        $matches = Event::where('status', 'live')->get();
        $oddsList = [];
        foreach ($matches as $match) {
            $oddsList[] = Odds::where('event_id', $match->id)->first();
        }

        return view('livebets')->with('matches', $matches)->with('odds', $oddsList)->with('user', $user);
    }

    public function store(Request $request): RedirectResponse | View
    {
        $user = Auth::user();

        $sum = 0;
        $index = -1;
        for ($i=0; $i<strlen(strval($request["bet_code"])); $i++) {
            $option = strval($request["bet_code"])[$i];
            if ($sum != 0 && intval($option) != 0) {
                return back()->withErrors(["empty" => "Możliwość postawienia jednego meczu live"]);
            }
            if (intval($option) != 0) {
                $index = $i;
                $sum += intval($option);
            }
        }
        if ($sum == 0) {
            return back()->withErrors(["empty" => "Nie możesz obstawić pustego kuponu."]);
        }
        if ($request["amount"] <= 0) {
            return back()->withErrors(["amount" => "Podaj kwotę kuponu."]);
        }

        if (!$user) {
            return redirect()->route('home');
        }

        if ($user->deposit < $request["amount"]) {
            return redirect()->route('account.balance')->with('status', "Brak wystarczających środków na koncie.");
        }

        //everything checks out - lets get to work
        $betAmount = $request["amount"];
        $user->update(['deposit' => $user->deposit - $betAmount]);
        $day = getdate();
        $array = [$day['mday'] , $day['mon'], $day['year']];

        $liveMatches = Event::where('status', 'live')->orderBy('id')->get();
        //$bet_code = $request["bet_code"].str_repeat('0', count($liveMatches) - strlen($request["bet_code"]));
        $bet_code = $request["bet_code"];
        $ilm = $liveMatches[$index];

        if ($ilm instanceof Event) {
            $odds = Odds::where('event_id', $ilm->id)->first();
            if ($odds instanceof  Odds) {
                $draw = (1/$odds->win_op_1) + (1/$odds->win_op_2);
                $draw = floor((1 - $draw)*100)/100;
                if ($draw <= 0) {
                    $draw = 0.05;
                }
                switch (strval($bet_code)[$index]) {
                    case '1':
                        $totalOdd = $odds->win_op_1;
                        break;
                    case '2':
                        $draw = 1/$draw;
                        $totalOdd = floor($draw*100)/100;
                        break;
                    case'3':
                        $totalOdd = $odds->win_op_2;
                        break;
                    case'4':
                        $odds1X = 1 - 1/$odds->win_op_2;
                        if ($odds1X <= 0) {
                            $odds1X = 0.05;
                        }
                        $odds1X = 1/$odds1X;
                        $odds1X = floor($odds1X*100)/100;
                        $totalOdd = $odds1X;
                        break;
                    case'5':
                        $notdraw = 1 - 1/$draw;
                        if ($notdraw <= 0) {
                            $notdraw = 0.05;
                        }
                        $notdraw = 1/$notdraw;
                        $notdraw = floor($notdraw*100)/100;
                        $totalOdd = $notdraw;
                        break;
                    default:
                        $odds2X = 1 - 1/$odds->win_op_1;
                        if ($odds2X <= 0) {
                            $odds2X = 0.05;
                        }
                        $odds2X = 1/$odds2X;
                        $odds2X = floor($odds2X*100)/100;
                        $totalOdd = $odds2X;
                }
                $odds->update(['sum' => $odds->sum + $request["amount"]]);
                $odds->update(['win_op_1' => $odds->win_op_1 - 0.1]);
                $odds->update(['win_op_2' => $odds->win_op_2 - 0.1]);

                $totalOdd = floor($totalOdd*100)/100;
                $bet = Bet::create([
                    'user_id' => $user->id,
                    'date' => implode("/", $array),
                    "stake" => $request["amount"],
                    "total_odd" => $totalOdd,
                    "status" => 0, //true - skończony, false - trwa
                    "bet_result" => 1, //0 - przegrany, 1 w trakcie, 2 - wygrany
                    'win_price' => $totalOdd * $request["amount"]
                ]);

                switch (strval($bet_code)[$index]) {
                    case '1':
                        $ans = $ilm->opponent_1;
                        break;
                    case'5':
                        $ans = "12";
                        break;
                    case '2':
                        $ans = "X";
                        break;
                    case'3':
                        $ans = $ilm->opponent_2;
                        break;
                    case'4':
                        $ans = "1X";
                        break;
                    default:
                        $ans = "X2";
                }
                $eventID = $ilm->id;
                BetEvent::create([
                    'bet_id' => $bet->id,
                    'event_id' => $eventID,
                    "answer" => $ans
                ]);
            }
        }


        return redirect('/')->with('status', "Kupon postawiony");
    }
}
