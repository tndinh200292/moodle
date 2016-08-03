<?php
/**
 * Created by PhpStorm.
 * User: tndin
 * Date: 7/30/2016
 * Time: 5:24 PM
 */
require_once('../config.php');
require_once($CFG->libdir.'/coursecatlib.php');
require_once($CFG->dirroot.'/course/lib.php');
require_once('classes/baihocaction.php');
require_once('edit_bai_form.php');

$PAGE->set_pagelayout('admin');
$url = new moodle_url('/custom_course/editbai.php');

$PAGE->set_context(context_system::instance());
$idChuong = optional_param('cateId', 0, PARAM_INT);
$id = optional_param('id', 0, PARAM_INT);
$backURL = new moodle_url('/custom_course/subject_chapters.php', array('id'=>$idChuong));
global $DB;

if (isset($id) && $id>0) {
    //truong hop update
    $url->param('id', $id);
    $PAGE->set_title('Cập nhật bài học');
} else {
    $PAGE->set_title('Thêm bài học mới');
}
echo $OUTPUT->header();
$PAGE->set_url($url);
echo $id;
$myForm = new form_edit_bai(null, array('idBai'=>$id, 'cateId'=>$idChuong));
if ($myForm->is_cancelled()) {

} else if ($data = $myForm->get_data()){
    require_once ('classes/customcatews.php');
    echo ($backURL);
    $currentId = $data->id;
    if (isset($currentId) && $currentId > 0){
        //update
        $DB->update_record('course', $data);
    } else {
        //add new
        $DB->insert_record('course', $data);
    }
    echo "sub";


}
$myForm->display();
?>
<script>

    YUI().use('node', function (Y) {
       Y.one('#id_cancel').on('click', function(e){
           e.preventDefault();
           window.location.replace('<?php echo $backURL; ?>');
       }) ;
    });
</script>
<!--<script>
    function moodle_ws_call (serverUrl, token, type) {
        this.serverUrl = serverUrl;
        this.token = serverUrl;
    }

    moodle_ws_call.prototype.call = function(Y, functionName, data) {
        //console.log(functionName + "---" + data);
        console.log(this.serverUrl);
        Y.io(this.serverUrl, {
            data: data,
            type: 'POST',
            on: {
                success: function(o) {
                    alert(o.responseText);
                    Y.fire('ws_done', {
                        data: 'test'
                    });
                },
                failure: function () {
                    alert('fail');
                }
            }
        });


    }

    YUI().use('node', 'io', 'event', function(Y){
        var token = '67ca0972bec372c401bca03a48d653be';
        var domainname = 'http://dev.hoctructuyen247.com';
        Y.on('ws_done', function (e) {
            console.log(e);
        });

        Y.one('#region-main form').on('submit', function(e){
            e.preventDefault();
            var form = e.currentTarget;
            var id = form.one('[name="id"]');
            var ws_call = new moodle_ws_call(domainname + '/webservice/rest/server.php', token, 'json');
            var criterial = [
                {
                key: 'depth',
                value: 2
                }
            ]
            criterial = JSON.stringify(criterial);
            console.log(criterial);
            var data = {
                wstoken: token,
                wsfunction: 'core_course_get_categories',
                moodlewsrestformat: 'json',
                criteria: criterial
                //users: userstocreate
            }
            ws_call.call(Y, 'core_course_get_categories', data);
        });


    });
</script>-->
<?php
echo $OUTPUT->footer();