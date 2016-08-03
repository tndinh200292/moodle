<?php

/**
 * Created by PhpStorm.
 * User: tndin
 * Date: 7/30/2016
 * Time: 10:00 PM
 */
class categoriesws
{

    public static function getCategories () {
        global $CFG, $PAGE, $DB;
        require_once ($CFG->wwwroot.'/custom_course/lib/curl.php');
        $token = '13a7e02feed21632b4ce3a26afc48995';
        $domainname = 'http://www.dev.hoctructuyen247.com';
        $functionname = 'core_course_get_categories';
// REST RETURNED VALUES FORMAT
        $restformat = 'xml'; //Also possible in Moodle 2.2 and later: 'json'
        //Setting it to 'json' will fail all calls on earlier Moodle version
//////// moodle_user_create_users ////////
/// PARAMETERS - NEED TO BE CHANGED IF YOU CALL A DIFFERENT FUNCTION
        $curl = new curl();
        header('Content-Type: text/plain');
        $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;
        $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
        $resp = $curl->get($serverurl . $restformat);
        return $resp;
    }

}