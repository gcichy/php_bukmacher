<?php

namespace TestsCodeception\Acceptance;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use TestsCodeception\Support\AcceptanceTester;

class Test06_BuyPremiumCest
{
    public function buypremiumTest(AcceptanceTester $I): void
    {
        $I->wantTo('buy premium account');

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
            'deposit' => 7,
        ]);

        $user = $I->grabEntryFromDatabase('users', ['id' => $id]);
        $user = (object) $user;

        $I->fillField('email', 'jacekSasin@wp.pl');
        $I->fillField('password', 'Jarek&Gwint77');
        $I->click('Zaloguj się');

        $I->seeCurrentUrlEquals('/');
        $I->click('#UserIconA');
        $I->seeCurrentUrlEquals('/account');


        $I->click("Kup konto premium");
        $I->seeCurrentUrlEquals('/account/premium');
        $I->dontSeeLink("Doładuj konto");
        $I->click("Kup");
        $I->see('Aby kontynuować zaakceptuj regulamin', 'li');

        $I->checkOption('form input[name="statute"]');
        $I->click("Kup");
        $I->see('Brak środków do zakupu konta premium.', 'p');

        $I->seeCurrentUrlEquals('/account/add_balance');
        $I->see("Doładuj konto", 'h1');

        $I->click("Potwierdź");
        $I->see('Należy podac kwotę.', 'li');

        $code = '234567';
        $I->haveInDatabase('blik_codes', ["code"=>$code]);
        $I->fillField('amount', '50');
        $I->fillField('blikCode', $code);
        $I->click('Potwierdź');

        $I->seeCurrentUrlEquals('/account');
        $I->click("Kup konto premium");
        $I->seeCurrentUrlEquals('/account/premium');

        $I->checkOption('form input[name="statute"]');
        $I->dontSeeInDatabase("premiums", ['user_id'=>$user->id]);
        $I->click("Kup");

        $I->seeCurrentUrlEquals('/account');

        $I->seeInDatabase('premiums', ['user_id'=>$user->id]);


        $I->see("Gratulujemy zostania użytkownikiem premium ".$user->name."!", 'p');
        $day = getdate();
        if ($day['mon'] == 12) {
            $array = [$day['mday'] , 1 , $day['year'] + 1];
        } else {
            $array = [$day['mday'] , $day['mon'] + 1, $day['year']];
        }
        $I->see("Data wygaśnięcia konta premium: ".implode("/", $array), 'p');

        $user_updated = $I->grabEntryFromDatabase('users', ['person_number' => $user->person_number]);
        $user_updated = (object)$user_updated;
        $I->seeInDatabase("premiums", ['user_id'=>$user_updated->id]);
        $I->assertEquals($user_updated->premium, true);
        $I->see("Premium", 'p');

        $seepremium = [ "Zakłady live", "Zdrapka"];
        for ($i = 0; $i < count($seepremium); $i++) {
            $I->see($seepremium[$i]);
        }
        $I->dontSeeLink("Kup konto premium");
        $I->click("Strona główna");
        $I->seeCurrentUrlEquals('/');
        $I->seeNumberOfElements(".ads_img", 0);

        $titleList = ["Zakłady live", "Zdrapka", "Zakłady bukmacherskie", "Zakłady specjalne", "Strona główna", "Wyniki"];
        $linkList = ["/livebets", "/scratchcard", "/bets", "/specialoffers", "/home", '/results'];

        for ($i = 0; $i < count($titleList); $i++) {
            $I->seeLink($titleList[$i], $linkList[$i]);
        }
    }
}
