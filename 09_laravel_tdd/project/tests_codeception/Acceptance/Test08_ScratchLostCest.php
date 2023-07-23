<?php

namespace TestsCodeception\Acceptance;

use App\Models\User;
use App\Models\Premium;
use Illuminate\Support\Facades\DB;
use TestsCodeception\Support\AcceptanceTester;

class Test08_ScratchLostCest
{
    public function scratchwontest(AcceptanceTester $I): void
    {
        $I->wantTo('Lose Scratch');

        $I->amOnPage('/home');

        $I->seeCurrentUrlEquals('/');

        $I->click("Zaloguj się");
        $I->seeCurrentUrlEquals('/login');


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
            'deposit' => 70000000,
        ]);
        $user = $I->grabEntryFromDatabase('users', ['id' => $id]);
        $user = (object)$user;
        $id = $I->haveInDatabase("premiums", [
            'scratches_left' => 3,
            'user_id' => $user->id,
            'scratchcard_id' => '123244123',
            'expiration_date' => '12/02/2023',
            'harakiried' => false
        ]);

        $I->fillField('email', 'jacekSasin@wp.pl');
        $I->fillField('password', 'Jarek&Gwint77');
        $I->click('Zaloguj się');

        $I->seeCurrentUrlEquals('/');

        $I->seeLink('Zdrapka');
        $I->click('Zdrapka');
        $I->seeCurrentUrlEquals('/scratchcard');

        $I->seeElement('#scratch');

        $I->see('Pozostała liczba prób: 3', 'p');
        $I->click('#scratch_1');
        $I->click('#scratch_7');

        $I->click("Strona główna");
        $I->seeCurrentUrlEquals('/');
        $I->click('Zdrapka');

        $I->see('Pozostała liczba prób: 1', 'p');

        $premium = $I->grabEntryFromDatabase('premiums', ['user_id' => $user->id]);
        $premium = (object)$premium;

        $I->assertEquals($premium->scratches_left, 1);
        $I->see($user->deposit);

        $I->click('#scratch_9');

        $I->seeElement('#endGame');

        $I->see($user->deposit);

        $user_after = $I->grabEntryFromDatabase('users', ['id' => $user->id]);
        $user_after = (object)$user_after;

        $I->assertEquals($user_after->deposit, $user->deposit);

        $I->amOnPage('/');
        $I->dontSeeElement('#endGame');
        $I->amOnPage('/scratchcard');

        $I->seeElement('#endGame');

        $I->click('#scratch_2');
        $I->see('Pozostała liczba prób: 0', 'p');
        $I->seeElement('#wait');
    }
}
