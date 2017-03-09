<?php

$msg=($_REQUEST['msg']); 

require_once('../../config.php');
require_once('sms_form.php');
require_once("lib.php");
// to ensure that form required parametrs  are given otherwise it wouldn't show
$c_id = required_param('c_id', PARAM_INT);
$r_id = required_param('r_id', PARAM_INT);
$form = new sms_send();
$table= $form->display_report($c_id,$r_id);
$a= html_writer::table($table);
 echo $a."<input type='hidden' value='$msg' name='msg' />";
// echo  $a; 
?>