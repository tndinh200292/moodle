<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require_once('../config.php');
require_once('edit_form.php');
global $DB;
$PAGE->set_context(get_system_context());
$PAGE->set_pagelayout('admin');
$PAGE->set_title("Quản lý bài giảng");
$PAGE->set_heading("Bài giảng");
$PAGE->set_url($CFG->wwwroot.'/custom_course/index.php');

$backURL = optional_param('backURL', 0, PARAM_TEXT);
echo  $backURL;

echo $OUTPUT->header();

$mform = new lesson_edit_form();
$mform -> display();

echo $OUTPUT->footer();

?>