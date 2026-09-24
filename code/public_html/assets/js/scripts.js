$(document).ready(function() {
    'use strict';
    /*-----------------------------------------------------------------------------------*/
    /*	SCROLL NAVIGATION HIGHLIGHT
    /*-----------------------------------------------------------------------------------*/
//    var headerWrapper = parseInt($('.navbar').height(), 10);
//    var header_height = $('.navbar').height();
//    var shrinked_header_height = 70;
//    var firstStyle=
//    {
//        'padding-top':''+shrinked_header_height+'px',
//        'margin-top':'-'+shrinked_header_height+'px'
//    };
//    $('.onepage section').css(firstStyle);
//    var secondStyle=
//    {
//      'padding-top':''+header_height+'px',
//      'margin-top':'-'+header_height+'px'
//    };
//    $('.onepage section:first-of-type').css(secondStyle);
//    var offsetTolerance = -(header_height);
//    //Detecting user's scroll
//    $(window).scroll(function() {
//        //Check scroll position
//        var scrollPosition = parseInt($(this).scrollTop(), 10);
//        //Move trough each menu and check its position with scroll position then add current class
//        $('.onepage .navbar ul.navbar-nav a').each(function() {
//            var thisHref = $(this).attr('href');
//            var thisTruePosition = parseInt($(thisHref).offset().top, 10);
//            var thisPosition = thisTruePosition - headerWrapper - offsetTolerance;
//            if (scrollPosition >= thisPosition) {
//                $('.current').removeClass('current');
//                $('.navbar ul.navbar-nav a[href=' + thisHref + ']').parent('li').addClass('current');
//            }
//        });
//        //If we're at the bottom of the page, move pointer to the last section
//        var bottomPage = parseInt($(document).height(), 10) - parseInt($(window).height(), 10);
//        if (scrollPosition == bottomPage || scrollPosition >= bottomPage) {
//            $('.current').removeClass('current');
//            $('.onepage .navbar ul.navbar-nav a:last').parent('li').addClass('current');
//        }
//    });
    /*-----------------------------------------------------------------------------------*/
    /*	MENU
	/*-----------------------------------------------------------------------------------*/
    $('.js-activated').dropdownHover({
        instantlyCloseOthers: false,
        delay: 0
    }).dropdown();
    $('.btn.responsive-menu').on('click', function() {
        $(this).toggleClass('opn');
    });
    $('.onepage .navbar .nav li a').on('click', function() {
        $('.navbar .navbar-collapse.in').collapse('hide');
        $('.btn.responsive-menu').removeClass('opn');
    });
    $('.offset').css('padding-top', $('.navbar').height() + 'px');
    /*-----------------------------------------------------------------------------------*/
	/*	STICKY FILTER HIGHLIGHT
	/*-----------------------------------------------------------------------------------*/
//		var stickyWrapper		= parseInt($('#sticky-filter').height(), 10);
//		var stickyOffsetTolerance	= 70;
//		//Detecting user's scroll
//		$(window).scroll(function() {
//			//Check scroll position
//			var stickyScrollPosition	= parseInt($(this).scrollTop(), 10);
//			//Move trough each menu and check its position with scroll position then add current class
//			$('#sticky-filter a').each(function() {
//				var stickyThisHref				= $(this).attr('href');
//				var stickyThisTruePosition	= parseInt($(stickyThisHref).offset().top, 10);
//				var stickyThisPosition 		= stickyThisTruePosition - stickyWrapper - stickyOffsetTolerance;
//				if(stickyScrollPosition >= stickyThisPosition) {
//					$('.current').removeClass('current');
//					$('#sticky-filter a[href='+ stickyThisHref +']').parent('li').addClass('current');
//				}
//			});
//			//If we're at the bottom of the page, move pointer to the last section
//			var stickyBottomPage	= parseInt($(document).height(), 10) - parseInt($(window).height(), 10);
//			if(stickyScrollPosition == stickyBottomPage || stickyScrollPosition >= stickyBottomPage) {
//				$('.current').removeClass('current');
//				$('#sticky-filter a:last').parent('li').addClass('current');
//			}
//		});
    /*-----------------------------------------------------------------------------------*/
    /*	LOCALSCROLL
	/*-----------------------------------------------------------------------------------*/
    $('.navbar, .scroll').localScroll({
        hash: true
    });
    $('#sticky-filter ul').localScroll({
	    offset: {top:-134, left:0}
    });
    /*-----------------------------------------------------------------------------------*/
	/*	STICKY FILTER
	/*-----------------------------------------------------------------------------------*/
	$("#sticky-filter").sticky({ topSpacing: 70, className:"sfilter", responsiveBreakpoint: 0 });

    /*-----------------------------------------------------------------------------------*/
    /*	OWL CAROUSEL
	/*-----------------------------------------------------------------------------------*/
    $('.carousel-boxed').owlCarousel({
        loop: true,
        margin: 30,
        nav: true,
        navText: ['', ''],
        dots: false,
        lazyLoad:true,
        responsive: {
            0: {
                items: 1
            },
            480: {
                items: 2
            },
            768: {
                items: 2
            },
            992: {
                items: 4
            }
        },

        onRefreshed: function () {
            var h;
            $('.owl-item img').each(function( index ) {
                h = (300 * $(this).width())/234;
                $(this).height(h);
            });
        }
    });

    /*-----------------------------------------------------------------------------------*/
    /*	SWIPER
	/*-----------------------------------------------------------------------------------*/
    $('.swiper-container.gallery').each(function(){
		  $(this).swiper({
		    grabCursor: true,
		    slidesPerView: 'auto',
		    wrapperClass: 'swiper',
		    slideClass: 'item',
		    offsetPxBefore: 0,
		    offsetPxAfter: 0
		  });

		  var $swipers = $(this);

		$swipers.siblings('.arrow-left').on( "click", function(){
		$swipers.data('swiper').swipeTo($swipers.data('swiper').activeIndex-1);
		return false;
		});
		$swipers.siblings('.arrow-right').on( "click", function(){
		$swipers.data('swiper').swipeTo($swipers.data('swiper').activeIndex+1);
		return false;
		});
	});
    /*-----------------------------------------------------------------------------------*/
    /*	FITVIDS VIDEO
	/*-----------------------------------------------------------------------------------*/
    $('.player').fitVids();
    /*-----------------------------------------------------------------------------------*/
    /*	IMAGE ICON HOVER
	/*-----------------------------------------------------------------------------------*/

    // Tooltip only Text
    function article_in($this){
        $($this).toggleClass('ahover');
    }
    function article_out($this){
            $($this).removeClass('ahover');
    }


    $('.icon-overlay a').prepend('<span class="icn-more"></span>');
    $('.icon-player a').prepend('<span class="icn-play"></span>');

    if ('ontouchstart' in document.documentElement) {

        $('a.article_dest').on('touchstart', function(e){
            article_in(this);
        });

        $('a.article_dest').on('touchend', function(e){
            article_out(this);
        });
//        $('a.article_dest').on('touchmove', function(e){
//            article_in(this);
//        });

        $('.main.icon-player a').on('touchstart', function(e){
            article_in(this);
        });

        $('.main.icon-player a').on('touchend', function(e){
            article_out(this);
        });
//        $('.main.icon-player a').on('touchmove', function(e){
//            article_in(this);
//        });

    }else{
            $('a.article_dest').hover(function(){
                article_in(this);
            }, function() {
                article_out(this);
            });

            $('.main.icon-player a').hover(function(){
                article_in(this);
            }, function() {
                article_out(this);
            });
    }




        /*-----------------------------------------------------------------------------------*/
    /*	Youtube Player API
    /*-----------------------------------------------------------------------------------*/
    //<iframe width="560" height="315" src="//www.youtube.com/embed/h4l4HGiR7sE?rel=0&modestbranding=1&autohide=1&showinfo=0&controls=0&autoplay=1" frameborder="0" allowfullscreen></iframe>

    function loadScript(src, callback)
    {
        var s,
            r,
            t;
        r = false;
        s = document.createElement('script');
        s.type = 'text/javascript';
        s.src = src;
        s.onload = s.onreadystatechange = function() {
          //console.log( this.readyState ); //uncomment this line to see which ready states are called.
          if ( !r && (!this.readyState || this.readyState == 'complete') )
          {
            r = true;
            callback();
          }
        };
        t = document.getElementsByTagName('script')[0];
        t.parentNode.insertBefore(s, t);
    }

    function create_yt_api(ID){

        loadScript("https://www.youtube.com/iframe_api", function(){
             // onYouTubeIframeAPIReady() is called when the IFrame API is ready to go.
            window.onYouTubeIframeAPIReady = function() {
                window.yt_player = new YT.Player('player', {
                    videoId: ID, // the ID of the video (mentioned above)
                    playerVars: {
                      autoplay: 1, // start automatically
                      controls: 0, // don't show the controls (we can't click them anyways)
                      modestbranding: 1, // show smaller logo
                      loop: 1, // loop when complete
                      showinfo: 0,
                      autohide: 0,
                      playsinline:1,
                      playlist: ID, // required for looping, matches the video ID
                      rel: 0
                    },
                    events: {
                        'onStateChange': onPlayerStateChange,
                        'onReady': onPlayerReady
                    }
                });
            };
        });
    }

    function onPlayerStateChange(event) {
        window.yt_player_state = event.data;
    }

    function onPlayerReady(event) {
        window.yt_player.mute();
        window.yt_player.playVideo();
    }

    /*-----------------------------------------------------------------------------------*/
    /*	REVOLUTION
    /*-----------------------------------------------------------------------------------*/
    var rev_fullScreen = 'off';
    if (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i)) {
        if(current_menu==='home'){
            rev_fullScreen = 'on';
        }
    }
    window.onload = function(){
        var api_rev = $('.tp-fullscreen').revolution({
    //        delay: 9000,
    //        startwidth: 1920,
    //        startheight: 746,
    //        hideThumbs: 0,
    //        hideArrowsOnMobile: "on",
    //        hideBulletsOnMobile: "on",
    //        fullWidth: "on",
            fullScreen: rev_fullScreen,
            soloArrowLeftHOffset: 0,
            soloArrowRightHOffset: 0,

            delay:9000,
            startwidth:1170,
            startheight:500,
                    minHeight:"320",
            hideThumbs:10,
            fullWidth:"on",
            forceFullWidth:"off",
            lazyType: 'none'
        });
    };

    /*api_rev.bind("revolution.slide.onchange",function (e, data) {
        if(data.slideIndex === 2){
            if(window.yt_player === undefined){
                create_yt_api('h4l4HGiR7sE');
                $(data.slide[0]).find('.slotholder').hide();
            }else if(window.yt_player !== undefined){
                window.yt_player.playVideo();
            }

            if(window.innerHeight > window.innerWidth){
                $('#player').removeClass('bg-video-player');
                $('#player').addClass('bg-video-mo-player');
            }else{
                $('#player').removeClass('bg-video-mo-player');
                $('#player').addClass('bg-video-player');
            }

        }else if(window.yt_player_state===1 && window.yt_player !== undefined){
            window.yt_player.pauseVideo();
        }
    });*/

    /*-----------------------------------------------------------------------------------*/
    /*	TABS
	/*-----------------------------------------------------------------------------------*/
    $('.tabs.tabs-top').easytabs({
        animationSpeed: 300,
        updateHash: false
    });
    /*-----------------------------------------------------------------------------------*/
    /*	TOGGLE
	/*-----------------------------------------------------------------------------------*/
    $('.panel-group').find('.panel-default:has(".in")').addClass('panel-active');
    $('.panel-group').on('shown.bs.collapse', function(e) {
        $(e.target).closest('.panel-default').addClass(' panel-active');
    }).on('hidden.bs.collapse', function(e) {
        $(e.target).closest('.panel-default').removeClass(' panel-active');
    });
    /*-----------------------------------------------------------------------------------*/
    /*	BACKGROUND VIDEO PARALLAX
	/*-----------------------------------------------------------------------------------*/
    $('#video-office').backgroundVideo({
        $outerWrap: $('.outer-wrap'),
        pauseVideoOnViewLoss: false,
        parallaxOptions: {
            effect: 1.9
        }
    });
    /*-----------------------------------------------------------------------------------*/
    /*	PARALLAX MOBILE
	/*-----------------------------------------------------------------------------------*/
    if (navigator.userAgent.match(/Android/i) || navigator.userAgent.match(/webOS/i) || navigator.userAgent.match(/iPhone/i) || navigator.userAgent.match(/iPad/i) || navigator.userAgent.match(/iPod/i) || navigator.userAgent.match(/BlackBerry/i)) {
        $('.parallax').addClass('mobile');
    }
    /*-----------------------------------------------------------------------------------*/
    /*	DATA REL
	/*-----------------------------------------------------------------------------------*/
    $('a[data-rel]').each(function() {
        $(this).attr('rel', $(this).data('rel'));
    });
    /*-----------------------------------------------------------------------------------*/
    /*	PRETTIFY
	/*-----------------------------------------------------------------------------------*/
    window.prettyPrint && prettyPrint()
    /*-----------------------------------------------------------------------------------*/
    /*	TOOLTIP
    /*-----------------------------------------------------------------------------------*/
    if ($("[rel=tooltip]").length) {
        $("[rel=tooltip]").tooltip();
    }
    /*-----------------------------------------------------------------------------------*/
    /* INPUT FOCUS
	/*-----------------------------------------------------------------------------------*/
    $('.comment-form input[title], .comment-form textarea').each(function () {
        if ($(this).val() === '') {
            $(this).val($(this).attr('title'));
        }

        $(this).focus(function () {
            if ($(this).val() == $(this).attr('title')) {
                $(this).val('').addClass('focused');
            }
        });
        $(this).blur(function () {
            if ($(this).val() === '') {
                $(this).val($(this).attr('title')).removeClass('focused');
            }
        });
    });
    /*-----------------------------------------------------------------------------------*/
    /* WIDTH CLASS
	/*-----------------------------------------------------------------------------------*/
    assign_bootstrap_mode();
    $(window).resize(function() {
        assign_bootstrap_mode();
    });
    function assign_bootstrap_mode() {
        var width = $(window).width();
        var mode = '';
        if (width < 768) {
            mode = "mode-xs";
        } else if (width < 992) {
            mode = "mode-sm";
        } else if (width < 1200) {
            mode = "mode-md";
        } else if (width > 1200) {
            mode = "mode-lg";
        }
        $("body").removeClass("mode-xs").removeClass("mode-sm").removeClass("mode-md").removeClass("mode-lg").addClass(mode);
    }

    /*-----------------------------------------------------------------------------------*/
	/*	FLICKR
	/*-----------------------------------------------------------------------------------*/
    $('.flickr-feed').dcFlickr({
        limit: 10,
        q: {
            id: '51789731@N07',
            lang: 'en-us',
            format: 'json',
            jsoncallback: '?'
        },
        onLoad: function() {
            $('.flickr-feed .item . a').prepend('<span class="icn-more"></span>');
        }
    });
    /*-----------------------------------------------------------------------------------*/
    /*	ISOTOPE GRID VIEW COL3
    /*-----------------------------------------------------------------------------------*/
    var $gridviewcol3 = $('.grid-view.col3 .isotope');
    $gridviewcol3.isotope({
        itemSelector: '.grid-view-post',
        transitionDuration: '0.6s',
        masonry: {
            columnWidth: '.col-sm-6.col-md-4'
        },
        layoutMode: 'masonry'
    });
    $(window).resize(function() {
        $gridviewcol3.isotope({
            masonry: {
                columnWidth: '.col-sm-6.col-md-4'
            }
        });
    });
    $gridviewcol3.imagesLoaded(function() {
        $gridviewcol3.isotope('layout');
    });
    /*-----------------------------------------------------------------------------------*/
    /*	ISOTOPE GRID VIEW COL2
    /*-----------------------------------------------------------------------------------*/
    var $gridviewcol2 = $('.grid-view.col2 .isotope');
    $gridviewcol2.isotope({
        itemSelector: '.grid-view-post',
        transitionDuration: '0.6s',
        masonry: {
            columnWidth: '.col-md-6.col-sm-12'
        },
        layoutMode: 'masonry'
    });
    $(window).resize(function() {
        $gridviewcol2.isotope({
            masonry: {
                columnWidth: '.col-md-6.col-sm-12'
            }
        });
    });
    $gridviewcol2.imagesLoaded(function() {
        $gridviewcol2.isotope('layout');
    });
    /*-----------------------------------------------------------------------------------*/
    /*	ISOTOPE PORTFOLIO GRID
    /*-----------------------------------------------------------------------------------*/
    var $portfoliogrid = $('.image-grid .isotope');
    $portfoliogrid.isotope({
        itemSelector: '.item',
        transitionDuration: '0.7s',
        masonry: {
            columnWidth: $portfoliogrid.width() / 12
        },
        layoutMode: 'masonry'
    });
    $(window).resize(function() {
        $portfoliogrid.isotope({
            masonry: {
                columnWidth: $portfoliogrid.width() / 12
            }
        });
    });
    $portfoliogrid.imagesLoaded(function() {
        $portfoliogrid.isotope('layout');
    });

/*--Share Facebook Button--*/
window.fbAsyncInit = function() {
  FB.init({
    appId      : 825771534261712,
    xfbml      : true,
    version    : 'v2.4'
  });
};

(function(d, s, id){
   var js, fjs = d.getElementsByTagName(s)[0];
   if (d.getElementById(id)) {return;}
   js = d.createElement(s); js.id = id;
   js.src = "//connect.facebook.net/en_US/sdk.js";
   fjs.parentNode.insertBefore(js, fjs);
 }(document, 'script', 'facebook-jssdk'));

function js_share_facebook() {
    $('.js-share-facebook').on('click', function() {
        var url = $(this).context.dataset.url;
        var title = $(this).context.dataset.title;
        var description = $(this).context.dataset.description;
        var img = $(this).context.dataset.img;
        FB.ui({
            method: 'share_open_graph',
            action_type: 'og.shares',
            action_properties: JSON.stringify({
                object : {
                   'og:url': url,
                   'og:title': title,
                   'og:description': description,
                   'og:og:image:width': '1024',
                   'og:image:height': '1024',
                   'og:image': img
                }
            })
        });
    });
}
js_share_facebook();

    /*-----------------------------------------------------------------------------------*/
    /*	laster highlight most popular
    /*-----------------------------------------------------------------------------------*/
(function($, document, window, viewport){

function adjust_height_home_article_img(el){

        var base_h = 520;//208
        var base_w = 780;//313

        var image = new Image();
        image.src = el.attr("src");
        var naturalWidth = image.naturalWidth;
        var naturalHeight = image.naturalHeight;

        var new_base_h = (naturalWidth * base_h) / base_w;


//        console.log(image.src);
//        console.log('naturalWidth:' + naturalWidth);

        var diff_percent = (Math.abs(naturalHeight - new_base_h) / ((naturalHeight + new_base_h)/2) ) * 100;

//        console.log('diff_percent:', diff_percent);
        //h = (300 * el.width())/234;
        if(diff_percent > 20){
            var h = ( base_h * el.width() ) / base_w;
//            console.log('h:', h);
            el.height(h);
        }

}

function adjust_height_home_article_img_all(){
    $('.home figure.main img.article_cover').each(function( index ) {
        var base_h = 520;//208
        var base_w = 780;//313

        var image = new Image();
        image.src = $(this).attr("src");
        var naturalWidth = image.naturalWidth;
        var naturalHeight = image.naturalHeight;

        var new_base_h = (naturalWidth * base_h) / base_w;


//        console.log(image.src);
//        console.log('naturalWidth:' + naturalWidth);

        var diff_percent = (Math.abs(naturalHeight - new_base_h) / ((naturalHeight + new_base_h)/2) ) * 100;

//        console.log('diff_percent:', diff_percent);
        //h = (300 * $(this).width())/234;
        if(diff_percent > 20){
            var h = (base_h * $(this).width())/base_w;
            console.log('h:', h);
            $(this).height(h);
        }

    });
}

    $(".home figure.main img.article_cover").load(function() {
        adjust_height_home_article_img($(this));
    }).each(function() {
        if(this.complete) {
            $(this).load();
        }
    });
    // Executes once whole document has been loaded
    $(document).ready(function() {
        console.log('Current breakpoint: ', viewport.current());
        if(viewport.is("<=sm")){
			$(".divgoto .main img").removeClass("carousel_img_click");
			$(".divgoto .main img").attr("data-toggle", "modal");

			$('.cl-effect-13').owlCarousel({
				center: true,
				items:3,
				startPosition:1,
				loop:true,
				nav: true,
				navText: ['', ''],
				dots: false,
				lazyLoad:true,
				autoWidth:true,
				margin:0
			});

                       /*
                        *  slick_div
                        * */
//                       $('.slick_div').on('init', function(event, slick){
//                                var el = $(this).find('.slick-current .article_dest');
//                                el.on('click', function(e){
//                                    e.preventDefault();
//                                    window.location = $(this).data("href2");
//                                    return true;
//                                });
//
//                                $('.slick_div').off('afterChange').on('afterChange', function(event, slick, currentSlide){
//                                    //$('.slick-slide').not('.slick-current').find('.article_dest').off('click');
//                                    var el = $(this).find('.slick-current .article_dest');
//                                    el.on('click', function(e){
//                                        e.preventDefault();
//                                        window.location = $(this).attr("href");
//                                        return true;
//                                    });
//                                });
//
//                            });

                        $(".slick_div").slick({
                            dots: false,
                            infinite: true,
                            arrows: true,
                            centerMode: true,
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            centerPadding: '25px',
                            lazyLoad: 'ondemand',
                            focusOnSelect: true
                        });




//                        $('.article_dest').unbind('click').bind('click', function(e){
//                            //e.stopPropagation();
//                            return false;
//                        });




            $(".cl-effect-13 a").removeClass("actived");

        }else{
            $(window).on("load", function() {
                    window.$grid = $('.row_masonry').masonry({
                            itemSelector : '.article_masonry'
                    });

            });
                $(".divgoto .main img").addClass("carousel_img_click");
                $(".divgoto .main img").attr("data-toggle", "");
                $(".cl-effect-13 a").attr("style", "");
            }

		/*-----------------------------------------------------------------------------------*/
		/*  carousel_img_click
		/*-----------------------------------------------------------------------------------*/
		$('.carousel_img_click').on('click', function() {
			var value = '#'+$(this).context.dataset.value;
			var getHtml = $(value).html();
			$(".show_fade_pop").html(getHtml);
			$(".show_fade_pop_mobile").html(getHtml);
			var w_window = $( window ).width();
			if(w_window > 767){
				$(".show_fade_pop").show("slow");
			}else{
				$(".show_fade_pop_mobile").show("slow");
				$("html, body").animate({ scrollTop: 0 }, "slow");
			}
			$(value).show();
			var H_val2 = $(value + ' .modal-dialog').height(); //we can only get height if its visible
			$(value).hide();
			var H_carousel = $(".carousel-wrapper").height();
			var H_all;
			if(H_carousel > H_val2){
				H_all = H_carousel;
			}else{
				H_all = H_val2;
			}
                        js_share_facebook();
			$(".carousel-wrapper").animate({height:H_all},200);
		});

    });

    $(window).resize(
        viewport.changed(function(){
            console.log('Current breakpoint: ', viewport.current());
            var g_viewport = viewport.current();

            if(g_viewport !== 'xs' && g_viewport !== 'sm'){
                $(".slick_div").css("width", "inherit");
                $(".slick_div").slick('unslick');
                setTimeout(function(){
                    window.$grid = $('.row_masonry').masonry({
                            itemSelector : '.article_masonry'
                    });
                }, 600);
            }else{
                $(".slick_div").css("width", "100%");
                setTimeout(function(){
                    $('.row_masonry').masonry('destroy');
                    $('.slick_div').on('init', function(event, slick){
                        adjust_height_home_article_img_all();
                    });
//                    if($(".slick_div").hasClass('slick-initialized')) {
//                        $(".slick_div").slick('unslick');
//                    }
                    $(".slick_div").slick({
                        dots: false,
                        infinite: true,
                        arrows: true,
                        centerMode: true,
                        slidesToShow: 1,
                        slidesToScroll: 3,
                        centerPadding: '25px',
                        lazyLoad: 'ondemand'
                    });
                }, 300);

            }
        })
    );



})(jQuery, document, window, ResponsiveBootstrapToolkit);

	$('.prev_title').click(function(){
		var $next = $('.cl-effect-13 a.active').removeClass('active').prev('a');
		if ($next.length) {
		$next.addClass('active');
		}
		else {
			$(".cl-effect-13 a:last").addClass('active');
		}
	});

	$('.next_title').click(function(){
		var $next = $('.cl-effect-13 a.active').removeClass('active').next('a');
		if ($next.length) {
		$next.addClass('active');
		}
		else {
			$(".cl-effect-13 a:first").addClass('active');
		}
	});

	$('.responsive-menu').click(function(){
		var aria_expanded = $( ".responsive-menu" ).hasClass("opn");
		if(aria_expanded == true){
			$('.navbar-header').attr("style", "background: rgba(0, 0, 0, 0.9);");
			$('nav.navbar.navbar-expand-lg').attr("style", "background: rgba(0, 0, 0, 1);");
		}else{
			$('.navbar-header').attr("style", "background: none;");
                        $('nav.navbar.navbar-expand-lg').attr("style", "background: rgba(0, 0, 0, 0.7);");
		}
	});

});

/*-----------------------------------------------------------------------------------*/
/*	LOADING
/*-----------------------------------------------------------------------------------*/
$(window).load(function() {
    $('#status').fadeOut();
    $('.modal_loading').delay(350).fadeOut('slow');
    $('.modal_loading .textload').delay(0).fadeOut('slow');
    $('body').delay(350).css({
        'overflow': 'visible'
    });
});
/*-----------------------------------------------------------------------------------*/
/*	FORM
/*-----------------------------------------------------------------------------------*/
document.addEventListener("DOMContentLoaded", function() {
    var myForm;
    myForm = new VanillaForm(document.querySelector("form.vanilla"));
});
/*-----------------------------------------------------------------------------------*/
/*	STICKY HEADER
/*-----------------------------------------------------------------------------------*/
function init() {
"use strict";
        window.addEventListener('scroll', function(e){
            var distanceY = window.pageYOffset || document.documentElement.scrollTop,
                shrinkOn = 100,
                header = document.querySelector(".navbar");
            if (distanceY > shrinkOn) {
                classie.add(header,"fixed");
            } else {
                if (classie.has(header,"fixed")) {
                    classie.remove(header,"fixed");
                }
            }
        });
    }
    window.onload = init();
$(window).resize(function() {
	$('.offset').css('padding-top', $('.navbar').height() + 'px');
});

function DropDown(el) {
	this.dd = el;
	this.placeholder = this.dd.children('span');
	this.opts = this.dd.find('ul.dropdown > li');
	this.val = '';
	this.index = -1;
	this.initEvents();
}
DropDown.prototype = {
	initEvents : function() {
		var obj = this;

		$('.close-dd-selected').on('click', function() {
			$(this).removeClass('active');
			return false;
		});

		obj.dd.on('click', function(event){
			$(this).addClass('active');
			return false;
		});

		obj.opts.on('click',function(){
			var opt = $(this);
			$('#dd .dropdown li').removeClass('dd-selected');
			opt.addClass('dd-selected');
			obj.val = opt.text();
			obj.index = opt.index();
			obj.placeholder.text(obj.val);
		});
	},
	getValue : function() {
		return this.val;
	},
	getIndex : function() {
		return this.index;
	}
}

$(function() {

	var dd = new DropDown( $('#dd') );

	$('.close-dd-selected').on('click', function() {
        $('#dd').removeClass('active');
    });

});

$(document).ready(function(){

    $(function(){

        $(document).on( 'scroll', function(){

            if ($(window).scrollTop() > 100) {
                        $('.scroll-top-wrapper').addClass('show');
                } else {
                        $('.scroll-top-wrapper').removeClass('show');
                }
            });

            $('.scroll-top-wrapper').on('click', scrollToTop);
    });

    function scrollToTop() {
            verticalOffset = typeof(verticalOffset) != 'undefined' ? verticalOffset : 0;
            element = $('body');
            offset = element.offset();
            offsetTop = offset.top;
            $('html, body').animate({scrollTop: offsetTop}, 500, 'linear');
    }

//    $('figure.main.magazine img').each(function( index ) {
//        var h = (600 * $(this).width())/456;
//        $(this).height(h);
//    });


    /* jQuery Validate Emails with Regex */
    function validateEmail(Email) {
        var pattern = /^([\w-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([\w-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/;
        return $.trim(Email).match(pattern) ? true : false;
    }

    /* jQuery Validate Emails with Regex */
    function popError(txt,header) {
        if(header === ''){
            header = 'Error';
        }
        $("#myModal .modal-title").html(header);
        $("#myModal .modal-body").html('<p>'+txt+'</p>');
        $("#myModal").modal();
    }



    $( "#submit_subscribe" ).click(function( ) {
        var emailaddress = $(".txt_subscribe").val();
        if(emailaddress == ''){
            popError( "กรุณากรอก Email" );
            return;
        }
        if( !validateEmail(emailaddress)) {
            popError( "กรุณากรอก Email ให้ถูกต้อง" );
            return;
        }else{
            var dataip;
            $.getJSON("http://jsonip.com", function (data) {
                dataip = data.ip;
                $.ajax({
                    type: "POST",
                    url: "//"+document.domain+"/contactus/subscribe",
                    data: {q:"subscribe",emailaddress:emailaddress,dataip:dataip},
                    success: function(msg){
                        popError("ข้อมูลของท่าน ถูกบันทึกเรียบร้อยแล้ว","Success");
                        $(".txt_subscribe").val('');
                        var msg = jQuery.parseJSON(msg);
                        //console.log(msg);
                    }
                });
            });
            return;
        }
    });
/*
    function seemore_in(){
        $('.seemore .unu').addClass('unu_motion');
        $('.seemore .doi').addClass('doi_motion');
        $('.seemore .seemore_text').addClass('seemore_text_motion');
        $('.seemore .wheel').addClass('wheel_motion');

    }

    function seemore_out(){
        $('.seemore .unu').removeClass('unu_motion');
        $('.seemore .doi').removeClass('doi_motion');
        $('.seemore .seemore_text').removeClass('seemore_text_motion');
        $('.seemore .wheel').removeClass('wheel_motion');
    }

    $('div.seemore').on('touchstart', function(e){
        seemore_in();
    });

    $('div.seemore').on('touchend', function(e){
        seemore_out();
    });

    $("div.seemore").on({
        mouseenter: function () {
            seemore_in();
        },
        mouseleave: function () {
            seemore_out();
        }
    });
    */
});

function openSearch() {
    $('#myOverlay').slideDown( "slow", function() { });
}
function closeSearch() {
    $('#myOverlay').slideUp( "slow", function() { });
}