<?php
$wplc_basic_plugin_url = get_option('siteurl')."/wp-content/plugins/wp-live-chat-support/";

function wplc_log_user_on_page($name,$email,$session) {
    global $wpdb;
    global $wplc_tblname_chats;
    
    $wplc_settings = get_option('WPLC_SETTINGS');
    


    if(isset($wplc_settings['wplc_record_ip_address']) && $wplc_settings['wplc_record_ip_address'] == 1){

        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }

        $user_data = array(
            'ip' => $ip_address,
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        );
    } else {
        $user_data = array(
            'ip' => "",
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        );
    }


    /* user types 
     * 1 = new
     * 2 = returning
     * 3 = timed out
     */
    
     $other = array(
         "user_type" => 1
     );
    
    $wpdb->insert( 
	$wplc_tblname_chats, 
	array( 
            'status' => '5', 
            'timestamp' => current_time('mysql'),
            'name' => $name,
            'email' => $email,
            'session' => $session,
            'ip' => maybe_serialize($user_data),
            'url' => $_SERVER['HTTP_REFERER'],
            'last_active_timestamp' => current_time('mysql'),
            'other' => maybe_serialize($other),
	), 
	array( 
            '%s', 
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s',
            '%s'
	) 
    );
    
    $lastid = $wpdb->insert_id;


    return $lastid;

}
function wplc_update_user_on_page($cid, $status = 5,$session) {

    global $wpdb;
    global $wplc_tblname_chats;
    $wplc_settings = get_option('WPLC_SETTINGS');
     
    if(isset($wplc_settings['wplc_record_ip_address']) && $wplc_settings['wplc_record_ip_address'] == 1){
        
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        $user_data = array(
            'ip' => $ip_address,
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        );
    } else {
        $user_data = array(
            'ip' => "",
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        );
    }
    
//    $query =
//        "
//    UPDATE $wplc_tblname_chats
//        SET
//            `url` = '".$_SERVER['HTTP_REFERER']."',
//            `last_active_timestamp` = '".date("Y-m-d H:i:s")."',
//            `ip` = '".maybe_serialize($user_data)."',
//            `status` = '$status',
//            `session` = '$session'
//
//        WHERE `id` = '$cid'
//        LIMIT 1
//    ";
//    $results = $wpdb->query($query);
    
    $query = $wpdb->update( 
        $wplc_tblname_chats, 
        array( 
            'url' => $_SERVER['HTTP_REFERER'],
            'last_active_timestamp' => current_time('mysql'),
            'ip' => maybe_serialize($user_data),
            'status' => $status,
            'session' => $session,
        ), 
        array('id' => $cid), 
        array( 
            '%s',
            '%s',
            '%s',
            '%d',
            '%s'
        ), 
        array('%d') 
    );


    return $query;


}



function wplc_record_chat_msg($from,$cid,$msg) {
    global $wpdb;
    global $wplc_tblname_msgs;

    if ($from == "2") {
        $wplc_current_user = get_current_user_id();

        if( get_user_meta( $wplc_current_user, 'wplc_ma_agent', true ) ){
        /*
          -- modified in in 6.0.04 --

          if(current_user_can('wplc_ma_agent') || current_user_can('manage_options')){   
         */  } else { return "security issue"; }
    }

    if ($from == "1") {
        $fromname = wplc_return_chat_name(sanitize_text_field($cid));
        //$fromemail = wplc_return_chat_email($cid);
        $orig = '2';
    }
    else {
        $fromname = apply_filters("wplc_filter_admin_name","Admin");
        //$fromemail = "SET email";
        $orig = '1';
    }
    
    $msg = apply_filters("wplc_filter_message_control",$msg);

    $wpdb->insert( 
	$wplc_tblname_msgs, 
	array( 
            'chat_sess_id' => $cid, 
            'timestamp' => current_time('mysql'),
            'msgfrom' => $fromname,
            'msg' => $msg,
            'status' => 0,
            'originates' => $orig
	), 
	array( 
            '%s', 
            '%s',
            '%s',
            '%s',
            '%d',
            '%s'
	) 
    );
    
    wplc_update_active_timestamp(sanitize_text_field($cid));
    wplc_change_chat_status(sanitize_text_field($cid),3);
    
    return true;

}

function wplc_update_active_timestamp($cid) {
    global $wpdb;
    global $wplc_tblname_chats;
//    $results = $wpdb->get_results(
//        "
//        UPDATE $wplc_tblname_chats
//        SET `last_active_timestamp` = '".date("Y-m-d H:i:s")."'
//        WHERE `id` = '$cid'
//        LIMIT 1
//        "
//    );
    $wpdb->update( 
        $wplc_tblname_chats, 
        array( 
            'last_active_timestamp' => current_time('mysql')
        ), 
        array('id' => $cid), 
        array('%s'), 
        array('%d') 
    );
    
    wplc_change_chat_status(sanitize_text_field($cid),3);
    return true;

}

function wplc_return_chat_name($cid) {
    global $wpdb;
    global $wplc_tblname_chats;
    $results = $wpdb->get_results(
        "
        SELECT *
        FROM $wplc_tblname_chats
        WHERE `id` = '$cid'
        "
    );
    foreach ($results as $result) {
        return $result->name;
    }

}
function wplc_return_chat_email($cid) {
    global $wpdb;
    global $wplc_tblname_chats;
    $results = $wpdb->get_results(
        "
        SELECT *
        FROM $wplc_tblname_chats
        WHERE `id` = '$cid'
        "
    );
    foreach ($results as $result) {
        return $result->email;
    }

}
function wplc_list_chats() {

    global $wpdb;
    global $wplc_tblname_chats;
    $status = 3;
    $wplc_c = 0;    
    $results = $wpdb->get_results(
        "
	SELECT *
	FROM $wplc_tblname_chats
        WHERE `status` = 3 OR `status` = 2 OR `status` = 10
        ORDER BY `timestamp` ASC

	"
    );
    
        $table = "<div class='wplc_chats_container'>";    
            
    if (!$results) {
        $table.= "<p>".__("No chat sessions available at the moment","wplivechat")."</p>";
    } else {
        $table .= "<h2>".__('Active Chats', 'wplivechat')."</h2>";
        
        foreach ($results as $result) {
            unset($trstyle);
            unset($actions);
            $wplc_c++;

            
            global $wplc_basic_plugin_url;
            $user_data = maybe_unserialize($result->ip);
            $user_ip = $user_data['ip'];
            $browser = wplc_return_browser_string($user_data['user_agent']);
            $browser_image = wplc_return_browser_image($browser,"16");
            
            if($user_ip == ""){
                $user_ip = __('IP Address not recorded', 'wplivechat');
            } else {
                $user_ip = "<a href='http://www.ip-adress.com/ip_tracer/" . $user_ip . "' title='".__('Whois for' ,'wplivechat')." ".$user_ip."' target='_BLANK'>".$user_ip."</a>";
            } 
            
            if ($result->status == 2) {
                $url = admin_url( 'admin.php?page=wplivechat-menu&action=ac&cid='.$result->id);
                $actions = "<a href=\"".$url."\" class=\"wplc_open_chat button button-primary\" window-title=\"WP_Live_Chat_".$result->id."\">".__("Accept Chat","wplivechat")."</a>";
                $trstyle = "style='background-color:#FFFBE4; height:30px;'";
                $icon = "<i class=\"fa fa-phone wplc_pending\" title='".__('Incoming Chat', 'wplivechat')."' alt='".__('Incoming Chat', 'wplivechat')."'></i><div class='wplc_icon_message'>".__('You have an incoming chat.', 'wplivechat')."</div>";
            }
            if ($result->status == 3) {
                $url = admin_url( 'admin.php?page=wplivechat-menu&action=ac&cid='.$result->id);
                $actions = "<a href=\"".$url."\" class=\"wplc_open_chat button button-primary\" window-title=\"WP_Live_Chat_".$result->id."\">".__("Open Chat Window","wplivechat")."</a>";
                $trstyle = "style='background-color:#F7FCFE; height:30px;'";
                $icon = "<i class=\"fa fa-check-circle wplc_active\" title='".__('Chat Active', 'wplivechat')."' alt='".__('Chat Active', 'wplivechat')."'></i><div class='wplc_icon_message'>".__('This chat is active', 'wplivechat')."</div>";                        
            }
            
            
            /* if ($wplc_c>1) { $actions = wplc_get_msg(); } */
            
           $trstyle = "";
            
            $table .= "
                <div class='wplc_single_chat' id='record_".$result->id."' $trstyle> 
                    <div class='wplc_chat_section section_1'>
                        <div class='wplc_user_image' id='chat_image_".$result->id."'>
                            <img src=\"//www.gravatar.com/avatar/".md5($result->email)."?s=60&d=mm\" />
                        </div>
                        <div class='wplc_user_meta_data'>
                            <div class='wplc_user_name' id='chat_name_".$result->id."'>
                                <h3>".$result->name.$icon."</h3>
                                <a href='mailto:".$result->email."' target='_BLANK'>".$result->email."</a>
                            </div>                                
                        </div>    
                    </div>
                    <div class='wplc_chat_section section_2'>
                        <div class='admin_visitor_advanced_info'>
                            <strong>" . __("Site Info", "wplivechat") . "</strong>
                            <hr />
                            <span class='part1'>" . __("Chat initiated on:", "wplivechat") . "</span> <span class='part2'> <a href='".$result->url."' target='_BLANK'>" . $result->url . "</a></span>
                        </div>

                        <div class='admin_visitor_advanced_info'>
                            <strong>" . __("Advanced Info", "wplivechat") . "</strong>
                            <hr />
                            <span class='part1'>" . __("Browser:", "wplivechat") . "</span><span class='part2'> $browser <img src='" . $wplc_basic_plugin_url . "/images/$browser_image' alt='$browser' title='$browser' /><br />
                            <span class='part1'>" . __("IP Address:", "wplivechat") . "</span><span class='part2'> ".$user_ip."
                        </div>
                    </div>
                    <div class='wplc_chat_section section_3'>
                        <div class='wplc_agent_actions'>
                            $actions
                        </div>
                    </div>
                </div>
                    ";
        }
    }
    $table .= "</div>";

    return $table;
}

function wplc_time_ago($time_ago)
{
    $time_ago = strtotime($time_ago);
    $cur_time   = current_time('timestamp');
    $time_elapsed   = $cur_time - $time_ago;
    $seconds    = $time_elapsed ;
    $minutes    = round($time_elapsed / 60 );
    $hours      = round($time_elapsed / 3600);
    $days       = round($time_elapsed / 86400 );
    $weeks      = round($time_elapsed / 604800);
    $months     = round($time_elapsed / 2600640 );
    $years      = round($time_elapsed / 31207680 );
    // Seconds
    if($seconds <= 60){
        return "0 min";
    }
    //Minutes
    else if($minutes <=60){
        if($minutes==1){
            return "1 min";
        }
        else{
            return "$minutes min";
        }
    }
    //Hours
    else if($hours <=24){
        if($hours==1){
            return "1 hr";
        }else{
            return "$hours hrs";
        }
    }
    //Days
    else if($days <= 7){
        if($days==1){
            return "1 day";
        }else{
            return "$days days";
        }
    }
    //Weeks
    else if($weeks <= 4.3){
        if($weeks==1){
            return "1 week";
        }else{
            return "$weeks weeks";
        }
    }
    //Months
    else if($months <=12){
        if($months==1){
            return "1 month";
        }else{
            return "$months months";
        }
    }
    //Years
    else{
        if($years==1){
            return "1 year";
        }else{
            return "$years years";
        }
    }
}

add_filter("wplc_filter_list_chats_actions","wplc_filter_control_list_chats_actions",15,3); 
/**
 * Only allow agents access
 * @return void
 * @since  6.0.00
 * @version  6.0.04 Updated to ensure those with the correct access can access this function
 * @author  Nick Duncan <nick@codecabin.co.za>
 */
function wplc_filter_control_list_chats_actions($actions,$result,$post_data) {
    $aid = apply_filters("wplc_filter_aid_in_action","");
    
    $wplc_current_user = get_current_user_id();

    if( get_user_meta( $wplc_current_user, 'wplc_ma_agent', true ) ){

        if (intval($result->status) == 2) {
            $url_params = "&action=ac&cid=".$result->id.$aid;
            $url = admin_url( 'admin.php?page=wplivechat-menu'.$url_params);
            $actions = "<a href=\"".$url."\" class=\"wplc_open_chat button button-primary\" window-title=\"WP_Live_Chat_".$result->id."\">".__("Accept Chat","wplivechat")."</a>";
        }
        else if (intval($result->status) == 3) {
            $url_params = "&action=ac&cid=".$result->id.$aid;
            $url = admin_url( 'admin.php?page=wplivechat-menu'.$url_params);
            $actions = "<a href=\"".$url."\" class=\"wplc_open_chat button button-primary\" window-title=\"WP_Live_Chat_".$result->id."\">".__("Open Chat","wplivechat")."</a>";
        }
    } else {
        $actions = "<a href='#'>".__( 'Only chat agents can accept chats', 'wplivechat' )."</a>";
    }
    return $actions;
}

function wplc_list_chats_new($post_data) {

    global $wpdb;
    global $wplc_tblname_chats;
    $status = 3;
    $wplc_c = 0;    
    $results = $wpdb->get_results("SELECT * FROM $wplc_tblname_chats WHERE `status` = 3 OR `status` = 2 OR `status` = 10 OR `status` = 5 or `status` = 8 or `status` = 9 ORDER BY `timestamp` ASC");
    $data_array = array();
    $id_list = array();
    
            
    if (!$results) {
        $data_array = false;
    } else {
        
        
        foreach ($results as $result) {
            unset($trstyle);
            unset($actions);
            
            
            

            global $wplc_basic_plugin_url;
            $user_data = maybe_unserialize($result->ip);
            $user_ip = $user_data['ip'];
            $browser = wplc_return_browser_string($user_data['user_agent']);
            $browser_image = wplc_return_browser_image($browser,"16");
            
            if($user_ip == ""){
                $user_ip = __('IP Address not recorded', 'wplivechat');
            } else {
                $user_ip = "<a href='http://www.ip-adress.com/ip_tracer/" . $user_ip . "' title='".__('Whois for' ,'wplivechat')." ".$user_ip."' target='_BLANK'>".$user_ip."</a>";
            } 


            $actions = apply_filters("wplc_filter_list_chats_actions","",$result,$post_data);
            
            
            
            
            
            
           $trstyle = "";
            
           $id_list[intval($result->id)] = true;
           
           $data_array[$result->id]['name'] = $result->name;
           $data_array[$result->id]['email'] = $result->email;
           
           $data_array[$result->id]['status'] = $result->status;
           $data_array[$result->id]['action'] = $actions;
           $data_array[$result->id]['timestamp'] = wplc_time_ago($result->timestamp);

           if ((current_time('timestamp') - strtotime($result->timestamp)) < 3600) {
               $data_array[$result->id]['type'] = __("New","wplivechat");
           } else {
               $data_array[$result->id]['type'] = __("Returning","wplivechat");
           }
           
           $data_array[$result->id]['image'] = "<img src=\"//www.gravatar.com/avatar/".md5($result->email)."?s=30&d=mm\"  class='wplc-user-message-avatar' />";
           $data_array[$result->id]['data']['browsing'] = $result->url;
           $path = parse_url($result->url, PHP_URL_PATH);
           
           if (strlen($path) > 20) {
                $data_array[$result->id]['data']['browsing_nice_url'] = substr($path,0,20).'...';
           } else { 
               $data_array[$result->id]['data']['browsing_nice_url'] = $path;
           }
           
           $data_array[$result->id]['data']['browser'] = "<img src='" . $wplc_basic_plugin_url . "/images/$browser_image' alt='$browser' title='$browser' /> ";
           $data_array[$result->id]['data']['ip'] = $user_ip;
        }
        $data_array['ids'] = $id_list;
    }
    
    return json_encode($data_array);
}



function wplc_return_user_chat_messages($cid) {

    global $wpdb;
    global $wplc_tblname_msgs;
    
    $wplc_settings = get_option("WPLC_SETTINGS");

    if(isset($wplc_settings['wplc_display_name']) && $wplc_settings['wplc_display_name'] == 1){ $display_name = 1; } else { $display_name = 0; }
            
    
    $results = $wpdb->get_results(
        "
            SELECT *
            FROM $wplc_tblname_msgs
            WHERE `chat_sess_id` = '$cid' AND `status` = '0' AND `originates` = '1'
            ORDER BY `timestamp` ASC

        "
    );
    $cdata = wplc_get_chat_data($cid);
    $msg_hist = "";
    foreach ($results as $result) {
        $id = $result->id;
        $from = $result->msgfrom;


        $msg = $result->msg;
        //$timestamp = strtotime($result->timestamp);
        //$timeshow = date("H:i",$timestamp);
        if($result->originates == 1){
            $class = "wplc-admin-message wplc-color-bg-4 wplc-color-2 wplc-color-border-4";
            if(function_exists("wplc_pro_get_admin_picture")){
                $src = wplc_pro_get_admin_picture();
                if($src){
                    $image = "<img src=".$src." width='20px' id='wp-live-chat-2-img'/>";
                } else {
                    $image = "";
                }
            } else {
                $other = maybe_unserialize($cdata->other);
                if (isset($other['aid'])) {
                    $user_info = get_userdata(intval($other['aid']));
                    /* get agent id */
                    $image = "<img src='//www.gravatar.com/avatar/".md5($user_info->user_email)."?s=30'  class='wplc-admin-message-avatar' />";    
                } else {

                    /* get default setting in the notifications tab */
                    $image = "";
                    if(1 == 1) {

                    } else {
                        /* there's nothing Jim.. */
                        $image = "";
                    }
                }
                
            }
        } else {
            $class = "wplc-user-message wplc-color-bg-1 wplc-color-2 wplc-color-border-1";
            
            if(isset($_COOKIE['wplc_email']) && $_COOKIE['wplc_email'] != ""){ $wplc_user_gravatar = md5(strtolower(trim(sanitize_text_field($_COOKIE['wplc_email'])))); } else { $wplc_user_gravatar = ""; }
        
            if($wplc_user_gravatar != ""){
                $image = "<img src='//www.gravatar.com/avatar/$wplc_user_gravatar?s=30'  class='wplc-user-message-avatar' />";
            } else {
                $image = "";
            }
        }
        
        if(function_exists('wplc_decrypt_msg')){
            $msg = wplc_decrypt_msg($msg);
        }
        $msg = apply_filters("wplc_filter_message_control_out",$msg);

            
        if($display_name){
            $msg_hist .= "<span class='wplc-admin-message wplc-color-bg-4 wplc-color-2 wplc-color-border-4'>$image <strong>$from</strong>: $msg</span><br /><div class='wplc-clear-float-message'></div>";
        } else {            
            $msg_hist .= "<span class='wplc-admin-message wplc-color-bg-4 wplc-color-2 wplc-color-border-4'>$msg</span><div class='wplc-clear-float-message'></div>";
        }
        
        
        

    }

    return $msg_hist;


}


function wplc_change_chat_status($id,$status,$aid = 0) {
    global $wpdb;
    global $wplc_tblname_chats;




    if ($aid > 0) {
        /* only run when accepting a chat */
        $results = $wpdb->get_results("SELECT * FROM ".$wplc_tblname_chats." WHERE `id` = '".$id."' LIMIT 1");
        foreach ($results as $result) {
            $other = maybe_unserialize($result->other);
            if (isset($other['aid']) && $other['aid'] > 0) {
                /* we have recorded this already */

            } else {
                /* first time answering the chat! */


                /* send welcome note */
                $wplc_settings = get_option("WPLC_SETTINGS");
                $wplc_welcome = __('Welcome. How may I help you?', 'wplivechat');
                if(isset($wplc_settings['wplc_using_localization_plugin']) && $wplc_settings['wplc_using_localization_plugin'] == 1){ $wplc_using_locale = true; } else { $wplc_using_locale = false; }
                if (!isset($wplc_settings['wplc_user_welcome_chat']) || $wplc_settings['wplc_user_welcome_chat'] == "") { $wplc_settings['wplc_user_welcome_chat'] = $wplc_welcome; }
                $text2 = ($wplc_using_locale ? $wplc_welcome : stripslashes($wplc_settings['wplc_user_welcome_chat']));  

                $chat_id = sanitize_text_field($id);
                $chat_msg = sanitize_text_field($text2);
                $wplc_rec_msg = wplc_record_chat_msg("2",$chat_id,$chat_msg);
            }

            $other['aid'] = $aid;
        }
    }

        


    if ($aid > 0) { 
        $wpdb->update( 
            $wplc_tblname_chats, 
            array( 
                'status' => $status,
                'other' => maybe_serialize($other)
            ), 
            array('id' => $id), 
            array(
                '%d',
                '%s'
                ), 
            array('%d') 
        );        
    } else {
        $wpdb->update( 
            $wplc_tblname_chats, 
            array( 
                'status' => $status
            ), 
            array('id' => $id), 
            array('%d'), 
            array('%d') 
        );
    }
    return true;

}

//come back here
function wplc_return_chat_messages($cid,$transcript = false,$html = true) {
    global $wpdb;
    global $wplc_tblname_msgs;
    
    $wplc_settings = get_option("WPLC_SETTINGS");

    if(isset($wplc_settings['wplc_display_name']) && $wplc_settings['wplc_display_name'] == 1){ $display_name = 1; } else { $display_name = 0; }
           
    $results = wplc_get_chat_messages($cid);
    if (!$results) { return; }
   
    $cdata = wplc_get_chat_data($cid);
    $msg_hist = "";
    $previous_time = "";
    $previous_timestamp = 0;
    foreach ($results as $result) {
        $from = $result->msgfrom;
        $msg = stripslashes($result->msg);
        $timestamp = strtotime($result->timestamp);

        $time_diff = $timestamp - $previous_timestamp;
        if ($time_diff > 60) { $show_time = true; } else { $show_time = false; }
//        $date = new DateTime($timestamp);
        $timeshow = date('l, F d Y h:i A',$timestamp);

        if (!$transcript) { if ($previous_time == $timeshow || !$show_time) { $timeshow = ""; } }
        $previous_time = $timeshow;
        $previous_timestamp = $timestamp;
        
        
        $image = "";        
        if($result->originates == 1){
            
            

            $class = "wplc-admin-message wplc-color-bg-4 wplc-color-2 wplc-color-border-4";
            if(function_exists("wplc_pro_get_admin_picture")){
                $src = wplc_pro_get_admin_picture();
                if($src){
                    $image = "<img src=".$src." width='20px' id='wp-live-chat-2-img'/>";
                }
            } else {
                $other = maybe_unserialize($cdata->other);
                if (isset($other['aid'])) {
                    $user_info = get_userdata(intval($other['aid']));
                    /* get agent id */
                    $image = "<img src='//www.gravatar.com/avatar/".md5($user_info->user_email)."?s=30'  class='wplc-admin-message-avatar' />";    
                } else {

                    /* get default setting in the notifications tab */
                    $image = "";
                    if(1 == 1) {

                    } else {
                        /* there's nothing Jim.. */
                        $image = "";
                    }
                }
                
            }
        } else {
            $class = "wplc-user-message wplc-color-bg-1 wplc-color-2 wplc-color-border-1";
            
            if(isset($_COOKIE['wplc_email']) && $_COOKIE['wplc_email'] != ""){ $wplc_user_gravatar = md5(strtolower(trim(sanitize_text_field($_COOKIE['wplc_email'])))); } else { $wplc_user_gravatar = ""; }
        
            if($wplc_user_gravatar != ""){
                $image = "<img src='//www.gravatar.com/avatar/$wplc_user_gravatar?s=30'  class='wplc-user-message-avatar' />";
            } else {
                $image = "";
            }
        }
        
        if(function_exists('wplc_decrypt_msg')){
            $msg = wplc_decrypt_msg($msg);
        }
        
        $msg = apply_filters("wplc_filter_message_control_out",$msg);

        if($display_name){
            if ($html) {
                $msg_hist .= "<span class='chat_time wplc-color-4'>$timeshow</span> <span class='$class'>$image <strong>$from</strong>: $msg</span><br /><div class='wplc-clear-float-message'></div>";
            } else {
                $msg_hist .= "($timeshow) $from: $msg\r\n";
            }
        } else {
            if ($html) {
                $msg_hist .= "<span class='chat_time wplc-color-4'>$timeshow</span> <span class='$class'>$msg</span><br /><div class='wplc-clear-float-message'></div>";    
            } else {
                $msg_hist .= "($timeshow) $from: $msg\r\n";
            }
            
        }

    }
    return $msg_hist;


}


function wplc_mark_as_read_user_chat_messages($cid) {
    global $wpdb;
    global $wplc_tblname_msgs;
    $results = $wpdb->get_results(
        "
            SELECT *
            FROM $wplc_tblname_msgs
            WHERE `chat_sess_id` = '$cid' AND `status` = '0' AND `originates` = '1'
            ORDER BY `timestamp` DESC

        "
    );


    foreach ($results as $result) {
        $id = $result->id;
//        $check = $wpdb->query(
//            "
//            UPDATE $wplc_tblname_msgs
//            SET `status` = 1
//            WHERE `id` = '$id'
//            LIMIT 1
//
//	"
//        );
        
        $wpdb->update( 
            $wplc_tblname_msgs, 
            array( 
                'status' => 1
            ), 
            array('id' => $id), 
            array('%d'), 
            array('%d') 
        );
        
        
    }
    return "ok";


}
//here
function wplc_return_admin_chat_messages($cid) {
    $wplc_current_user = get_current_user_id();
    if( get_user_meta( $wplc_current_user, 'wplc_ma_agent', true ) ){
    /*
      -- modified in in 6.0.04 --

      if(current_user_can('wplc_ma_agent') || current_user_can('manage_options')){   
     */
        $wplc_settings = get_option("WPLC_SETTINGS");


        if(isset($wplc_settings['wplc_display_name']) && $wplc_settings['wplc_display_name'] == 1){ $display_name = 1; } else { $display_name = 0; }
                
        global $wpdb;
        global $wplc_tblname_msgs;
            
        $results = $wpdb->get_results(
            "
                SELECT *
                FROM $wplc_tblname_msgs
                WHERE `chat_sess_id` = '$cid' AND `status` = '0' AND `originates` = '2'
                ORDER BY `timestamp` ASC

            "
        );

        $msg_hist = "";
        foreach ($results as $result) {

            $id = $result->id;
            $from = $result->msgfrom;
            wplc_mark_as_read_admin_chat_messages($id);    
            $msg = $result->msg;
            //$timestamp = strtotime($result->timestamp);
            //$timeshow = date("H:i",$timestamp);
            $image = "";        
            if($result->originates == 1){
                $class = "wplc-admin-message  wplc-color-bg-4 wplc-color-2 wplc-color-border-4";
                if(function_exists("wplc_pro_get_admin_picture")){
                    $src = wplc_pro_get_admin_picture();
                    if($src){
                        $image = "<img src=".$src." width='20px' id='wp-live-chat-2-img'/>";
                    }
                } else {
                    /* HERE */
                    $image = "<img src='//www.gravatar.com/avatar/$wplc_user_gravatar?s=20' class='wplc-admin-message-avatar' />";
                }
            } else {
                $class = "wplc-user-message wplc-color-bg-1 wplc-color-2 wplc-color-border-1";

                if(isset($_COOKIE['wplc_email']) && $_COOKIE['wplc_email'] != ""){ $wplc_user_gravatar = md5(strtolower(trim($_COOKIE['wplc_email']))); } else { $wplc_user_gravatar = ""; }

                if($wplc_user_gravatar != ""){
                    $image = "<img src='//www.gravatar.com/avatar/$wplc_user_gravatar?s=20' class='wplc-user-message-avatar' />";
                } else {
                    $image = "";
                }
            }

            if(function_exists('wplc_decrypt_msg')){
                $msg = wplc_decrypt_msg($msg);
            }

            $msg = apply_filters("wplc_filter_message_control_out",$msg);

            if($display_name){
                $msg_hist .= "<span class='wplc-user-message wplc-color-bg-1 wplc-color-2 wplc-color-border-1'>".$image."<strong>$from</strong>: $msg</span><br /><div class='wplc-clear-float-message'></div>";            
            } else {            
                $msg_hist .= "<span class='wplc-user-message wplc-color-bg-1 wplc-color-2 wplc-color-border-1'>$msg</span><br /><div class='wplc-clear-float-message'></div>";
            }
        }



        return $msg_hist;
    } else {
        return "security issue";
    }


}
function wplc_mark_as_read_admin_chat_messages($mid) {
    $wplc_current_user = get_current_user_id();

    if( get_user_meta( $wplc_current_user, 'wplc_ma_agent', true ) ){
    /*
      -- modified in in 6.0.04 --

      if(current_user_can('wplc_ma_agent') || current_user_can('manage_options')){   
     */

        global $wpdb;
        global $wplc_tblname_msgs;

        $wpdb->update( 
            $wplc_tblname_msgs, 
            array( 
                'status' => 1
            ), 
            array('id' => $mid), 
            array('%d'), 
            array('%d') 
        );

    } else { return "security issue"; }


}





function wplc_return_chat_session_variable($cid) {
    global $wpdb;
    global $wplc_tblname_chats;
    $results = $wpdb->get_results(
        "
        SELECT *
        FROM $wplc_tblname_chats
        WHERE `id` = '$cid'
        "
    );
    foreach ($results as $result) {
        return $result->session;
    }
}



function wplc_return_chat_status($cid) {
    global $wpdb;
    global $wplc_tblname_chats;
    $results = $wpdb->get_results(
        "
        SELECT *
        FROM $wplc_tblname_chats
        WHERE `id` = '$cid'
        "
    );
    foreach ($results as $result) {
        return $result->status;
    }
}


function wplc_return_status($status) {
    if ($status == 1) {
        return __("complete","wplivechat");
    }
    if ($status == 2) {
        return __("pending", "wplivechat");
    }
    if ($status == 3) {
        return __("active", "wplivechat");
    }
    if ($status == 4) {
        return __("deleted", "wplivechat");
    }
    if ($status == 5) {
        return __("browsing", "wplivechat");
    }
    if ($status == 6) {
        return __("requesting chat", "wplivechat");
    }
    if($status == 8){
        return __("Chat Ended - User still browsing", "wplivechat");
    }
    if($status == 9){
        return __("User is browsing but doesn't want to chat", "wplivechat");
    }
    
}

add_filter("wplc_filter_mail_body","wplc_filter_control_mail_body",10,2);
function wplc_filter_control_mail_body($header,$msg) {
    $body = '
<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">      
    <html>
    
    <body>



        <table id="" border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #ec822c;">
        <tbody>
          <tr>
            <td width="100%" style="padding: 30px 20px 100px 20px;">
              <table align="center" cellpadding="0" cellspacing="0" class="" width="100%" style="border-collapse: separate; max-width:600px;">
                <tbody>
                  <tr>
                    <td style="text-align: center; padding-bottom: 20px;">
                      
                      <p>'.$header.'</p>
                    </td>
                  </tr>
                </tbody>
              </table>

              <table id="" align="center" cellpadding="0" cellspacing="0" class="" width="100%" style="border-collapse: separate; max-width: 600px; font-family: Georgia, serif; font-size: 12px; color: rgb(51, 62, 72); border: 0px solid rgb(255, 255, 255); border-radius: 10px; background-color: rgb(255, 255, 255);">
              <tbody>
                  <tr>
                    <td class="sortable-list ui-sortable" style="padding:20px; text-align:center;">
                        '.nl2br($msg).'
                    </td>
                  </tr>
                </tbody>
              </table>

              <table align="center" cellpadding="0" cellspacing="0" class="" width="100%" style="border-collapse: separate; max-width:100%;">
                <tbody>
                  <tr>
                    <td style="padding:20px;">
                      <table border="0" cellpadding="0" cellspacing="0" class="" width="100%">
                        <tbody>
                          <tr>
                            <td id="" align="center">
                             <p>'.get_option('siteurl').'</p>
                            </td>
                          </tr>
                        </tbody>
                      </table>
                    </td>
                  </tr>
                </tbody>
              </table>
            </td>
          </tr>
        </tbody>
      </table>


        
        </div>
    </body>
</html>
            ';
            return $body;
}


/**
 * Send an email to the admin based on the settings in the settings page
 * @param  string $reply_to      email of the user
 * @param  string $reply_to_name name of the user
 * @param  string $subject       subject
 * @param  string $msg           message being emailed
 * @return void
 * @since  5.1.00
 */
function wplcmail($reply_to,$reply_to_name,$subject,$msg) {
    
    $wplc_pro_settings = get_option("WPLC_SETTINGS");
    if(isset($wplc_pro_settings['wplc_pro_chat_email_address'])){
        $email_address = $wplc_pro_settings['wplc_pro_chat_email_address'];
    }else{
        $email_address = get_option('admin_email');
    }

    $email_address = explode(',', $email_address);  
    
    if(get_option("wplc_mail_type") == "wp_mail" || !get_option('wplc_mail_type')){
        $headers[] = 'Content-type: text/html';
        $headers[] = 'Reply-To: '.$reply_to_name.'<'.$reply_to.'>';
        if($email_address){
            foreach($email_address as $email){
                /* Send offline message to each email address */
                $overbody = apply_filters("wplc_filter_mail_body",$subject,$msg);
                if (!wp_mail($email, $subject, $overbody, $headers)) {
                    $handle = fopen("wp_livechat_error_log.txt", 'a');
                    $error = date("Y-m-d H:i:s") . " WP-Mail Failed to send \n";
                    @fwrite($handle, $error);
                }
            }
        }
//        $to = $wplc_pro_settings['wplc_pro_chat_email_address'];
        return;
    } else {
    
        require 'phpmailer/PHPMailerAutoload.php';
        $wplc_pro_settings = get_option("WPLC_PRO_SETTINGS");
        $host = get_option('wplc_mail_host');
        $port = get_option('wplc_mail_port');
        $username = get_option("wplc_mail_username");
        $password = get_option("wplc_mail_password");
        if($host && $port && $username && $password){
            //Create a new PHPMailer instance
            $mail = new PHPMailer();
            //Tell PHPMailer to use SMTP
            $mail->isSMTP();
            //Enable SMTP debugging
            // 0 = off (for production use)
            // 1 = client messages
            // 2 = client and server messages
            $mail->SMTPDebug = 0;
            //Ask for HTML-friendly debug output
            $mail->Debugoutput = 'html';
            //Set the hostname of the mail server
            $mail->Host = $host;
            //Set the SMTP port number - likely to be 25, 26, 465 or 587
            $mail->Port = $port;
            //Set the encryption system to use - ssl (deprecated) or tls
            if($port == "587"){
                $mail->SMTPSecure = 'tls';
            } else if($port == "465"){
                $mail->SMTPSecure = 'ssl';
            }
            //Whether to use SMTP authentication
            $mail->SMTPAuth = true;
            //Username to use for SMTP authentication
            $mail->Username = $username;
            //Password to use for SMTP authentication
            $mail->Password = $password;
            //Set who the message is to be sent from
            $mail->setFrom($reply_to, $reply_to_name);
            //Set who the message is to be sent to
            $mail->addAddress($wplc_pro_settings['wplc_pro_chat_email_address']);
            //Set the subject line
            $mail->Subject = $subject;
            //Read an HTML message body from an external file, convert referenced images to embedded,
            //convert HTML into a basic plain-text alternative body
            $body = apply_filters("wplc_filter_mail_body",$subject,$msg);
            $mail->msgHTML($body);
            //Replace the plain text body with one created manually
            $mail->AltBody = $msg;


            //send the message, check for errors
            if (!$mail->send()) {
                $handle = fopen("wp_livechat_error_log.txt", 'a');
                $error = date("Y-m-d H:i:s")." ".$mail->ErrorInfo." \n";
                @fwrite($handle, $error); 
            }
            return;
        }
    }
}
/**
 * Sends offline messages to the admin (normally via ajax)
 * @param  string $name  Name of the user
 * @param  string $email Email of the user
 * @param  string $msg   The message being sent to the admin
 * @param  int    $cid   Chat ID
 * @return void
 */
function wplc_send_offline_msg($name,$email,$msg,$cid) {
    $subject = __("WP Live Chat Support - Offline Message from ", "wplivechat")."$name";
    $msg = __("Name", "wplivechat").": $name \n".
    __("Email", "wplivechat").": $email\n".
    __("Message", "wplivechat").": $msg\n\n".
    __("Via WP Live Chat Support", "wplivechat");
    wplcmail($email,$name, $subject, $msg);    
    return;
}


/**
 * Saves offline messages to the database
 * @param  string $name    User name
 * @param  string $email   User email
 * @param  string $message Message being saved
 * @return Void
 * @since  5.1.00
 */
function wplc_store_offline_message($name, $email, $message){
    global $wpdb;
    global $wplc_tblname_offline_msgs;
    
    $wplc_settings = get_option('WPLC_SETTINGS');
        
    if(isset($wplc_settings['wplc_record_ip_address']) && $wplc_settings['wplc_record_ip_address'] == 1){
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        $offline_ip_address = $ip_address;
    } else {
        $offline_ip_address = "";
    }


    $ins_array = array(
        'timestamp' => current_time('mysql'),
        'name' => $name,
        'email' => $email,
        'message' => $message,
        'ip' => $offline_ip_address,
        'user_agent' => $_SERVER['HTTP_USER_AGENT']
    );
    
    $rows_affected = $wpdb->insert( $wplc_tblname_offline_msgs, $ins_array );
    return;
}


function wplc_user_initiate_chat($name,$email,$cid = null,$session) {

    global $wpdb;
    global $wplc_tblname_chats;
    do_action("wplc_hook_initiate_chat",array("cid" => $cid, "name" => $name, "email" => $email));
  
    if (function_exists("wplc_list_chats_pro")) { /* check if functions-pro is around */
        wplc_pro_notify_via_email();
    }

    $wplc_settings = get_option('WPLC_SETTINGS');
        
    if(isset($wplc_settings['wplc_record_ip_address']) && $wplc_settings['wplc_record_ip_address'] == 1){
        if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip_address = $_SERVER['REMOTE_ADDR'];
        }
        $user_data = array(
            'ip' => $ip_address,
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        );
        $wplc_ce_ip = $ip_address;
    } else {
        $user_data = array(
            'ip' => "",
            'user_agent' => $_SERVER['HTTP_USER_AGENT']
        );
        $wplc_ce_ip = null;
    }
    
    if(function_exists('wplc_ce_activate')){
        /* Log the chat for statistical purposes as well */
        if(function_exists('wplc_ce_record_initial_chat')){
            wplc_ce_record_initial_chat($name, $email, $cid, $wplc_ce_ip, $_SERVER['HTTP_REFERER']);
        }                    
    }
    
    if ($cid != null) { /* change from a visitor to a chat */                

        $wpdb->update( 
            $wplc_tblname_chats, 
            array( 
                'status' => 2,
                'timestamp' => current_time('mysql'),
                'name' => $name,
                'email' => $email,
                'session' => $session,
                'ip' => maybe_serialize($user_data),
                'url' => $_SERVER['HTTP_REFERER'],
                'last_active_timestamp' => current_time('mysql')
            ), 
            array('id' => $cid), 
            array( 
                '%d',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            ), 
            array('%d') 
        );
        return $cid;
    }
    else { 
        
        $wpdb->insert( 
            $wplc_tblname_chats, 
            array( 
                'status' => 2, 
                'timestamp' => current_time('mysql'),
                'name' => $name,
                'email' => $email,
                'session' => $session,
                'ip' => maybe_serialize($user_data),
                'url' => $_SERVER['HTTP_REFERER'],
                'last_active_timestamp' => current_time('mysql')
            ), 
            array( 
                '%s', 
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s',
                '%s'
            ) 
        );
        
        
        $lastid = $wpdb->insert_id;
        return $lastid;
    }

}



function wplc_get_msg() {
    return "<a href=\"javascript:void(0);\" class=\"wplc_second_chat_request button button-primary\" style='cursor:not-allowed' title=\"".__("Get Pro Add-on to accept more chats","wplivechat")."\" target=\"_BLANK\">".__("Accept Chat","wplivechat")."</a>";
}
function wplc_update_chat_statuses() {

    global $wpdb;
    global $wplc_tblname_chats;
    $results = $wpdb->get_results(
        "
        SELECT *
        FROM $wplc_tblname_chats
        WHERE `status` = '2' OR `status` = '3' OR `status` = '5' or `status` = '8' or `status` = '9' or `status` = '10'
        "
    );
    foreach ($results as $result) {
        $id = $result->id;
        $timestamp = strtotime($result->last_active_timestamp);
        $datenow = current_time('timestamp');
        $difference = $datenow - $timestamp;
        
        if (intval($result->status) == 2) {
            if ($difference >= 60) { // 1 minute max
                wplc_change_chat_status($id,0);
            }
        }
        else if (intval($result->status) == 3) {
            if ($difference >= 300) { // 30 seconds
                wplc_change_chat_status($id,1);
            }
        }
        else if (intval($result->status) == 5) {
            if ($difference >= 120) { // 2 minute timeout
                wplc_change_chat_status($id,7); // 7 - timedout
            }
        } else if(intval($result->status) == 8){ // chat is complete but user is still browsing
            if ($difference >= 45) { // 30 seconds
                wplc_change_chat_status($id,1); // 1 - chat is now complete
            }
        } else if(intval($result->status) == 9 || $result->status == 10){
            if ($difference >= 120) { // 120 seconds
                wplc_change_chat_status($id,7); // 7 - timedout
            }
        }
    }
}
function wplc_check_pending_chats(){
    global $wpdb;
    global $wplc_tblname_chats;
    $sql = "SELECT * FROM `$wplc_tblname_chats` WHERE `status` = 2";
    $wpdb->query($sql);
    if($wpdb->num_rows){
        return true;
    } else {
        return false;
    }       
}
function wplc_get_active_and_pending_chats(){
    global $wpdb;
    global $wplc_tblname_chats;
    $sql = "SELECT * FROM `$wplc_tblname_chats` WHERE `status` = 2 OR `status` = 3 ORDER BY `status`";
    $results = $wpdb->get_results($sql);
    if($results){
        return $results;
    } else {
        return false;
    }
}
function wplc_convert_array_to_string($array){
    $string = "";
    if($array){
        foreach($array as $value){
            $string.= $value->id." ;";
        }
    } else {
        $string = false;
    }
    return $string;
}

function wplc_return_browser_image($string,$size) {
    switch($string) {
        
        case "Internet Explorer":
            return "web_".$size."x".$size.".png";
            break;
        case "Mozilla Firefox":
            return "firefox_".$size."x".$size.".png";
            break;
        case "Opera":
            return "opera_".$size."x".$size.".png";
            break;
        case "Google Chrome":
            return "chrome_".$size."x".$size.".png";
            break;
        case "Safari":
            return "safari_".$size."x".$size.".png";
            break;
        case "Other browser":
            return "web_".$size."x".$size.".png";
            break;
        default:
            return "web_".$size."x".$size.".png";
            break;       
    }
    
    
}
function wplc_return_browser_string($user_agent) {
if(strpos($user_agent, 'MSIE') !== FALSE)
   return 'Internet explorer';
 elseif(strpos($user_agent, 'Trident') !== FALSE) //For Supporting IE 11
    return 'Internet explorer';
 elseif(strpos($user_agent, 'Firefox') !== FALSE)
   return 'Mozilla Firefox';
 elseif(strpos($user_agent, 'Chrome') !== FALSE)
   return 'Google Chrome';
 elseif(strpos($user_agent, 'Opera Mini') !== FALSE)
   return "Opera";
 elseif(strpos($user_agent, 'Opera') !== FALSE)
   return "Opera";
 elseif(strpos($user_agent, 'Safari') !== FALSE)
   return "Safari";
 else
   return 'Other browser';
}

function wplc_error_directory() {
    $upload_dir = wp_upload_dir();
    
    if (is_multisite()) {
        if (!file_exists($upload_dir['basedir'].'/wp-live-chat-support')) {
            wp_mkdir_p($upload_dir['basedir'].'/wp-live-chat-support');
            $content = "Error log created";
            $fp = @fopen($upload_dir['basedir'].'/wp-live-chat-support'."/error_log.txt","w+");
            @fwrite($fp,$content);
        }
    } else {
        if (!file_exists(ABSPATH.'wp-content/uploads/wp-live-chat-support')) {
            wp_mkdir_p(ABSPATH.'wp-content/uploads/wp-live-chat-support');
            $content = "Error log created";
            $fp = @fopen(ABSPATH.'wp-content/uploads/wp-live-chat-support'."/error_log.txt","w+");
            @fwrite($fp,$content);
        }
        
    }
    return true;
    
}

function wplc_error_log($error) {
    
    $content = "\r\n[".date("Y-m-d")."] [".date("H:i:s")."]".$error;
    $fp = @fopen(ABSPATH.'/wp-content/uploads/wp-live-chat-support'."/error_log.txt","a+");
    @fwrite($fp,$content);
    @fclose($fp);
    
    
}
function Memory_Usage($decimals = 2)
{
    $result = 0;

    if (function_exists('memory_get_usage'))
    {
        $result = memory_get_usage() / 1024;
    }

    else
    {
        if (function_exists('exec'))
        {
            $output = array();

            if (substr(strtoupper(PHP_OS), 0, 3) == 'WIN')
            {
                exec('tasklist /FI "PID eq ' . getmypid() . '" /FO LIST', $output);

                $result = preg_replace('/[\D]/', '', $output[5]);
            }

            else
            {
                exec('ps -eo%mem,rss,pid | grep ' . getmypid(), $output);

                $output = explode('  ', $output[0]);

                $result = $output[1];
            }
        }
    }

    return number_format(intval($result) / 1024, $decimals, '.', '')." mb";
}
function wplc_get_memory_usage() {
    $size = memory_get_usage(true);
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    
}
function wplc_record_mem() {
    $data = array(
        'date' => current_time('mysql'),
        'php_mem' => wplc_get_memory_usage()
    );
    $fp = @fopen(ABSPATH.'/wp-content/uploads/wp-live-chat-support'."/mem_usag.csv","a+");
    fputcsv($fp, $data);
    fclose($fp);
}

function wplc_admin_display_missed_chats() {

    global $wpdb;
    global $wplc_tblname_chats;

    echo "
        <table class=\"wp-list-table widefat fixed \" cellspacing=\"0\">
            <thead>
                <tr>
                    <th class='manage-column column-id'><span>" . __("Date", "wplivechat") . "</span></th>
                    <th scope='col' id='wplc_name_colum' class='manage-column column-id'><span>" . __("Name", "wplivechat") . "</span></th>
                    <th scope='col' id='wplc_email_colum' class='manage-column column-id'>" . __("Email", "wplivechat") . "</th>
                    <th scope='col' id='wplc_url_colum' class='manage-column column-id'>" . __("URL", "wplivechat") . "</th>
                </tr>
            </thead>
            <tbody id=\"the-list\" class='list:wp_list_text_link'>";

    if (function_exists("wplc_register_pro_version")) {
        $sql = "
            SELECT *
            FROM $wplc_tblname_chats
            WHERE (`status` = 7 
            OR `agent_id` = 0)
            AND `email` != 'no email set'                     
            ORDER BY `timestamp` DESC
                ";
    } else {
        $sql = "
            SELECT *
            FROM $wplc_tblname_chats
            WHERE `status` = 7             
            AND `email` != 'no email set'                     
            ORDER BY `timestamp` DESC
                ";
    }

    $results = $wpdb->get_results($sql);

    if (!$results) {
        echo "<tr><td></td><td>" . __("You have not missed any chat requests.", "wplivechat") . "</td></tr>";
    } else {
        foreach ($results as $result) {
            echo "<tr id=\"record_" . $result->id . "\">";
            echo "<td class='chat_id column-chat_d'>" . $result->timestamp . "</td>";
            echo "<td class='chat_name column_chat_name' id='chat_name_" . $result->id . "'><img src=\"//www.gravatar.com/avatar/" . md5($result->email) . "?s=30\"  class='wplc-user-message-avatar' /> " . $result->name . "</td>";
            echo "<td class='chat_email column_chat_email' id='chat_email_" . $result->id . "'><a href='mailto:" . $result->email . "' title='Email " . ".$result->email." . "'>" . $result->email . "</a></td>";
            echo "<td class='chat_name column_chat_url' id='chat_url_" . $result->id . "'>" . $result->url . "</td>";
            echo "</tr>";
        }
    }

    echo "
            </tbody>
        </table>";
}


/**
 * Compares the users IP address to the list in the banned IPs in the settings page
 * @return BOOL
 */
function wplc_is_user_banned_basic(){
    $banned_ip = get_option('WPLC_BANNED_IP_ADDRESSES');
    if($banned_ip){
        $banned_ip = maybe_unserialize($banned_ip);
        $banned = 0;
        if (is_array($banned_ip)) {
            foreach($banned_ip as $ip){

                if(isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR'] != '') {
                    $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
                } else {
                    $ip_address = $_SERVER['REMOTE_ADDR'];
                }
                
                if(isset($ip_address)){
                    if($ip == $ip_address){
                        $banned++;
                    }
                } else {
                    $banned = 0;
                }
            }
        } else {
            return 0;
        }
    } else {
        $banned = 0;
    }
    return $banned;
}




function wplc_return_animations_basic(){
    
    $wplc_settings = get_option("WPLC_SETTINGS");
    
    if ($wplc_settings["wplc_settings_align"] == 1) {
        $original_pos = "bottom_left";
        //$wplc_box_align = "left:100px; bottom:0px;";
        $wplc_box_align = "bottom:0px;";
    } else if ($wplc_settings["wplc_settings_align"] == 2) {
        $original_pos = "bottom_right";
        //$wplc_box_align = "right:100px; bottom:0px;";
        $wplc_box_align = "bottom:0px;";
    } else if ($wplc_settings["wplc_settings_align"] == 3) {
        $original_pos = "left";
//        $wplc_box_align = "left:0; bottom:100px;";
        $wplc_box_align = " bottom:100px;";
        $wplc_class = "wplc_left";
    } else if ($wplc_settings["wplc_settings_align"] == 4) {
        $original_pos = "right";
//        $wplc_box_align = "right:0; bottom:100px;";
        $wplc_box_align = "bottom:100px;";
        $wplc_class = "wplc_right";
    }

    $animation_data = array();

    if(isset($wplc_settings['wplc_animation']) && $wplc_settings['wplc_animation'] == 'animation-1'){

        if($original_pos == 'bottom_right'){
            $wplc_starting_point = 'margin-bottom: -350px; right: 100px;';
            $wplc_animation = 'animation-1';
        } else if ($original_pos == 'bottom_left'){
            $wplc_starting_point = 'margin-bottom: -350px; left: 100px;';
            $wplc_animation = 'animation-1';
        } else if ($original_pos == 'left'){
            $wplc_starting_point = 'margin-bottom: -350px; left: 0px;';
            $wplc_box_align = "left:0; bottom:100px;";
            $wplc_animation = 'animation-1';
        } else if ($original_pos == 'right'){
            $wplc_starting_point = 'margin-bottom: -350px; right: 0px;';
            $wplc_animation = 'animation-1';
            $wplc_box_align = "right:0; bottom:100px;";
        }

        $animation_data['animation'] = $wplc_animation;
        $animation_data['starting_point'] = $wplc_starting_point;
        $animation_data['box_align'] =  $wplc_box_align;

    } else if (isset($wplc_settings['wplc_animation']) && $wplc_settings['wplc_animation'] == 'animation-2'){

        if($original_pos == 'bottom_right'){
            $wplc_starting_point = 'margin-bottom: 0px; right: -300px;';
            $wplc_animation = 'animation-2-br';
        } else if ($original_pos == 'bottom_left'){
            $wplc_starting_point = 'margin-bottom: 0px; left: -300px;';
            $wplc_animation = 'animation-2-bl';
        } else if ($original_pos == 'left'){
            $wplc_starting_point = 'margin-bottom: 0px; left: -999px;';
            $wplc_animation = 'animation-2-l';
        } else if ($original_pos == 'right'){
            $wplc_starting_point = 'margin-bottom: 0px; right: -999px;';               
            $wplc_animation = 'animation-2-r';
        }

        $animation_data['animation'] = $wplc_animation;
        $animation_data['starting_point'] = $wplc_starting_point;
        $animation_data['box_align'] =  $wplc_box_align;

    } else if (isset($wplc_settings['wplc_animation']) && $wplc_settings['wplc_animation'] == 'animation-3'){

        $wplc_animation = 'animation-3';

        if($original_pos == 'bottom_right'){
            $wplc_starting_point = 'margin-bottom: 0; right: 100px; display: none;';
        } else if ($original_pos == 'bottom_left'){
            $wplc_starting_point = 'margin-bottom: 0px; left: 100px; display: none;';
        } else if ($original_pos == 'left'){
            $wplc_starting_point = 'margin-bottom: 100px; left: 0px; display: none;';
        } else if ($original_pos == 'right'){
            $wplc_starting_point = 'margin-bottom: 100px; right: 0px; display: none;';
        }

        $animation_data['animation'] = $wplc_animation;
        $animation_data['starting_point'] = $wplc_starting_point;
        $animation_data['box_align'] =  $wplc_box_align;

    } else if (isset($wplc_settings['wplc_animation']) && $wplc_settings['wplc_animation'] == 'animation-4'){
        // Dont use an animation

        $wplc_animation = "animation-4";

        if($original_pos == 'bottom_right'){
            $wplc_starting_point = 'margin-bottom: 0; right: 100px; display: none;';
        } else if ($original_pos == 'bottom_left'){
            $wplc_starting_point = 'margin-bottom: 0px; left: 100px; display: none;';
        } else if ($original_pos == 'left'){
            $wplc_starting_point = 'margin-bottom: 100px; left: 0px; display: none;';
        } else if ($original_pos == 'right'){
            $wplc_starting_point = 'margin-bottom: 100px; right: 0px; display: none;';
        }

        $animation_data['animation'] = $wplc_animation;
        $animation_data['starting_point'] = $wplc_starting_point;
        $animation_data['box_align'] =  $wplc_box_align;

    } else {
        
        if($original_pos == 'bottom_right'){
            $wplc_starting_point = 'margin-bottom: 0; right: 100px; display: none;';
        } else if ($original_pos == 'bottom_left'){
            $wplc_starting_point = 'margin-bottom: 0px; left: 100px; display: none;';
        } else if ($original_pos == 'left'){
            $wplc_starting_point = 'margin-bottom: 100px; left: 0px; display: none;';
        } else if ($original_pos == 'right'){
            $wplc_starting_point = 'margin-bottom: 100px; right: 0px; display: none;';
        }
        
        $wplc_animation = 'none';
        
        $animation_data['animation'] = $wplc_animation;
        $animation_data['starting_point'] = $wplc_starting_point;
        $animation_data['box_align'] =  $wplc_box_align;
    }

    return $animation_data;
}
