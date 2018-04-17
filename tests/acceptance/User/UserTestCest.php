<?php

namespace User;
use Tests\_support\Pages\Change_Password;
use Tests\_support\Pages\Login;
use Tests\_support\Pages\Profile;
use Tests\_support\Pages\signup;


class UserTestCest
{

    public function loginAsUser(\AcceptanceTester $I) //yes
    {
        $I->wantTo("Verify the User login");
        $I->amOnPage(Login::$URL);
        $I->FillField(Login::$usernameField , "user");
        $I->FillField(Login::$passwordField, "f%/R4Uk#](wUvM'V");
        $I->click(Login::$submitButton);
        $I->seeCurrentUrlEquals('/auth/profile/edit');
    }

    public function ModifierInfo(\AcceptanceTester $I)
    {
        $I->wantTo("Modifier les infos d'un utilisateur");
        $this->loginAsUser($I);
        $I->fillField('input#fos_user_profile_form_phoneNumber', '0645879633');
        $I->fillField('input#fos_user_profile_form_current_password', "f%/R4Uk#](wUvM'V");
        $I->click(Profile::$btn_submit);
    }


    public function Modifier_password(\AcceptanceTester $I) // working
    {
        $I->wantTo("Modifier le mot de passe");
        $I->amOnPage(Profile::$URL);
        $I->FillField(Login::$usernameField  , "user_test");
        $I->FillField(Login::$passwordField , "f%/R4Uk#](wUvM'V");
        $I->click(Login::$submitButton);
        $I->amOnPage(Change_Password::$URL);
        $I->FillField(Change_Password::$CurrentPassField,"f%/R4Uk#](wUvM'V");
        $I->FillField(Change_Password::$NewPassField,"f%/R4Uk#](wUvM'");
        $I->FillField(Change_Password::$RepeatPassField,"f%/R4Uk#](wUvM'");
        $I->click("form[name=\"fos_user_change_password_form\"] button[type=\"submit\"]");
    }


        public function signup(\AcceptanceTester $I) //worked
    {
        $I->wantTo("S'inscrire");
        $I->amOnPage(signup::$URL);
        $I->FillField(signup::$usernameField,"user3");
        $I->FillField(signup::$EmailField,"user3.test@gmail.com");
        $I->FillField(signup::$PassField,"f%/R4Uk#](wUvM'V");
        $I->click(signup::$submitButton);
    }

    public function logout_test_user(\AcceptanceTester $I)
    {
        $I->wantTo('test logout user');
        $this->loginAsUser($I);
        $I->click('li:nth-child(3) > a');
        $I->seeCurrentUrlEquals('/auth/login');
    }

    public function Reset_password(\AcceptanceTester $I)
    {
        $I->wantTo('test reset password');
        $this->loginAsUser($I);
        $I->amOnPage('/auth/login');
        $I->click('form > div:nth-child(7) > a');
        $I->seeCurrentUrlEquals('/auth/resetting/request');
        $I->fillField('input#username','user');
        $I->click('div:nth-child(3) > input');
        $I->seeCurrentUrlEquals('/auth/resetting/check-email?username=user');
    }


    public function _before(\AcceptanceTester $I)
    {

    }

    public function _after(\AcceptanceTester $I)
    {

    }

    
    
}
