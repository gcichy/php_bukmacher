<?php

namespace TestsCodeception\Acceptance;

use App\Models\Event;
use App\Models\Test;
use Illuminate\Support\Facades\DB;
use TestsCodeception\Support\AcceptanceTester;

use function PHPUnit\Framework\stringContains;

class Test11_DisciplineCest
{
    public function disciplineTest(AcceptanceTester $I): void
    {
        $I->wantTo('Select discipline');

        $I->amOnPage('/home');

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
        $ido1 = $I->haveInDatabase("odds", [
            "event_id" => $event1["id"],
            "win_op_1" => 1.2,
            "win_op_2" => 5,
            "sum" => 0
        ]);

        $ido2 = $I->haveInDatabase("odds", [
            "event_id" => $event2["id"],
            "win_op_1" => 1.2,
            "win_op_2" => 5,
            "sum" => 0
        ]);

        $ido3 = $I->haveInDatabase("odds", [
            "event_id" => $event3["id"],
            "win_op_1" => 1.2,
            "win_op_2" => 5,
            "sum" => 0
        ]);

        $I->click("Dyscypliny");
        $I->seeCurrentUrlEquals('/');

        $disc_list = ['Piłka Nożna', 'Siatkówka', 'Piłka Ręczna', 'Dart', 'Koszykówka', 'E-sport'];
        $disc_link = ['/football', '/volleyball', '/handball', '/dart', '/basketball', '/esport'];
        for ($i = 0; $i < count($disc_list); $i++) {
            $I->seeLink($disc_list[$i], "/bets".$disc_link[$i]);
        }
        $I->click('Siatkówka');
        $I->seeCurrentUrlEquals('/bets/volleyball');
        $I->seeInDatabase("events", ['opponent_1' => 'G2']);
        $temp = $I->grabTextFrom("h4");
        $I->assertTrue(str_contains(strval($temp), "Siatkówka"));
        $I->assertFalse(str_contains(strval($temp), "Piłka Nożna"));
        $I->assertFalse(str_contains(strval($temp), "E-sport"));
        $I->assertFalse(str_contains(strval($temp), "Piłka Ręczna"));
        $I->assertFalse(str_contains(strval($temp), "Dart"));
        $I->assertFalse(str_contains(strval($temp), "Koszykówka"));

        $I->click("Dyscypliny");
        $I->seeCurrentUrlEquals('/bets/volleyball');

        $I->click("Piłka Nożna");
        $temp = $I->grabTextFrom("h4");
        $I->assertTrue(str_contains(strval($temp), "Piłka Nożna"));
        $I->assertFalse(str_contains(strval($temp), "E-sport"));
        $I->assertFalse(str_contains(strval($temp), "Siatkówka"));
        $I->assertFalse(str_contains(strval($temp), "Piłka Ręczna"));
        $I->assertFalse(str_contains(strval($temp), "Dart"));
        $I->assertFalse(str_contains(strval($temp), "Koszykówka"));
    }
}
