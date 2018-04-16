<?php

namespace User;

class UserTestCest
{

    public function loginAsUser(\AcceptanceTester $I) //yes
    {
        $I->wantTo("Verify the User login");
        $I->amOnPage("/auth/login");
        $I->FillField('_username' , "user");
        $I->FillField('_password' , "f%/R4Uk#](wUvM'V");
        $I->click('_submit');
        $I->seeCurrentUrlEquals('/auth/profile/edit');
    }

    public function ModifierInfo(\AcceptanceTester $I)
    {
        $I->wantTo("Modifier les informations du profil");
        $this->loginAsUser($I);
        $I->FillField('fos_user_profile_form[phoneNumber]','062145359954');
        $I->FillField('fos_user_profile_form[current_password]' , "f%/R4Uk#](wUvM'V");
        $I->click("div.col-md-5 button");
        $I->seeCurrentUrlEquals('/auth/profile/edit');
    }


    public function Modifier_password(\AcceptanceTester $I) // working
    {
        $I->wantTo("Modifier le mot de passe");
        $I->amOnPage("/auth/login");
        $I->FillField('_username' , "user_test");
        $I->FillField('_password' , "f%/R4Uk#](wUvM'V");
        $I->click('_submit');
        $I->amOnPage('/auth/profile/change-password');
        $I->FillField('input#fos_user_change_password_form_current_password',"f%/R4Uk#](wUvM'V");
        $I->FillField('input#fos_user_change_password_form_plainPassword_first',"f%/R4Uk#](wUvM'");
        $I->FillField('input#fos_user_change_password_form_plainPassword_second',"f%/R4Uk#](wUvM'");
        $I->click("form[name=\"fos_user_change_password_form\"] button[type=\"submit\"]");
    }


        public function signup(\AcceptanceTester $I) //worked
    {
        $I->wantTo("S'inscrire");
        $I->amOnPage("/auth/register/");
        $I->FillField('fos_user_registration_form[username]',"user3");
        $I->FillField('fos_user_registration_form[email]',"user3.test@gmail.com");
        $I->FillField('fos_user_registration_form[plainPassword]',"f%/R4Uk#](wUvM'V");
        $I->click('create');
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
