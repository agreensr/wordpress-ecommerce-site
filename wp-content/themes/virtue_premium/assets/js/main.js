/* Initialize
*/
var kt_isMobile = {
    Android: function() {
        return navigator.userAgent.match(/Android/i);
    },
    BlackBerry: function() {
        return navigator.userAgent.match(/BlackBerry/i);
    },
    iOS: function() {
        return navigator.userAgent.match(/iPhone|iPad|iPod/i);
    },
    Opera: function() {
        return navigator.userAgent.match(/Opera Mini/i);
    },
    Windows: function() {
        return navigator.userAgent.match(/IEMobile/i);
    },
    any: function() {
        return (kt_isMobile.Android() || kt_isMobile.BlackBerry() || kt_isMobile.iOS() || kt_isMobile.Opera() || kt_isMobile.Windows());
    }
};
if( !kt_isMobile.any() ) {
/*! Stellar.js v0.6.2 | Copyright 2014, Mark Dalgleish | http://markdalgleish.com/projects/stellar.js | http://markdalgleish.mit-license.org */
!function(a,b,c,d){function e(b,c){this.element=b,this.options=a.extend({},g,c),this._defaults=g,this._name=f,this.init()}var f="stellar",g={scrollProperty:"scroll",positionProperty:"position",horizontalScrolling:!0,verticalScrolling:!0,horizontalOffset:0,verticalOffset:0,responsive:!1,parallaxBackgrounds:!0,parallaxElements:!0,hideDistantElements:!0,hideElement:function(a){a.hide()},showElement:function(a){a.show()}},h={scroll:{getLeft:function(a){return a.scrollLeft()},setLeft:function(a,b){a.scrollLeft(b)},getTop:function(a){return a.scrollTop()},setTop:function(a,b){a.scrollTop(b)}},position:{getLeft:function(a){return-1*parseInt(a.css("left"),10)},getTop:function(a){return-1*parseInt(a.css("top"),10)}},margin:{getLeft:function(a){return-1*parseInt(a.css("margin-left"),10)},getTop:function(a){return-1*parseInt(a.css("margin-top"),10)}},transform:{getLeft:function(a){var b=getComputedStyle(a[0])[k];return"none"!==b?-1*parseInt(b.match(/(-?[0-9]+)/g)[4],10):0},getTop:function(a){var b=getComputedStyle(a[0])[k];return"none"!==b?-1*parseInt(b.match(/(-?[0-9]+)/g)[5],10):0}}},i={position:{setLeft:function(a,b){a.css("left",b)},setTop:function(a,b){a.css("top",b)}},transform:{setPosition:function(a,b,c,d,e){a[0].style[k]="translate3d("+(b-c)+"px, "+(d-e)+"px, 0)"}}},j=function(){var b,c=/^(Moz|Webkit|Khtml|O|ms|Icab)(?=[A-Z])/,d=a("script")[0].style,e="";for(b in d)if(c.test(b)){e=b.match(c)[0];break}return"WebkitOpacity"in d&&(e="Webkit"),"KhtmlOpacity"in d&&(e="Khtml"),function(a){return e+(e.length>0?a.charAt(0).toUpperCase()+a.slice(1):a)}}(),k=j("transform"),l=a("<div />",{style:"background:#fff"}).css("background-position-x")!==d,m=l?function(a,b,c){a.css({"background-position-x":b,"background-position-y":c})}:function(a,b,c){a.css("background-position",b+" "+c)},n=l?function(a){return[a.css("background-position-x"),a.css("background-position-y")]}:function(a){return a.css("background-position").split(" ")},o=b.requestAnimationFrame||b.webkitRequestAnimationFrame||b.mozRequestAnimationFrame||b.oRequestAnimationFrame||b.msRequestAnimationFrame||function(a){setTimeout(a,1e3/60)};e.prototype={init:function(){this.options.name=f+"_"+Math.floor(1e9*Math.random()),this._defineElements(),this._defineGetters(),this._defineSetters(),this._handleWindowLoadAndResize(),this._detectViewport(),this.refresh({firstLoad:!0}),"scroll"===this.options.scrollProperty?this._handleScrollEvent():this._startAnimationLoop()},_defineElements:function(){this.element===c.body&&(this.element=b),this.$scrollElement=a(this.element),this.$element=this.element===b?a("body"):this.$scrollElement,this.$viewportElement=this.options.viewportElement!==d?a(this.options.viewportElement):this.$scrollElement[0]===b||"scroll"===this.options.scrollProperty?this.$scrollElement:this.$scrollElement.parent()},_defineGetters:function(){var a=this,b=h[a.options.scrollProperty];this._getScrollLeft=function(){return b.getLeft(a.$scrollElement)},this._getScrollTop=function(){return b.getTop(a.$scrollElement)}},_defineSetters:function(){var b=this,c=h[b.options.scrollProperty],d=i[b.options.positionProperty],e=c.setLeft,f=c.setTop;this._setScrollLeft="function"==typeof e?function(a){e(b.$scrollElement,a)}:a.noop,this._setScrollTop="function"==typeof f?function(a){f(b.$scrollElement,a)}:a.noop,this._setPosition=d.setPosition||function(a,c,e,f,g){b.options.horizontalScrolling&&d.setLeft(a,c,e),b.options.verticalScrolling&&d.setTop(a,f,g)}},_handleWindowLoadAndResize:function(){var c=this,d=a(b);c.options.responsive&&d.bind("load."+this.name,function(){c.refresh()}),d.bind("resize."+this.name,function(){c._detectViewport(),c.options.responsive&&c.refresh()})},refresh:function(c){var d=this,e=d._getScrollLeft(),f=d._getScrollTop();c&&c.firstLoad||this._reset(),this._setScrollLeft(0),this._setScrollTop(0),this._setOffsets(),this._findParticles(),this._findBackgrounds(),c&&c.firstLoad&&/WebKit/.test(navigator.userAgent)&&a(b).load(function(){var a=d._getScrollLeft(),b=d._getScrollTop();d._setScrollLeft(a+1),d._setScrollTop(b+1),d._setScrollLeft(a),d._setScrollTop(b)}),this._setScrollLeft(e),this._setScrollTop(f)},_detectViewport:function(){var a=this.$viewportElement.offset(),b=null!==a&&a!==d;this.viewportWidth=this.$viewportElement.width(),this.viewportHeight=this.$viewportElement.height(),this.viewportOffsetTop=b?a.top:0,this.viewportOffsetLeft=b?a.left:0},_findParticles:function(){{var b=this;this._getScrollLeft(),this._getScrollTop()}if(this.particles!==d)for(var c=this.particles.length-1;c>=0;c--)this.particles[c].$element.data("stellar-elementIsActive",d);this.particles=[],this.options.parallaxElements&&this.$element.find("[data-stellar-ratio]").each(function(){var c,e,f,g,h,i,j,k,l,m=a(this),n=0,o=0,p=0,q=0;if(m.data("stellar-elementIsActive")){if(m.data("stellar-elementIsActive")!==this)return}else m.data("stellar-elementIsActive",this);b.options.showElement(m),m.data("stellar-startingLeft")?(m.css("left",m.data("stellar-startingLeft")),m.css("top",m.data("stellar-startingTop"))):(m.data("stellar-startingLeft",m.css("left")),m.data("stellar-startingTop",m.css("top"))),f=m.position().left,g=m.position().top,h="auto"===m.css("margin-left")?0:parseInt(m.css("margin-left"),10),i="auto"===m.css("margin-top")?0:parseInt(m.css("margin-top"),10),k=m.offset().left-h,l=m.offset().top-i,m.parents().each(function(){var b=a(this);return b.data("stellar-offset-parent")===!0?(n=p,o=q,j=b,!1):(p+=b.position().left,void(q+=b.position().top))}),c=m.data("stellar-horizontal-offset")!==d?m.data("stellar-horizontal-offset"):j!==d&&j.data("stellar-horizontal-offset")!==d?j.data("stellar-horizontal-offset"):b.horizontalOffset,e=m.data("stellar-vertical-offset")!==d?m.data("stellar-vertical-offset"):j!==d&&j.data("stellar-vertical-offset")!==d?j.data("stellar-vertical-offset"):b.verticalOffset,b.particles.push({$element:m,$offsetParent:j,isFixed:"fixed"===m.css("position"),horizontalOffset:c,verticalOffset:e,startingPositionLeft:f,startingPositionTop:g,startingOffsetLeft:k,startingOffsetTop:l,parentOffsetLeft:n,parentOffsetTop:o,stellarRatio:m.data("stellar-ratio")!==d?m.data("stellar-ratio"):1,width:m.outerWidth(!0),height:m.outerHeight(!0),isHidden:!1})})},_findBackgrounds:function(){var b,c=this,e=this._getScrollLeft(),f=this._getScrollTop();this.backgrounds=[],this.options.parallaxBackgrounds&&(b=this.$element.find("[data-stellar-background-ratio]"),this.$element.data("stellar-background-ratio")&&(b=b.add(this.$element)),b.each(function(){var b,g,h,i,j,k,l,o=a(this),p=n(o),q=0,r=0,s=0,t=0;if(o.data("stellar-backgroundIsActive")){if(o.data("stellar-backgroundIsActive")!==this)return}else o.data("stellar-backgroundIsActive",this);o.data("stellar-backgroundStartingLeft")?m(o,o.data("stellar-backgroundStartingLeft"),o.data("stellar-backgroundStartingTop")):(o.data("stellar-backgroundStartingLeft",p[0]),o.data("stellar-backgroundStartingTop",p[1])),h="auto"===o.css("margin-left")?0:parseInt(o.css("margin-left"),10),i="auto"===o.css("margin-top")?0:parseInt(o.css("margin-top"),10),j=o.offset().left-h-e,k=o.offset().top-i-f,o.parents().each(function(){var b=a(this);return b.data("stellar-offset-parent")===!0?(q=s,r=t,l=b,!1):(s+=b.position().left,void(t+=b.position().top))}),b=o.data("stellar-horizontal-offset")!==d?o.data("stellar-horizontal-offset"):l!==d&&l.data("stellar-horizontal-offset")!==d?l.data("stellar-horizontal-offset"):c.horizontalOffset,g=o.data("stellar-vertical-offset")!==d?o.data("stellar-vertical-offset"):l!==d&&l.data("stellar-vertical-offset")!==d?l.data("stellar-vertical-offset"):c.verticalOffset,c.backgrounds.push({$element:o,$offsetParent:l,isFixed:"fixed"===o.css("background-attachment"),horizontalOffset:b,verticalOffset:g,startingValueLeft:p[0],startingValueTop:p[1],startingBackgroundPositionLeft:isNaN(parseInt(p[0],10))?0:parseInt(p[0],10),startingBackgroundPositionTop:isNaN(parseInt(p[1],10))?0:parseInt(p[1],10),startingPositionLeft:o.position().left,startingPositionTop:o.position().top,startingOffsetLeft:j,startingOffsetTop:k,parentOffsetLeft:q,parentOffsetTop:r,stellarRatio:o.data("stellar-background-ratio")===d?1:o.data("stellar-background-ratio")})}))},_reset:function(){var a,b,c,d,e;for(e=this.particles.length-1;e>=0;e--)a=this.particles[e],b=a.$element.data("stellar-startingLeft"),c=a.$element.data("stellar-startingTop"),this._setPosition(a.$element,b,b,c,c),this.options.showElement(a.$element),a.$element.data("stellar-startingLeft",null).data("stellar-elementIsActive",null).data("stellar-backgroundIsActive",null);for(e=this.backgrounds.length-1;e>=0;e--)d=this.backgrounds[e],d.$element.data("stellar-backgroundStartingLeft",null).data("stellar-backgroundStartingTop",null),m(d.$element,d.startingValueLeft,d.startingValueTop)},destroy:function(){this._reset(),this.$scrollElement.unbind("resize."+this.name).unbind("scroll."+this.name),this._animationLoop=a.noop,a(b).unbind("load."+this.name).unbind("resize."+this.name)},_setOffsets:function(){var c=this,d=a(b);d.unbind("resize.horizontal-"+this.name).unbind("resize.vertical-"+this.name),"function"==typeof this.options.horizontalOffset?(this.horizontalOffset=this.options.horizontalOffset(),d.bind("resize.horizontal-"+this.name,function(){c.horizontalOffset=c.options.horizontalOffset()})):this.horizontalOffset=this.options.horizontalOffset,"function"==typeof this.options.verticalOffset?(this.verticalOffset=this.options.verticalOffset(),d.bind("resize.vertical-"+this.name,function(){c.verticalOffset=c.options.verticalOffset()})):this.verticalOffset=this.options.verticalOffset},_repositionElements:function(){var a,b,c,d,e,f,g,h,i,j,k=this._getScrollLeft(),l=this._getScrollTop(),n=!0,o=!0;if(this.currentScrollLeft!==k||this.currentScrollTop!==l||this.currentWidth!==this.viewportWidth||this.currentHeight!==this.viewportHeight){for(this.currentScrollLeft=k,this.currentScrollTop=l,this.currentWidth=this.viewportWidth,this.currentHeight=this.viewportHeight,j=this.particles.length-1;j>=0;j--)a=this.particles[j],b=a.isFixed?1:0,this.options.horizontalScrolling?(f=(k+a.horizontalOffset+this.viewportOffsetLeft+a.startingPositionLeft-a.startingOffsetLeft+a.parentOffsetLeft)*-(a.stellarRatio+b-1)+a.startingPositionLeft,h=f-a.startingPositionLeft+a.startingOffsetLeft):(f=a.startingPositionLeft,h=a.startingOffsetLeft),this.options.verticalScrolling?(g=(l+a.verticalOffset+this.viewportOffsetTop+a.startingPositionTop-a.startingOffsetTop+a.parentOffsetTop)*-(a.stellarRatio+b-1)+a.startingPositionTop,i=g-a.startingPositionTop+a.startingOffsetTop):(g=a.startingPositionTop,i=a.startingOffsetTop),this.options.hideDistantElements&&(o=!this.options.horizontalScrolling||h+a.width>(a.isFixed?0:k)&&h<(a.isFixed?0:k)+this.viewportWidth+this.viewportOffsetLeft,n=!this.options.verticalScrolling||i+a.height>(a.isFixed?0:l)&&i<(a.isFixed?0:l)+this.viewportHeight+this.viewportOffsetTop),o&&n?(a.isHidden&&(this.options.showElement(a.$element),a.isHidden=!1),this._setPosition(a.$element,f,a.startingPositionLeft,g,a.startingPositionTop)):a.isHidden||(this.options.hideElement(a.$element),a.isHidden=!0);for(j=this.backgrounds.length-1;j>=0;j--)c=this.backgrounds[j],b=c.isFixed?0:1,d=this.options.horizontalScrolling?(k+c.horizontalOffset-this.viewportOffsetLeft-c.startingOffsetLeft+c.parentOffsetLeft-c.startingBackgroundPositionLeft)*(b-c.stellarRatio)+"px":c.startingValueLeft,e=this.options.verticalScrolling?(l+c.verticalOffset-this.viewportOffsetTop-c.startingOffsetTop+c.parentOffsetTop-c.startingBackgroundPositionTop)*(b-c.stellarRatio)+"px":c.startingValueTop,m(c.$element,d,e)}},_handleScrollEvent:function(){var a=this,b=!1,c=function(){a._repositionElements(),b=!1},d=function(){b||(o(c),b=!0)};this.$scrollElement.bind("scroll."+this.name,d),d()},_startAnimationLoop:function(){var a=this;this._animationLoop=function(){o(a._animationLoop),a._repositionElements()},this._animationLoop()}},a.fn[f]=function(b){var c=arguments;return b===d||"object"==typeof b?this.each(function(){a.data(this,"plugin_"+f)||a.data(this,"plugin_"+f,new e(this,b))}):"string"==typeof b&&"_"!==b[0]&&"init"!==b?this.each(function(){var d=a.data(this,"plugin_"+f);d instanceof e&&"function"==typeof d[b]&&d[b].apply(d,Array.prototype.slice.call(c,1)),"destroy"===b&&a.data(this,"plugin_"+f,null)}):void 0},a[f]=function(){var c=a(b);return c.stellar.apply(c,Array.prototype.slice.call(arguments,0))},a[f].scrollProperty=h,a[f].positionProperty=i,b.Stellar=e}(jQuery,this,document);
}
(function($){
	'use strict';
	$.fn.kt_imagesLoaded = (function(){
		var kt_imageLoaded = function (img, cb, delay){
			var timer;
			var isReponsive = false;
			var $parent = $(img).parent();
			var $img = $('<img />');
			var srcset = $(img).attr('srcset');
			var sizes = $(img).attr('sizes') || '100vw';
			var src = $(img).attr('src');
			var onload = function(){
				$img.off('load error', onload);
				clearTimeout(timer);
				cb();
			};
			if(delay){
				timer = setTimeout(onload, delay);
			}
			$img.on('load error', onload);

			if($parent.is('picture')){
				$parent = $parent.clone();
				$parent.find('img').remove().end();
				$parent.append($img);
				isReponsive = true;
			}

			if(srcset){
				$img.attr('sizes', sizes);
				$img.attr('srcset', srcset);
				if(!isReponsive){
					$img.appendTo(document.createElement('div'));
				}
				isReponsive = true;
			} else if(src){
				$img.attr('src', src);
			}

			if(isReponsive && !window.HTMLPictureElement){
				if(window.respimage){
					window.respimage({elements: [$img[0]]});
				} else if(window.picturefill){
					window.picturefill({elements: [$img[0]]});
				} else if(src){
					$img.attr('src', src);
				}
			}
		};

		return function(cb){
			var i = 0;
			var $imgs = $('img', this).add(this.filter('img'));
			var ready = function(){
				i++;
				if(i >= $imgs.length){
					cb();
				}
			};
			if(!$imgs.length) {
				return cb();
			}
			$imgs.each(function(){
				kt_imageLoaded(this, ready);
			});
			return this;
		};
	})();
})(jQuery);
jQuery(document).ready(function ($) {

	// Bootstrap Init
		$("[rel=tooltip]").tooltip();
		$('[data-toggle=tooltip]').tooltip();
		$("[data-toggle=popover]").popover();
		$('#authorTab a').click(function (e) {e.preventDefault(); $(this).tab('show'); });
		$('.sc_tabs a').click(function (e) {e.preventDefault(); $(this).tab('show'); });
		
			$(document).mouseup(function (e) {
			    var container = $("#kad-menu-search-popup");
				if (!container.is(e.target) && container.has(e.target).length === 0) {
			        $('#kad-menu-search-popup.in').collapse('hide');
			    }
			});
			$('#kad-menu-search-popup').on('shown.bs.collapse', function () {
			   $('.kt-search-container .search-query').focus();
			});
		$('.kt_typed_element').each(function() {
				var first = $(this).data('first-sentence'),
					second = $(this).data('second-sentence'),
					third = $(this).data('third-sentence'),
					fourth = $(this).data('fourth-sentence'),
					loopeffect = $(this).data('loop'),
					speed = $(this).data('speed'),
					startdelay = $(this).data('start-delay'),
					linecount = $(this).data('sentence-count');
					if(startdelay == null) {startdelay = 500;}
					if(linecount == '1'){
						var options = {
					      strings: [first],
					      typeSpeed: speed,
					      startDelay: startdelay,
					      loop: loopeffect,
					  }
			    	}else if(linecount == '3'){
						var options = {
					      strings: [first, second, third],
					      typeSpeed: speed,
					      startDelay: startdelay,
					      loop: loopeffect,
					  }
			    	} else if(linecount == '4'){
			    		var options = {
					      strings: [first, second, third, fourth],
					      typeSpeed: speed,
					      startDelay: startdelay,
					      loop: loopeffect,
					  }
			    	} else {
			    		var options = {
					      strings: [first, second],
					      typeSpeed: speed,
					      startDelay: startdelay,
					      loop: loopeffect,
					  }
			    	}
				$(this).appear(function() {
					$(this).typed(options);
				},{accX: 0, accY: -25});
      	});
		$(".videofit").fitVids();
		$(".embed-youtube").fitVids();
		$('.kt-m-hover').bind('touchend', function(e) {
	        $(this).toggleClass('kt-mobile-hover');
	        $(this).toggleClass('kt-mhover-inactive');
	    });

		$('.collapse-next').click(function (e) {
			//e.preventDefault();
		    var $target = $(this).siblings('.sf-dropdown-menu');
		     if($target.hasClass('in') ) {
		    	$target.collapse('toggle');
		    	$(this).removeClass('toggle-active');
		    } else {
		    	$target.collapse('toggle');
		    	$(this).addClass('toggle-active');
		    }
		});
	// Lightbox
	function kt_check_images( index, element ) {
			return /(png|jpg|jpeg|gif|tiff|bmp)$/.test(
				$( element ).attr( 'href' ).toLowerCase().split( '?' )[0].split( '#' )[0]
			);
		}

		function kt_find_images() {
			$( 'a[href]' ).filter( kt_check_images ).attr( 'data-rel', 'lightbox' );
		}
		kt_find_images();
		
		$.extend(true, $.magnificPopup.defaults, {
			tClose: '',
			tLoading: light_load, // Text that is displayed during loading. Can contain %curr% and %total% keys
			gallery: {
				tPrev: '', // Alt text on left arrow
				tNext: '', // Alt text on right arrow
				tCounter: light_of // Markup for "1 of 7" counter
			},
			image: {
				tError: light_error, // Error message when image could not be loaded
				titleSrc: function(item) {
					return item.el.find('img').attr('alt');
					}
				}
		});
		$("a[rel^='lightbox']").magnificPopup({type:'image'});
		$("a[data-rel^='lightbox']").magnificPopup({type:'image'});
		$('.kad-light-gallery').each(function(){
			$(this).find('a[rel^="lightbox"]').magnificPopup({
				type: 'image',
				gallery: {
					enabled:true
					},
					image: {
						titleSrc: 'title'
					},
				});
		});
		$('.kad-light-gallery').each(function(){
			$(this).find("a[data-rel^='lightbox']").magnificPopup({
				type: 'image',
				gallery: {
					enabled:true
					},
					image: {
						titleSrc: 'title'
					}
				});
		});
		$('.kad-light-wp-gallery').each(function(){
			$(this).find('a[rel^="lightbox"]').magnificPopup({
				type: 'image',
				gallery: {
					enabled:true
					},
					image: {
						titleSrc: function(item) {
						return item.el.find('img').attr('alt');
						}
					}
				});
		});
		$('.kad-light-wp-gallery').each(function(){
			$(this).find('a[data-rel^="lightbox"]').magnificPopup({
				type: 'image',
				gallery: {
					enabled:true
					},
					image: {
						titleSrc: function(item) {
						return item.el.find('img').attr('alt');
						}
					}
				});
		});
		$('.kad-light-mosaic-gallery').each(function(){
			$(this).find('a[data-rel^="lightbox"]').magnificPopup({
				type: 'image',
				gallery: {
					enabled:true
					},
					image: {
						titleSrc: function(item) {
						return item.el.siblings('img').attr('alt');
						}
					}
				});
		});
		$("a.pvideolight").magnificPopup({type:'iframe'});
		$("a.ktvideolight").magnificPopup({type:'iframe'});
		$(".no-lightbox").magnificPopup({
		disableOn: function() {
		 return false;
		}
		});
		function kad_infintescroll_newelements() {
			$("a[rel^='lightbox']").magnificPopup({type:'image'});
		$("a[data-rel^='lightbox']").magnificPopup({type:'image'});
		$('.kad-light-gallery').each(function(){
			$(this).find('a[rel^="lightbox"]').magnificPopup({
				type: 'image',
				gallery: {
					enabled:true
					},
					image: {
						titleSrc: 'title'
					},
				});
		});
		$('.kad-light-gallery').each(function(){
			$(this).find("a[data-rel^='lightbox']").magnificPopup({
				type: 'image',
				gallery: {
					enabled:true
					},
					image: {
						titleSrc: 'title'
					}
				});
		});
		$('.kad-light-wp-gallery').each(function(){
			$(this).find('a[rel^="lightbox"]').magnificPopup({
				type: 'image',
				gallery: {
					enabled:true
					},
					image: {
						titleSrc: function(item) {
						return item.el.find('img').attr('alt');
						}
					}
				});
		});
		$('.kad-light-wp-gallery').each(function(){
			$(this).find("a[data-rel^='lightbox']").magnificPopup({
				type: 'image',
				gallery: {
					enabled:true
					},
					image: {
						titleSrc: function(item) {
						return item.el.find('img').attr('alt');
						}
					}
				});
		});
		}
		$(window).on("infintescrollnewelements", function( event ) {kad_infintescroll_newelements();});
			// Custom Select
		$('#archive-orderby').customSelect();
		if( $(window).width() > 790 && !kt_isMobile.any() ) {
			$('.kad-select').select2({minimumResultsForSearch: -1 });
			$('.variations td.product_value select').select2({minimumResultsForSearch: -1 });
			$('.woocommerce-ordering .orderby').select2({minimumResultsForSearch: -1 });
			$('.component_options_select').select2({minimumResultsForSearch: -1 });
			$( '.component' ).on( 'wc-composite-item-updated', function() {
				$('select').select2("destroy");
				$('select').select2({minimumResultsForSearch: -1 });
			});
		} else {
			$('.kad-select').customSelect();
			$('.woocommerce-ordering .orderby').customSelect();
			$('.variations td.product_value select').customSelect();
		}
		var select2select = $('body').attr('data-jsselect');
		if( $(window).width() > 790 && !kt_isMobile.any() && (select2select == 1 )) {
			$('select').select2({minimumResultsForSearch: -1 });
			$('select.country_select').select2();
			$('select.state_select').select2();
			$('.kt-no-select2').each(function(){
				$(this).select2('destroy'); 
			});
		}

	if ($('.tab-pane .kad_product_wrapper').length) {
		var $container = $('.kad_product_wrapper');
		$('.sc_tabs').on('shown.bs.tab', function  (e) {
			$container.isotopeb({masonry: {columnWidth: '.kad_product'}, transitionDuration: '0.8s'});
		});
	}
	if ($('.panel-body .kad_product_wrapper').length) {
		var $container = $('.kad_product_wrapper');
		$('.panel-group').on('shown.bs.collapse', function  (e) {
		$container.isotopeb({masonry: {columnWidth: '.kad_product'}, transitionDuration: '0.8s'});
		});
		$('.panel-group').on('hidden.bs.collapse', function  (e) {
			$container.isotopeb({masonry: {columnWidth: '.kad_product'}, transitionDuration: '0.8s'});
		});
	}
	// anchor scroll
	
		$('.kad_fullslider_arrow').localScroll();
		var stickyheader = $('body').attr('data-sticky'),
		header = $('#kad-banner'),
		productscroll = $('body').attr('data-product-tab-scroll');
		if(productscroll == 1 && $(window).width() > 992){
			if(stickyheader == 1) {var offset_h = $(header).height() + 100; } else { var offset_h = 100;}
			$('.woocommerce-tabs').localScroll({offset: -offset_h});
		}

	// Sticky Header Varibles
	var stickyheader = $('body').attr('data-sticky'),
		shrinkheader = $('#kad-banner').attr('data-header-shrink'),
		mobilestickyheader = $('#kad-banner').attr('data-mobile-sticky'),
		win = $(window),
		header = $('.stickyheader #kad-banner'),
		headershrink = $('.stickyheader #kad-banner #kad-shrinkheader'),
		logo = $('.stickyheader #kad-banner #logo a, .stickyheader #kad-banner #logo a #thelogo'),
		logobox = $('.stickyheader #kad-banner #logo a img'),
		menu = $('.stickyheader #kad-banner .nav-main ul.sf-menu > li > a'),
		content = $('.stickyheader .wrap'),
		mobilebox = $('.stickyheader .mobile-stickyheader .mobile_menu_collapse'),
		headerouter = $('.stickyheader .sticky-wrapper'),
		shrinkheader_height = $('#kad-banner').attr('data-header-base-height'),
		topOffest = $('body').hasClass('admin-bar') ? 32 : 0;

	function kad_sticky_header() {
		var header_height = $(header).height(),
		topbar_height = $('.stickyheader #kad-banner #topbar').height();
		set_height = function() {
				var scrollt = win.scrollTop(),
                newH = 0;
                if(scrollt < 0) {
                	scrollt = 0;
                }
                if(scrollt < shrinkheader_height/1) {
                    newH = shrinkheader_height - scrollt/2;
                    header.removeClass('header-scrolled');
                }else{
                    newH = shrinkheader_height/2;
                    header.addClass('header-scrolled');
                }
                menu.css({'height': newH + 'px', 'lineHeight': newH + 'px'});
                headershrink.css({'height': newH + 'px', 'lineHeight': newH + 'px'});
                header.css({'height': newH + topbar_height + 'px'});
                logo.css({'height': newH + 'px', 'lineHeight': newH + 'px'});
                logobox.css({'maxHeight': newH + 'px'});
            };
		if (shrinkheader == 1 && stickyheader == 1 && $(window).width() > 992 ) {
	        header.css({'top': topOffest + 'px'});
			header.sticky({topSpacing:topOffest});
			win.scroll(set_height);
		} else if( stickyheader == 1 && $(window).width() > 992) {
			header.css({'height': header_height + 'px'});
			header.css({'top': topOffest + 'px'});
			header.sticky({topSpacing:topOffest});
		} else if (shrinkheader == 1 && stickyheader == 1 && mobilestickyheader == 1 && $(window).width() < 992 ) {
			header.css({'height': 'auto'});
			header.sticky({topSpacing:topOffest});
			var win_height = $(window).height();
			var mobileh_height = shrinkheader_height/2;
			mobilebox.css({'maxHeight': win_height - mobileh_height + 'px'});
		} else {
			header.css({'position':'static'});
			content.css({'padding-top': '15px'});
			header.css({'height': 'auto'});
		}

	}
	header.imagesLoadedn( function() {
		kad_sticky_header();
	});

	function kad_mobile_sticky_header() {
		var mobile_header_height = $('#kad-mobile-banner').height(),
			topOffest = $('body').hasClass('admin-bar') ? 32 : 0,
			mobilestickyheader = $('#kad-mobile-banner').attr('data-mobile-header-sticky');
			if($(window).width() < 600 && $('body').hasClass('admin-bar')) {
				topOffest = 0;
			} else if ($(window).width() < 782 && $('body').hasClass('admin-bar')) {
				topOffest = 46;
			}
		if (mobilestickyheader == 1) {
			$('#kad-mobile-banner').sticky({topSpacing:topOffest});
			var window_height = $(window).height();
			$('#mg-kad-mobile-nav #mh-mobile_menu_collapse').css({'maxHeight': window_height - mobile_header_height + 'px'});
		}
	}
	if( $('#kad-mobile-banner').length) {
		kad_mobile_sticky_header();
	}
	//Superfish Menu
		$('ul.sf-menu').superfish({
			delay:       200,
			animation:   {opacity:'show',height:'show'},
			speed:       'fast'
		});
	function kad_fullwidth_panel() {
		$('.kt-panel-row-stretch').each(function(){
			var margins = $(window).width() - $(this).parent('.panel-grid').width();
			$(this).css({'padding-left': margins/2 + 'px'});
			$(this).css({'padding-right': margins/2 + 'px'});
			$(this).css({'margin-left': '-' + margins/2 + 'px'});
			$(this).css({'margin-right': '-' + margins/2 + 'px'});
			$(this).css({'visibility': 'visible'});
		});
		$('.panel-row-style-wide-grey').each(function(){
			var margins = $(window).width() - $(this).parent('.panel-grid').width();
			$(this).css({'padding-left': margins/2 + 'px'});
			$(this).css({'padding-right': margins/2 + 'px'});
			$(this).css({'margin-left': '-' + margins/2 + 'px'});
			$(this).css({'margin-right': '-' + margins/2 + 'px'});
			$(this).css({'visibility': 'visible'});
		});
		$('.panel-row-style-wide-feature').each(function(){
			var margins = $(window).width() - $(this).parent('.panel-grid').width();
			$(this).css({'padding-left': margins/2 + 'px'});
			$(this).css({'padding-right': margins/2 + 'px'});
			$(this).css({'margin-left': '-' + margins/2 + 'px'});
			$(this).css({'margin-right': '-' + margins/2 + 'px'});
			$(this).css({'visibility': 'visible'});
		});
		$('.panel-row-style-wide-parallax').each(function(){
			var margins = $(window).width() - $(this).parent('.panel-grid').width();
			$(this).css({'padding-left': margins/2 + 'px'});
			$(this).css({'padding-right': margins/2 + 'px'});
			$(this).css({'margin-left': '-' + margins/2 + 'px'});
			$(this).css({'margin-right': '-' + margins/2 + 'px'});
			$(this).css({'visibility': 'visible'});
		});
		$('.kt-panel-row-full-stretch').each(function(){
			var margins = $(window).width() - $(this).parent('.panel-grid').width();
			$(this).css({'margin-left': '-' + margins/2 + 'px'});
			$(this).css({'margin-right': '-' + margins/2 + 'px'});
			$(this).css({'width': + $(window).width() + 'px'});
			$(this).css({'visibility': 'visible'});
		});
		$('.kt-custom-row-full-stretch').each(function(){
			var margins = $(window).width() - $(this).parents('#content').width();
			$(this).css({'margin-left': '-' + margins/2 + 'px'});
			$(this).css({'margin-right': '-' + margins/2 + 'px'});
			$(this).css({'width': + $(window).width() + 'px'});
			$(this).css({'visibility': 'visible'});
		});
		$('.kt-custom-row-full').each(function(){
			var margins = $(window).width() - $(this).parents('#content').width();
			$(this).css({'padding-left': margins/2 + 'px'});
			$(this).css({'padding-right': margins/2 + 'px'});
			$(this).css({'margin-left': '-' + margins/2 + 'px'});
			$(this).css({'margin-right': '-' + margins/2 + 'px'});
			$(this).css({'visibility': 'visible'});
		});
	}
	kad_fullwidth_panel();
	$(window).on("debouncedresize", function( event ) {kad_fullwidth_panel();});
	 
	 //aminiate in
    var $animate = $('body').attr('data-animate');
    if( $animate == 1 && $(window).width() > 790) {
            //fadein
        $('.kad-animation').each(function() {
            $(this).appear(function() {
            	$(this).delay($(this).attr('data-delay')).animate({'opacity' : 1, 'top' : 0},800,'swing');},{accX: 0, accY: -25},'easeInCubic');
        });
        $('.kt-animate-fade-in-up').each(function() {
            $(this).appear(function() {
            	$(this).animate({'opacity' : 1, 'top' : 0},900,'swing');},{accX: 0, accY: -25},'easeInCubic');
        });
        $('.kt-animate-fade-in-down').each(function() {
            $(this).appear(function() {
            	$(this).animate({'opacity' : 1, 'top' : 0},900,'swing');},{accX: 0, accY: -25},'easeInCubic');
        });
        $('.kt-animate-fade-in-left').each(function() {
            $(this).appear(function() {
            	$(this).animate({'opacity' : 1, 'left' : 0},900,'swing');},{accX: -25, accY: 0},'easeInCubic');
        });
        $('.kt-animate-fade-in-right').each(function() {
            $(this).appear(function() {
            	$(this).animate({'opacity' : 1, 'right' : 0},900,'swing');},{accX: -25, accY: 0},'easeInCubic');
        });
        $('.kt-animate-fade-in').each(function() {
            $(this).appear(function() {
            	$(this).animate({'opacity' : 1 },900,'swing');});
        });
    } else {
    	$('.kad-animation').each(function() {
    		$(this).animate({'opacity' : 1, 'top' : 0});
    	});
    	$('.kt-animate-fade-in-up').each(function() {
    		$(this).animate({'opacity' : 1, 'top' : 0});
    	});
    	$('.kt-animate-fade-in-down').each(function() {
    		$(this).animate({'opacity' : 1, 'top' : 0});
    	});
    	$('.kt-animate-fade-in-left').each(function() {
    		$(this).animate({'opacity' : 1, 'left' : 0});
    	});
    	$('.kt-animate-fade-in-right').each(function() {
    		$(this).animate({'opacity' : 1, 'right' : 0});
    	});
    	$('.kt-animate-fade-in').each(function() {
    		$(this).animate({'opacity' : 1});
    	});
    }
    
     //init Flexslider
     $('.kt-flexslider').each(function(){

	 	var flex_speed = $(this).data('flex-speed'),
		flex_animation = $(this).data('flex-animation'),
		flex_initdelay = $(this).data('flex-initdelay'),
		flex_animation_speed = $(this).data('flex-anim-speed'),
		flex_auto = $(this).data('flex-auto');
		if(flex_initdelay == null) {flex_initdelay = 0;}
	 	$(this).flexslider({
	 		animation: flex_animation,
			animationSpeed: flex_animation_speed,
			slideshow: flex_auto,
			initDelay: flex_initdelay,
			slideshowSpeed: flex_speed,
			start: function ( slider ) {
				slider.removeClass( 'loading' );
			}
		});
    });

     //init isotope
    $('.init-isotope').each(function(){
    	var isocontainer = $(this),
    	iso_selector = $(this).data('iso-selector'),
    	iso_style = $(this).data('iso-style'),
    	iso_filter = $(this).data('iso-filter');
		isocontainer.kt_imagesLoaded( function(){
			isocontainer.isotopeb({masonry: {columnWidth: iso_selector}, layoutMode:iso_style, itemSelector: iso_selector, transitionDuration: '0.8s'});
			if(isocontainer.attr('data-fade-in') == 1) {
				var isochild = isocontainer.find('.kt_item_fade_in');
				isochild.css('opacity',0);
					isochild.each(function(i){
									$(this).delay(i*150).animate({'opacity':1},350);
					});
			}
			if(iso_filter == true) {
				var thisparent = isocontainer.parents('.main');
				var thisfilters = thisparent.find('#filters');
				if(thisfilters.length) {
				thisfilters.on( 'click', 'a', function( event ) {
						var filtr = $(this).attr('data-filter');
						isocontainer.isotopeb({ filter: filtr });
						  return false; 
					});
					var $optionSets = $('#options .option-set'),
	          		$optionLinks = $optionSets.find('a');	
					$optionLinks.click(function(){ 
						var $this = $(this); if ( $this.hasClass('selected') ) {return false;}
						var $optionSet = $this.parents('.option-set'); $optionSet.find('.selected').removeClass('selected'); $this.addClass('selected');
					});
				}
			}
		});
				
	});
 $('.init-mosaic-isotope').each(function(){
    	var isocontainer = $(this),
    	iso_selector = $(this).data('iso-selector'),
    	iso_style = $(this).data('iso-style'),
    	iso_filter = $(this).data('iso-filter');
		isocontainer.imagesLoadedn( function(){
			isocontainer.isotopeb({layoutMode:iso_style, itemSelector: iso_selector, transitionDuration: '.8s'});
			if(isocontainer.attr('data-fade-in') == 1) {
				var isochild = isocontainer.find('.kt_item_fade_in');
				isochild.css('opacity',0);
					isochild.each(function(i){
									$(this).delay(i*150).animate({'opacity':1},350);
					});
			}
			if(iso_filter == true) {
				var thisparent = isocontainer.parents('.main');
				var thisfilters = thisparent.find('#filters');
				if(thisfilters.length) {
				thisfilters.on( 'click', 'a', function( event ) {
						var filtr = $(this).attr('data-filter');
						isocontainer.isotopeb({ filter: filtr });
						  return false; 
					});
					var $optionSets = $('#options .option-set'),
	          		$optionLinks = $optionSets.find('a');	
					$optionLinks.click(function(){ 
						var $this = $(this); if ( $this.hasClass('selected') ) {return false;}
						var $optionSet = $this.parents('.option-set'); $optionSet.find('.selected').removeClass('selected'); $this.addClass('selected');
					});
				}
			}
		});
				
	});
// Toggle 
if ($('.kt_product_toggle_container').length) {
			var thistoggleon = $('.kt_product_toggle_container .toggle_list'),
			thistoggleoff = $('.kt_product_toggle_container .toggle_grid');
			thistoggleon.click(function(){
					if ( $(this).hasClass('toggle_active') ) {return false;}
					$(this).parents('.kt_product_toggle_container').find('.toggle_active').removeClass('toggle_active');
					$(this).addClass('toggle_active');
					if ($('.kad_product_wrapper').length) { 
						$('.kad_product_wrapper').addClass('shopcolumn1');
						$('.kad_product_wrapper').addClass('tfsinglecolumn');
						var $container = $('.kad_product_wrapper'),
						iso_selector = $('.kad_product_wrapper').data('iso-selector');
						$container.isotopeb({masonry: {columnWidth: iso_selector}, transitionDuration: '.4s'});
					}
					  return false; 
				});
			thistoggleoff.click(function(){
					if ( $(this).hasClass('toggle_active') ) {return false;}
					$(this).parents('.kt_product_toggle_container').find('.toggle_active').removeClass('toggle_active');
					$(this).addClass('toggle_active');
					if ($('.kad_product_wrapper').length) { 
						$('.kad_product_wrapper').removeClass('shopcolumn1');
						$('.kad_product_wrapper').removeClass('tfsinglecolumn');
						var $container = $('.kad_product_wrapper'),
						iso_selector = $('.kad_product_wrapper').data('iso-selector');
						$container.isotopeb({masonry: {columnWidth: iso_selector}, transitionDuration: '.4s'});
					}
					  return false; 
				});
			}
	if ($('.kt_product_toggle_container_list').length) {
			var thistoggleon = $('.kt_product_toggle_container_list .toggle_list'),
			thistoggleoff = $('.kt_product_toggle_container_list .toggle_grid');
			thistoggleon.click(function(){
					if ( $(this).hasClass('toggle_active') ) {return false;}
					$(this).parents('.kt_product_toggle_container_list').find('.toggle_active').removeClass('toggle_active');
					$(this).addClass('toggle_active');
					if ($('.kad_product_wrapper').length) { 
						$('.kad_product_wrapper').addClass('shopcolumn1');
						$('.kad_product_wrapper').addClass('tfsinglecolumn');
						$('.kad_product_wrapper').removeClass('kt_force_grid_three');
						var $container = $('.kad_product_wrapper'),
						iso_selector = $('.kad_product_wrapper').data('iso-selector');
						$container.isotopeb({masonry: {columnWidth: iso_selector}, transitionDuration: '.4s'});
					}
					  return false; 
				});
			thistoggleoff.click(function(){
					if ( $(this).hasClass('toggle_active') ) {return false;}
					$(this).parents('.kt_product_toggle_container_list').find('.toggle_active').removeClass('toggle_active');
					$(this).addClass('toggle_active');
					if ($('.kad_product_wrapper').length) { 
						$('.kad_product_wrapper').removeClass('shopcolumn1');
						$('.kad_product_wrapper').removeClass('tfsinglecolumn');
						$('.kad_product_wrapper').addClass('kt_force_grid_three');
						var $container = $('.kad_product_wrapper'),
						iso_selector = $('.kad_product_wrapper').data('iso-selector');
						$container.isotopeb({masonry: {columnWidth: iso_selector}, transitionDuration: '.4s'});
					}
					  return false; 
				});
	}
if ($('.woocommerce-tabs .panel .init-isotope').length) {
		var $container = $('.init-isotope'),
		iso_selector = $('.init-isotope').data('iso-selector');
		function woo_refreash_iso(){
			$container.isotopeb({masonry: {columnWidth: iso_selector}, transitionDuration: '0s'});
		}
	$('.woocommerce-tabs ul.tabs li a' ).click( function() {
		setTimeout(woo_refreash_iso, 50);
	});
}
if ($('.panel-body .init-isotope').length) {
		var $container = $('.init-isotope'),
		iso_selector = $('.init-isotope').data('iso-selector');
		$('.panel-group').on('shown.bs.collapse', function  (e) {
		$container.isotopeb({masonry: {columnWidth: iso_selector}, transitionDuration: '0s'});
		});
	}
	if ($('.tab-pane .init-isotope').length) {
		$('.tab-pane .init-isotope').each(function(){
		var $container = $(this),
		iso_selector = $(this).data('iso-selector');
		$('.sc_tabs').on('shown.bs.tab', function  (e) {
			$container.isotopeb({masonry: {columnWidth: iso_selector}, transitionDuration: '0s'});
		});
	});
	}
		 //init carousel
     jQuery('.initcaroufedsel').each(function(){
     	  var container = jQuery(this);
          var wcontainerclass = container.data('carousel-container'), 
          cspeed = container.data('carousel-speed'), 
          ctransition = container.data('carousel-transition'),
          cscroll = container.data('carousel-scroll'), 
          cauto = container.data('carousel-auto'),
          carouselid = container.data('carousel-id'),
          ss = container.data('carousel-ss'), 
          xs = container.data('carousel-xs'),
          sm = container.data('carousel-sm'),
          md = container.data('carousel-md');
          if(cscroll == '') {cscroll = null;}
          var wcontainer = jQuery(wcontainerclass);
          function getUnitWidth() {var width;
          if(jQuery(window).width() <= 540) {
          width = wcontainer.width() / ss;
          } else if(jQuery(window).width() <= 768) {
          width = wcontainer.width() / xs;
          } else if(jQuery(window).width() <= 990) {
          width = wcontainer.width() / sm;
          } else {
          width = wcontainer.width() / md;
          }
          return width;
          }

          function setWidths() {
          var unitWidth = getUnitWidth() -1;
          container.children().css({ width: unitWidth });
          }
          setWidths();
          function initCarousel() {
          container.carouFredSel({
            scroll: {items:cscroll, easing: "swing", duration: ctransition, pauseOnHover : true}, 
            auto: {play: cauto, timeoutDuration: cspeed},
              prev: '#prevport-'+carouselid, next: '#nextport-'+carouselid, pagination: false, swipe: true, items: {visible: null}
            });
	      }
	      container.imagesLoadedn( function(){
	      	wcontainer.animate({'opacity' : 1});
          	initCarousel();
          	wcontainer.parent().removeClass('carousel_outerrim_load');
      	});
          	jQuery(window).on("debouncedresize", function( event ) {
          		container.trigger("destroy");
          		setWidths();
          		initCarousel();
          	});
    });
		//init carouselslider
	    jQuery('.initcarouselslider').each(function(){
	     	  var container = jQuery(this);
	          var wcontainerclass = container.data('carousel-container'), 
	          cspeed = container.data('carousel-speed'), 
	          ctransition = container.data('carousel-transition'),
	          cauto = container.data('carousel-auto'),
	          carouselid = container.data('carousel-id'),
	          carheight = container.data('carousel-height'),
	          align = 'center';
	          var wcontainer = jQuery(wcontainerclass);

	          function setWidths() {
	            var unitWidth = container.width();
	            container.children().css({ width: unitWidth });
	              if(jQuery(window).width() <= 768) {
	                  carheight = null;
	                  container.children().css({ height: 'auto' });
	              }
	          }
	          setWidths();
	          function initCarouselslider() {
	            container.carouFredSel({
	              width: '100%',
	              height: carheight,
	              align: align,
	              auto: {play: cauto, timeoutDuration: cspeed},
	              scroll: {items : 1,easing: 'quadratic'},
	              items: {visible: 1,width: 'variable'},
	              prev: '#prevport-'+carouselid,
	              next: '#nextport-'+carouselid,
	              swipe: {onMouse: false,onTouch: true},
	            });
		      }
		      container.imagesLoadedn( function(){
	          	initCarouselslider();
	            wcontainer.animate({'opacity' : 1});
	            wcontainer.css({ height: 'auto' });
	            wcontainer.parent().removeClass('loading');
	      	});
	          	jQuery(window).on("debouncedresize", function( event ) {
	          		container.trigger("destroy");
	          		setWidths();
	          		initCarouselslider();
	          	});
	    });
    //init image carousel
    jQuery('.initimagecarousel').each(function(){
     	  var container = jQuery(this);
          var wcontainerclass = container.data('carousel-container'), 
          cspeed = container.data('carousel-speed'), 
          ctransition = container.data('carousel-transition'),
          cauto = container.data('carousel-auto'),
          carouselid = container.data('carousel-id'),
          carheight = container.data('carousel-height'),
          align = false;
          var wcontainer = jQuery(wcontainerclass);

          function setWidths() {
          	if(jQuery(window).width() <= 767) {
            	align = 'center';
                carheight = null;
                var unitWidth = jQuery(window).width() -10;
                container.children().css({ width: unitWidth });
                container.children().css({ height: 'auto' });
            }
          }
          setWidths();
        function initImageCarousel() {
            container.carouFredSel({
				width: '100%',
				height: carheight,
				align: align,
				auto: {play: cauto, timeoutDuration: cspeed},
				scroll: {items : 1,easing: 'quadratic'},
				items: {visible: 1, width: 'variable'},
                prev: '#prevport-'+carouselid, 
                next: '#nextport-'+carouselid,
                swipe: {onMouse: true,onTouch: true},
                onCreate: function() {
					jQuery('.gallery-carousel').css('positon','static');
				}
            });
	      }
	    container.imagesLoadedn( function(){
          	initImageCarousel();
            wcontainer.animate({'opacity' : 1});
            wcontainer.css({ height: 'auto' });
            wcontainer.parent().removeClass('loading');
      	});
          	jQuery(window).on("debouncedresize", function( event ) {
          		container.trigger("destroy");
          		setWidths();
          		initImageCarousel();
          	});
    });

});
if( kt_isMobile.any() ) {
jQuery(document).ready(function ($) {
	
	matchMedia('only screen and (max-width: 480px)').addListener(function(list){
		$('select.hasCustomSelect').removeAttr("style");
		$('select.hasCustomSelect').css({'width':'250px'});
    	$('.kad-select.customSelect').remove();
    	$('select.kad-select').customSelect();
    	$('.customSelectInner').css('width','100%');
	});
		$('.caroufedselclass').tswipe({
			              excludedElements:"button, input, select, textarea, .noSwipe",
						   tswipeLeft: function() {
							$('.caroufedselclass').trigger('next', 1);
						  },
						  tswipeRight: function() {
							$('.caroufedselclass').trigger('prev', 1);
						  },
						  tap: function(event, target) {
							window.open(jQuery(target).closest('.grid_item').find('a').attr('href'), '_self');
						  }
		});
		$('.caroufedselgallery').tswipe({
			              excludedElements:"button, input, select, textarea, .noSwipe",
						   tswipeLeft: function() {
							$('.caroufedselgallery').trigger('next', 1);
						  },
						  tswipeRight: function() {
							$('.caroufedselgallery').trigger('prev', 1);
						  },
						  tap: function(event, target) {
						  	var tid = $(target).data("grid-id");
							$(target).closest('a.lightboxhover').magnificPopup('open', tid);
						  }
		});
		
	});

}
if( !kt_isMobile.any() ) {
	jQuery( window ).load(function () {
				jQuery(this).stellar({
					   	responsive: true,
					   	horizontalScrolling: false,
						verticalOffset: 40
				});
	});
}
jQuery( window ).load(function () {
	jQuery('body').animate({'opacity' : 1});
});
