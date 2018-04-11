<?php

namespace Admin;

class AdminTestCest
{
    public function frontpageWorks(\AcceptanceTester $I)
    {
        $I->wantTo("Test frontpage");
        $I->amOnPage('/');
        $I->see('Welcome');
    }

    public function loginAsAdmin(\AcceptanceTester $I)
    {
        $I->wantTo("Verify the Admin login");
        $I->amOnPage("/auth/register/");
        $I->click("div.text-center a");
        $I->seeCurrentUrlEquals('/auth/login/');
        $I->FillField('_username', "admin");
        $I->FillField('_password', "f%/R4Uk#](wUvM'V");
        $I->click('_submit');
        $I->seeCurrentUrlEquals('/');
    }


    public function AfficherDetailsUser(\AcceptanceTester $I)
    {
        $I->wantTo("Afficher les infos de User_test d'un utilisateur");
        $this->loginAsAdmin($I);
        $I->amOnPage("/admin/user/43");
        $I->see('user_test');
    }

    public function ModifierInfoUser(\AcceptanceTester $I) //tested
    {
        $I->wantTo("Modifier les infos d'un utilisateur");
        $this->loginAsAdmin($I);
        $I->amOnPage("/admin/user/42/edit");
        $I->fillField('appbundle_user[phoneNumber]', '0645879633');
        $I->click('div.btn-group button');
    }

    public function Add_user(\AcceptanceTester $I)
    {
        $I->wantTo("Ajouter un utilisateur");
        $this->loginAsAdmin($I);
        $I->amOnPage('/admin/user/new');
        $I->fillField('appbundle_user[fullName]', 'usertest');
        $I->fillField('appbundle_user[username]', 'usertest');
        $I->fillField('appbundle_user[email]', 'usertest.email@gmail.com');
        $I->fillField('appbundle_user[new_password][first]', "f%/R4Uk#](wUvM'V");
        $I->fillField('appbundle_user[new_password][second]', "f%/R4Uk#](wUvM'V");
        $I->fillField('appbundle_user[phoneNumber]', "0661458792");
        $I->click("send");
    }


    public function _before(\AcceptanceTester $I)
    {

    }

    public function _after(\AcceptanceTester $I)
    {
    }

}
