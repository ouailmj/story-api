<?php
/**
 * Created by PhpStorm.
 * User: farah-pc
 * Date: 23/05/18
 * Time: 14:42
 */

namespace Tests\acceptance\EventManager;


use Tests\_support\Pages\Event_test;
use Tests\_support\Pages\Login;

class EventTestCest
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

    public function Add_event_free(\AcceptanceTester $I)
    {
        $this->loginAsAdmin($I);
        $I->wantTo("Verification d'ajout d'un event");
        $I->amOnPage(Event_test::$URL);
        $I->click(Event_test::$Plan);
        $I->click('button[type="submit"]:nth-child(2) > li');
        $I->seeInCurrentUrl('/event-information/');
        $I->fillField(Event_test::$Description, 'hello');
        $I->fillField(Event_test::$event_name, 'title ');
        $I->fillField(Event_test::$Place, 'Marrakech');
        $I->fillField(Event_test::$StartDateField, '2018-10-23T10:00');
        $I->fillField(Event_test::$EndDateField, '2018-10-23T12:00');
        $I->selectOption('appbundle_event[privacy]', 'privé');
        $I->click(Event_test::$Next2);
        $I->seeInCurrentUrl('/event-cover/');
        $I->click(Event_test::$Next1);
        $I->click(Event_test::$true);
    }

   public function delete_event(\AcceptanceTester $I)
    {
        $this->loginAsAdmin($I);
        $I->amOnPage("/event/269");
        $I->click("form[name=\"form\"] > a");
    }
}