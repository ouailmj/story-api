<?php
/**
 * Created by PhpStorm.
 * User: farah-pc
 * Date: 23/05/18
 * Time: 14:19
 */

namespace Tests\_support\Pages;


class Add_event
{
// include url of current page
    public static $URL = "/add-event/choose-plan";


    //inputs attribute name elements
    // - for add a new event (Free)
        public static $PlanGratuit = "input#choose_plan_plan_1";
        public static $Next1 = "form[name=\"choose_plan\"] button[type=\"submit\"]:nth-child(2) > li > i";
        public static $event_name= "input#appbundle_event_title";
        public static $Place = "input#appbundle_event_place";
        public static $StartDateField = "input#appbundle_event_startsAt";
        public static $EndDateField = "input#appbundle_event_endsAt";
        public static $Description = "textarea#appbundle_event_description";
        public static $Next2 = "button[type=\"submit\"] > li > i"; //same Next3
        public static $email_payment = "input#add-friend";
        public static $add = "form[name=\"appbundle_invitation_request\"] button[type=\"button\"]";
        public static $true = "button[type=\"submit\"] > li > i";

}
