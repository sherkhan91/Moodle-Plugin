<?php
// safety precaution to trigger some scripts that do not directly run by browser
defined('MOODLE_INTERNAL') || die;
// checking condition if user is admin
if($ADMIN->fulltree) {
// this set configuration settings 

    $settings->add(new admin_setting_configtext(get_string('block_sms_apikey', 'block_sms'),
                                                        get_string('sms_api_key', 'block_sms'),
                                                        get_string('sms_api_key', 'block_sms'),
                                                        '', PARAM_TEXT));
    $settings->add(new admin_setting_configtext(get_string('block_sms_api_username', 'block_sms'),
                                                        get_string('sms_api_username', 'block_sms'),
                                                        get_string('sms_api_username', 'block_sms'),
                                                        '', PARAM_TEXT));
    $settings->add(new admin_setting_configtext(get_string('block_sms_api_password', 'block_sms'),
                                                        get_string('sms_api_password', 'block_sms'),
                                                        get_string('sms_api_password', 'block_sms'),
                                                        '', PARAM_TEXT));

    $settings->add(new admin_setting_configselect('block_sms_api','SMS API Name','sendsms.pk',' ',array('Sendsms.pk ','Sendsms.pk')));
									
}