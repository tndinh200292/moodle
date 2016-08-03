<?php

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/coursecatlib.php');
require_once($CFG->dirroot.'/custom_course/classes/coursecustomlib.php');
require_once ('classes/coursecustomlib.php');

/**
 * The form for handling editing a course.
 */
class form_edit_cate extends moodleform {
    protected $course;
    protected $context;
    var $strHocKy1 = "Học kỳ I";
    var $strHocKy2 = "Học kỳ II";
    /**
     * Form definition.
     */
    function definition() {
        global $CFG, $PAGE, $DB;

        $mform    = $this->_form;
        $categoryid = $this->_customdata['categoryid'];
        $subjectId = $this->_customdata['subjectId']; //this is subject
        if (isset($categoryid)) {
            $coursecat = coursecat::get($categoryid, MUST_EXIST, true);
            $category = $coursecat->get_db_record(); //cate hien tai (Chuong )
            $subject = $DB->get_record('course_categories', array('id'=>$subjectId));
            $hocKy = $DB->get_record('course_categories', array('id'=>$category->parent));
            $khoisl = $DB->get_record('course_categories', array('id'=>$hocKy->parent));
        }
        //get khoi
        $khois = coursecustom::get_khoi_by_subject($subjectId);
        $khoiOptions = array();
        $khoiOptions[null] = 'Chọn khối';
        foreach ($khois as $khoi) {
            $khoiOptions["".$khoi->id] = $khoi->name;
        }
      //  $mform->addRule('slHocKy', 'Trường bắt buộc', 'required', null);
        //END hoc ky
        $mform->addElement('select', 'slKhoiHoc', "Khối ", $khoiOptions, array('onchange' => 'javascript:updateTxtKhoi(this);'));
        $mform->addElement('text', 'txtKhoi', 'txt khối', array("class"=>"hidden"));
        //   $mform->addRule('slKhoi', 'Trường bắt buộc', 'required', null);
        //END get khoi
        //hoc ky
        $radioarray=array();
        $radioarray[] = $mform->createElement('radio', 'hocKy', '',$this->strHocKy1 , $this->strHocKy1);
        $radioarray[] = $mform->createElement('radio', 'hocKy', '',$this->strHocKy2 , $this->strHocKy2);
        $mform->setDefault('hocKy', 1);
        $mform->addGroup($radioarray, 'radioar', 'Học kỳ', array(' '), false);

        $mform->addElement('text', 'tenChuong', "Tên chương"); // Add elements to your form
        $mform->addRule('tenChuong', 'Vui lòng nhập tên chương', 'required', null);

        $mform->addElement('text', 'order', "Sắp xếp"); // Add elements to your form

        /*$mform->addElement('filemanager', 'attachments', 'Đính kèm', null,
            array('subdirs' => 0, 'maxbytes' => 2000000, 'areamaxbytes' => 10485760, 'maxfiles' => 50,
                'accepted_types' => array('document'), 'return_types'=> FILE_INTERNAL | FILE_EXTERNAL));*/

        $mform->addElement('editor', 'description', "Mô tả chi tiết");

        if (isset($coursecat) && $categoryid>0) {
            $mform->setDefault('tenChuong', $category->name);
            $mform->setDefault('hocKy', $hocKy->name);
            $mform->getElement('slKhoiHoc')->setSelected($khoisl->id);
            $mform->setDefault('txtKhoi', $khoisl->id);
            $mform->setDefault('order', $category->sortorder);
            $mform->getElement('description')->setValue(array('text' => $category->description));
        }



        $mform->setType('editor', PARAM_RAW);
        $mform->addElement('text', 'id', 'id', array('class'=>'hidden'));
        $mform->setType('id', PARAM_INT);
        $mform->setDefault('id', $categoryid);

        $this->add_action_buttons(true, "Lưu");
    }

    /**
     * Fill in the current page data for this course.
     */
    function definition_after_data() {
        global $DB;

        $mform = $this->_form;
    }

    /**
     * Validation.
     *
     * @param array $data
     * @param array $files
     * @return array the errors that were found
     */
    function validation($data, $files) {
        global $DB;

        $errors = parent::validation($data, $files);
        return $errors;
    }
}

