<?php

namespace TestsCodeception\Acceptance;

use App\Models\SpecialEvent;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use TestsCodeception\Support\AcceptanceTester;

class Test15_SpecialBetsCest
{
    public function specialbetsTest(AcceptanceTester $I): void
    {
        $I->wantTo('SpecialEvent Bet');

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

        $id1 = $I->haveInDatabase("special_events", [
            "question" => "W który z tych dwóch dni w Krakowie spadnie więcej śniegu?",
            "answer_1" => "Wielka Sobota",
            "answer_2" => "Wielki Piątek",
            "correct" => "Wielki Piątek"
        ]);

        $id2 = $I->haveInDatabase("special_events", [
            "question" => "Czy Pan Najlepszy Prowadzący mgr. inż. Damian Gwiżdż zaliczy nam ten projekt?",
            "answer_1" => "Tak",
            "answer_2" => "Nie",
            "correct" => "Tak"
        ]);

        $event1 = $I->grabEntryFromDatabase('special_events', ['id' => $id1]);
        $event2 = $I->grabEntryFromDatabase('special_events', ['id' => $id2]);
        $event1 = (object)$event1;
        $event2 = (object)$event2;

        $ido1 = $I->haveInDatabase("odds", [
            "event_id" => $event1->id,
            "win_op_1" => 1,
            "win_op_2" => 1000,
            "sum" => 0
        ]);

        $ido2 = $I->haveInDatabase("odds", [
            "event_id" => $event2->id,
            "win_op_1" => 1.9,
            "win_op_2" => 1.9,
            "sum" => 0,
        ]);

        $odds1 = $I->grabEntryFromDatabase('odds', ['id' => $ido1]);
        $odds2 = $I->grabEntryFromDatabase('odds', ['id' => $ido2]);
        $odds1 = (object)$odds1;
        $odds2 = (object)$odds2;

        $I->click("Zakłady specjalne");
        $I->seeCurrentUrlEquals('/specialoffers');

        $I->see($event1->answer_1);
        $I->see($event1->answer_2);


        $I->see("Stawka");
        $I->click("Postaw kupon");
        $I->see("Nie możesz obstawić pustego kuponu.", "li");

        $I->fillField('bet_code', '620');
        $I->click("Postaw kupon");
        $I->see("Podaj kwotę kuponu.", 'li');

        $I->dontSeeInDatabase("bets", ["id" => $user->id]);
        $I->fillField('amount', "10");
        $I->fillField('bet_code', '620');
        $I->click("Postaw kupon");
        $I->seeCurrentUrlEquals('/account/add_balance');
        $I->see("Brak wystarczających środków na koncie.", "p");

        $I->amOnPage('/specialoffers');
        $I->fillField('amount', "5");
        $I->fillField('bet_code', '620');
        $I->click("Postaw kupon");
        $I->seeCurrentUrlEquals('/');
        $I->see("Kupon postawiony", "p");

        $user_updated = $I->grabEntryFromDatabase('users', ['id' => $user->id]);
        $user_updated = (object)$user_updated;

        $odds_update = $I->grabEntryFromDatabase('odds', ['id' => $odds1->id]);
        $odds_update = (object)$odds_update;

        $I->assertEquals($user->deposit - 5, $user_updated->deposit);
        $I->seeInDatabase("bets", ["user_id" => $user->id]);

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
