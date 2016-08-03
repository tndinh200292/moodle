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
$PAGE->set_url($CFG->wwwroot.'/custom_course/course_content.php');

echo $OUTPUT->header();
//echo "Hello World";
//http://localhost/moodle/custom_course/index.php?id=1
$courseId = optional_param('id', 0, PARAM_INT); // Course id.
$course = $DB->get_record('course', array('id'=>''.$courseId));
//var_dump($course);
/*echo "<div class='course_title'>";
echo "<h1>".$course->fullname."</h1";
echo "</div>";*/
?>
<div class='course_title'>
    <h2><?php echo $course->fullname; ?></h2>
    <hr>
</div>

<?php
    $li_lesson = $DB->get_records('lesson', array ('course' => $courseId), '', 'id,course,name,intro');
   // var_dump($li_lesson);
    $urlAddCourse = new moodle_url('/custom_course/lesson_edit.php', array('backURL' => new moodle_url("/custom_course/index.php", array('id' => $courseId))));
?>

<a class="btn btn-primary" style="margin-bottom: 5px;" href="<?php echo $urlAddCourse; ?>"><i class="fa fa-plus"></i>Thêm bài học mới </a>
<div id="custom_course_datatables">
    
</div>

<script type='text/javascript'>
YUI().use('node', 'json-parse', 'datatable', function(Y){
   console.log(Y); 
   var data = '<?php echo json_encode(array_values($li_lesson)) ?>';
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
           "course", 
           "name", 
           "intro",
           {
               label: ' ',
               allowHTML: true,
               formatter: function (o) {
                   console.log(o);
                   return '<button><i class="fa fa-edit"></i></button>'
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