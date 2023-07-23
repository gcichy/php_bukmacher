<?php

namespace TestsCodeception\Acceptance;

use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use TestsCodeception\Support\AcceptanceTester;

class Test13_LiveBetsCest
{
    public function livebetsTest(AcceptanceTester $I): void
    {
        $I->wantTo('Live Bet');

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
            'premium' => true,
            'deposit' => 7,
        ]);
        $user = $I->grabEntryFromDatabase('users', ['id' => $id]);
        $user = (object)$user;

        $ip = $I->haveInDatabase("premiums", [
            'scratches_left' => 3,
            'user_id' => $user->id,
            'scratchcard_id' => '123244123',
            'expiration_date' => '12/02/2023',
            'harakiried' => false
        ]);
        $user_premium = $I->grabEntryFromDatabase('premiums', ['user_id' => $user->id]);
        $user_premium = (object)$user_premium;

        $I->fillField('email', 'jacekSasin@wp.pl');
        $I->fillField('password', 'Jarek&Gwint77');
        $I->click('Zaloguj się');

        $I->seeCurrentUrlEquals('/');

        $id1 = $I->haveInDatabase("events", [
            'opponent_1' => "Krzysztof Ratajski",
            'opponent_2' => "Micheal Smith",
            'date' => "2023-01-16",
            'discipline' => "dart",
            'time' => "21:00",
            'timezone' => 'UTC',
            'league' => 'World Cup',
            'round' => 'Final',
            'status' => 'live',
            'score' => "5-3"
        ]);

        $id2 = $I->haveInDatabase("events", [
            'opponent_1' => "G2",
            'opponent_2' => "Heretics",
            'date' => "2023-01-20",
            'discipline' => "esport",
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
            'date' => "2023-01-16",
            'discipline' => "football",
            'time' => "20:00",
            'timezone' => 'UTC',
            'league' => 'Champions League',
            'round' => 'Semi-Final',
            'status' => 'live',
            'score' => "2-0"
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
            "sum" => 0,
        ]);
        $ido2 = $I->haveInDatabase("odds", [
            "event_id" => $event3->id,
            "win_op_1" => 1.2,
            "win_op_2" => 5,
            "sum" => 0,
        ]);
        $odds1 = $I->grabEntryFromDatabase('odds', ['id' => $ido1]);
        $odds1 = (object)$odds1;

        $I->amOnPage("/account/history");
        $I->see("Brak kuponów w historii.");
        $I->dontSeeElement(".bet");

        $I->click("Zakłady live");
        $I->seeCurrentUrlEquals("/livebets");
        $I->see("Stawka");

        $I->click("Postaw kupon");
        $I->see("Nie możesz obstawić pustego kuponu.", "li");

        $I->see($event1->opponent_1);
        $I->dontSee($event2->opponent_1);

        $I->fillField('bet_code', '62');
        $I->click("Postaw kupon");
        $I->see("Możliwość postawienia jednego meczu live", 'li');

        $I->fillField('bet_code', '60');
        $I->click("Postaw kupon");
        $I->see("Podaj kwotę kuponu.", 'li');

        $I->dontSeeInDatabase("bets", ["user_id" => $user->id]);
        $I->fillField('amount', "10");
        $I->fillField('bet_code', '60');
        $I->click("Postaw kupon");
        $I->seeCurrentUrlEquals('/account/add_balance');
        $I->see("Brak wystarczających środków na koncie.", "p");

        $I->amOnPage('/livebets');
        $I->fillField('bet_code', '60');
        $I->fillField('amount', "5");
        $I->click("Postaw kupon");
        $I->seeCurrentUrlEquals('/');
        $I->see("Kupon postawiony", "p");

        $user_updated = $I->grabEntryFromDatabase('users', ['id' => $id]);
        $user_updated = (object)$user_updated;
        $odds_update = $I->grabEntryFromDatabase('odds', ['id' => $ido1]);
        $odds_update = (object)$odds_update;

        $I->assertEquals($user->deposit - 5, $user_updated->deposit);
        $I->seeInDatabase("bets", ["user_id" => $user->id]);

        $I->amOnPage("/account/history");
        $I->see("Historia kuponów");
        $day = getdate();
        $array = [$day['mday'] , $day['mon'], $day['year']];
        $I->see(implode("/", $array));
    }
}
