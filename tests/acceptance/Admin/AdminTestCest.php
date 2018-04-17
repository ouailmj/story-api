<?php

namespace Admin;
use Tests\_support\Pages\Add_user;
use Tests\_support\Pages\Change_Password;
use Tests\_support\Pages\Login;
use Tests\_support\Pages\Profile;


class AdminTestCest
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
        $I->fillField('input#appbundle_user_phoneNumber', '0645879633');
        $I->fillField('input#appbundle_user_new_password_second', "f%/R4Uk#](wUvM'V");
        $I->fillField('input#appbundle_user_new_password_second', "f%/R4Uk#](wUvM'V");
        $I->click('form[name="appbundle_user"] button[type="submit"]');
    }

    public function Add_user(\AcceptanceTester $I)
    {
        $I->wantTo("Ajouter un utilisateur");
        $this->loginAsAdmin($I);
        $I->amOnPage('/admin/user/new');
        $I->fillField(Add_user::$FullNameField, 'usertest');
        $I->fillField(Add_user::$UsernameField, 'usertest');
        $I->fillField(Add_user::$emailField, 'usertest.email@gmail.com');
        $I->fillField(Add_user::$PasswordField, "f%/R4Uk#](wUvM");
        $I->fillField(Add_user::$ConfirmePasswordField, "f%/R4Uk#](wUvM");
        $I->fillField(Add_user::$PhoneNumberField, "0661458792");
        $I->click('send');
    }

    public function ModifierInfoAdmin(\AcceptanceTester $I)
    {
        $I->wantTo("Modifier les informations du profil");
        $this->loginAsAdmin($I);
        $I->FillField(Profile::$PhoneNumberField,'062145359953');
        $I->FillField('input#fos_user_profile_form_current_password', "f%/R4Uk#](wUvM'V");
        $I->click(Profile::$btn_submit);
        $I->seeCurrentUrlEquals('/auth/profile/edit');
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
