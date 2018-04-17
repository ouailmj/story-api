<?php
/**
 * Created by PhpStorm.
 * User: Farah
 * Date: 17-Apr-18
 * Time: 10:40 AM
 */

namespace Tests\_support\Pages;


class Add_user
{

    // include url of current page
    public static $URL = "/admin/user/new";


    //inputs attribute name elements
    // - for add a new user
    public static $FullNameField = "input#appbundle_user_fullName";
    public static $UsernameField = "input#appbundle_user_username";
    public static $emailField = "input#appbundle_user_email";
    public static $PasswordField = "input#appbundle_user_new_password_first";
    public static $ConfirmePasswordField = "input#appbundle_user_new_password_second";
    public static $PhoneNumberField = "input#appbundle_user_phoneNumber";
    public static $btn_submit = "form[name=\"appbundle_user\"] i";

}