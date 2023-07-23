<?php

namespace TestsCodeception\Acceptance;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use TestsCodeception\Support\AcceptanceTester;

class Test05_AddBalanceCest
{
    public function addbalanceTest(AcceptanceTester $I): void
    {
        $I->wantTo('Add balance to my account');

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

        $user = $I->grabEntryFromDatabase('users', ['id' => $id]);
        $user = (object)$user;

        $I->fillField('email', 'jacekSasin@wp.pl');
        $I->fillField('password', 'Jarek&Gwint77');
        $I->click('Zaloguj się');

        $I->seeCurrentUrlEquals('/');
        $I->click('#UserIconA');
        $I->seeCurrentUrlEquals('/account');


        $I->click("Doładuj konto");
        $I->seeCurrentUrlEquals('/account/add_balance');
        $notTitle = [ "Moje konto", "Zakłady live", "Zdrapka"];
        for ($i = 0; $i < count($notTitle); $i++) {
            $I->dontSee($notTitle[$i]);
        }
        $titleList = ["Zakłady bukmacherskie", "Dyscypliny", "Zakłady specjalne", "Strona główna"];
        $linkList = ["/bets", "/sports", "/specialoffers", "/home"];

        for ($i = 0; $i < count($titleList); $i++) {
            $I->dontSeeLink($titleList[$i], $linkList[$i]);
        }
        $I->seeElement("#UserIconA");

        //specjalnie błędny kod, którego nie będzie w naszej bazie
        $I->fillField('blikCode', '3457');
        $I->click('Potwierdź');

        $I->see("Należy podac kwotę.");

        $I->fillField('blikCode', '3457');
        $I->fillField('amount', '50');
        $I->click('Potwierdź');
        $I->see("Doładowanie nie powiodło się, zły kod blik.");

        $code = '234567';
        $I->haveInDatabase('blik_codes', ["code"=>$code]);
        $I->fillField('amount', '50');
        $I->fillField('blikCode', $code);
        $I->click('Potwierdź');

        $I->seeCurrentUrlEquals('/account');
        $I->see('Twoje konto zostało doładowane!');

        //sprawdzenie czy balans zmienił się w bazie i na stronie
        $user_updated = $I->grabEntryFromDatabase('users', ['id' => $user->id]);
        $user_updated = (object)$user_updated;

        $I->assertEquals($user_updated->deposit, $user->deposit + 50);
        $I->see($user_updated->deposit);
    }
}
