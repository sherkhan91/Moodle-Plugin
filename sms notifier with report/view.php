<?php
require_once('../../config.php');
require_once('sms_form.php');
// require_once('simplehtml_form.php');
require_once("lib.php");
// Global variable.
global $DB, $OUTPUT, $PAGE, $CFG, $USER;
require_login();
// Plugin variable.
$viewpage = required_param('viewpage', PARAM_INT);
$rem = optional_param('rem', null, PARAM_RAW);
$edit = optional_param('edit', null, PARAM_RAW);
$delete = optional_param('delete', null, PARAM_RAW);
$id = optional_param('id', null, PARAM_INT);
// Page settings.
$PAGE->set_pagelayout('standard');
//$PAGE->set_title(get_string('pluginname', 'block_sms'));
$PAGE->set_title('ISRA');
$PAGE->set_heading('SMS Notification');
$pageurl = new moodle_url('/blocks/sms/view.php?viewpage=2');
$refreshurl = new moodle_url('/blocks/sms/view.php?viewpage=1');
echo $OUTPUT->header();

// Conditions
//---------------------------------------------------------------------------------------------------------------------------------------------------
if($viewpage==1)
{
$uu=$USER->id;
if($uu=='2')
{
global $DB;
$form = new simplehtml_form();

  $toform['viewpage'] = $viewpage;
    $form->set_data($toform);
    $form->display();
    $table=$form->display_report();
    echo html_writer::table($table);



echo "<form method='POST' action=' '>";
echo "<input type='submit' name='delete'  value='Delete all'>";
echo "</form>";

if (isset($_POST['delete'])) 
{ 
global  $DB;
        $DB->delete_records('history');
 redirect($refreshurl);
}  

}
else
{
echo "you don't have permission to see history";
}

}




//---------------------------------------------------------------------------------------------------------------

 else if($viewpage == 2) {
    $form = new sms_send();
    $form->display();
    $table=$form->display_report();
    $a= html_writer::table($table);
    echo "<form action='' method='post' name='tests'><div id='table-change'>".$a."</div><input type='submit' style='margin-left:700px;'
         name='submit' id='smssend' value='Send SMS'/><input type='hidden' name='viewpage' id='viewpage' value='$viewpage'/></form>";

    if(isset($_REQUEST['submit'])) {
        $msg=$_REQUEST['msg']; // SMS Meassage.
        $user = $_REQUEST['user']; // User ID.
        if(empty($user)) {
            echo("You didn't select any user.");
        }
        else {
            $N = count($user);
        }
       
        global $DB, $CFG;
        $table = new html_table();
        $table->head  = array(get_string('serial_no', 'block_sms'), get_string('moodleuser', 'block_sms'), get_string('usernumber', 'block_sms'), get_string('status', 'block_sms'));
        $table->size  = array('10%', '40%', '30%', '20%');
        $table->align  = array('center', 'left', 'center', 'center');
        $table->width = '100%';
        // Sendsms.pk API.
        if($CFG->block_sms_api == 1) 
{
            for($a=0; $a< $N;$a++) {
                 $id=$user[$a];
                 $sql='SELECT usr.firstname, usr.id, usr.lastname, usr.email,usr.phone2 FROM {user} usr WHERE usr.id =?';
                 $rs2 = $DB->get_record_sql($sql, array($id));
                 $no= $rs2->phone2;
                 if(!empty($no)) {
                     $status = send_sms($no,$msg);
                     if($status == get_string('sent', 'block_sms')) {
                   $status="sent";   }
                }
                else {
	$status="error";
         
                }
                $row = array();
               $row[] = $a+1;
               $row[] = $rs2->firstname;
              $row[] = $rs2->phone2;
              $row[] = $status;
               $table->data[] = $row;



$record= new stdClass();
//$record->Id = $id+1 ;
$record->Name = $rs2->firstname;
$record->Message = $msg;
$record->Phone= $rs2->phone2;
$record->Status= $status;
//$record->time    this by default get current time defined in history table

$DB->insert_record('history', $record);


            }

        }

        echo html_writer::table($table);
    }


}
else if($viewpage == 3) { 
    $form = new template_form();

    if($rem) {
        if($delete) {
            global  $DB;
            $DB->delete_records('block_sms_template', array('id'=>$delete));
            redirect($pageurl);
        }
        else {
              echo $OUTPUT->confirm(get_string('askfordelete', 'block_sms'), '/blocks/sms/view.php?viewpage=3&rem=rem&delete='.$id, '/blocks/sms/view.php?viewpage=3');
        }
   }
    // Edit Message Template.
    if($edit) {
        $get_template = $DB->get_record('block_sms_template', array('id'=>$id), '*');
        $form = new template_form();
        $form->set_data($get_template);
    }

    $toform['viewpage'] = $viewpage;
    $form->set_data($toform);
    $form->display();
    $table=$form->display_report();
    echo html_writer::table($table);
   
   
}



if($fromform = $form->get_data()) { 
        
    if($viewpage == 3) { 
        global $DB;
        $chk = ($fromform->id) ? $DB->update_record('block_sms_template', $fromform) : $DB->insert_record('block_sms_template', $fromform);
        redirect($pageurl);
    }
}

$params = array($viewpage);
// way to import .js file in any file of moodle
$PAGE->requires->js_init_call('M.block_sms.init', $params);
echo $OUTPUT->footer();