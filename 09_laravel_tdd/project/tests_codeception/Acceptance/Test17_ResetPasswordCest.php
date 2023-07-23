<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test17_ResetPasswordCest
{
    public function loginTest(AcceptanceTester $I): void
    {
        $I->wantTo('login with existing user');

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
            'person_number' => '12345678901',
            'confirmed' => true,
            'premium' => false,
            'deposit' => 70000000,
        ]);

        $I->click('Zapomniałeś hasła?');

        $I->seeCurrentUrlEquals('/forgot-password');

        $I->dontSee("Otrzymany kod");
        $I->dontSee("Hasło");
        $I->dontSee("Potwierdź hasło");

        $I->see('Zapomniałeś hasła? Żaden problem. Podaj nam swój adres email, na który wyślemy Ci link do zmiany hasła na nowe.');
        $I->see("Zatwierdź", 'button');

        $I->click('Zatwierdź');
        $I->see('Pole email jest wymagane');

        $I->fillField('email', 'jjj@');
        $I->click('Zatwierdź');
        $I->see("Adres email musi zawierać znak '@', a po nim adres domeny.");

        $I->fillField('email', 'acekSasin@wp.pl');
        $I->click('Zatwierdź');
        $I->see("Konto z podanym adresem email nie istnieje");

        $I->fillField('email', 'jacekSasin@wp.pl');
        $I->click('Zatwierdź');


        $I->see('Kod zmiany hasła został wysłany na email: jacekSasin@wp.pl');
        $I->see("Otrzymany kod");
        $I->see("Hasło");
        $I->see("Potwierdź hasło");

        $I->see('Zresetuj Hasło', 'button');

        $I->click('Zresetuj Hasło');
        $I->seeNumberOfElements('.error', 3);
        $I->see('Kod zmiany hasła został wysłany na email: jacekSasin@wp.pl');

        $I->fillField('token', '234');
        $I->click('Zresetuj Hasło');
        $I->seeNumberOfElements('.error', 2);

        $I->fillField('password', 'NoweHasłoJa$ka1');
        $I->click('Zresetuj Hasło');
        $I->see('Pole Otrzymany kod jest wymagane');
        $I->see('Pole Hasło różni się od pola Potwierdź hasło.');
        $I->see('Pole Potwierdź hasło jest wymagane');

        $url = explode("/", strval($I->grabFromCurrentUrl()));
        $token = substr($url[2], 0, 10);

        $I->fillField('token', $token);
        $I->fillField('password', 'NoweHasłoJa$ka1');
        $I->fillField('password_confirmation', 'NoweHasłoJa$ka1');
        $I->click('Zresetuj Hasło');

        $I->seeCurrentUrlEquals('/login');
        $I->see('Zmieniono hasło');

        $I->fillField('email', 'jacekSasin@wp.pl');
        $I->fillField('password', 'NoweHasłoJa$ka1');
        $I->click('Zaloguj się');


        $I->seeCurrentUrlEquals('/');
        $I->see('Witaj z powrotem Jacek!');
    }
}
