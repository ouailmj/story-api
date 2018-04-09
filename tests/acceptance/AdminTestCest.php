<?php


class AdminTestCest
{
    public function frontpageWorks(AcceptanceTester $I)
    {
        $I->wantTo("Test frontpage");
        $I->amOnPage('/');
        $I->see('Welcome');
    }

     public function loginAsAdmin(AcceptanceTester $I)
     {
         $I->wantTo("Verify the User login");
         $I->amOnPage("/auth/register/");
         $I->click("div.text-center a");
         $I->seeCurrentUrlEquals('/auth/login/');
         $I->FillField('_username' , "admin");
         $I->FillField('_password' , "f%/R4Uk#](wUvM'V");
         $I->click('_submit');
         $I->see('Victoria');
         $I->seeCurrentUrlEquals('/');
     }

    
     public function ModifierInfo(AcceptanceTester $I)
     {
         $this->loginAsAdmin($I);
         $I->wantTo("Modifier les informations du profil");
        // $I->click("Victoria");
         $I->seeCurrentUrlEquals('');
         $I->click("ul.dropdown-menu.dropdown-menu-right li:nth-child(1) a");
         $I->seeCurrentUrlEquals('/auth/profile/');
         $I->click("div.btn-group a:nth-child(1)");
         $I->FillField('fos_user_profile_form[phoneNumber]','062455478');
         $I->FillField('fos_user_profile_form[current_password]' , "f%/R4Uk#](wUvM'V");
         $I->click("update");
         $I->seeCurrentUrlEquals('/auth/profile/');
         $I->see("062455478");
     }

     public function Modifier_password(AcceptanceTester $I)
     {
         $this->loginAsAdmin($I);
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

     public function delete_account_user(AcceptanceTester $I)
     {
            $this->loginAsAdmin($I);
            $I->wantTo("supprimer mon compte");
            $I->click("ul.dropdown-menu.dropdown-menu-right li:nth-child(1) a");
            $I->seeCurrentUrlEquals('/auth/profile/');
            $I->click('div.btn-group a:nth-child(3)');
     }
    
    

    public function _before(AcceptanceTester $I)
    {

    }

    public function _after(AcceptanceTester $I)
    {
    }

    
    
}
