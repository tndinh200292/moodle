<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('../config.php');
require_once($CFG->libdir.'/coursecatlib.php');
require_once('edit_chapter_form.php');
require_once('classes/coursecustomlib.php');
global $DB;
$url = new moodle_url('/custom_course/editchapter.php');
$parentId = optional_param('parentId', 0, PARAM_INT);
$windowsState = optional_param('windowsState', 'admin', PARAM_ALPHA);
$id = optional_param('id', 0, PARAM_INT);
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout($windowsState);
$backURL = new moodle_url('subject_content.php', array('parentId'=>$parentId));
$url->param('parentId', $parentId);
if (isset($id) && $id > 0) {
    $coursecat = coursecat::get($id, MUST_EXIST, true);
    $category = $coursecat->get_db_record();
    $PAGE->set_title("Cập nhật chương");
    $url->param('id', $id);
} else {
    $PAGE->set_title("Thêm chương mới");

}

$PAGE->set_heading("Bài giảng");

$PAGE->set_url($url);
echo $OUTPUT->header();
$manageUrl = new moodle_url('/custom_course/subject_content.php', array('parentId'=>$parentId));
$myForm = new form_edit_cate(null, array('categoryid' => $id, 'subjectId' => $parentId));
if ($myForm->is_cancelled()) {
    redirect($manageUrl, $manageUrl, 3);
} else if ($data = $myForm->get_data()){
    require_once ('classes/customcatews.php');
    $currentId = $data->id;
    if (isset($currentId) && $currentId > 0){
        //update
        unset($coursecat);
        $coursecat = coursecat::get($currentId, MUST_EXIST, true);
        $updateCate = $coursecat->get_db_record();
        print_r($coursecat);
        print_r($updateCate);
        $updateCate->name = $data->tenChuong;
        $updateCate->parent = $parent->id;
        $updateCate->depth = 4;
        $updateCate->description = $data->description['text'];
        $updateCate->descriptionformat = $data->description['format'];
        update($updateCate);
    } else {
        //add new
        $parent = coursecustom::get_cate_by_P_N($data->txtKhoi, $data->hocKy);
        $customcatews = new customcatews();
        $cate = new stdClass();
        $cate->name = $data->tenChuong;
        $cate->parent = $parent->id;
        $cate->depth = 4;
        $cate->description = $data->description['text'];
        $cate->descriptionformat = $data->description['format'];
        $idChuong = $customcatews->add_category($cate);
        //redirect(new moodle_url('/custom_course/editchapter.php', array('id'=>$idChuong->id)), $idChuong, 10);
        header("location:/custom_course/editchapter.php?".http_build_query(array('id'=>$idChuong->id)));
    }

}
$myForm->display();

?>
    <script>
        function updateTxtKhoi (that) {
            YUI().use('node', function(Y){
                Y.one('#id_txtKhoi').set('value', Y.one('#id_slKhoiHoc').get('value'));

            });

        }
        YUI().use('node', function (Y) {
            Y.one('#id_cancel').on('click', function(e){
                e.preventDefault();
                window.location.replace('<?php echo $backURL; ?>');
            }) ;
        });
    </script>
<?php
echo $OUTPUT->footer();
?>