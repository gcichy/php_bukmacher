<?php

namespace TestsCodeception\Acceptance;

use App\Models\Event;
use App\Models\Odds;
use App\Models\SpecialEvent;
use Illuminate\Support\Facades\DB;
use TestsCodeception\Support\AcceptanceTester;

class Test16_UnlogSpecialBetsCest
{
    public function unlogspecialbetsTest(AcceptanceTester $I): void
    {
        $I->wantTo('Unlog SpecialEvent Bet');
        $I->amOnPage("/home");
        $I->seeCurrentUrlEquals('/');

        $id1 = $I->haveInDatabase("special_events", [
            "question" => "Czy Pan Najlepszy Prowadzący mgr. inż. Damian Gwiżdż zaliczy nam ten projekt?",
            "answer_1" => "Tak",
            "answer_2" => "Nie",
            "correct" => "Tak"
        ]);

        $id2 = $I->haveInDatabase("special_events", [
            "question" => "W który z tych dwóch dni w Krakowie spadnie więcej śniegu?",
            "answer_1" => "Wielka Sobota",
            "answer_2" => "Wielki Piątek",
            "correct" => "Wielki Piątek"
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

        $I->dontSeeInDatabase("bets", ["id" => 11]);
        $I->fillField('amount', "10");
        $I->fillField('bet_code', '620');
        $I->click("Postaw kupon");

        $I->seeCurrentUrlEquals('/betcode');
        $I->see("Wygenerowany kod", "p");
        $I->seeElement("#code");
        $I->seeElement("#description");

        $odds_update = $I->grabEntryFromDatabase('odds', ['id' => $ido1]);
        $odds_update = (object)$odds_update;

        $I->assertEquals($odds1->sum, $odds_update->sum);
        $I->assertEquals($odds1->win_op_1, $odds_update->win_op_1);
        $I->assertEquals($odds1->win_op_2, $odds_update->win_op_2);
        $I->dontSeeInDatabase("bets", ["id" => 11]);
    }
}
