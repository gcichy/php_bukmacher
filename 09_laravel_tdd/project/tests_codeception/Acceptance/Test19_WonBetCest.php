<?php

namespace TestsCodeception\Acceptance;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use TestsCodeception\Support\AcceptanceTester;

class Test19_WonBetCest
{
    public function betsTest(AcceptanceTester $I): void
    {
        $I->wantTo('See won bet');

        $I->amOnPage('/');

        $pass = 'Jarek&Gwint77';

        $id = $I->haveInDatabase("users", [
            'email' => 'jacekSasin@wp.pl',
            'password' => password_hash($pass, PASSWORD_DEFAULT),
            'name' => 'Jacek',
            'surname' => 'Sasin',
            'nickname' => 'Jaca',
            'phone_number' => '999999999',
            'person_number' => '81010277901',
            'confirmed' => true,
            'premium' => false,
            'deposit' => 7,
        ]);
        $user = $I->grabEntryFromDatabase('users', ['id' => $id]);
        $user = (object)$user;

        $I->amOnPage('/login');
        $I->fillField('email', 'jacekSasin@wp.pl');
        $I->fillField('password', 'Jarek&Gwint77');
        $I->click('Zaloguj się');

        $I->seeCurrentUrlEquals('/');

        $events = $I->grabEntriesFromDatabase('events', ['status' => 'upcoming']);
        $events = count($events);

        $id1 = $I->haveInDatabase("events", [
            'opponent_1' => "Krzysztof Ratajski",
            'opponent_2' => "Micheal Smith",
            'date' => "2023-01-26",
            'discipline' => "Dart",
            'time' => "21:00",
            'timezone' => 'UTC',
            'league' => 'World Cup',
            'round' => 'Final',
            'status' => 'live',
            'score' => "0:1"
        ]);

        $id2 = $I->haveInDatabase("events", [
            'opponent_1' => "G2",
            'opponent_2' => "Heretics",
            'date' => "2023-01-19",
            'discipline' => "Siatkówka",
            'time' => "20:00",
            'timezone' => 'UTC',
            'league' => 'LEC',
            'round' => 'Final',
            'status' => 'upcoming',
            'score' => null
        ]);

        $id3 = $I->haveInDatabase("events", [
            'opponent_1' => "Real Madrid",
            'opponent_2' => "Tottenham",
            'date' => "2023-01-19",
            'discipline' => "Piłka Nożna",
            'time' => "20:00",
            'timezone' => 'UTC',
            'league' => 'Champions League',
            'round' => 'Semi-Final',
            'status' => 'ended',
            'score' => "0:0"
        ]);

        $event1 = $I->grabEntryFromDatabase('events', ['id' => $id1]);
        $event2 = $I->grabEntryFromDatabase('events', ['id' => $id2]);
        $event3 = $I->grabEntryFromDatabase('events', ['id' => $id3]);

        $event1 = (object)$event1;
        $event2 = (object)$event2;
        $event3 = (object)$event3;

        $ido1 = $I->haveInDatabase("odds", [
            "event_id" => $event1->id,
            "win_op_1" => 1.2,
            "win_op_2" => 5,
            "sum" => 0
        ]);

        $ido2 = $I->haveInDatabase("odds", [
            "event_id" => $event2->id,
            "win_op_1" => 1.2,
            "win_op_2" => 5,
            "sum" => 0
        ]);

        $ido3 = $I->haveInDatabase("odds", [
            "event_id" => $event3->id,
            "win_op_1" => 1.2,
            "win_op_2" => 5,
            "sum" => 0
        ]);

        $odds1 = $I->grabEntryFromDatabase('odds', ['id' => $ido1]);
        $odds2 = $I->grabEntryFromDatabase('odds', ['id' => $ido2]);
        $odds3 = $I->grabEntryFromDatabase('odds', ['id' => $ido3]);

        $odds1 = (object)$odds1;
        $odds2 = (object)$odds2;
        $odds3 = (object)$odds3;

        $I->click("Zakłady bukmacherskie");
        $I->seeCurrentUrlEquals('/bets');

        $code = str_repeat('0', $events).'3';
        $I->fillField('amount', "5");
        $I->fillField('bet_code', $code);
        $I->click("Postaw kupon");
        $I->seeCurrentUrlEquals('/');
        $I->see("Kupon postawiony", "p");

        $I->updateInDatabase('events', ['date' => '2023-01-03', 'score' => '0:2'], ['id' => $event2->id]);

        $I->amOnPage('/account');
        $I->amOnPage('/account/history');
        $user_updated = $I->grabEntryFromDatabase('users', ['id' => $user->id]);
        $user_updated = (object)$user_updated;

        $I->assertEquals(floor(($user->deposit -5 +25*0.85)*100)/100, $user_updated->deposit);
        $I->see('Kupon wygrany');
        $I->seeInDatabase("bets", ['id' => $user->id, 'status' => '1', 'bet_result' => '2']);
    }
}
