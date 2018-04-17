<?php
/**
 * Created by PhpStorm.
 * User: Farah
 * Date: 17-Apr-18
 * Time: 10:32 AM
 */

namespace Tests\_support\Pages;


class Profile
{
    // include url of current page
    public static $URL = "/auth/profile/edit";


    //inputs attribute name elements
    // - for edit profil form
    public static $FullNameField = "input#fos_user_profile_form_fullName";
    public static $emailField = "input#fos_user_profile_form_email";
    public static $PhoneNumberField = "input#fos_user_profile_form_phoneNumber";
    public static $btn_submit = "form[name=\"fos_user_profile_form\"] button[type=\"submit\"]";

    //pour modifier un user
    public static $username = "user";
    public static $Password = "f%/R4Uk#](wUvM'V";
    public static $Phone = "062145359954";
    public static $currentPassword = "f%/R4Uk#](wUvM'V";
}