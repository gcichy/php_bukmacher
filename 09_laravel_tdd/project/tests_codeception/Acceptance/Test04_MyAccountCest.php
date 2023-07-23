<?php

namespace TestsCodeception\Acceptance;

use App\Models\User;
use TestsCodeception\Support\AcceptanceTester;

class Test04_MyAccountCest
{
    public function myaccountTest(AcceptanceTester $I): void
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
        $notTitle = [ "Moje konto", "Zakłady live", "Zdrapka"];
        for ($i = 0; $i < count($notTitle); $i++) {
            $I->dontSee($notTitle[$i]);
        }
        $titleList = ["Zakłady bukmacherskie", "Zakłady specjalne", "Strona główna"];
        $linkList = ["/bets", "/specialoffers", "/home"];

        for ($i = 0; $i < count($titleList); $i++) {
            $I->seeLink($titleList[$i], $linkList[$i]);
        }
        $I->see("Imie", "p");
        $I->see("Jacek", "p");
        $I->see("Nazwisko", "p");
        $I->see("Sasin", "p");
        $I->see("email", "p");
        $I->see("jacekSasin@wp.pl", "p");
        $I->see("Pseudonim", "p");
        $I->see("Jaca", "p");
        $I->see("Numer telefonu", "p");
        $I->see("999999999", "p");
        $I->see("Pesel", "p");
        $I->see("81010277901", "p"); // data urodzenia i plec odczytywana z peselu
        $I->see("Data urodzenia", "p");
        $I->see("02-01-1981", "p");
        $I->see("Płeć", "p");
        $I->see("mężczyzna", "p");
        $I->see("Rodzaj konta", "p");
        $I->see("Standardowe", "p");
        $I->seeLink("Doładuj konto");
        $I->seeLink("Kup konto premium");
        $I->seeLink("Historia kuponów");
        $I->seeLink("Usuń konto");
    }
}
