<?php

namespace TestsCodeception\Acceptance;

use App\Models\User;
use TestsCodeception\Support\AcceptanceTester;

class Test10_DeleteAccountCest
{
    public function deleteaccountTest(AcceptanceTester $I): void
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
            'premium' => false,
            'deposit' => 70000000,
        ]);

        $I->fillField('email', 'jacekSasin@wp.pl');
        $I->fillField('password', 'Jarek&Gwint77');
        $I->click('Zaloguj się');

        $I->seeCurrentUrlEquals('/');
        $I->click('#UserIconA');
        $I->seeCurrentUrlEquals('/account');

        $I->click("Usuń konto");
        $I->seeCurrentUrlEquals('/account/delete');
        $I->see('Aby usunąć konto przepisz zdanie: "Nie płacz kiedy odjadę."');

        $I->fillField('sentence', '"Nie płacz kiedy odjadę"');
        $I->click("Usuń konto");

        $I->see("Bład w zdaniu potwierdzającym usunięcie konta", "li");

        $I->fillField('sentence', '"Nie płacz kiedy odjadę."');
        $I->click("Usuń konto");


        $I->dontSeeInDatabase('users', ['id' => $id]);
        $I->seeCurrentUrlEquals('/');
        $I->see("Konto pomyślnie usunięte. Żegnaj!", 'p');

        $titleList = ["Zarejestruj się", "Zaloguj się"];
        $linkList = ["/register", "/login"];

        for ($i = 0; $i < count($titleList); $i++) {
            $I->seeLink($titleList[$i], $linkList[$i]);
        }
    }
}
