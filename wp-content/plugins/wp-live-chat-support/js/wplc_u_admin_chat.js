var wplc_ajaxurl = wplc_ajaxurl;
var chat_status = 3;
var cid = wplc_cid;

if (typeof wplc_action2 !== "undefined" && wplc_action2 !== "") { 

    var data = {
        action: 'wplc_admin_long_poll_chat',
        security: wplc_ajax_nonce,
        cid: cid,
        chat_status: chat_status,
        action_2: wplc_action2,
        wplc_extra_data: wplc_extra_data
    };
} else {
    var data = {
        action: 'wplc_admin_long_poll_chat',
        security: wplc_ajax_nonce,
        cid: cid,
        chat_status: chat_status,
        wplc_extra_data: wplc_extra_data
    };

}
var wplc_run = true;
var wplc_had_error = false;
var wplc_display_name = wplc_name;
var wplc_enable_ding = wplc_enable_ding;
var wplc_user_email_address = wplc_user_email;

function wplc_call_to_server_admin_chat(data) {
jQuery.ajax({
    url: wplc_ajaxurl,
    data: data,
    security: wplc_ajax_nonce,
    type: "POST",
    success: function (response) {
        if (response) {

            response = JSON.parse(response);

            if (response['action'] === "wplc_ma_agant_already_answered") {
                jQuery(".end_chat_div").empty();
                jQuery('#admin_chat_box').empty().append("<h2>This chat has already been answered. Please close the chat window</h2>");
                wplc_run = false;
            }

            if (response['action'] === "wplc_update_chat_status") {
                data['chat_status'] = response['chat_status'];
                wplc_display_chat_status_update(response['chat_status'], cid);
            }
            if (response['action'] === "wplc_new_chat_message") {
                current_len = jQuery("#admin_chat_box_area_" + cid).html().length;
                jQuery("#admin_chat_box_area_" + cid).append(response['chat_message']);
                new_length = jQuery("#admin_chat_box_area_" + cid).html().length;
                if (current_len < new_length) {
                    if (typeof wplc_enable_ding !== 'undefined' && wplc_enable_ding === "1") {
                        new Audio(wplc_ding_file).play()                               
                    }
                }
                var height = jQuery('#admin_chat_box_area_' + cid)[0].scrollHeight;
                jQuery('#admin_chat_box_area_' + cid).scrollTop(height);
            }
            if (response['action'] === "wplc_user_open_chat") {
                data['action_2'] = "";
                window.location.replace(wplc_url);
            }

        }
    },
    error: function (jqXHR, exception) {
        if (jqXHR.status == 404) {
            if (window.console) { console.log('Requested page not found. [404]'); }
            wplc_run = false;
        } else if (jqXHR.status == 500) {
            if (window.console) { console.log('Internal Server Error [500].'); }
            wplc_run = true;
            wplc_had_error = true;
            setTimeout(function () {
                wplc_call_to_server_admin_chat(data);
            }, 10000);
        } else if (exception === 'parsererror') {
            if (window.console) { console.log('Requested JSON parse failed.'); }
            wplc_run = false;
        } else if (exception === 'abort') {
            if (window.console) { console.log('Ajax request aborted.'); }
            wplc_run = false;
        } else {
            if (window.console) { console.log('Uncaught Error.\n' + jqXHR.responseText); }
            wplc_run = true;
            wplc_had_error = true;
            setTimeout(function () {
                wplc_call_to_server_admin_chat(data);
            }, 10000);
        }
    },
    complete: function (response) {
        //console.log(wplc_run);
        if (wplc_run && !wplc_had_error) {
            setTimeout(function () {
                wplc_call_to_server_admin_chat(data);
            }, 1500);
        }
    },
    timeout: 120000
    
    });
};

function wplc_display_chat_status_update(new_chat_status, cid) {
if (new_chat_status === "0") {
} else {
    if (chat_status !== new_chat_status) {
        previous_chat_status = chat_status;
        //console.log("previous chat status: "+previous_chat_status);
        chat_status = new_chat_status;
        //console.log("chat status: "+chat_status);

        if ((previous_chat_status === "2" && chat_status === "3") || (previous_chat_status === "5" && chat_status === "3")) {
            jQuery("#admin_chat_box_area_" + cid).append("<em>"+wplc_string1+"</em><br />");
            var height = jQuery('#admin_chat_box_area_' + cid)[0].scrollHeight;
            jQuery('#admin_chat_box_area_' + cid).scrollTop(height);

        } else if (chat_status == "10" && previous_chat_status == "3") {
            jQuery("#admin_chat_box_area_" + cid).append("<em>"+wplc_string2+"</em><br />");
            var height = jQuery('#admin_chat_box_area_' + cid)[0].scrollHeight;
            jQuery('#admin_chat_box_area_' + cid).scrollTop(height);
        }
        else if (chat_status === "3" && previous_chat_status === "10") {
            jQuery("#admin_chat_box_area_" + cid).append("<em>"+wplc_string3+"</em><br />");
            var height = jQuery('#admin_chat_box_area_' + cid)[0].scrollHeight;
            jQuery('#admin_chat_box_area_' + cid).scrollTop(height);
        }
        else if (chat_status === "1" || chat_status === "8") {
            wplc_run = false;
            jQuery("#admin_chat_box_area_" + cid).append("<em>"+wplc_string4+"</em><br />");
            var height = jQuery('#admin_chat_box_area_' + cid)[0].scrollHeight;
            jQuery('#admin_chat_box_area_' + cid).scrollTop(height);
            document.getElementById('wplc_admin_chatmsg').disabled = true;
        }
    }
}
}



jQuery(document).ready(function () {

    var wplc_image = admin_pic;
    


    jQuery("#wplc_admin_chatmsg").focus();


    wplc_call_to_server_admin_chat(data);
    
    if (typeof wplc_action2 !== "undefined" && wplc_action2 !== "") { return; }

    if (jQuery('#wplc_admin_cid').length) {
        var wplc_cid = jQuery("#wplc_admin_cid").val();
        var height = jQuery('#admin_chat_box_area_' + wplc_cid)[0].scrollHeight;
        jQuery('#admin_chat_box_area_' + wplc_cid).scrollTop(height);
    }



    jQuery(".wplc_admin_accept").on("click", function () {
        wplc_title_alerts3 = setTimeout(function () {
            document.title = "WP Live Chat Support";
        }, 2500);
        var cid = jQuery(this).attr("cid");

        var data = {
            action: 'wplc_admin_accept_chat',
            cid: cid,
            security: wplc_ajax_nonce
        };
        jQuery.post(wplc_ajaxurl, data, function (response) {
            //console.log("wplc_admin_accept_chat");
            wplc_refresh_chat_boxes[cid] = setInterval(function () {
                wpcl_admin_update_chat_box(cid);
            }, 3000);
            jQuery("#admin_chat_box_" + cid).show();
        });
    });

    jQuery("#wplc_admin_chatmsg").keyup(function (event) {
        if (event.keyCode == 13) {
            jQuery("#wplc_admin_send_msg").click();
        }
    });

    jQuery("#wplc_admin_close_chat").on("click", function () {
        var wplc_cid = jQuery("#wplc_admin_cid").val();
        var data = {
            action: 'wplc_admin_close_chat',
            security: wplc_ajax_nonce,
            cid: wplc_cid,
            wplc_extra_data: wplc_extra_data

        };
        jQuery.post(wplc_ajaxurl, data, function (response) {
            //console.log("wplc_admin_close_chat");
            
            window.close();
        });

    });

    function wplc_strip(str) {
        str=str.replace(/<br>/gi, "\n");
        str=str.replace(/<p.*>/gi, "\n");
        str=str.replace(/<a.*href="(.*?)".*>(.*?)<\/a>/gi, " $2 ($1) ");
        str=str.replace(/<(?:.|\s)*?>/g, "");

        str=str.replace('iframe', "");    
        str=str.replace('src', "");    
        str=str.replace('href', "");  
        str=str.replace('<', "");  
        str=str.replace('>', "");  

        return str;
    }

    jQuery("#wplc_admin_send_msg").on("click", function () {
        var wplc_cid = jQuery("#wplc_admin_cid").val();
        var wplc_chat = wplc_strip(document.getElementById('wplc_admin_chatmsg').value);
        var wplc_name = "a" + "d" + "m" + "i" + "n";
        jQuery("#wplc_admin_chatmsg").val('');

        if (wplc_display_name == 'display') {
            jQuery("#admin_chat_box_area_" + wplc_cid).append("<span class='wplc-admin-message'>" + wplc_image + " <strong>" + wplc_name + "</strong>:<hr/ style='margin-bottom: 0px;'>" + wplc_chat + "</span><br /><div class='wplc-clear-float-message'></div>");
        } else {
            jQuery("#admin_chat_box_area_" + wplc_cid).append("<span class='wplc-admin-message'>" + wplc_chat + "</span><br /><div class='wplc-clear-float-message'></div>");
        }
        var height = jQuery('#admin_chat_box_area_' + wplc_cid)[0].scrollHeight;
        jQuery('#admin_chat_box_area_' + wplc_cid).scrollTop(height);


        var data = {
            action: 'wplc_admin_send_msg',
            security: wplc_ajax_nonce,
            cid: wplc_cid,
            msg: wplc_chat,
            wplc_extra_data:wplc_extra_data
        };
        jQuery.post(wplc_ajaxurl, data, function (response) {

        });


    });







});