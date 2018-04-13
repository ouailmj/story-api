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
        $I->FillField('fos_user_change_password_form[current_password]',"f%/R4Uk#](wUvM'V");
        $I->FillField('fos_user_change_password_form[plainPassword][first]',"f%/R4Uk#](wUvM'");
        $I->FillField('fos_user_change_password_form[plainPassword][second]',"f%/R4Uk#](wUvM'");
        $I->click("div.col-md-5 button");
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

    public function _before(\AcceptanceTester $I)
    {

    }

    public function _after(\AcceptanceTester $I)
    {

    }

    
    
}
