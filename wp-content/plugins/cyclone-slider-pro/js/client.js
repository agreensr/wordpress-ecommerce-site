jQuery(document).ready(function($){
    
    /*** Lightbox ***/
    (function() {
        try{
            $('.cycloneslider').each(function(i){ /* Loop thru each slider */
                var items = [];
                
                $(this).find('.magnific-pop').each(function(i){ /* Loop thru each magnific popup links with images */
                    var title = $(this).next().find('.cycloneslider-caption-title').text();
                    
                    items.push({
                        src: $(this).find('img').attr('src'),
                        type: 'image',
                        title: title
                    });
                });
                $(this).data('items', items);
            });
            
            $('.cycloneslider').on('click', '.cycloneslider-slide-image.cycle-slide-active', function(e){ /* Add only to image slide */
                
                var slider = $(this).parents('.cycloneslider'),
                    items = slider.data('items'),
                    slides = slider.children('.cycloneslider-slides'),
                    index = $(this).index('.cycloneslider-slide-image');
                
                if (items.length>0) {
                    e.preventDefault();
                    $.magnificPopup.open({
                        items: items,
                        gallery: {
                            enabled: true
                        },
                        callbacks: {
                            beforeOpen: function() {
                                slides.cycle('pause');
                            },
                            close: function() {
                                slides.cycle('resume');
                            }
                        }
                    });
                    $.magnificPopup.instance.goTo( index );
                }
            })
        } catch (ignore) {}
    })();
    
});

/*** Global template functionalities ***/
(function() {
    var slides_selector = '.cycloneslider-template-dark .cycloneslider-slides';
    
    slides_selector += ',.cycloneslider-template-default .cycloneslider-slides';
    slides_selector += ',.cycloneslider-template-standard .cycloneslider-slides';
    slides_selector += ',.cycloneslider-template-thumbnails .cycloneslider-slides';
    
    slides_selector += ',.cycloneslider-template-galleria .cycloneslider-slides';
    slides_selector += ',.cycloneslider-template-text .cycloneslider-slides';
    slides_selector += ',.cycloneslider-template-dos .cycloneslider-slides';
    
    jQuery(document).on('cycle-before', slides_selector, function( event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag ) {
        var slide = jQuery( outgoingSlideEl ), /* Current slide */
            curHeight = 0,
            nextHeight = 0;
        
        /* Autoheight when dynamic height is on */
        /* Using getBoundingClientRect() instead of jQuery's outerHeight() for more accurate reading (floating point values) */
        if( "on" == optionHash.dynamicHeight ) {
            curHeight = jQuery(outgoingSlideEl)[0].getBoundingClientRect().height;
            if ( undefined == curHeight || 0 == curHeight ) { /* IE8 returns undefined so we use outerHeight as fallback. Also works for older templates! */
                curHeight = jQuery(outgoingSlideEl).outerHeight();
            }
            
            nextHeight = jQuery(incomingSlideEl)[0].getBoundingClientRect().height;
            if ( undefined == nextHeight || 0 == nextHeight ) { /* IE8 returns undefined so we use outerHeight as fallback. Also works for older templates! */
                nextHeight = jQuery(incomingSlideEl).outerHeight();
            }
            if ( nextHeight != curHeight ) jQuery(this).animate({height:nextHeight}, optionHash.autoHeightSpeed, optionHash.autoHeightEasing);
        }
        
        if(slide.hasClass('cycloneslider-slide-youtube')) pauseYoutube( slide ); /* Pause youtube video on next */
        
        if(slide.hasClass('cycloneslider-slide-vimeo')) pauseVimeo( slide ); /* Pause vimeo video on next */
    });

    jQuery(document).on('cycle-initialized cycle-after', slides_selector, function( event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag ) {
        var index = (event.type == 'cycle-initialized') ? optionHash.currSlide : optionHash.nextSlide;
        var slide = jQuery( optionHash.slides[ index ] ); /* Current slide */
        
        /* Make sure current slide has the highest z-index.
        Fix for issue when previous slides appear on top of the current slide due to cycle's weird z-index assigments */
        if( false == optionHash.hideNonActive ) slide.css('zIndex', parseInt(slide.css('zIndex'))+20);
    });
    
    function pauseYoutube( slide ){
        var data = {
            "event": "command",
            "func": "pauseVideo",
            "args": [],
            "id": ""
        }
        postMessage( slide.find('iframe'), data, '*');
    }
    
    function pauseVimeo( slide ){
        postMessage( slide.find('iframe'), {method:'pause'}, slide.find('iframe').attr('src'));
    }
    
    function postMessage(iframe, data, url){
        try{
            if (iframe[0]) { // Frame exists
                iframe[0].contentWindow.postMessage(JSON.stringify(data), url);
            }
        } catch (ignore) {}
    }
    
    jQuery(document).on('keyup', '.cycloneslider', function( e ) {
        var KEY_LEFT = 37,
            KEY_RIGHT = 39,
            key = null;
        
        e = e || window.event; // Use param e if it exists. use window.event otherwise (for <=IE8)

        key = e.keyCode;
        
        if ( key == KEY_LEFT ) {
            jQuery(this).children('.cycle-slideshow').cycle('prev');
        } else if ( key == KEY_RIGHT ) {
            jQuery(this).children('.cycle-slideshow').cycle('next');
        }
        
    });
})();

