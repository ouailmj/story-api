<?php

namespace Tests\_support\Pages;


class Change_Password
{
    // include url of current page
    public static $URL = '/auth/profile/change-password';
    public static $URL_CHANGE_PASS = "/app/change_password";

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $CurrentPassField    = 'input#fos_user_change_password_form_current_password';
    public static $NewPassField    = 'input#fos_user_change_password_form_plainPassword_first';
    public static $RepeatPassField     = 'input#fos_user_change_password_form_plainPassword_second';


    public static $CurrentPassField_CLIENT    = 'input#app_user_change_password_current_password';
    public static $NewPassField_CLIENT    = 'input#app_user_change_password_plainPassword_first';
    public static $RepeatPassField_CLEINT     = 'input#app_user_change_password_plainPassword_second';
}