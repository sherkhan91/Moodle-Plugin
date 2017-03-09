<?php
require_once("{$CFG->libdir}/formslib.php");
require_once("lib.php");


// extends moodle form class 
class sms_send extends moodleform {
    public function definition() {
        global $DB,$CFG,$USER;
// initiate a new form
        $mform =& $this->_form;
	$mform->addElement('header', 'sms_send', get_string('sms_send', 'block_sms'));
         {
// get current user id and convert it in string
 $uid=$USER->id;
$var;
$var = (string)$uid;	
 // query to get courses 			         
      $attributes =  $DB->get_records_sql_menu("SELECT c.id, c.fullname FROM {course} c JOIN {context} ctx ON c.id = ctx.instanceid JOIN {role_assignments} ra ON ra.contextid = ctx.id JOIN {user} u ON u.id = ra.userid where u.id='$var' and c.fullname != 'project'", array($params=null), $limitfrom=0, $limitnum=$courses);
        }
// create a form element
      $mform->addElement('select', 'c_id', get_string('selectcourse', 'block_sms'),$attributes);     
        $mform->setType('c_id', PARAM_INT);  // set type of parameters
         {
$uu=$USER->id;
	if($uu=='2') {
	    $attributes1=array('Teacher', 'Student','all'); }
else { $attributes1=array('Student'); }
        }
   // query to get roles
        $attributes2 =  $DB->get_records_sql_menu('SELECT id , name FROM {role} ', null, $limitfrom=0, $limitnum=0);
        $attributes=array_intersect($attributes2, $attributes1);
        $mform->addElement('select', 'r_id', get_string('selectrole', 'block_sms'), $attributes);
// query to get templates
        $attributes =  $DB->get_records_sql_menu('SELECT id,tname FROM {block_sms_template}', null, $limitfrom=0, $limitnum=0);
        $mform->addElement('selectwithlink', 'm_id', get_string('selectmsg', 'block_sms'), $attributes, null,
                           array('link' => $CFG->wwwroot.'/blocks/sms/view.php?viewpage=3', 'label' => get_string('template', 'block_sms')));
// text field for writing message
        $attributes = array('rows' => '6', 'cols' => '45', 'maxlength' => '160');
        $mform->setType('r_id', PARAM_INT);
        $mform->addElement('textarea', 'sms_body', get_string('sms_body', 'block_sms'), $attributes);
       $mform->addRule('sms_body','Please write Message' , 'required', 'client');
       $mform->addRule('sms_body', $errors, 'required', null, 'server');
        $mform->setType('sms_body', PARAM_TEXT);
        $mform->addElement('html', '<img src="Loading.gif" id="load" style="margin-left:6cm;" />');
        $mform->addElement('hidden', 'viewpage', '2');
        $mform->addElement('hidden', 'id');
        $mform->addElement('button', 'nextbtn', 'Show Users', array("id" => "btnajax"));
    }
// set up user table on page 
    public function display_report($c_id, $r_id) {
        global $DB, $OUTPUT, $CFG, $USER;
        $table = new html_table();
        $table->attributes = array("name" => "userlist");
        $table->attributes = array("id" => "userlist");
        $table->width = '100%';
        $table->data  = array();
        if(empty($c_id)) {
            $c_id=1;
            $r_id=3;
        }
// display users
        $sql="SELECT usr.firstname, usr.id, usr.lastname, usr.email,usr.phone2,c.fullname
            FROM {course} c
            INNER JOIN {context} cx ON c.id = cx.instanceid
            AND cx.contextlevel = '50' and c.id=$c_id
            INNER JOIN {role_assignments} ra ON cx.id = ra.contextid
            INNER JOIN {role} r ON ra.roleid = r.id
            INNER JOIN {user} usr ON ra.userid = usr.id
            WHERE r.id = $r_id";
        $count  =  $DB->record_exists_sql($sql, array ($params=null));

//----------------------------
echo "<script type='text/javascript'>
var toggle = true;

function toggleBoxes() {
    var objList = document.getElementsByName('user[]')
    
    for(i = 0; i < objList.length; i++)
        objList[i].checked = toggle;
    
    toggle = !toggle;
}
</script>";

        if($count >= 1) {
            $table->head  = array(get_string('serial_no', 'block_sms'), get_string('name', 'block_sms'), get_string('cell_no', 'block_sms'), get_string('select', 'block_sms'));
            $table->size  = array('10%', '20%', '20%', '20%');
            $table->align  = array('center', 'left', 'center', 'center');
            $rs = $DB->get_recordset_sql($sql, array());

            $i=0;
           echo "<form>";
            foreach ($rs as $log) {
                $fullname = $log->firstname;
                $row = array();
                $row[] = ++$i;
                $row[] = $log->firstname;
                $row[] = $log->phone2;
                $row[] = "<input type='checkbox' name='user[]' value='$log->id'>";
                $table->data[] = $row;
            }


echo "<input type='button' id='chk' name='chck' value='select all' onClick='toggleBoxes()' style='margin-left:940px;'></form>";

        }

        else {

            $row = array();
            $row[] = "<div id='load-users' style='border: 1px solid;margin: 10px 0px;padding:15px 10px 15px 50px;background-repeat: no-repeat;background-position: 10px center;color: #00529B;background-image: url(".'pic/info.png'."); background-color: #BDE5F8;border-color: #3b8eb5;'>Record not Found</div>";  
            $table->data[] = $row;
        }
        return $table;
    }
}
// Display SMS Template. and creates another form for template
class template_form extends moodleform {
    public function definition() {
        $mform =& $this->_form;
        $mform->addElement('header', 'sms_template_header', get_string('sms_template_header', 'block_sms'));
        $mform->addElement('text', 'tname', 'Name:', array('size' => 44, 'maxlength' => 160));
        $mform->addRule('tname', 'Please Insert Template Name', 'required', 'client');
        $mform->setType('tname', PARAM_TEXT);
        $mform->addElement('textarea', 'template', 'Message:', array('rows' => '6', 'cols' => '47', 'maxlength' => '160', 'id' => 'asd123'));
        $mform->addRule('template', 'Please Insert Template Message', 'required', 'client');
        $mform->setType('template', PARAM_TEXT);
        $mform->addElement('hidden', 'viewpage');
        $mform->addElement('hidden', 'id');
        $this->add_action_buttons();
    }
    public function validation($data) {

        
         global $DB;
        $errors= array();
        if($data['tname'] == "") {
           echo  $errors['tname'] = "Please Insert Temaplte Name.";
            return $errors;
        }
        else if($DB->record_exists('block_sms_template',array('tname' => $data['tname']))) {

echo  $errors['template'] = 'Template Name is already exists';

            return $errors;

        }
        else {
            return true;
        }  
        return true;
    }

    public function display_report() {
        global $DB, $OUTPUT, $CFG, $USER;
        $table = new html_table();
        $table->head  = array(get_string('serial_no', 'block_sms'), get_string('name', 'block_sms'), get_string('msg_body', 'block_sms'), get_string('edit', 'block_sms'), get_string('delete', 'block_sms'));
        $table->size  = array('10%', '20%', '50%', '10%', '10%');
        $table->align  = array('center', 'left', 'left', 'center', 'center');
        $table->width = '100%';
        $table->data  = array();
        $sql="SELECT * FROM {block_sms_template}";
        $rs = $DB->get_recordset_sql($sql, array(),  $page*$perpage, $perpage);
        
        $i=0;
        foreach ($rs as $log) {
            $row = array();
            $row[] = ++$i;
            $row[] = $log->tname;
            $row[] = $log->template;
            $row[] = '<a  title="Edit" href="'.$CFG->wwwroot.'/blocks/sms/view.php?viewpage=3&edit=edit&id='.$log->id.'"/><img src="'.$OUTPUT->pix_url('t/edit') . '" class="iconsmall" /></a> ';
            $row[] = '<a  title="Remove" href="'.$CFG->wwwroot.'/blocks/sms/view.php?viewpage=3&rem=remove&id='.$log->id.'"/><img src="'.$OUTPUT->pix_url('t/delete') . '" class="iconsmall"/></a>';
            $table->data[] = $row;
        }
        return $table;
    }


}

//------------------------------------------------
// history form
 
class simplehtml_form extends moodleform {
    //Add elements to form
    public function definition() {
        global $CFG, $DB;

        $mform =& $this->_form; // Don't forget the underscore! 
 
  
}

public function display_report() {

        global $DB, $OUTPUT, $CFG, $USER;

        $table = new html_table();
        $table->head  = array( get_string('serial_no', 'block_sms'),  get_string('name', 'block_sms'), get_string('message', 'block_sms'), get_string('cell_no', 'block_sms'), get_string('status', 'block_sms'), get_string('time', 'block_sms'));
        $table->size  = array('10%','20','30%','10%','10%','20%');
        $table->align  = array('left','left','center', 'left','left','left');
        $table->width = '100%';
        $table->data  = array();
        $sql="SELECT id,name,phone,status,time,message FROM {history}";
        $rs = $DB->get_recordset_sql($sql, array());
        
      //  $i=0;
        foreach ($rs as $log) {
            $row = array();
            $row[]=$log->id;
            $row[] = $log->name;
            $row[]=$log->message;
             $row[]=$log->phone;
             $row[]=$log->status;
             $row[]=$log->time;
            $table->data[] = $row;
        }

        return $table;
    }

}
//-------------------------------------------
