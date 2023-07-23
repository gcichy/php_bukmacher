<?php

namespace App\Http\Controllers\Account;

use App\Models\Bet;
use App\Models\BetEvent;
use App\Models\Event;
use App\Models\SpecialEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class AccountHistoryController
{
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            $bets = Bet::where('user_id', $user->id)->get();
            if ($bets->count()) {
                $bet_events = [];
                foreach ($bets as $bet) {
                    $events = BetEvent::where('bet_id', $bet->id)->get();
                    if ($events->count()) {
                        $extended_events = array();
                        foreach ($events as $event) {
                            if ($event->answer == 'Tak' || $event->answer == 'Nie') {
                                $temp = SpecialEvent::find($event->event_id);
                                if ($temp) {
                                    $event['question'] = $temp->question;
                                    $event['answer_1'] = $temp->answer_1;
                                    $event['answer_2'] = $temp->answer_2;
                                    $extended_events[] = $event;
                                }
                            } else {
                                $temp = Event::find($event->event_id);
                                if ($temp) {
                                    $event['opponent_1'] = $temp->opponent_1;
                                    $event['opponent_2'] = $temp->opponent_2;
                                    $event['date'] = $temp->date;
                                    $event['discipline'] = $temp->discipline;
                                    $event['score'] = $temp->score;
                                    $extended_events[] = $event;
                                }
                            }
                        }
                        $bet_events[$bet->id] = $extended_events;
                    }
                }
                return view('account.history', compact('bets', 'user', 'bet_events'));
            }
            return view('account.history', compact('bets', 'user'));
        }
        return redirect('/login');
    }
}
