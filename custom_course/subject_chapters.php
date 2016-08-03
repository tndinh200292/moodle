<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('../config.php');
require_once($CFG->dirroot.'/course/lib.php');
global $DB;
$PAGE->set_context(get_system_context());
$PAGE->set_pagelayout('admin');
$PAGE->set_title("Quản lý bài giảng");
$PAGE->set_heading("Bài giảng");
$cateId = optional_param('id', 0, PARAM_INT);
$action = optional_param('action', '', PARAM_TEXT);

$PAGE->set_url($CFG->wwwroot.'/custom_course/subject_chapters.php', array('id'=>$cateId));
if ($action) {
    echo "delete";
    $idBai = required_param('idBai', PARAM_INT);
    delete_course($idBai);
    redirect($PAGE->url);
}
$currentCate = $DB->get_record('course_categories', array('id'=>$cateId));
//xu ly de lay thong tin subject
$subjectId = substr($currentCate->path,1,1);
$subject = $DB->get_record('course_categories', array('id'=>$subjectId));
//END xu ly de lay thong tin subject

$parentCate = $DB->get_record('course_categories', array('id'=>$currentCate->parent));
$urlCourseLesson = new moodle_url('/custom_course/course_lessons.php', array('subjectId' => $subjectId, 'chuongId' => $currentCate->id));
$baseURLQuestion = new moodle_url('/question/edit.php');
$urlEditBaiHoc = new moodle_url('/custom_course/editbai.php', array("cateId" => $currentCate->id));
echo $OUTPUT->header();
$courses = $DB->get_records('course',array('category'=>$cateId), null, 'id,category,fullname,shortname,startdate');
?>
    <div class="breadcump"><?php

        $breadData = array (
            '0' => array ('label' => $subject->name, 'href' => new moodle_url('/custom_course/subject_content.php',
                array('parentId'=> $subject->id))),
            '1' => array ('label' => $currentCate->name, 'href' => new moodle_url('/custom_course/subject_chapters.php',
                array('id'=> $currentCate->id))),
        );
        require_once('breadcump.php');

        ?></div>
    <div class="subject_content_toolbar" id="subject_content_toolbar" style="margin-top: 10px;">
        <a class="btn btn-primary pull-left" style="margin-bottom: 5px;" href='<?php
        echo $urlEditBaiHoc;
        ?>'><i class="fa fa-plus"></i>Thêm bài mới</a>
    </div>
    <div id="custom_course_datatables"></div>

    <script type='text/javascript'>
        function kiemTraXoa () {
            if (confirm("Xoá chương được chọn ?")){
                return true;
            }
            return false;
        }
        YUI().use('node', 'json-parse', 'datatable', function(Y){
            //console.log(Y);
            var data = '<?php echo json_encode(array_values($courses)) ?>';
            var baseUrl = '<?php echo $urlCourseLesson ?>';
            data = Y.JSON.parse(data);
            //console.log(data);
            var tableLesson = new Y.DataTable({
                width: '100%',
                columns: [
                    {
                        label: '#',
                        formatter: function(o){
                            return o.rowIndex + 1;
                        }
                    },
                    {
                        key: "fullname",
                        label: "Tên bài học ",
                        allowHTML: true,
                        formatter: function(o) {
                            var url = baseUrl + '&courseId=' + o.data.id;
                            //return '<a href="javascript:void(0);">' + o.data.fullname + '</a>';
                            return o.data.fullname;
                        }
                    },
                    {
                        key: 'shortname',
                        label: 'Tên tắt'
                    },
                    {
                        label: 'Ngày bắt đầu',
                        formatter: function(o){
                            var d = new Date(o.data.startdate * 1000);
                            return d.getDate() + '/' + (d.getMonth()+1) + '/' + d.getFullYear();
                        }
                    },
                    {
                        label: ' ',
                        allowHTML: true,
                        nodeFormatter: function (o) {
                            //return '<button><i class="fa fa-edit"></i></button>'
                            var editUrl = '<?php echo $urlEditBaiHoc ?>';
                            editUrl = editUrl + '&id=' + o.data.id;
                            var edit = Y.Node.create('<a class="btn btn-success" href="' + editUrl + '"></a>');
                            edit.append("<i class='fa fa-edit'></i>")
                            o.cell.append(edit);

                            var url = '<?php echo $baseURLQuestion ?>';
                            url = url + '?courseid=' + o.data.id;
                            var ques = Y.Node.create('<a class="btn btn-success" href="' + url + '" placeholder="Quản lý câu hỏi"></a>');
                            ques.append("<i class='fa fa-question'></i>")
                            o.cell.append(ques);

                            var dlURL = '<?php echo $PAGE->url ?>';
                            dlURL = dlURL + "&idBai=" + o.data.id;
                            dlURL = dlURL + "&action=delete";
                            var btn_delete = Y.Node.create('<a href="' + dlURL + '" class="btn btn-danger" onclick="kiemTraXoa ();"></a>');
                            btn_delete.append("<i class='fa fa-remove'></i>")
                            o.cell.append(btn_delete);
                        }
                    }
                ],
                data: data,
            }).render('#custom_course_datatables');
        });
    </script>

<?php
echo $OUTPUT->footer();
?>