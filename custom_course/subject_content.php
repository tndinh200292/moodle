<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('../config.php');
//require_once('../course/classes/editcategory_form.php');
require_once('classes/coursecustomlib.php');
require_once($CFG->libdir.'/coursecatlib.php');
global $DB;
$PAGE->set_context(context_system::instance());
$PAGE->set_pagelayout('admin');
$PAGE->set_title("Quản lý bài giảng");
$PAGE->set_heading("Bài giảng");

echo $OUTPUT->header();
$parentId = required_param('parentId', PARAM_INT);
$action = optional_param('action', null, PARAM_ALPHA);
if ($action) {
    $deleteId = required_param('id', PARAM_INT);
    $coursecat = coursecat::get($deleteId, MUST_EXIST, true);
    //print_r($cate);
    //$coursecat->delete_full();
    if ($coursecat->can_delete_full()){
        $coursecat->delete_full();
    }
}
$PAGE->set_url(new moodle_url('/custom_course/subject_content.php', array('parentId' => $parentId)));
$parentCate = $DB->get_record('course_categories', array('id'=>$parentId));
///$cates = $DB->get_records('course_categories',array('parent'=>$parentId), null, 'id,name,description,parent,path');
$select = 'depth = ? AND path LIKE ?'; //is put into the where clause
/*
 * Note ve to chuc
 * Subject => depth = 1
 * * Khoi => depth = 2
 * * * Hoc ky => depth = 3
 * * * * Chuong => depth = 4
 */
//$cates = $DB->get_records_sql('SELECT * FROM {course_categories} WHERE depth = ? AND path LIKE ? ORDER BY sortorder ASC', array( '4' , '/'.$parentId.'%' ));
$customCates = coursecustom::get_chuong_by_subject($parentId);

$subjectId = $parentId;
$khoisOfSubject = $DB->get_records('course_categories',array('parent'=>$subjectId, 'depth'=>'2'), null, 'id,name,description,parent,path');

//get list hoc ky
$hocKysOfSubject = coursecustom::get_hk_by_subject($parentId);
//END get list hoc ky
$baseURLSubjectCourses = new moodle_url('/custom_course/subject_chapters.php');
$baseURLChapterEdit = new moodle_url('/custom_course/editchapter.php');


?>

    <style>
        #subject_content_toolbar label {
            margin-right: 5px;
        }
    </style>

    <div class="breadcump" style="background: #f5f5f5;"><?php

        $breadData = array (
            '0' => array ('label' => $parentCate->name, 'href' =>
                new moodle_url('/custom_course/subject_content.php', array('parentId'=> $parentId))),
        );
        require_once('breadcump.php');

        ?></div>
    <div class="subject_content_toolbar" id="subject_content_toolbar" style="margin-top: 10px;">
        <a class="btn btn-primary pull-left" id="addNewButton" style="margin-bottom: 5px;" href='<?php
        echo new moodle_url("/custom_course/editchapter.php", array('parentId'=>$subjectId));
        ?>'><i class="fa fa-plus"></i>Thêm chương mới</a>
        <label class="pull-right khoi">Khối <select class="slKhoi">
                <option value="0">Chọn khối</option>
                <?php
                foreach ($khoisOfSubject as $khoi) {
                    echo "<option value='".$khoi->id."'>".$khoi->name."</option>";
                }
                ?>
            </select></label>
        <label class="pull-right hocKy">Học kỳ <select class="slHocKy">
                <option value="0">Chọn học kỳ</option>
                <?php
                foreach ($hocKysOfSubject as $hocKy) {
                    echo "<option value='".$hocKy->id."'>".$hocKy->name."</option>";
                }
                ?>
            </select></label>
    </div>
    <div id="custom_course_datatables"></div>
    <script type='text/javascript'>

        function kiemTraXoa () {
            if (confirm("Xoá chương được chọn ?")){
                return true;
            }
            return false;
        }

        YUI().use('node', 'json-parse', 'datatable', 'node-event-delegate',
            "panel", "datatable-base", "dd-plugin", function(Y){
                console.log(Y);
                var data = '<?php echo json_encode(array_values($customCates)) ?>';
                console.info(data);
                data = Y.JSON.parse(data);
                console.log(data);
                var myTable = new Y.DataTable({
                    width: '100%',
                    columns: [
                        {
                            label: '#',
                            formatter: function(o){
                                return o.rowIndex + 1;
                            }
                        },
                        {
                            key: 'Tên chương',
                            allowHTML: true,
                            nodeFormatter: function (o) {
                                var editURL = '<?php echo $baseURLSubjectCourses; ?>';
                                editURL = editURL + "?id=" + o.data.id;
                                return o.cell.setHTML('<a href="' + editURL + '">' + o.data.name + '</a>');
                            }
                        },
                        {
                            key: 'description',
                            label: 'Mô tả',
                            allowHTML: true,
                        },
                        {
                            key: 'tenHocKy',
                            label: 'Học Kỳ',
                        },
                        {
                            key: 'tenKhoi',
                            label: 'Khối',
                        },
                        {
                            key: 'coursecount',
                            label: 'Số bài',
                            allowHTML: true,
                        },
                        {
                            label: ' ',
                            nodeFormatter: function(o) {
                                var url = '<?php echo $baseURLChapterEdit ?>';
                                url = url + '?id=' + o.data.id;
                                url = url + '&parentId=' + '<?php echo $parentId; ?>';
                                var edit = Y.Node.create('<a class="btn btn-success" href="' + url + '"></a>');
                                edit.append("<i class='fa fa-edit'></i>")
                                o.cell.append(edit);

                                var ques = Y.Node.create('<a class="btn btn-success" href="javascript:void(0);" placeholder="Quản lý câu hỏi"></a>');
                                ques.append("<i class='fa fa-question'></i>")
                                o.cell.append(ques);

                                var btn_delete = Y.Node.create('<a class="btn btn-danger" href="<?php echo $PAGE->url ?>&action=delete&id=' + o.data.id + '" onclick="kiemTraXoa();"></a>');
                                btn_delete.append("<i class='fa fa-remove'></i>")
                                o.cell.append(btn_delete);
                            }
                        }
                    ],
                    data: data,
                }).render('#custom_course_datatables');

                Y.one('#subject_content_toolbar').delegate ('change', function(e) {
                    //alert('filter active');
                    var content = Y.one('#subject_content_toolbar');
                    var idHocKy = content.one('.slHocKy').get('value');
                    var idKhoi = content.one('.slKhoi').get('value');
                    filterModel(idKhoi, idHocKy);
                }, 'select');

                function filterModel(idKhoi, idHocKy) {
                    // reset model list to include all colors to prepare for filter
                    myTable.set('data', data);
                    console.log("hoc ky: " + idHocKy);
                    console.log("Khoi : " + idKhoi);
                    var list = myTable.data,
                        filteredData = list.filter({asList: true}, function (list) {
                            //console.log(list);
                            var validHocKy = list.get('idHocKy');
                            validHocKy = (parseInt(idHocKy)==0) || validHocKy==idHocKy;
                            //console.log(validHocKy);
                            var validKhoi = list.get('idKhoi');

                            validKhoi = (parseInt(idKhoi)==0) || validKhoi==idKhoi;
                            // console.log(validKhoi);
                            return validHocKy && validKhoi;
                        });
                    myTable.set('data', filteredData);
                }

            });
    </script>

<?php
echo $OUTPUT->footer();
?>