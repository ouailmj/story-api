<?php
/**
 * Created by PhpStorm.
 * User: farah-pc
 * Date: 23/05/18
 * Time: 14:19
 */

namespace Tests\_support\Pages;


class Event_test
{
// include url of current page
    public static $URL = "";


    //inputs attribute name elements
    // - for add a new event (Free)
        public static $Plan = "choose_plan[plan]";
        public static $Next1 = "form[name=\"appbundle_event\"] button[type=\"submit\"] > li > i";
        public static $event_name= "appbundle_event[title]";
        public static $Place = "appbundle_event[place]";
        public static $StartDateField = "appbundle_event[startsAt]";
        public static $EndDateField = "appbundle_event[endsAt]";
        public static $Description = "appbundle_event[description]";
        public static $Next2 = "button[type=\"submit\"]"; //same Next3
        public static $email_payment = "input#add-friend";
        public static $add = "form[name=\"appbundle_invitation_request\"] button[type=\"button\"]";
        public static $true = "button[type=\"submit\"] > li > i";
        public static $submit = "form[name=\"form\"] > a";

}
