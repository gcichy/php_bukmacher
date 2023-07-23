<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test18_ResultsCest
{
    public function resultsTest(AcceptanceTester $I): void
    {
        $I->wantTo('See Results');

        $id1 = $I->haveInDatabase("events", [
            'opponent_1' => "Krzysztof Ratajski",
            'opponent_2' => "Micheal Smith",
            'date' => "06/01/2023",
            'discipline' => "dart",
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
            'date' => "09/01/2023",
            'discipline' => "esport",
            'time' => "20:00",
            'timezone' => 'UTC',
            'league' => 'LEC',
            'round' => 'Final',
            'status' => 'ended',
            'score' => '2:1'
        ]);

        $id3 = $I->haveInDatabase("events", [
            'opponent_1' => "Real Madrid",
            'opponent_2' => "Tottenham",
            'date' => "04/01/2023",
            'discipline' => "football",
            'time' => "20:00",
            'timezone' => 'UTC',
            'league' => 'Champions League',
            'round' => 'Semi-Final',
            'status' => 'live',
            'score' => '0:0'
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

        $I->amOnPage('/results');
        $I->seeCurrentUrlEquals('/results');

        $titleList = ["Zarejestruj się", "Zaloguj się", "Zakłady bukmacherskie", "Zakłady specjalne", "Strona główna", "Wyniki"];
        $linkList = ["/register", "/login", "/bets", "/specialoffers", "/home", "/results"];

        for ($i = 0; $i < count($titleList); $i++) {
            $I->seeLink($titleList[$i], $linkList[$i]);
        }
        $I->see($event2->opponent_1);
        $I->dontSee($event1->opponent_1);
        $I->dontSee($event3->opponent_1);
    }
}
