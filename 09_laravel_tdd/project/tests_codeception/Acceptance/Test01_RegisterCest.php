<?php

namespace TestsCodeception\Acceptance;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use TestsCodeception\Support\AcceptanceTester;

class Test01_RegisterCest
{
    public function registerTest(AcceptanceTester $I): void
    {
        $I->wantTo('Register new user');

        $I->amOnPage('/home');

        $I->seeCurrentUrlEquals('/');

        $I->click('Zarejestruj się');

        $I->seeCurrentUrlEquals('/register');

        $I->click("Załóż konto");

        $I->seeNumberOfElements(".error", 9);

        //password complexity check
        $I->fillField('email', 'john.gmail.com');
        $I->fillField('name', 'John');
        $I->fillField('surname', 'Doee');
        $I->fillField('nickname', 'DoeDoeeJohn');
        $I->fillField('password', 'secret');
        $I->fillField('repeat_password', 'secret1');
        $I->fillField('phone_number', '+48721997998');
        $I->fillField('person_number', '22211194853'); //od 2016, generator - https://pesel.cstudios.pl/o-generatorze/generator-on-line#
        $I->fillField('promo_code', '');
        $I->checkOption('form input[name="terms"]');

        $I->seeCheckboxIsChecked('#terms');

        $I->click("Załóż konto");
        $I->seeNumberOfElements(".error", 4);

        $I->see('Email jest niepoprawny', 'li');
        $I->see('Hasło musi zawierać przynajmniej 8 znaków, wielką literę, małą literę, cyfrę oraz jeden znak specjalny', 'li');
        $I->see('Użytkownik musi mieć ukończone 18 lat', 'li');

        $I->fillField('password', 'Jassny&Gwint77');
        $I->fillField('email', 'john@gmail.com');
        $I->fillField('person_number', '103229012232000');


        $I->click("Załóż konto");
        $I->seeNumberOfElements(".error", 3);

        $I->see('Pole Powtórz hasło jest wymagane', 'li');
        $I->see('Wymagana jest akceptacja regulaminu', 'li');
        $I->see('Pesel musi mieć 11 cyfr', 'li');

        $I->fillField('password', 'Jassny&Gwint77');
        $I->fillField('repeat_password', 'Jassny&Gwint77');
        $I->fillField('person_number', '99021077614');//99021077614  02322901223
        $I->checkOption('form input[name="terms"]');

        $I->wantTo('See if User is in database');
        $I->dontSeeInDatabase('users', ['person_number' => '99021077614']);

        $I->click("Załóż konto");
        $I->seeInDatabase('users', ['person_number' => '99021077614']);

        $user = $I->grabEntryFromDatabase('users', ['person_number' => '99021077614']);
        $user = (object)$user;

        $I->assertEquals($user->email, "john@gmail.com");
        $I->assertEquals($user->name, "John");
        $I->assertEquals($user->surname, "Doee");
        $I->assertEquals($user->nickname, "DoeDoeeJohn");
        $I->assertTrue(password_verify("Jassny&Gwint77", $user->password));
        $I->assertEquals($user->phone_number, "+48721997998");
        $I->assertEquals($user->person_number, "99021077614");


        $I->see("Wprowadź kod potwierdzający, który został wysłany na twojego maila.", 'p');

        $I->click('Potwierdź rejestrację');
        $I->see('Wprowadź kod, który przesłaliśmy ci na maila', 'li');

        $I->fillField('token', '1234');
        $I->click('Potwierdź rejestrację');
        $I->see('Wprowadzony kod jest niepoprawny. Spróbuj ponownie', 'li');

        $url = explode("/", strval($I->grabFromCurrentUrl()));
        $I->fillField('token', substr($url[2], 0, 10));
        $I->click('Potwierdź rejestrację');

        $I->seeCurrentUrlEquals('/login');
        $I->see('Rejestracja przebiegła pomyślnie. Powodzenia!', 'p');
    }
}
