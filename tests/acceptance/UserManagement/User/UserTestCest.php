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
        //$I->seeCurrentUrlEquals('/app/events');
    }

    public function ModifierInfo(\AcceptanceTester $I)
    {
        $I->wantTo("Modifier les infos d'un utilisateur");
        // $this->loginAsUser($I);
        // $I->amOnPage(Login::$URL_Profile);
        // $I->fillField('input#app_user_profile_phoneNumber', '0645879633');
        // $I->fillField('input#app_user_profile_phoneNumber', "f%/R4Uk#](wUvM'V");
        // $I->click(Profile::$btn_submit_client);
    }


    public function Modifier_password(\AcceptanceTester $I) // working
    {
        $I->wantTo("Modifier le mot de passe");
        // $this->loginAsUser($I);
        // $I->amOnPage(Change_Password::$URL_CHANGE_PASS);
        // $I->FillField(Change_Password::$CurrentPassField_CLIENT,"f%/R4Uk#](wUvM'V");
        // $I->FillField(Change_Password::$NewPassField_CLIENT,"f%/R4Uk#](wUvM'");
        // $I->FillField(Change_Password::$RepeatPassField_CLEINT,"f%/R4Uk#](wUvM'");
        // $I->click("form[name=\"app_user_change_password\"] button[type=\"submit\"]");
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

  /*  public function logout_test_user(\AcceptanceTester $I)
    {
        $I->wantTo('test logout user');
        $this->loginAsUser($I);
        $I->click('li:nth-child(3) > a');
        $I->seeCurrentUrlEquals('/auth/login');
    }*/

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
