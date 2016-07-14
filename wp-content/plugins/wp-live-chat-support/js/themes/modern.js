jQuery(document).on("wplc_minimize_chat", function( e ) {
    wplc_is_chat_open = false;
    jQuery('#wp-live-chat').height("");
    if(jQuery("#wp-live-chat").attr("original_pos") === "bottom_right"){
        jQuery("#wp-live-chat").css("left", "");
        jQuery("#wp-live-chat").css("bottom", "0");
        jQuery("#wp-live-chat").css("right", "100px");
    } else if(jQuery("#wp-live-chat").attr("original_pos") === "bottom_left"){
        jQuery("#wp-live-chat").css("left", "100px");
        jQuery("#wp-live-chat").css("bottom", "0");
        jQuery("#wp-live-chat").css("right", "");
    } else if(jQuery("#wp-live-chat").attr("original_pos") === "left"){
        jQuery("#wp-live-chat").css("left", "0");
        jQuery("#wp-live-chat").css("bottom", "100px");
    } else if(jQuery("#wp-live-chat").attr("original_pos") === "right"){
        jQuery("#wp-live-chat").css("left", "");
        jQuery("#wp-live-chat").css("right", "0");
        jQuery("#wp-live-chat").css("bottom", "100px");
        jQuery("#wp-live-chat").css("width", "");
    }
    jQuery('#wp-live-chat').addClass("wplc_close");
    jQuery('#wp-live-chat').removeClass("wplc_open");
    //jQuery("#wp-live-chat").css(jQuery("#wp-live-chat").attr("original_pos"), "100px");
    jQuery("#wp-live-chat").css("top", "");
    jQuery("#wp-live-chat-1").show();
    jQuery("#wp-live-chat-1").css('cursor', 'pointer');
    jQuery("#wp-live-chat-2").hide();
    jQuery("#wp-live-chat-3").hide();
    jQuery("#wp-live-chat-4").hide();
    jQuery("#wp-live-chat-react").hide();
    jQuery("#wp-live-chat-minimize").hide();

    jQuery("#wp-live-chat-header").show();

});

jQuery(document).on("wplc_start_chat", function( e ) { 
	jQuery("#wp-live-chat-2-inner").hide("slow");
	jQuery("#wp-live-chat-3").show();
});

jQuery(document).on( "wplc_open_chat_1", function( e ) {
    jQuery('#wp-live-chat').removeClass("wplc_close");
    jQuery('#wp-live-chat').addClass("wplc_open");
    jQuery("#wp-live-chat-react").hide();
    jQuery("#wp-live-chat-header").hide();
    Cookies.set('wplc_hide', "", { expires: 1, path: '/' });
    jQuery("#wp-live-chat-minimize").show();




});


jQuery(document).on("wplc_animation_done", function(e) {
    jQuery("#wplc_chatbox").css("top",
        35+jQuery("#wplc_logo").height()+"px"
    );

});
jQuery(document).on( "wplc_open_chat_2", function( e ) {
	jQuery("#wp-live-chat-1").hide();
	jQuery("#wp-live-chat-2-inner").hide();

	jQuery("#wp-live-chat-2").show();

    wplc_chat_status = Cookies.get('wplc_chat_status');
    if (typeof e.wplc_online !== "undefined" && e.wplc_online === true) {
	   jQuery("#wp-live-chat-4").show();
       jQuery.event.trigger({type: "wplc_animation_done"});
       jQuery("#wplc_chatmsg").focus();
    }

	jQuery("#wp-live-chat-3").hide();

	jQuery("#wp-live-chat-minimize").css("right","23px");
	Cookies.set('wplc_minimize', "", { expires: 1, path: '/' });



});
       

jQuery(document).ready(function() { 



	//opens chat when clicked on top bar
	jQuery("body").on("click", "#wp-live-chat-header", function() {
        jQuery("#wplc_hovercard").fadeOut("fast");
        wplc_is_chat_open = true;
	    jQuery.event.trigger({type: "wplc_open_chat"});
	});

    jQuery("body").on("hover", "#wp-live-chat-header", function(){
        if (!wplc_is_chat_open) {
         jQuery("#wplc_hovercard").fadeIn('fast');
        }
    });
    jQuery("body").on("click", "#wplc_hovercard_min", function() {
     jQuery("#wplc_hovercard").fadeOut('fast');
    });
    jQuery("body").on("click", "#speeching_button", function() {
     jQuery("#wp-live-chat-header").click();
    });



            

});