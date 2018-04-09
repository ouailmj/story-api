<?php

namespace User;

class UserTestCest
{
    public function frontpageWorks(\AcceptanceTester $I)
    {
        $I->wantTo("Test frontpage");
        $I->amOnPage('/');
        $I->see('Welcome');
    }

     public function loginAsUser(\AcceptanceTester $I)
     {
         $I->wantTo("Verify the User login");
         $I->amOnPage("/auth/register/");
         $I->click("div.text-center a");
         $I->seeCurrentUrlEquals('/auth/login/');
         $I->FillField('_username' , "user");
         $I->FillField('_password' , "f%/R4Uk#](wUvM'V");
         $I->click('_submit');
         $I->see('Victoria');
         $I->seeCurrentUrlEquals('/');
     }

     public function ModifierInfo(\AcceptanceTester $I)
     {
         $this->loginAsUser($I);
         $I->wantTo("Modifier les informations du profil");
        // $I->click("Victoria");
         $I->seeCurrentUrlEquals('');
         $I->click("ul.dropdown-menu.dropdown-menu-right li:nth-child(1) a");
         $I->seeCurrentUrlEquals('/auth/profile/');
         $I->click("div.btn-group a:nth-child(1)");
         $I->FillField('fos_user_profile_form[phoneNumber]','062457819');
         $I->FillField('fos_user_profile_form[current_password]' , "f%/R4Uk#](wUvM'V");
         $I->click("update");
         $I->seeCurrentUrlEquals('/auth/profile/');
         $I->see("062457819");
     }


     public function Modifier_password(\AcceptanceTester $I)
     {
         $this->loginAsUser($I);
         $I->wantTo("Modifier le mot de passe");
         $I->click("ul.dropdown-menu.dropdown-menu-right li:nth-child(1) a");
         $I->see('Mon profil');
         $I->click("div.btn-group a:nth-child(2)");
         $I->seeCurrentUrlEquals('/auth/profile/change-password');
         $I->FillField('fos_user_change_password_form[current_password]',"f%/R4Uk#](wUvM'V");
         $I->FillField('fos_user_change_password_form[plainPassword][first]',"123456");
         $I->FillField('fos_user_change_password_form[plainPassword][second]',"123456");
         $I->click("update");
         $I->see("Mon profil");
     }


     public function delete_account_user(\AcceptanceTester $I)
     {
            $this->loginAsUser($I);
            $I->wantTo("supprimer mon compte");
            $I->click("ul.dropdown-menu.dropdown-menu-right li:nth-child(1) a");
            $I->seeCurrentUrlEquals('/auth/profile/');
            $I->click('div.btn-group a:nth-child(3)');
     }
    
    public function signup(\AcceptanceTester $I)
    {
        $I->wantTo("S'inscrire");
        $I->amOnPage("/auth/register/");
        $I->FillField('fos_user_registration_form[username]',"user2");
        $I->FillField('fos_user_registration_form[email]',"user2.test@gmail.com");
        $I->FillField('fos_user_registration_form[plainPassword]',"147258");
        $I->click("div.container-login100-form-btn button");
    }

    public function _before(\AcceptanceTester $I)
    {

    }

    public function _after(\AcceptanceTester $I)
    {

    }

    
    
}