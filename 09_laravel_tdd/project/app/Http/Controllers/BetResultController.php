<?php

namespace App\Http\Controllers;

use App\Models\Bet;
use App\Models\BetEvent;
use App\Models\Event;
use App\Models\SpecialEvent;
use App\Models\User;
use Nette\Utils\DateTime;

class BetResultController
{
    public static function update(): void
    {
        self::events_update();
        /*
         * bierzemy bety po kolei i sprawdzamy czy wszystkie eventy są w statusie ended
         * i czy jakiś bet_event jest przegrany -> jeslitak to bet od razu zmieniamy na przegrany
         * jesli wszystkie eventy są skończone i wygrane to wtedy dajemy kupon jako wygrany i od razu robimy update
         * ddepozytu += winprice
         */
        $bets = Bet::where('status', 0)->get();
        foreach ($bets as $bet) {
            $b_events = BetEvent::where('bet_id', $bet->id)->get();
            $bet_win = true;
            $not_ended = 0;
            //bierzemy każdy bet_event dla beta i sprawdzamy czy wszystkie są skończone oraz czy są trafione
            foreach ($b_events as $b_ev) {
                //spr czy to sepcial event
                if ($b_ev->answer == 'Tak' || $b_ev->answer == 'Nie') {
                    $special_event = SpecialEvent::where('id', $b_ev->event_id)->first();
                    //jesli nasz typ się zgadza z oczekiwaniami to wygrany
                    if ($special_event instanceof SpecialEvent) {
                        if ($b_ev->answer == $special_event->correct) {
                            $bet->update(['status' => 1, 'bet_result' => 2, 'win_price' => floor($bet->stake * $bet->total_odd * 85) / 100]);
                            $user = User::where('id', $bet->user_id)->first();
                            if ($user instanceof  User) {
                                User::where('id', $bet->user_id)->update(['deposit' => floor($user->deposit + $bet->stake * $bet->total_odd * 85) / 100]);
                            }
                        } else {
                            $bet->update(['status' => true, 'bet_result' => 0, 'win_price' => 0]);
                        }
                    }
                } else {
                    //tu zwykły event
                    $event = Event::where('id', $b_ev->event_id)->first();
                    if ($event instanceof Event) {
                        if ($event->status == 'ended') {
                            //spr czy typ jest trafiony
                            $isWon = self::event_win($b_ev, $event);
                            if (!$isWon) {
                                $bet_win = false;
                            }
                        } else {
                            //jeśli nie ma statusu ended to kupon nadal w grze
                            $not_ended = 1;
                        }
                    }
                }
            }
            //jesli kupon jest ended
            if (!$not_ended) {
                //jesli wygrany to zmieniamy status na 2, ustawiamy win price i dodajemy do depozytu
                if ($bet_win) {
                    $bet->update(['status' => 1, 'bet_result' => 2, 'win_price' => floor($bet->stake * $bet->total_odd * 85)/100]);
                    $user = User::where('id', $bet->user_id)->first();
                    if ($user instanceof User) {
                        User::where('id', $bet->user_id)->update(['deposit' => $user->deposit + floor($bet->stake * $bet->total_odd * 85) / 100]);
                    }
                } else {
                    $bet->update(['status' => 1, 'bet_result' => 0]);
                }
            }
        }
    }

    public static function events_update(): void
    {
        $up_events = Event::where('status', 'upcoming')->get();
        foreach ($up_events as $ev) {
            $date = $ev->date .' '.strval($ev->time);
            $startdate = DateTime::createFromFormat('Y-m-d H:i:s', $date);
            $now = new DateTime("now");
            if ($now != false) {
                $now = $now->modify("+1 hour");
                //trzeba zupdateować event do live
                if ($now > $startdate) {
                    $ev->update(['status' => 'live']);
                }
            }
        }

        $live_events = Event::where('status', 'live')->get();
        foreach ($live_events as $li) {
            $date = $li->date.' '.strval($li->time);
            //dd(DateTime::createFromFormat('Y-m-!d H:i:s', '2009-02-15 15:16:17'));
            $enddate = DateTime::createFromFormat('Y-m-d H:i:s', $date);
            if ($enddate != false) {
                $enddate = $enddate->modify("+2 hour");
            }
            $now = new DateTime("now");
            if ($now != false) {
                $now = $now->modify("+1 hour");
            }
            //trzeba zupdateować event do ended
            if ($now > $enddate) {
                $li->update(['status' => 'ended']);
            }
        }
//            dd(DateTime::createFromFormat('Y-m-d h:i:s', $date) == $now);
//            dd(date($date));
        //echo ;
    }

    public static function event_win(BetEvent $bet_event, Event $event): bool
    {
        $score = explode(':', strval($event->score));
        $score_1 = (int)$score[0];
        $score_2 = (int)$score[1];
        switch ($bet_event->answer) {
            case $event->opponent_1:
                if ($score_1 > $score_2) {
                    $event_win = true;
                } else {
                    $event_win = false;
                }
                break;
            case $event->opponent_2:
                if ($score_1 < $score_2) {
                    $event_win = true;
                } else {
                    $event_win = false;
                }
                break;
            case 'X':
                if ($score_1 == $score_2) {
                    $event_win = true;
                } else {
                    $event_win = false;
                }
                break;
            case '1X':
                if ($score_1 >= $score_2) {
                    $event_win = true;
                } else {
                    $event_win = false;
                }
                break;
            case'12':
                if ($score_1 != $score_2) {
                    $event_win = true;
                } else {
                    $event_win = false;
                }
                break;
            case'X2':
                if ($score_1 <= $score_2) {
                    $event_win = true;
                } else {
                    $event_win = false;
                }
                break;
            default:
                $event_win = false;
                break;
        }
        return $event_win;
    }
}
