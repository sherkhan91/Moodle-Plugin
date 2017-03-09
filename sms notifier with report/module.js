// JavaScript Document
// M is the namespaace of java script library
M.block_sms = {};  //initilized the library for block
M.block_sms.init = function(Y,param3) {    //param3 means view page

    // Variables.
//y.one returns a single node instance bound to the node 
    var showuser= Y.one("#btnajax");   
    var sms_send = Y.one('#smssend');
    var action = Y.one('#id_r_id'); // used to bound the role id drop down list
    var action1 = Y.one('#id_m_id');
    var action2 = Y.one('#id_c_id');
    var userlist= Y.one("#table-change");
    var img=Y.one('#load');

    // Body load first time
    var msg_body = Y.one('#id_sms_body');
    
    //action on m_id , message template from load message.
       var m_id=action1.get('value');
            Y.io('load_message.php?m_id='+m_id, {         // y.io is yahoo input output
                on: { 
                    start: function(id, args) {
                        msg_body.hide();
                        img.show();
                        
                    },
                    complete: function(id, e) {
// json is a light weight database like xml , json , javascript object notation
                        var json = e.responseText;
// for print purpose ,,, whatever object we pass to it, it prints here we have give the json database to print
                        console.log(json);
                        img.hide();
                        msg_body.show();
                        msg_body.set('value', json);
                    }
                 }
          });

    // Image default setting.
    img.hide();
    sms_send.hide();

    // Event occurs after click on show user button.
    showuser.on('click',function() {
        var content = Y.one('#id_sms_body');
        var c_id=action2.get('value');
        var r_id=action.get('value');
        var msg = content.get('value');

        Y.io('user_list.php?msg='+msg+'&c_id='+c_id+'&r_id='+r_id, {
            on: {
                start: function(id, args) {
                    userlist.set('innerHTML','<img src="Loading.gif" id="load-users" style="margin-left:6cm;" />');
                },
                complete: function(id, e) {
                    var json = e.responseText;
                    console.log(json);
                    userlist.set('innerHTML', json);
                    sms_send.show();
                    }
                }
          });
    });
    // End show user event.

    // If viewpage is 2 means send sms page.
    if(param3 == '2') {
        action.on('change', function() {
            var b=this.get('text');
        });

        // Select Message Template.
        action1.on('change', function() {
            var content = Y.one('#id_sms_body');
            var m_id=action1.get('value');
            Y.io('load_message.php?m_id='+m_id, {
                on: {
                    start: function(id, args) {
                        content.hide();
                        img.show();
                    },
                    complete: function(id, e) {
                        var json = e.responseText;
                        console.log(json);
                        img.hide();
                        content.show();
                        content.set('value', json);
                    }
                 }
          });
       });
    }
};