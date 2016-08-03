<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('../../config.php');
require_once('../edit_cate_form.php');
require_once('../classes/coursecustomlib.php');
global $DB;
$parentId = optional_param('parent', 0, PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);
$PAGE->set_context(get_system_context());
$PAGE->set_pagelayout('admin');
if (isset($id) && $id > 0) {
    $PAGE->set_title("Cập nhật chương");
} else {
    $PAGE->set_title("Thêm nhật chương");
}

$PAGE->set_heading("Bài giảng");
$PAGE->set_url(new moodle_url('/custom_course/popup/editcate.php'));
echo $OUTPUT->header();
$manageUrl = new moodle_url('/custom_course/subject_content.php');

$myForm = new form_edit_cate(null, array('categoryid' => $id, 'subjectId' => $parentId));
if ($myForm->is_cancelled()) {
    $manageUrl->param('parentId', $parentId);
    redirect($manageUrl);
} else {

}
$myForm->display();
?>
<?php
echo $OUTPUT->footer();
?>