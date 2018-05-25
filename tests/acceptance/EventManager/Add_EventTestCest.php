<?php
/**
 * Created by PhpStorm.
 * User: farah-pc
 * Date: 23/05/18
 * Time: 14:42
 */

namespace Tests\acceptance\EventManager;


use Tests\_support\Pages\Add_event;
use Tests\_support\Pages\Login;

class Add_EventTestCest
{
    public function loginAsAdmin(\AcceptanceTester $I) //yes
    {
        $I->wantTo("Verify the Admin login");
        $I->amOnPage(Login::$URL);
        $I->FillField(Login::$usernameField, "admin");
        $I->FillField(Login::$passwordField, "f%/R4Uk#](wUvM'V");
        $I->click(Login::$submitButton);
        $I->seeCurrentUrlEquals('/auth/profile/edit');
    }

    public function Add_event(\AcceptanceTester $I)
    {
        $this->loginAsAdmin($I);
        $I->wantTo("Verification d'ajout d'un event");
        $I->amOnPage(Add_event::$URL);
        $I->click("choose_plan[plan]");
        $I->click('button[type="submit"]:nth-child(2) > li');
        $I->seeInCurrentUrl('/event-information/');
        $I->FillField(Add_event::$event_name, "Weeding");
        $I->FillField(Add_event::$Place, "Istanbul");
        $I->FillField(Add_event::$StartDateField, "22/06/2018, 08:20");
        $I->FillField(Add_event::$EndDateField, "22/06/2018, 9:30");
        $I->FillField(Add_event::$Description, "This is my sister's birthday!");
        $I->click(Add_event::$Next2);
        $I->see('event cover');
        $I->click("button[type=\"submit\"] > li > i");
        $I->fillField('listItem', 'farah.oubelkas@gmail.com');
        $I->click('Add');
        $I->click(Add_event::$true);
        $I->seeCurrentURLEquals('/events');
//        $I->FillField(Add_event::$email_payment, "example@email.com");
//        $I->click(Add_event::$add);

    }
}