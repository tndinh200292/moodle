<?php

/**
 * Created by PhpStorm.
 * User: tndin
 * Date: 7/30/2016
 * Time: 5:22 PM
 */
class BaiActionUtil
{

    public static function getBaiHocById ($id) {
        global $DB;
        $lesson = $DB::get_record('course', array('id'=>$id));
        return $lesson;
    }

}