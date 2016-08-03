<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('../config.php');
global $DB;
$PAGE->set_context(get_system_context());
$PAGE->set_pagelayout('admin');
$PAGE->set_title("Quản lý bài giảng");
$PAGE->set_heading("Bài giảng");
$PAGE->set_url($CFG->wwwroot.'/custom_course/course_lessons.php');
//get cac thong tin ve course hien tai
$courseId = optional_param('courseId', 0, PARAM_INT);
$cCourse = $DB->get_record('course', array('id'=>$courseId));
$lessons = $DB->get_records('lesson',array('course'=>$courseId), null, 'id,course,name');
//END get cac thong tin ve course hien tai
//get course module lesson (13)
$module = 13; //page
$course_module = $DB->get_record('course_modules', array('course'=>$courseId, 'module' => $module));
// END get course module lesson (13)
echo $OUTPUT->header();
$baseEditLessonURL = new moodle_url('/mod/lesson/edit.php');
?>
    <div class="breadcump"><?php

        $chuong = $DB->get_record('course_categories', array('id'=>$cCourse->category));
        $hocKy = $DB->get_record('course_categories', array('id'=>$chuong->parent));
        $khoi = $DB->get_record('course_categories', array('id'=>$hocKy->parent));
        $subject = $DB->get_record('course_categories', array('id'=>$khoi->parent));
        $breadData = array (
            '0' => array ('label' => $subject->name, 'href' => new moodle_url('/custom_course/subject_content.php',
                array('parentId'=> $subject->id))),
            '1' => array ('label' => $chuong->name, 'href' => new moodle_url('/custom_course/subject_chapters.php',
                array('id'=> $chuong->id))),
            '2' => array ('label' => $cCourse->fullname, 'href' => new moodle_url('/custom_course/course_lessons.php',
                array('id'=> $courseId, 'subjectId' => $subject->id, 'courseId' => $courseId))),
        );
        require_once('breadcump.php');

        ?></div>
    <div class="course_lesson_toolbar" id="course_lesson_toolbar" style="margin-top: 10px;">
        <button class="btn btn-primary pull-left" style="margin-bottom: 5px;"><i class="fa fa-plus"></i>Thêm bài học mới</button>
    </div>

    <div id="custom_course_datatables"></div>

    <script type='text/javascript'>
    YUI().use('node', 'json-parse', 'datatable', function(Y){
       console.log(Y);
       var data = '<?php echo json_encode(array_values($lessons)) ?>';
       console.info(data);
       data = Y.JSON.parse(data);
       console.log(data);
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
                   key: "name",
                   label: "Tên bài học",
                   allowHTML: true,
                   formatter: function(o) {
                       return '<a href="javascript:void(0);">' + o.data.name + '</a>';
                   }
               },
               {
                   label: ' ',
                   allowHTML: true,
                   nodeFormatter: function (o) {
                       //return '<button><i class="fa fa-edit"></i></button>'
                       var edit = Y.Node.create('<a class="btn btn-success" href="javascript:void(0);"></a>');
                       edit.append("<i class='fa fa-edit'></i>")
                       o.cell.append(edit);

                       var ques = Y.Node.create('<a class="btn btn-success" href="javascript:void(0);" placeholder="Quản lý câu hỏi"></a>');
                       ques.append("<i class='fa fa-question'></i>")
                       o.cell.append(ques);

                       var btn_delete = Y.Node.create('<a class="btn btn-danger"></a>');
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