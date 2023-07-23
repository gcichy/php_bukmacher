<?php

namespace TestsCodeception\Acceptance;

use TestsCodeception\Support\AcceptanceTester;

class Test03_MainPageCest
{
    public function loginTest(AcceptanceTester $I): void
    {
        $I->wantTo('See Welcome Page');

        $I->amOnPage('/home');

        $I->seeCurrentUrlEquals('/');
        //menu
        $titleList = ["Zarejestruj się", "Zaloguj się", "Zakłady bukmacherskie", "Zakłady specjalne", "Strona główna", "Wyniki"];
        $linkList = ["/register", "/login", "/bets", "/specialoffers", "/home", "/results"];

        for ($i = 0; $i < count($titleList); $i++) {
            $I->seeLink($titleList[$i], $linkList[$i]);
        }
        //not in menu
        $notTitle = [ "Zakłady live", "Zdrapka"];
        for ($i = 0; $i < count($notTitle); $i++) {
            $I->dontSee($notTitle[$i]);
        }

        //main page
        $mainList = ["O nas", "Kontakt", "Regulamin"];
        for ($i = 0; $i < count($mainList); $i++) {
            $I->see($mainList[$i]);
        }

        //check ads
        $I->seeNumberOfElements(".ads_img", 3);

        //check logo image
        $I->seeElement('img', ["alt"=>"Logo"]);
    }
}
