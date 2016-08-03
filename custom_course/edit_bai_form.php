<?php

defined('MOODLE_INTERNAL') || die;
require_once('../config.php');
require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/coursecatlib.php');
require_once($CFG->libdir.'/datalib.php');
require_once($CFG->dirroot.'/custom_course/classes/coursecustomlib.php');
require_once ('classes/baihocaction.php');

/**
 * The form for handling editing a course.
 */
class form_edit_bai extends moodleform {
    protected $course;
    protected $context;

    /**
     * Form definition.
     */
    function definition() {
        global $CFG, $PAGE, $DB;


        $mform    = $this->_form;
        $courseid = $this->_customdata['idBai'];
        $cateId = $this->_customdata['cateId'];
        $mform->addElement('text', 'fullname', "Tên bài học"); // Add elements to your form
        $mform->addElement('text', 'shortname', "Tên ngắn gọn"); // Add elements to your form
        $mform->addElement('date_selector', 'startdate', "Ngày bắt đầu");
        $mform->addElement('editor', 'summary', "Tóm tắt");
        $mform->addElement('text', 'category', "category"); // Add elements to your form
        $mform->setDefault('category', $cateId);
        $mform->addRule('shortname', 'Trường bắt buộc', 'required', null);
        $mform->addRule('fullname', 'Trường bắt buộc', 'required', null);
        $mform->addElement('text', 'id', "id"); // Add elements to your form
        if (isset($courseid) && $courseid>0) {
            //thuc hien set defaut data
            $bai = $DB->get_record('course', array('id'=>$courseid), '*', MUST_EXIST);
            $mform->setDefault('fullname', $bai->fullname);
            $mform->getElement('summary')->setValue(array('text' => $bai->summary));
            $mform->setDefault('shortname', $bai->shortname);
            $mform->setDefault('id', $bai->id);
        }

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
        //kiem tra khoi = 0
        /*if ($data['slKhoi'] == 0) {
            $errors['slKhoi'] = "Vui lòng chọn khối!";
        }*/
        //END kiem tra khoi = 0
        return $errors;
    }
}

