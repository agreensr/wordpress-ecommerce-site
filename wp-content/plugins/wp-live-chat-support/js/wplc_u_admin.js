var wplc_ajaxurl = wplc_ajaxurl;
var data = {
    action: 'wplc_admin_long_poll',
    security: wplc_ajax_nonce,
    wplc_list_visitors_data: false,
    wplc_update_admin_chat_table: false,
    wplc_extra_data: wplc_extra_data
};
var wplc_pending_refresh = null;
var current_chat_ids = new Object();
var chat_count = 0;
var wplc_run = true;
var ringer_cnt = 0;
var orig_title = document.getElementsByTagName("title")[0].innerHTML;

var wplc_notification_icon_url = wplc_notification_icon;

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};
function wplc_notify_agent() {


    if (typeof wplc_wav_file !== 'undefined') {
        new Audio(wplc_wav_file).play()                               
    }

    
    if (ringer_cnt <= 0) {
        wplc_desktop_notification();
    }
    ringer_cnt++;

    if (ringer_cnt > 1) {
        clearInterval(wplc_pending_refresh);
        wplc_title_alerts4 = setTimeout(function () {
            document.title = orig_title;
        }, 4000);
        return;
    }

    document.title = "** CHAT REQUEST **";
    wplc_title_alerts2 = setTimeout(function () {
        document.title = "** CHAT REQUEST **";
    }, 2000);
    wplc_title_alerts4 = setTimeout(function () {
        document.title = orig_title;
    }, 4000);


        
    

}
function wplc_call_to_server(data) {


    jQuery.ajax({
        url: wplc_ajaxurl,
        data: data,
        type: "POST",
        success: function (response) {

            //Update your dashboard gauge
            if (response) {
                response = JSON.parse(response);
                
                if(response.hasOwnProperty("error")){
                    /* stopping due to error */
                    wplc_run = false;
                    if (response['error'] === 1) {
                     location.reload();
                    }
                    
                }

                data["wplc_update_admin_chat_table"] = response['wplc_update_admin_chat_table'];
                if (response['action'] === "wplc_update_chat_list") {
                    wplc_handle_chat_output(response['wplc_update_admin_chat_table']);
                    if (response['pending'] === true) {
                        
                        wplc_notify_agent();
                        wplc_pending_refresh = setInterval(function () {
                            
                            wplc_notify_agent();
                        }, 5000);
                    } else {
                        clearInterval(wplc_pending_refresh);
                        ringer_cnt = 0;
                    }
                }
                if (response['action'] === "wplc_update_admin_chat") {
                    jQuery("#wplc_admin_chat_area").html(response['wplc_update_admin_chat_table']);
                    if (response['pending'] === true) {

                        var orig_title = document.getElementsByTagName("title")[0].innerHTML;
                        var ringer_cnt = 0;
                        wplc_pending_refresh = setInterval(function () {

                            if (ringer_cnt <= 0) {
                                wplc_desktop_notification();
                            }

                            ringer_cnt++;

                            if (ringer_cnt > 1) {
                                clearInterval(wplc_pending_refresh);
                                wplc_title_alerts4 = setTimeout(function () {
                                    document.title = orig_title;
                                }, 4000);
                                return;
                            }

                            document.title = "** CHAT REQUEST **";
                            wplc_title_alerts2 = setTimeout(function () {
                                document.title = "** CHAT REQUEST **";
                            }, 2000);
                            wplc_title_alerts4 = setTimeout(function () {
                                document.title = orig_title;
                            }, 4000);


                            //document.getElementById("wplc_sound").innerHTML = "<embed src='"+   +"' hidden=true autostart=true loop=false>";

                        }, 5000);
                    } else {
                        clearInterval(wplc_pending_refresh);
                    }
                }

            }
        },
        error: function (jqXHR, exception) {
            if (jqXHR.status == 404) {
                if( window.console ) { console.log('Requested page not found. [404]'); }
                wplc_run = false;
            } else if (jqXHR.status == 500) {
                if( window.console ) { console.log('Internal Server Error [500].'); }
                wplc_run = false;
            } else if (exception === 'parsererror') {
                if( window.console ) { console.log('Requested JSON parse failed.'); }
                wplc_run = false;
            } else if (exception === 'abort') {
                if( window.console ) { console.log('Ajax request aborted.'); }
                wplc_run = false;
            } else {
                if( window.console ) { console.log('Uncaught Error.\n' + jqXHR.responseText); }
                wplc_run = false;
            }
        },
        complete: function (response) {
            //console.log(wplc_run);
            if (wplc_run) {
                setTimeout(function () {
                    wplc_call_to_server(data);
                }, 1500);
            }
        },
        timeout: 120000
    });
};


function wplc_handle_chat_output(response) {
var obj = jQuery.parseJSON(response);
if (obj === false || obj === null) {
        jQuery("#wplc_chat_ul").html("");
        current_chat_ids = {};
        wplc_handle_count_change(0);
        
} else {
    var size = Object.size(current_chat_ids);
    wplc_handle_count_change(size);
    if (size < 1) {
        /* no prior visitor information, update without any checks */
        current_chat_ids = obj["ids"];
        wplc_update_chat_list(false,obj);
    } else {
        /* we have had visitor information prior to this call, update systematically */
        if (obj === null) {
            jQuery("#wplc_chat_ul").html("");
        } else {
            current_chat_ids = obj["ids"];
            wplc_update_chat_list(true,obj);
        }
    }


}
var size = Object.size(current_chat_ids);
wplc_handle_count_change(size);



}
function wplc_handle_count_change(qty) {
if (qty > chat_count) {
    jQuery(".wplc_chat_vis_count_box").animate({backgroundColor: '#B3D24B'}, 300);
    jQuery(".wplc_vis_online").html(qty);
    jQuery(".wplc_chat_vis_count_box").animate({backgroundColor: 'white'}, 200);
} else if (qty === chat_count) {
    jQuery(".wplc_vis_online").html(qty);
} else {
    jQuery(".wplc_chat_vis_count_box").animate({backgroundColor: '#E1734A'}, 300);
    jQuery(".wplc_vis_online").html(qty);
    jQuery(".wplc_chat_vis_count_box").animate({backgroundColor: 'white'}, 200);
}
chat_count = qty;

}


function wplc_get_status_name(status) {
if (status === 1) {
    return "<span class='wplc_status_box wplc_status_"+status+"'>complete</span>";
}
if (status === 2) {
    return "<span class='wplc_status_box wplc_status_"+status+"'>pending</span>";
}
if (status === 3) {
    return "<span class='wplc_status_box wplc_status_"+status+"'>active</span>";
}
if (status === 4) {
    return "<span class='wplc_status_box wplc_status_"+status+"'>deleted</span>";
}
if (status === 5) {
    return "<span class='wplc_status_box wplc_status_"+status+"'>browsing</span>";
}
if (status === 6) {
    return "<span class='wplc_status_box wplc_status_"+status+"'>requesting chat</span>";
}
if (status === 8){
    return "<span class='wplc_status_box wplc_status_"+status+"'>chat ended</span></span>";
}
if (status === 9){
    return "<span class='wplc_status_box wplc_status_"+status+"'>chat closed</span>";
}
if (status === 10){
    return "<span class='wplc_status_box wplc_status_8'>minimized</span>";
}
}
function wplc_get_type_box(type) {
if (type === "New") {
    return "<span class='wplc_status_box wplc_type_new'>New</span>";
}
if (type === "Returning") {
    return "<span class='wplc_status_box wplc_type_returning'>Returning</span>";
}
}


function wplc_create_chat_ul_element_after_eating_vindaloo(obj,key) {

var v_img = obj[key]['image'];
var v_name = obj[key]['name'];
var v_email = obj[key]['email'];
var v_browser = obj[key]['data']['browser'];
var v_browsing = obj[key]['data']['browsing_nice_url'];
var v_browsing_url = obj[key]['data']['browsing'];
var v_status = obj[key]['status'];
var v_time = obj[key]['timestamp'];
var v_type = obj[key]['type'];
var v_action = obj[key]['action'];
var v_status_string = wplc_get_status_name(parseInt(v_status));
var v_ip_address = obj[key]['data']['ip'];

var v_vis_html = "<span class='wplc_headerspan_v'>"+v_name+"</span>";
var v_nr_html = "<span class='wplc_headerspan_nr'><span class='browser-tag'>"+v_browser+"</span> "+wplc_get_type_box(v_type)+"</span>";
var v_time_html = "<span class='wplc_headerspan_t'><span class='wplc_status_box wplc_status_1'>"+v_time+"</span></span>";
var v_nr_data = "<span class='wplc_headerspan_d'><span class='wplc-sub-item-header'>Page:</span> <a href='"+v_browsing_url+"' target='_BLANK'>"+v_browsing+"</a><br /><span class='wplc-sub-item-header'>Email:</span> <a href='mailto:"+v_email+"' target='_BLANK' class='wplc-sub-item-email-string'>"+v_email+"</a><br/><span class='wplc-sub-item-header'>IP: </span>"+v_ip_address+"</span>";
var v_nr_status_html = "<span class='wplc_headerspan_s'>"+v_status_string+"</span>";
var v_nr_action_html = "<span class='wplc_headerspan_a 1'>"+v_action+"</span>";

var wplc_v_html = "\
    <ul id='wplc_p_ul_"+key+"' class='wplc_p_cul' cid='"+key+"'>\n\
            <li>"+v_vis_html+"</li>\n\
            <li>"+v_time_html+"</li>\n\
            <li>"+v_nr_html+"</li>\n\
            <li>"+v_nr_data+"</li>\n\
            <li>"+v_nr_status_html+"</li>\n\
            <li>"+v_nr_action_html+"</li>\n\
    <ul>";
return wplc_v_html;


}

function wplc_update_chat_list(update,obj) {

/* first compare existing elements with the elements on the page */
if (update === false) {
    jQuery( ".wplc_chat_ul" ).html("");

    for (var key in obj) {
        if (obj.hasOwnProperty(key) && key !== "ids") {
            wplc_v_html = wplc_create_chat_ul_element_after_eating_vindaloo(obj,key);
            jQuery( "#wplc_chat_ul" ).append(wplc_v_html).hide().fadeIn(2000);
            
        }
    }
    current_chat_ids = obj["ids"];

} else {
    
    for (var key in current_chat_ids) {
        current_id = key;
        if (document.getElementById("wplc_p_ul_"+current_id) !== null) {
            /* element is already there */
            /* update element */
            if (typeof obj[current_id] !== "undefined") { /* if this check isnt here, it will throw an error. This check is here incase the item has been deleted. If it has, it will be handled futher down */
                jQuery("#wplc_p_ul_"+current_id).remove();
                wplc_v_html = wplc_create_chat_ul_element_after_eating_vindaloo(obj,current_id);
                jQuery( "#wplc_chat_ul" ).append(wplc_v_html);
                //jQuery( ".wplc_chats_container" ).append(obj[current_id]['content']);
            }


        } else {
            jQuery("#nifty_c_none").hide();
            /* new element to be created */
            if (typeof obj[current_id] !== "undefined") { /* if this check isnt here, it will throw an error. This check is here incase the item has been deleted. If it has, it will be handled futher down */
                
                wplc_v_html = wplc_create_chat_ul_element_after_eating_vindaloo(obj,current_id);
                jQuery( "#wplc_chat_ul" ).append(wplc_v_html);
                
                jQuery("#wplc_p_ul_"+current_id).hide().fadeIn(2000);
                
            }
        }


    }

}

    /* compare new elements to old elements and delete where neccessary */


    jQuery(".wplc_p_cul").each(function(n, i) {
        var cid = jQuery(this).attr("cid");
        if (typeof cid !== "undefined") {
            if (typeof current_chat_ids[cid] !== "undefined") { /* element still there dont delete */ }
            else {
                jQuery("#wplc_p_ul_"+cid).fadeOut(2000).delay(2000).remove();
                
            }
            var size = Object.size(current_chat_ids);
            wplc_handle_count_change(size);
        }
        // do something with it
    });
    if(jQuery('.wplc_p_cul').length < 1) {
        wplc_handle_count_change(0);
        current_chat_ids = {};
    }





}


jQuery(document).ready(function () {
    jQuery('body').on("click", "a", function (event) {
        if (jQuery(this).hasClass('wplc_open_chat')) {
            if (event.preventDefault) {
                event.preventDefault();
            } else {
                event.returnValue = false;
            }
            window.open(jQuery(this).attr("href"), jQuery(this).attr("window-title"), "width=800,height=600,scrollbars=yes", false);
        }
    });
    
    jQuery('body').on("click", "#wplc_close_ftt", function (event) {
        jQuery("#wplcftt").fadeOut(1000);
        var data = {
            action: 'wplc_hide_ftt',
            security: wplc_ajax_nonce,
        };
        jQuery.ajax({
            url: wplc_ajaxurl,
            data: data,
            type: "POST",
            success: function (response) {

            }
        });
       
    });

    wplc_call_to_server(data);

    jQuery("body").on("click", ".wplc_delete_message", function(e){

        var message_id = jQuery(this).attr('mid');

        var data = {
            action: 'delete_offline_message',
            security: wplc_ajax_nonce,
            mid: message_id
        }

        jQuery.post( wplc_ajaxurl, data, function( response ){

            if( response ){

                jQuery('#record_'+message_id).fadeOut(700);

            }


        });

    });

});

