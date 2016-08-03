<?php

/**
 * Created by PhpStorm.
 * User: tndin
 * Date: 7/31/2016
 * Time: 8:22 AM
 */
global $CFG;
require_once($CFG->libdir.'/filelib.php');
require_once("$CFG->dirroot/webservice/lib.php");

class customcatews
{
    var $domainname = 'http://dev.hoctructuyen247.com/';
    var $token = '67ca0972bec372c401bca03a48d653be';
    var $restformat = 'json';

    public function get_categories ($criterias){
        $functionname = 'core_user_get_users';
        $serverurl = $this->domainname . '/webservice/rest/server.php'. '?wstoken=' . $this->token . '&wsfunction='.$functionname;
        $restformat = ($this->restformat == 'json')?'&moodlewsrestformat=' . $this->restformat:'';
        $curl = new curl();
        $url = $serverurl . $restformat;
        $url = $url."criteria[0][key]=username&criteria[0][value]=admin";
        echo $url;
        $resp = $curl->post($url);
        return $resp;
    }

    public function create_test_users () {
        $token = '67ca0972bec372c401bca03a48d653be';
        $domainname = 'http://dev.hoctructuyen247.com';
        $functionname = 'core_user_create_users';
// REST RETURNED VALUES FORMAT
        $restformat = 'json'; //Also possible in Moodle 2.2 and later: 'json'
        //Setting it to 'json' will fail all calls on earlier Moodle version
//////// moodle_user_create_users ////////
/// PARAMETERS - NEED TO BE CHANGED IF YOU CALL A DIFFERENT FUNCTION
        $user1 = new stdClass();
        $user1->username = 'testusername1';
        $user1->password = 'testpassword1';
        $user1->firstname = 'testfirstname1';
        $user1->lastname = 'testlastname1';
        $user1->email = 'testemail1@moodle.com';
        $user1->auth = 'manual';
        $user1->idnumber = 'testidnumber1';
        $user1->lang = 'en';
        $user1->theme = 'standard';
        $user1->timezone = '-12.5';
        $user1->mailformat = 0;
        $user1->description = 'Hello World!';
        $user1->city = 'testcity1';
        $user1->country = 'au';
        $preferencename1 = 'preference1';
        $preferencename2 = 'preference2';
        $user1->preferences = array(
            array('type' => $preferencename1, 'value' => 'preferencevalue1'),
            array('type' => $preferencename2, 'value' => 'preferencevalue2'));
        $user2 = new stdClass();
        $user2->username = 'testusername2';
        $user2->password = 'testpassword2';
        $user2->firstname = 'testfirstname2';
        $user2->lastname = 'testlastname2';
        $user2->email = 'testemail2@moodle.com';
        $user2->timezone = 'Pacific/Port_Moresby';
        $users = array($user1, $user2);
        $params = array('users' => $users);
/// REST CALL
        header('Content-Type: text/plain');
        $serverurl = $domainname . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$functionname;
        $curl = new curl;
//if rest format == 'xml', then we do not add the param for backward compatibility with Moodle < 2.2
        $restformat = ($restformat == 'json')?'&moodlewsrestformat=' . $restformat:'';
        $resp = $curl->post($serverurl . $restformat, $params);
        print_r($resp);
    }
    public function add_category ($cate) {
        global $DB, $CFG;
        //return $DB->insert_record('course_categories', $cate, true, false);
        require_once($CFG->libdir.'/coursecatlib.php');
        coursecat::create($cate);
    }
}