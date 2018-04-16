<?php

namespace Admin;

class AdminTestCest
{
    public function loginAsAdmin(\AcceptanceTester $I) //yes
    {
        $I->wantTo("Verify the Admin login");
        $I->amOnPage("/auth/login");
        $I->FillField('_username', "admin");
        $I->FillField('_password', "f%/R4Uk#](wUvM'V");
        $I->click('_submit');
        $I->seeCurrentUrlEquals('/auth/profile/edit');
    }

    public function AfficherDetailsUser(\AcceptanceTester $I)
    {
        $I->wantTo("Afficher les infos de User_test d'un utilisateur");
        $this->loginAsAdmin($I);
        $I->amOnPage("/admin/user/3");
        $I->see('user');
    }

    public function ModifierInfoUser(\AcceptanceTester $I) //tested
    {
        $I->wantTo("Modifier les infos d'un utilisateur");
        $this->loginAsAdmin($I);
        $I->amOnPage("/admin/user/1/edit");
        $I->fillField('appbundle_user[phoneNumber]', '0645879633');
        $I->fillField('appbundle_user[new_password][first]', "f%/R4Uk#](wUvM'V");
        $I->fillField('appbundle_user[new_password][second]', "f%/R4Uk#](wUvM'V");
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
        $I->fillField('appbundle_user[new_password][first]', "f%/R4Uk#](wUvM");
        $I->fillField('appbundle_user[new_password][second]', "f%/R4Uk#](wUvM");
        $I->fillField('appbundle_user[phoneNumber]', "0661458792");
        $I->click("send");
    }

    public function ModifierInfoAdmin(\AcceptanceTester $I)
    {
        $I->wantTo("Modifier les informations du profil");
        $this->loginAsAdmin($I);
        $I->FillField('fos_user_profile_form[phoneNumber]','062145359953');
        $I->FillField('fos_user_profile_form[current_password]' , "f%/R4Uk#](wUvM'V");
        $I->click("div.col-md-5 button");
        $I->seeCurrentUrlEquals('/auth/profile/edit');
    }

    public function delete_user(\AcceptanceTester $I) //worked
    {

        $I->wantTo("delete account");
        $this->loginAsAdmin($I);
        $I->amOnPage('/admin/user/10');
        $I->click('form[name="form"] > button[type="submit"]');
    }

    public function logout_test(\AcceptanceTester $I)
    {
        $I->wantTo('test logout');
        $this->loginAsAdmin($I);
        $I->click('li:nth-child(4) > a');
        $I->seeCurrentUrlEquals('/auth/login');
    }

    public function _before(\AcceptanceTester $I)
    {

    }

    public function _after(\AcceptanceTester $I)
    {

    }

}
