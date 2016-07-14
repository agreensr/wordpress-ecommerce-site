/*
 * Cookie Status
 * 
 * 1 - complete - user has left site
 * 2 - pending - user waiting for chat to be answered by admin
 * 3 - active chat - user and admin are chatting
 * 4 - deleted
 * 5 - browsing - no data has been inputted
 * 6 - requesting chat - admin has requested a chat with user
 * 7 - timed out - visitor has timed out 
 * 8 - complete but now browsing again
 * 9 - user closed chat before starting chat
 * 10 - user minimized active chat
 * 11 - user moved on to another page (session variable is different)
 * 
 */
var wplc_is_chat_open = false;
jQuery(document).ready(function() {        
    var wplc_session_variable = new Date().getTime();
    var wplc_cid;
    var wplc_check_hide_cookie;
    var wplc_chat_status = "";
    var wplc_cookie_name = "";
    var wplc_cookie_email = "";
    var wplc_init_chat_box_check = true;
    var wplc_cid = null;
    var wplc_online = false;
    var initial_data = {};
    var wplc_fist_run = true;    
 

    wplc_cid = Cookies.get('wplc_cid');
    
    if(typeof wplc_cid === 'undefined'){
        wplc_cid = null;
    } else {
        wplc_cid = Cookies.get('wplc_cid');
    }
    
    wplc_check_hide_cookie = Cookies.get('wplc_hide');
    wplc_check_minimize_cookie = Cookies.get('wplc_minimize');
    wplc_chat_status = Cookies.get('wplc_chat_status');
    wplc_cookie_name = Cookies.get('wplc_name');
    wplc_cookie_email = Cookies.get('wplc_email');
    // Always start on 5 - ajax will then return chat status if active
    Cookies.set('wplc_chat_status', 5, { expires: 1, path: '/' });
    wplc_chat_status = 5;
    
    var wplc_run = true;

    var data = {
        action: 'wplc_get_chat_box',
        security: wplc_nonce
    };
    jQuery.ajax({
        url: wplc_ajaxurl_site,
        data:data,
        type:"POST",
        success: function(response) {
            /* inject html */
            if(response){
                response = JSON.parse(response);
                 
                jQuery( "body" ).append( response['cbox']);

                if( typeof wplc_cookie_name == 'undefined' || typeof wplc_cookie_email == 'undefined' ){

                    var wplc_cookie_name =  jQuery( jQuery.parseHTML( response['cbox'] ) ).find( "#wplc_name" ).val(); 
                    var wplc_cookie_email =  jQuery( jQuery.parseHTML( response['cbox'] ) ).find( "#wplc_email" ).val(); 

                }
                
                /* is an agent online? */
                if (response['online'] === false) {
                    wplc_run = false;
                    wplc_online = false;
                } else {
                    wplc_online = true;
                }
                if (wplc_filter_run_override === "0") { wplc_run = false; } else { /* we can run */ }

                /* start long polling */
                var data = {
                    action: 'wplc_call_to_server_visitor',
                    security: wplc_nonce,
                    cid:wplc_cid,
                    wplc_name: wplc_cookie_name,
                    wplc_email: wplc_cookie_email,
                    status:wplc_chat_status,
                    wplcsession:wplc_session_variable,
                    wplc_extra_data:wplc_extra_data
                };

                initial_data = data;
                // ajax long polling function
                if (wplc_filter_run_override === "0") { 
                    wplc_call_to_server_chat(data,true,true);
                } else { 
                    wplc_call_to_server_chat(data,true,false);
                }
                
                if(wplc_cid !== null   && wplc_init_chat_box_check == true && wplc_init_chat_box !== false){
                    wplc_init_chat_box(wplc_cid,wplc_chat_status);
                }
             
                
            }

        }

    });


    
    function wplc_call_to_server_chat(data,first_run,short_poll) {
        if (typeof first_run === "undefined") { first_run = false; };
        if (typeof short_poll === "undefined") { short_poll = false; };
        data.first_run = first_run;
        data.short_poll = short_poll;
        jQuery.ajax({
            url: wplc_ajaxurl,
            data:data,
            type:"POST",
            success: function(response) {
                
                if(response){

                    response = JSON.parse(response);
                    data['wplc_name'] = response['wplc_name'];
                    data['wplc_email'] = response['wplc_email'];
                    data['action_2'] = "";
                    data['cid'] = response['cid'];

                    Cookies.set('wplc_cid', response['cid'], { expires: 1, path: '/' });
                    Cookies.set('wplc_name', response['wplc_name'], { expires: 1, path: '/' });
                    Cookies.set('wplc_email', response['wplc_email'], { expires: 1, path: '/' });



                    wplc_cid = jQuery.trim(response['cid']);
                    wplc_chat_status = response['status'];
                    Cookies.set('wplc_chat_status', null, { path: '/' });
                    Cookies.set('wplc_chat_status', wplc_chat_status, { expires: 1, path: '/' });
                    // handle response
                    if(data['status'] == response['status']){
                        if(data['status'] == 5 && wplc_init_chat_box_check === true && wplc_init_chat_box !== false){ // open chat box on load
                            wplc_init_chat_box(data['cid'], data['status']);
                        } 
                        if(response['status'] == 3 && response['data'] != null){ // if active and data is returned
                            wplc_run = true;

                            jQuery("#wplc_chatbox").append(response['data'].wplcStripSlashes());
                            if(response['data']){
                                var height = jQuery('#wplc_chatbox')[0].scrollHeight;
                                jQuery('#wplc_chatbox').scrollTop(height);
                                if (typeof wplc_enable_ding !== 'undefined' && wplc_enable_ding === "1") {
                                    new Audio(wplc_plugin_url+'/wp-live-chat-support/ding.mp3').play()                               
                                }
                            } 
                        }
                    } else {
                        
                        data['status'] = wplc_chat_status;
                        Cookies.set('wplc_chat_status', wplc_chat_status, { expires: 1, path: '/' });
                        if(response['status'] == 0){ // no answer from admin
                            jQuery("#wp-live-chat-3").hide();
                            jQuery("#wp-live-chat-react").show().empty().append("<center>"+response['data']+"</center>");
                        }
                        else if(response['status'] == 8){ // chat has been ended by admin
                            wplc_run = false;

                            jQuery("#wp-live-chat-minimize").show();
                            document.getElementById('wplc_chatmsg').disabled = true;
                            jQuery("#wplc_chatbox").append("<em>"+response['data']+"</em><br />");
                            var height = jQuery('#wplc_chatbox')[0].scrollHeight;
                            jQuery('#wplc_chatbox').scrollTop(height);                        }
                        else if(parseInt(response['status']) == 11){ /* use moved on to another page (perhaps in another tab so close this instance */
                            jQuery("#wp-live-chat").css({ "display" : "none" });
                            wplc_run = false;
                        }
                        else if(parseInt(response['status']) == 3 || parseInt(response['status']) == 10){ // re-initialize chat
                            wplc_run = true;
                            jQuery("#wplc_cid").val(wplc_cid);
                            if(parseInt(response['status']) == 3) { // only if not minimized open aswell
                                open_chat(0);
                                if(jQuery('#wp-live-chat').hasClass('wplc_left') === true || jQuery('#wp-live-chat').hasClass('wplc_right') === true){
                                    jQuery('#wp-live-chat').height("400px");
                                }
                            }
                            if(parseInt(response['status']) == 10) { // only if not minimized open aswell
                                wplc_run = true;
                                open_chat(0);
                                
                            }
                            if(response['data'] != null){ // append messages to chat area
                                jQuery("#wplc_chatbox").append(response['data'].wplcStripSlashes());
                                if(response['data']){
                                    var height = jQuery('#wplc_chatbox')[0].scrollHeight;
                                    jQuery('#wplc_chatbox').scrollTop(height);
                                    
                                } 
                            }
                        }
                        

                        
                    }  
                }
            },
            error: function(jqXHR, exception) {
                    if (jqXHR.status == 404) {
                        if (window.console) { console.log('Requested page not found. [404]'); }
            			wplc_run = false;
                    } else if (jqXHR.status == 500) {
                        if (window.console) { console.log('Internal Server Error [500].'); }
			            wplc_run = false;
                    } else if (exception === 'parsererror') {
                        if (window.console) { console.log('Requested JSON parse failed.'); }
			            wplc_run = false;
                    } else if (exception === 'abort') {
                        if (window.console) { console.log('Ajax request aborted.'); }
			            wplc_run = false;
                    } else {
                        if (window.console) { console.log('Uncaught Error.\n' + jqXHR.responseText); }
			            wplc_run = false;
                    }                    
                },
                complete: function(response){
                    if (wplc_run) { 
                        setTimeout(function() { wplc_call_to_server_chat(data,false,false); }, 1500);
                        
                    }
            },
            timeout: 120000            
        });
        
    };  
    
    function wplc_init_chat_box(cid, status){
        if(wplc_chat_status == 9 && wplc_check_hide_cookie == "yes"){
            
        } else {
            if(wplc_check_hide_cookie != "yes"){
                wplc_dc = setTimeout(function (){
                    /*
                     * 1- Slide Up 
                     * 2- Slide Across (Left/Right) 
                     * 3- Slide Down 
                     * 4- Fade In 
                     */
                
                    var wplc_window_id = jQuery("#wp-live-chat");
                
                    var wplc_theme_chosen = jQuery(wplc_window_id).attr('wplc_animation');

                    switch(wplc_theme_chosen){
                        case 'none':
                            jQuery(wplc_window_id).css('display', 'block');
                            break;
                        case 'animation-1':
                            // Slide Up
                            jQuery(wplc_window_id).animate({'marginBottom' : '0px'}, 1000);
                            break;
                        case 'animation-2-bl':
                            // Slide Accross from left
                            jQuery(wplc_window_id).animate({'left' : '100px'}, 1000);
                            break;
                        case 'animation-2-br':
                            // Slide Accross from right
                            jQuery(wplc_window_id).animate({'right' : '100px'}, 1000);
                            break;
                        case 'animation-2-l':
                            // Slide Accross from left
                            jQuery(wplc_window_id).animate({"left" : '0px'}, 1000);
                            break;
                        case 'animation-2-r':
                            // Slide Accross from right
                            jQuery(wplc_window_id).animate({'right' : '0px'}, 1000);
                            break;
                        case 'animation-3':
                            // Fade In
                            jQuery(wplc_window_id).fadeIn('slow');
                        case 'animation-4':
                            jQuery(wplc_window_id).css('display', 'block');
                            break;
                        default:
                            jQuery(wplc_window_id).css('display', 'block');
                            break;
                    }
                                   
                    //jQuery("#wp-live-chat").css({ "display" : "block" });
                    if(jQuery("#wp-live-chat").attr('wplc-auto-pop-up') === "1"){
                        open_chat(0);
                    }

                    jQuery.event.trigger({type: "wplc_animation_done"});
                }, parseInt(window.wplc_delay));
            }
        }
        wplc_init_chat_box = false;
    }


    function wplc_sound(source,volume,loop)
    {
        this.source=source;
        this.volume=volume;
        this.loop=loop;
        var son;
        this.son=son;
        this.finish=false;
        this.stop=function()
        {
            document.body.removeChild(this.son);
        }
        this.start=function()
        {
            if(this.finish)return false;
            this.son=document.createElement("embed");
            this.son.setAttribute("src",this.source);
            this.son.setAttribute("hidden","true");
            this.son.setAttribute("volume",this.volume);
            this.son.setAttribute("autostart","true");
            this.son.setAttribute("loop",this.loop);
            document.body.appendChild(this.son);
        }
        this.remove=function()
        {
            document.body.removeChild(this.son);
            this.finish=true;
        }
        this.init=function(volume,loop)
        {
            this.finish=false;
            this.volume=volume;
            this.loop=loop;
        }
    }
    
    
     
    //placeholder text fix for IE
    jQuery('#wp-live-chat [placeholder]').focus(function() {
        var input = jQuery(this);
        if (input.val() == input.attr('placeholder')) {
            input.val('');
            input.removeClass('placeholder');
        }
    }).blur(function() {
        var input = jQuery(this);
        if (input.val() == '' || input.val() == input.attr('placeholder')) {
            input.addClass('placeholder');
            input.val(input.attr('placeholder'));
        }
    }).blur();
        
   
        /* minimize chat window */
        jQuery("body").on("click", "#wp-live-chat-minimize", function() {
            jQuery.event.trigger({type: "wplc_minimize_chat"});


            Cookies.set('wplc_minimize', "yes", { expires: 1, path: '/' });
            wplc_chat_status = Cookies.get('wplc_chat_status');
            if(wplc_chat_status != 5 && wplc_chat_status != 10 && wplc_chat_status != 9 && wplc_chat_status != 8){
                var data = {
                    action: 'wplc_user_minimize_chat',
                    security: wplc_nonce,
                    cid: wplc_cid
                };
                
                jQuery.post(wplc_ajaxurl, data, function(response) {

                });
            }
            
        });
         /* close chat window */
        jQuery("body").on("click", "#wp-live-chat-close", function() {
            
            jQuery("#wp-live-chat").hide();
            jQuery("#wp-live-chat-1").hide();
            jQuery("#wp-live-chat-2").hide();
            jQuery("#wp-live-chat-3").hide();
            jQuery("#wp-live-chat-4").hide();
            jQuery("#wp-live-chat-react").hide();
            jQuery("#wp-live-chat-minimize").hide();
            if (typeof wplc_hide_chat !== "undefined" && wplc_hide_chat !== "" && wplc_hide_chat !== null) { Cookies.set('wplc_hide', wplc_hide_chat , { expires: 1, path: '/' });  } else {
                var wplc_expire_date = new Date();
                var minutes = 2;
                wplc_expire_date.setTime(wplc_expire_date.getTime() + (minutes * 60 * 1000));
                Cookies.set('wplc_hide', "yes" , { expires: wplc_expire_date , path: '/' });
            }
            var data = {
                action: 'wplc_user_close_chat',
                security: wplc_nonce,
                cid: wplc_cid,
                status: wplc_chat_status
            };
            jQuery.post(wplc_ajaxurl, data, function(response) {


            });            
        });  
        //open chat window function
         
        function open_chat(force){
            jQuery.event.trigger({type: "wplc_open_chat_1"});


            
            wplc_chat_status = Cookies.get('wplc_chat_status');
            if (parseInt(wplc_chat_status) == 3) {

                jQuery.event.trigger({type: "wplc_open_chat_2", wplc_online: wplc_online});
                
                var data = {
                    action: 'wplc_user_maximize_chat',
                    security: wplc_nonce,
                    cid: wplc_cid
                };
                jQuery.post(wplc_ajaxurl, data, function(response) {
                    
                        //log("user maximized chat success");
                });
            }
            else if (parseInt(wplc_chat_status) == 10) {
                    jQuery("#wp-live-chat-minimize").trigger("click");
                
            }
            
            else if (wplc_chat_status == 5 || wplc_chat_status == 9 || wplc_chat_status == 8){
                if(jQuery("#wp-live-chat-2").is(":visible") === false && jQuery("#wp-live-chat-4").is(":visible") === false){
                    jQuery("#wp-live-chat-2").show();         
                    var wplc_visitor_name = Cookies.get('wplc_name');            
                    if(Cookies.get('wplc_email') !== "no email set" && wplc_visitor_name.indexOf("user") >= 0){
                        jQuery("#wplc_name").val(Cookies.get('wplc_name'));
                        jQuery("#wplc_email").val(Cookies.get('wplc_email'));
                    }
                }
            }
            else if (wplc_chat_status == 2){
                jQuery("#wp-live-chat-3").show();
            } 
            else if(wplc_chat_status == 1){
                jQuery("#wp-live-chat-4").show();
                jQuery.event.trigger({type: "wplc_animation_done"});
                jQuery("#wplc_chatbox").append("The chat has been ended by the operator.<br />");
                var height = jQuery('#wplc_chatbox')[0].scrollHeight;
                jQuery('#wplc_chatbox').scrollTop(height);
                jQuery("#wp-live-chat-minimize").hide();
                document.getElementById('wplc_chatmsg').disabled = true;
            }   
               
            
        }

        //allows for a class to open chat window now
        jQuery("body").on("click", ".wp-live-chat-now", function() {
            open_chat(0);
        });
        
        
       

        jQuery("body").on("click", "#wplc_start_chat_btn", function() {
            var wplc_name = jQuery("#wplc_name").val();
            var wplc_email = jQuery("#wplc_email").val(); 

            if (wplc_name.length <= 0) { alert("Please enter your name"); return false; }
            if (wplc_email.length <= 0) { alert("Please enter your email address"); return false; }

            if(jQuery("#wplc_email").attr('wplc_hide') !== "1"){
                var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
                if (!testEmail.test(wplc_email)){
                    alert("Please Enter a Valid Email Address"); return false;
                }
            }

            /* start the long polling */
            wplc_run = true;
            
            if (wplc_filter_run_override === "1") { } else {
                initial_data.status = 2;

                /* force the loop to start only now, as we are not using the initiate extension */
                wplc_call_to_server_chat(initial_data,false,false);
            }

            jQuery.event.trigger({type: "wplc_start_chat"});
           
            
            var date = new Date();
            date.setTime(date.getTime() + (2 * 60 * 1000));
            
            wplc_cid = Cookies.get('wplc_cid');
            
            if (typeof wplc_cid !== "undefined" && wplc_cid !== null) { // we've already recorded a cookie for this person
                var data = {
                        action: 'wplc_start_chat',
                        security: wplc_nonce,
                        name: wplc_name,
                        email: wplc_email,
                        cid: wplc_cid,
                        wplcsession: wplc_session_variable,
                        wplc_extra_data:wplc_extra_data                        
                };
            } else { // no cookie recorded yet for this visitor
                var data = {
                        action: 'wplc_start_chat',
                        security: wplc_nonce,
                        name: wplc_name,
                        email: wplc_email,
                        wplcsession: wplc_session_variable,
                        wplc_extra_data:wplc_extra_data                        
                };
            }
            //changed ajax url so wp_mail function will work and not stop plugin from alerting admin there is a pending chat
            jQuery.post(wplc_ajaxurl, data, function(response) {
                    Cookies.set('wplc_chat_status', 2, { expires: date, path: '/' });
                    Cookies.set('wplc_name', wplc_name, { path: '/' } );
                    Cookies.set('wplc_email', wplc_email, { path: '/' } );
                    wplc_cid = jQuery.trim(response);

            });
        });


        jQuery("body").on("click", "#wplc_na_msg_btn", function() {
            var wplc_name = jQuery("#wplc_name").val();
            var wplc_email = jQuery("#wplc_email").val();
            var wplc_msg = jQuery("#wplc_message").val();
            var wplc_domain = jQuery("#wplc_domain_offline").val();
            var ip_address = jQuery("#wplc_ip_address").val();
            
            if (wplc_name.length <= 0) { alert("Please enter your name"); return false; }
            if (wplc_email.length <= 0) { alert("Please enter your email address"); return false; }
            var testEmail = /^[A-Z0-9._%+-]+@([A-Z0-9-]+\.)+[A-Z]{2,4}$/i;
            if (!testEmail.test(wplc_email)){
                alert("Please Enter a Valid Email Address"); return false;
            }
            if (wplc_msg.length <= 0) { alert("Please enter a message"); return false; }
            jQuery("#wp-live-chat-2-info").hide();
            jQuery("#wplc_message_div").html(wplc_offline_msg);

            wplc_cid = Cookies.get('wplc_cid');
                            
            var data = {
                    action: 'wplc_user_send_offline_message',
                    security: wplc_nonce,
                    cid: wplc_cid,
                    name: wplc_name,
                    email: wplc_email,
                    msg: wplc_msg,
                    ip: ip_address,
                    domain: wplc_domain,
                    wplc_extra_data:wplc_extra_data
            };
            
            jQuery.post(wplc_ajaxurl_site, data, function(response) {
                jQuery("#wplc_message_div").html(wplc_offline_msg3);
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


        jQuery("body").on("keyup","#wplc_chatmsg", function(event){
            if(event.keyCode === 13){
                jQuery("#wplc_send_msg").trigger("click");
            }
        });
        jQuery("body").on("click", "#wplc_send_msg", function() {
            var wplc_cid = jQuery("#wplc_cid").val();
            var wplc_chat = wplc_strip(document.getElementById('wplc_chatmsg').value);
            
            var wplc_name = jQuery("#wplc_name").val();
            if (typeof wplc_name == "undefined" || wplc_name == null || wplc_name == "") {
                wplc_name = Cookies.get('wplc_name');
            }

            var wplc_email = jQuery("#wplc_email").val();
            if (typeof wplc_email == "undefined" || wplc_email == null || wplc_email == "") {
                wplc_email = Cookies.get('wplc_email');
            }


            jQuery("#wplc_chatmsg").val('');
                        
            if(wplc_display_name == 'display'){
                if (wplc_gravatar_image.length > 1) {
                    jQuery("#wplc_chatbox").append("<span class='wplc-user-message wplc-color-bg-1 wplc-color-2 wplc-color-border-1'>"+wplc_gravatar_image+" <strong>"+wplc_name+"</strong>: "+wplc_chat+"</span><br /><div class='wplc-clear-float-message'></div>");
                } else {
                    jQuery("#wplc_chatbox").append("<span class='wplc-user-message wplc-color-bg-1 wplc-color-2 wplc-color-border-1'><img src='//www.gravatar.com/avatar/"+md5(wplc_email)+"?s=30' class='wplc-user-message-avatar' \/> <strong>"+wplc_name+"</strong>: "+wplc_chat+"</span><br /><div class='wplc-clear-float-message'></div>");
                }
            } else {
                jQuery("#wplc_chatbox").append("<span class='wplc-user-message wplc-color-bg-1 wplc-color-2 wplc-color-border-1'>"+wplc_chat+"</span><div class='wplc-clear-float-message'></div>");
            }
            
            var height = jQuery('#wplc_chatbox')[0].scrollHeight;
            jQuery('#wplc_chatbox').scrollTop(height);

            var data = {
                    action: 'wplc_user_send_msg',
                    security: wplc_nonce,
                    cid: wplc_cid,
                    msg: wplc_chat,
                    wplc_extra_data:wplc_extra_data
            };
            jQuery.post(wplc_ajaxurl, data, function(response) {

            });

        });   

        jQuery(document).on("wplc_open_chat", function (event) {

            /* what is the current status? */
            wplc_chat_status = Cookies.get('wplc_chat_status');
            var wplc_tmp_checker = wplc_pre_open_check_status(status, function() {
                open_chat();
            });


        }); 

        function wplc_pre_open_check_status(status, callback) {
            if (wplc_chat_status.length > 0) {
                if (parseInt(wplc_chat_status) === 10 || parseInt(wplc_chat_status) === 7) {
                    /* it was minimized or timedout, now we need to open it - set status to 3 (back to open chat) */
                    Cookies.set('wplc_chat_status', 3, { expires: 1, path: '/' });

                }
                if (parseInt(wplc_chat_status) === 0) {
                    /* no answer from agent previously */
                    Cookies.set('wplc_chat_status', 5, { expires: 1, path: '/' });                    
                }
                if (parseInt(wplc_chat_status) === 8) {
                    /* no answer from agent previously */
                    Cookies.set('wplc_chat_status', 5, { expires: 1, path: '/' });                    
                }
               
            }
            callback();
        }
        String.prototype.wplcStripSlashes = function(){
            return this.replace(/\\(.)/mg, "$1");
        }
        
    });
