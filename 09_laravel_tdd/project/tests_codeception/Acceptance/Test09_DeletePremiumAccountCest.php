<?php

namespace TestsCodeception\Acceptance;

use App\Models\User;
use App\Models\PremiumUser;
use Illuminate\Support\Facades\DB;
use TestsCodeception\Support\AcceptanceTester;

class Test09_DeletePremiumAccountCest
{
    public function deletepremiumaccountTest(AcceptanceTester $I): void
    {
        $I->wantTo('look at my account');

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
        $I->click('#UserIconA');
        $I->seeCurrentUrlEquals('/account');

        $I->click("Usuń konto");
        $I->seeCurrentUrlEquals('/account/delete');
        $I->see('Aby usunąć konto przepisz zdanie: "Litwo Ojczyzno Moja, Ty jesteś jak zdrowie, ile Cię trzeba cenić ten tylko się dowie kto Cię stracił."');

        $I->fillField('sentence', '"Litwo Ojczyzno Moja, Ty jesteś jak zdrowie, ile Cię trzeba cenić ten tylko się dowie kto Cię stracił"');
        $I->click("Usuń konto");

        $I->see("Bład w zdaniu potwierdzającym usunięcie konta", "li");

        $I->fillField('sentence', '"Litwo Ojczyzno Moja, Ty jesteś jak zdrowie, ile Cię trzeba cenić ten tylko się dowie kto Cię stracił."');
        $I->click("Usuń konto");

        $I->see("Twoje konto zostało doładowane kwotą 10zł, jest to kupon ostatniej szansy! Czy na pewno chcesz usunąć?", "p");
        $user_updated = $I->grabEntryFromDatabase('premiums', ['user_id' => $user->id]);
        $user_updated = (object)$user_updated;

        $I->assertEquals($user_updated->harakiried, true);

        $user_add = $I->grabEntryFromDatabase('users', ['id' => $user->id]);
        $user_add = (object)$user_add;

        $I->assertEquals($user->deposit+10, $user_add->deposit);

        $I->seeCurrentUrlEquals('/');
        $I->click('#UserIconA');
        $I->seeCurrentUrlEquals('/account');

        $I->click("Usuń konto");
        $I->seeCurrentUrlEquals('/account/delete');
        $I->see('Aby usunąć konto przepisz zdanie: "Litwo Ojczyzno Moja, Ty jesteś jak zdrowie, ile Cię trzeba cenić ten tylko się dowie kto Cię stracił."');

        $I->fillField('sentence', '"Litwo Ojczyzno Moja, Ty jesteś jak zdrowie, ile Cię trzeba cenić ten tylko się dowie kto Cię stracił."');
        $I->click("Usuń konto");

        $I->dontSeeInDatabase('users', ['id' => $user->id]);
        $I->dontSeeInDatabase('premiums', ['user_id' => $user_updated->id ]);
        $I->seeCurrentUrlEquals('/');
        $I->see("Konto pomyślnie usunięte. Żegnaj!", 'p');


        $titleList = ["Zarejestruj się", "Zaloguj się"];
        $linkList = ["/register", "/login"];

        for ($i = 0; $i < count($titleList); $i++) {
            $I->seeLink($titleList[$i], $linkList[$i]);
        }
    }
}
