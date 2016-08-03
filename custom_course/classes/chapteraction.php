<?php

/**
 * Created by PhpStorm.
 * User: tndin
 * Date: 7/30/2016
 * Time: 5:19 PM
 */
class chapteraction
{

    public static function getChapterById ($id) {
        global $DB;
        $chapter = $DB::get_record('course_categories', array('id' => $id));
        return $chapter;
    }

}