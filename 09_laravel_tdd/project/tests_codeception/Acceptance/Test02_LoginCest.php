<?php

namespace TestsCodeception\Acceptance;

use App\Models\User;

use TestsCodeception\Support\AcceptanceTester;

class Test02_LoginCest
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

        $I->click('Zaloguj się');
        $I->see('Pole email jest wymagane', 'li');
        $I->see('Pole hasło jest wymagane', 'li');

        $I->fillField('email', 'jacekSasin@wp.pl');
        $I->click('Zaloguj się');
        $I->see('Pole hasło jest wymagane', 'li');

        $I->fillField('password', 'złe hasło');
        $I->click('Zaloguj się');
        $I->see('Błędny email lub hasło.', 'li');

        $I->fillField('email', 'jacekSasin@wp.pl');
        $I->fillField('password', 'złe hasło');

        $I->click('Zaloguj się');

        $I->see('Błędny email lub hasło.', 'li');

        $I->fillField('email', 'jasnyPieron@gmail.com');
        $I->fillField('password', 'Jarek&Gwint77');
        $I->click('Zaloguj się');

        $I->see('Błędny email lub hasło.', 'li');

        $I->fillField('email', 'jacekSasin@wp.pl');
        $I->fillField('password', 'Jarek&Gwint77');

        $I->click('Zaloguj się');
        $I->dontSee('Błędny email lub hasło.', 'li');

        $I->seeCurrentUrlEquals('/');
        $I->seeNumberOfElements(".ads_img", 2);

        $user = $I->grabEntryFromDatabase('users', ['email' => 'jacekSasin@wp.pl']);
        $I->see('Witaj z powrotem '.$user['name'].'!', 'p');
        $I->see('Stan Konta: '.$user['deposit'], 'p');
        $I->see('Konto Standardowe', 'p');

        $I->amOnPage('/bets');
        $I->amOnPage('/home');
        $I->dontSee('Witaj z powrotem '.$user['name'].'!', 'p');
        $I->see('Stan Konta: '.$user['deposit'], 'p');
        $I->see('Konto Standardowe', 'p');

        $I->see('Wyloguj się', 'button');
        $I->click('Wyloguj się');

        $I->seeCurrentUrlEquals('/');
        $I->dontSee('Stan Konta: '.$user['deposit'], 'p');
        $I->dontSee('Konto Standardowe', 'p');
    }
}
