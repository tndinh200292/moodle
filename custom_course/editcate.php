<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('../config.php');
require_once($CFG->libdir.'/coursecatlib.php');
require_once('edit_cate_form.php');
require_once('classes/coursecustomlib.php');
global $DB;
$url = new moodle_url('/custom_course/editcate.php');
$parentId = optional_param('parentId', 0, PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$url->param('parentId', $parentId);
if (isset($id) && $id > 0) {
    $coursecat = coursecat::get($id, MUST_EXIST, true);
    $category = $coursecat->get_db_record();
    $PAGE->set_title("Cập nhật chương");
    $url->param('id', $id);
} else {
    $PAGE->set_title("Thêm nhật chương");

}

$PAGE->set_heading("Bài giảng");

$PAGE->set_url($url);
echo $OUTPUT->header();
$manageUrl = new moodle_url('/custom_course/subject_content.php');
$manageUrl->param('parentId', ''.$parentId);
$myForm = new form_edit_cate(null, array('categoryid' => $id, 'subjectId' => $parentId));
if ($myForm->is_cancelled()) {

    redirect($manageUrl);
} else if ($data = $myForm->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    if (isset($coursecat)) {
        if ((int)$data->parent !== (int)$coursecat->parent && !$coursecat->can_change_parent($data->parent)) {
            print_error('cannotmovecategory');
        }
        //$coursecat->update($data, $mform->get_description_editor_options());
    } else {
        //$category = coursecat::create($data, $mform->get_description_editor_options());
    }
    $manageurl->param('parentId', $parentId);
    redirect($manageurl);
}
$myForm->display();
?>
<?php
echo $OUTPUT->footer();
?>