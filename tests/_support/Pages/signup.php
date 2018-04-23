<?php
/**
 * Created by PhpStorm.
 * User: Farah
 * Date: 17-Apr-18
 * Time: 11:42 AM
 */

namespace Tests\_support\Pages;


class signup
{
    // include url of current page
    public static $URL = '/auth/register/';



    public static $usernameField    = 'input#fos_user_registration_form_username';
    public static $EmailField    = 'fos_user_registration_form[email]';
    public static $PassField    = 'fos_user_registration_form[plainPassword]';
    public static $submitButton     = 'create';



}