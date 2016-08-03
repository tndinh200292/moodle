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
$PAGE->set_url($CFG->wwwroot.'/custom_course/index.php');

echo $OUTPUT->header();
$cates = $DB->get_records('course_categories',array('parent'=>'0'));
?>

<div id="custom_course_datatables"></div>

<script type='text/javascript'>
YUI().use('node', 'json-parse', 'datatable', function(Y){
   console.log(Y);
   var data = '<?php echo json_encode(array_values($cates)) ?>';
   console.info(data);
   data = Y.JSON.parse(data);
   console.log(data);
   var tableLesson = new Y.DataTable({
       width: '100%',
       /*columns: [
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
       ],*/
       data: data,
    }).render('#custom_course_datatables');
});
</script>

<?php
echo $OUTPUT->footer();
?>