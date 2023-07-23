<?php

namespace TestsCodeception\Acceptance;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use TestsCodeception\Support\AcceptanceTester;

class Test12_BetsCest
{
    public function betsTest(AcceptanceTester $I): void
    {
        $I->wantTo('Bet');

        $I->amOnPage('/login');

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
        $I->fillField('email', 'jacekSasin@wp.pl');
        $I->fillField('password', 'Jarek&Gwint77');
        $I->click('Zaloguj się');

        $I->seeCurrentUrlEquals('/');

        $id1 = $I->haveInDatabase("events", [
            'opponent_1' => "Krzysztof Ratajski",
            'opponent_2' => "Micheal Smith",
            'date' => "2023-01-26",
            'discipline' => "Dart",
            'time' => "21:00",
            'timezone' => 'UTC',
            'league' => 'World Cup',
            'round' => 'Final',
            'status' => 'upcoming',
            'score' => null
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
            'status' => 'upcoming',
            'score' => null
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

        $I->amOnPage("/account/history");
        $I->see("Brak kuponów w historii.");
        $I->dontSeeElement(".bet");

        $I->click("Zakłady bukmacherskie");
        $I->seeCurrentUrlEquals('/bets');

        $I->see($event1->opponent_1);
        $I->see("X");
        $I->see($event1->opponent_2);
        $I->see("12");

        $I->see($event2->opponent_1);
        $I->see("X");
        $I->see($event2->opponent_2);
        $I->see("12");

        $I->see("Stawka");
        $I->click("Postaw kupon");
        $I->see("Nie możesz obstawić pustego kuponu.", "li");

        $I->fillField('bet_code', '620');
        $I->click("Postaw kupon");
        $I->see("Podaj kwotę kuponu.", 'li');

        $I->dontSeeInDatabase("bets", ['id' => $user->id]);
        $I->fillField('amount', "10");
        $I->fillField('bet_code', '620');
        $I->click("Postaw kupon");
        $I->seeCurrentUrlEquals('/account/add_balance');
        $I->see("Brak wystarczających środków na koncie.", "p");

        $I->amOnPage('/bets');
        $I->fillField('amount', "5");
        $I->fillField('bet_code', '620');
        $I->click("Postaw kupon");
        $I->seeCurrentUrlEquals('/');
        $I->see("Kupon postawiony", "p");

        $user_updated = $I->grabEntryFromDatabase('users', ['id' => $user->id]);
        $user_updated = (object)$user_updated;

        $odds_update = $I->grabEntryFromDatabase('odds', ['id' => $ido1]);
        $odds_update = (object)$odds_update;

        $I->assertEquals($user->deposit - 5, $user_updated->deposit);
        $I->seeInDatabase("bets", ['id' => $user->id]);

        $I->amOnPage("/account/history");
        $I->see("Historia kuponów");
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
        $I->see(implode("-", $array));
    }
}
