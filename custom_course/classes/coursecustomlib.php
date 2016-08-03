<?php

/**
 * Created by PhpStorm.
 * User: tndin
 * Date: 7/29/2016
 * Time: 10:09 PM
 */
class coursecustom
{

    protected static $coursecatfields = array(
        'id' => array('id', 0),
        'name' => array('na', ''),
        'idnumber' => array('in', null),
        'description' => null, // Not cached.
        'descriptionformat' => null, // Not cached.
        'parent' => array('pa', 0),
        'sortorder' => array('so', 0),
        'coursecount' => array('cc', 0),
        'visible' => array('vi', 1),
        'visibleold' => null, // Not cached.
        'timemodified' => null, // Not cached.
        'depth' => array('dh', 1),
        'path' => array('ph', null),
        'theme' => null, // Not cached.
    );
    /** @var int */
    protected $id;

    /** @var string */
    protected $name = '';

    /** @var string */
    protected $idnumber = null;

    /** @var string */
    protected $description = false;

    /** @var int */
    protected $descriptionformat = false;

    /** @var int */
    protected $parent = 0;

    /** @var int */
    protected $sortorder = 0;

    /** @var int */
    protected $coursecount = false;

    /** @var int */
    protected $visible = 1;

    /** @var int */
    protected $visibleold = false;

    /** @var int */
    protected $timemodified = false;

    /** @var int */
    protected $depth = 0;

    /** @var string */
    protected $path = '';

    /** @var string */
    protected $theme = false;

    /** @var bool */
    protected $hasmanagecapability = null;

    public static function get_chuong_by_subject ($subjectId=0) {
        global $DB;
        $cates = $DB->get_records_sql('SELECT * FROM {course_categories} WHERE depth = ? AND path LIKE ? ORDER BY sortorder ASC', array( '4' , '/'.$subjectId.'%' ));
        $customCates = array();
        foreach ($cates as $chuong) {
            $object = new stdClass();
            $object->id = $chuong->id;
            $object->name = $chuong->name;
            $object->description = $chuong->description;
            $object->coursecount = $chuong->coursecount;
            $object->depth = $chuong->depth;
            $object->path = $chuong->path;
            //xu ly hoc ky
            $hocky = $DB->get_record('course_categories', array('id'=>$chuong->parent));
            $object->tenHocKy = $hocky->name;
            $object->idHocKy = $hocky->id;
            //END xu ly hoc ky
            //xu ly khoi
            $khoi = $DB->get_record('course_categories', array('id'=>$hocky->parent));
            $object->tenKhoi = $khoi->name;
            $object->idKhoi = $khoi->id;
            //END xu ly khoi
            array_push($customCates, $object);
        }
        return $customCates;
    }

    public static function get_hk_by_subject ($subjectId=0) {
        global $DB;
        $cates = $DB->get_records_sql('SELECT * FROM {course_categories} WHERE depth = ? AND path LIKE ? ORDER BY sortorder ASC', array( '3' , '/'.$subjectId.'%' ));
        return $cates;
    }

    public static function get_hk_by_khoi ($idKhoi=0) {
        global $DB;
        $cates = $DB->get_record('course_categories', array('parent' => $idKhoi));
        return $cates;
    }

    public static function get_khoi_by_subject ($subjectId=0) {
        global $DB;
        $cates = $DB->get_records('course_categories', array('parent' => $subjectId));
        return $cates;
    }

    public static function get_cate_by_P_N ($parentId, $name){
        global $DB;
        $cate = $DB->get_record('course_categories', array('parent' => $parentId, 'name' => $name));
        return $cate;
    }
}