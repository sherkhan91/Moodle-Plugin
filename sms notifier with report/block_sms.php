<?php

// extends basic block class and initialize the block
class block_sms extends block_base {
    public function init() {
        $this->title = get_string('notification', 'block_sms');
    }
// what data is our block get from moodle e.g user,course etc specified here
    public function get_content() {
        global $CFG, $USER, $COURSE;
        if ($this->content !== null) {
            return $this->content;
        }
       
// generic empty class, as object in java or other languages , here its like that, as with object we can use any function.
      $this->content =  new stdClass;
        
// html::writer is a class and link function is called here to create link to pages.      
  
        $this->content->text .= html_writer::link(new moodle_url('/blocks/sms/view.php', array('viewpage' => '2')), get_string('sms_send', 'block_sms')).'<br>';
        $this->content->text .= html_writer::link(new moodle_url('/blocks/sms/view.php', array('viewpage' => '3')), get_string('sms_template', 'block_sms')).'<br>';    
        $this->content->text .= html_writer::link(new moodle_url('/blocks/sms/view.php', array('viewpage' => '1')), get_string('history', 'block_sms')).'<br>';   
        return $this->content;
    }
// does our block has global configurtion settings and to access all moodle config's.
    public function has_config() {
        return true;
    }
// in which format this block can appear in courses,blocks, modules etc  // if 'mod' => false then it wouldn't be used in modules
    public function applicable_formats() {
        return array('all' => true);
    }
// for seprate instance configuration of every instance
    public function instance_allow_config() {
        return true;
    }
// load immediatly after instance has created and save site,name,title etc
    public function specialization() {
        if (!empty($this->config->title)) {
            $this->title = $this->config->title;
        } 
        
    }
// to save instance data
    public function instance_config_save($data) {
        foreach ($data as $name => $value) {
            set_config($name, $value);
       
        }
        return true;
    }
}   