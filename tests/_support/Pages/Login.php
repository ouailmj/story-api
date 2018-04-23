<?php
namespace Tests\_support\Pages;

class Login
{
    // include url of current page
    public static $URL = '/auth/login';

    /**
     * Declare UI map for this page here. CSS or XPath allowed.
     * public static $usernameField = '#username';
     * public static $formSubmitButton = "#mainForm input[type=submit]";
     */

    public static $usernameField    = 'input#username';
    public static $passwordField    = 'input#password';
    public static $submitButton     = 'button#_submit';




}