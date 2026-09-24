{include file="header.tpl"}
{include file="loading.tpl"}
{ci_config name="base_url"}

<style>

    .slick-prev {
        left:-20px;
    }
    .slick-next {
        right:-20px;
    }
    .slick-prev:before, .slick-next:before {
        color: #6d6d6d;
        font-size: 3rem;
    }
    .tooltip-hover {
	display:none;
	position:absolute;
	border:1px solid #333;
	background-color:#161616;
	border-radius:5px;
	padding:7px;
        width:150px;
        text-align:center;
	color:#ffffff;
        z-index:9;
    }
    @media (max-width: 500px){
        .slick-prev{ left:0px; }
        .slick-next{ right:0px; }
    }

    a:hover , .post-title a:hover {literal}{{/literal}
        color: {$color};
    {literal}}{/literal}
    .btn:hover, .btn:focus, .btn:active, .btn.active {literal}{{/literal}
        background: {$color};
    {literal}}{/literal}
</style>

<script src="{$base_url}/assets/js/sticky.jquery.js"></script>


  {*foreach from=$articles->data item=article}
    <li>{$article->category_name}</li>
{/foreach*}
  <div class="tp-fullscreen-container revolution">
    <div class="tp-fullscreen">
      <ul>
        <!--<li data-transition="fade"> <img src="{$base_url}/assets/images/banner-Subdetail.png"  alt="" data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat" />
          <div class="tp-caption large text-center sfl" data-x="center" data-y="130" data-speed="900" data-start="800" data-easing="Sine.easeOut">Co van Kessel</div>
          <div class="tp-caption large text-center sfr" data-x="center" data-y="330" data-speed="900" data-start="1500" data-easing="Sine.easeOut">ปั่นเพื่อปั้นกรุง</div>
        </li>-->

            {foreach from=$images item=image}
                <li data-transition="fade">
                    <img src="{$image->image_url}"  alt="" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" />
                    <!--
                    {if empty($article_name2)}
                        <h1 class="tp-caption medium text-center sfl" data-x="center" data-y="220" data-speed="900" data-start="800" data-easing="Sine.easeOut">{$article_name1}</h1>
                    {else}
                        <h1 class="tp-caption medium text-center sfb" data-x="center" data-y="180" data-speed="900" data-start="800" data-easing="Sine.easeOut">{$article_name1}</h1>
                        <h1 class="tp-caption medium text-center sfb" data-x="center" data-y="320" data-speed="900" data-start="1500" data-easing="Sine.easeOut">{$article_name2}</h1>
                    {/if} -->
                </li>
            {/foreach}

      </ul>
      <div class="tp-bannertimer tp-bottom"></div>
    </div>
    <!-- /.tp-fullscreen-container -->
  </div>
  <!-- /.revolution -->

  <div class="article" style="overflow: hidden;">
    <div class="container inner">
        <div class="row">
            <div class="col-0 col-md-1"></div>
            <div class="article_name col-12 col-md-10">
                <h1>{$article_name}</h1>
            </div>
            <div class="col-0 col-md-1"></div>
        </div>
        <div class="row no-margin-col">
            <div class="col-0 col-md-1"></div>
            <div class="col-4 col-md-3 text-left"><img src="{$base_url}/assets/images/icon-open-book-filled.png" alt="" /> {$read_min} min. Read</div>
            <div class="col-4 col-md-4 text-center">{$article_create_date}</div>
            <div class="col-4 col-md-3 text-right">{$article_view} <img src="{$base_url}/assets/images/icon-eye.png" alt="" /></div>
            <div class="col-0 col-md-1"></div>
        </div>
        <div class="divide10"></div>
        <div class="divide10 hide_mobile"></div>
        <div class="divide10 hide_mobile"></div>
        <div class="row">
            <div class="col-12 col-md-1" style="margin-bottom: 10px;">
                <div class="row social-share share-mobile">
                    <div class="col-8 col-md-12"></div>
                </div>
            </div>
            <div class="col-12 col-md-10">
                <div class="main-content">
                    <div class="content-section">
                        {$content}
                    </div>
                    <!--<div class="main-content-end-padding">
                        <div class="row">
                            <div class="col-12 col-md-2">
                                <img src="{$base_url}/assets/images/kid-logo.png" alt="" />
                            </div>
                            <div class="col-12 col-md-10">
                                <div><b>Creative Thailand</b></div>
                                <div>โครงการ "Creative Thailand สร้างเศรษฐกิจไทยด้วยความคิดสร้างสรรค์" จัดตั้งขึ้นโดยศูนย์สร้างสรรค์งานออกแบบ สำนักงานบริหารและพัฒนาองค์ความรู้ (องค์การมหาชน) มีวัตถุประสงค์ในการเผยแพร่ความรู้เกี่ยวกับเศรษฐกิจสร้างสรรค์และการผลักดันอุตสาหกรรมสร้างสรรค์ให้เป็นกลไกสำคัญในการพัฒนาเศรษฐกิจของประเทศ โดยมีนิตยสารและเว็บไซต์ Creative Thailand เป็นแกนหลักในการนำเสนอและเผยแพร่องค์ความรู้ที่เกี่ยวข้อง</div>
                                <div><b>tcdc.or.th</b></div>
                            </div>
                            <div class="col-12" style="position: relative;display: block;padding-top: 20px;">
                                <div class="thin">
                                    <section class="color-6" >
                                        <h3 class="section-title text-center">CONTRIBUTORS</h3>
                                        <hr class="hr-title" color={$color}>
                                    </section>
                                </div>
                            </div>
                            <div class="row col-12">
                                <div class="col-6 col-md-3">
                                    <div><b>ศรัณยู นกแก้ว</b></div>
                                    <div>นักเขียน</div>
                                    <a class="btn btn-follow d-none d-lg-inline-block" href="#">Follow</a>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div><b>ศรัณยู นกแก้ว</b></div>
                                    <div>นักเขียน</div>
                                    <a class="btn btn-follow d-none d-lg-inline-block" href="#">Follow</a>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div><b>ศรัณยู นกแก้ว</b></div>
                                    <div>นักเขียน</div>
                                    <a class="btn btn-follow d-none d-lg-inline-block" href="#">Follow</a>
                                </div>
                                <div class="col-6 col-md-3">
                                    <div><b>ศรัณยู นกแก้ว</b></div>
                                    <div>นักเขียน</div>
                                    <a class="btn btn-follow d-none d-lg-inline-block" href="#">Follow</a>
                                </div>
                            </div>
                        </div>
                    </div> -->
                </div>
            </div>
            <aside class="col-12 col-md-1">
                <div class="sticky-content">
                    <div class="row social-share">
                        <!--div class="col-3 col-md-12"><div class="icon-slide-right ic-like" title="Like"></div></div>
                        <div class="col-3 col-md-12"><div class="icon-slide-right ic-save" title="Save"></div></div-->
                    </div>
                    <!--div class="fb-like" data-href="{$base_url}/" data-layout="box_count" data-action="like" data-size="large" data-show-faces="false" data-share="true"></div-->
                </div>
            </aside>
        </div>
        <!--<div class="row end-matter-info">
            <section class="col">
                <div class="fb-comments" data-href="{$smarty.server.HTTP_HOST}{$smarty.server.REQUEST_URI}" data-width="100%" data-numposts="5"></div>
            </section>
        </div>-->

        {if !empty($rel_article)}
            <div class="divide10 hide_mobile"></div>
            <div class="divide10 hide_mobile"></div>
            <div class="row" style="position: relative;display: block;padding-top: 20px;">
                <div class="thin">
                    <section class="color-6" >
                        <h3 class="section-title text-center">RELATED ARTICLES</h3>
                        <hr class="hr-title" color={$color}>
                    </section>
                </div>
                <div class="slick_div_relate">

                    {foreach from=$rel_article key=k item=i}
                        {if is_numeric($k) }
                            <div class="article_masonry col-12">
                              <div class="item post">
                                    <figure class="main icon-overlay lightbox"><a href="{$base_url}/article/{$i.category}/{$i.article_id}/{$i.article_slug}"><img src="{$i.image_url}" alt="" /></a></figure>
                                    <div class="box text-left">
                                      <div class="divide10"></div>
                                      <h4 class="post-title">
                                          <a href="{$base_url}/article/{$i.category}/{$i.article_id}/{$i.article_slug}">
                                                          {*<span class="show">THE POSIBILITY</span>*}
                                                          <span>{$i.article_name}</span>
                                          </a>
                                      </h4>
                                      <p>
                                        {$i.article_intro}
                                      </p>
                                      <div class="meta text-right">
                                          <span class="date">{$i.article_read} Min. Read</span>
                                          <div class="see">{$i.article_view} <img src="{$base_url}/assets/images/icon-eye.png" alt="See" style="width: auto;"/></div>
                                      </div>
                                </div>
                                <!-- /.box -->
                              </div>
                            </div>
                        {/if}
                    {/foreach}

                </div>
                <div class="divide10 hide_mobile"></div>
                <div class="divide10 hide_mobile"></div>
                <div class="divide10 hide_mobile"></div>
                <div class="divide10 hide_mobile"></div>
                <div class="thin">
                    <section class="color-6" >
                        <h3 class="section-title text-center">MOST POPULAR</h3>
                        <hr class="hr-title" color={$color}>
                    </section>
                </div>
                <div class="slick_div_relate">
                    {foreach from=$pop_article key=k item=a}
                        {if isset($a.result) && !empty($a.result) }
                            {foreach from=$a.result item=i}
                                <div class="article_masonry col-12">
                                  <div class="item post">
                                    <figure class="main icon-overlay lightbox"><a href="{$base_url}/article/{$k}/{$i.article_id}/{$i.article_slug}"><img src="{$i.image_url}" alt="" /></a></figure>
                                    <div class="box text-left">
                                      <div class="divide10"></div>
                                      <h4 class="post-title">
                                          <a href="{$base_url}/article/{$k}/{$i.article_id}/{$i.article_slug}">
                                                          {*<span class="show">THE POSIBILITY</span>*}
                                                          <span>{$i.article_name}</span>
                                          </a>
                                      </h4>
                                      <p>
                                        {$i.article_intro}
                                      </p>
                                      <div class="meta text-right">

                                          <span class="date">{$i.article_read} Min. Read</span>
                                          <div class="see">{$i.article_view} <img src="{$base_url}/assets/images/icon-eye.png" alt="See" style="width: auto;"/></div>
                                      </div>
                                    </div>
                                    <!-- /.box -->
                                  </div>
                                </div>
                            {/foreach}
                        {/if}
                    {/foreach}

                </div>
            </div>
        {/if}
    </div>
    <!-- /.container -->
  </div>
  <!-- /.light-wrapper -->
{literal}
<script>
(function(){
    $(".sticky-content").stick_in_parent({offset_top: 120,offset_bottom: 120});



    $(".slick_div_relate").slick({
        infinite: false,
        responsive: [{
            breakpoint: 2000,
            settings: {
                dots: false,
                infinite: false,
                arrows: true,
                centerMode: false,
                slidesToShow: 3,
                slidesToScroll: 1,
                centerPadding: '0px',
                lazyLoad: 'ondemand'
            }
          },
          {
            breakpoint: 1024,
            settings: {
                dots: false,
                infinite: false,
                arrows: true,
                centerMode: false,
                slidesToShow: 2,
                slidesToScroll: 1,
                centerPadding: '0px',
                lazyLoad: 'ondemand'
            }
          }
          , {
            breakpoint: 767,
            settings: {
                dots: false,
                infinite: true,
                arrows: true,
                centerMode: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                centerPadding: '25px',
                lazyLoad: 'ondemand',
                focusOnSelect: true
            }
          }
          , {
            breakpoint: 500,
            settings: {
                dots: false,
                infinite: true,
                arrows: true,
                centerMode: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                centerPadding: '25px',
                lazyLoad: 'ondemand',
                focusOnSelect: true
            }
          }
      ]
      });

        $('.slick_div_relate figure.main img').each(function( index ) {
            //console.log($(this).width());
            var h = (202 * $(this).width())/360;
            $(this).height(h);
        });
})();

(function($) {
	$.miniShare = function(options) {
		var def_html =
			[	"<div class=\"col-2 col-md-12\"><div class=\"facebook\"><a><div class=\"icon-slide-right ic-fb social_tooltip\" title=\"Share on Facebook\"></div></a></div></div>"
			,	"<div class=\"col-2 col-md-12\"><div class=\"twitter\"><a><div class=\"icon-slide-right ic-tw social_tooltip\" title=\"Share on Twitter\"></div></a></div></div>"
			].join('');

		var services = {
			twitter: "https://twitter.com/intent/tweet?text=",
			facebook: "https://www.facebook.com/sharer/sharer.php?u="
		};

		var defaults = {
			url: document.location,
			message: "Share This",
			done_message: "Thanks!",
			twitter_message: {/literal}'{$article_name1}'{literal},
			html: def_html,
		}

		var opts = $.extend(defaults, options);

		widget = $('.social-share').append(opts.html);

		$('.social-share a').click(function() {
			var parent_class = $(this).parent().attr('class');
			if(services[parent_class] == undefined) {
				return false;
			};
			var the_url = '';
			if(parent_class == "twitter") {
				message = opts.twitter_message;
				the_url = services[parent_class] + opts.twitter_message + "&url=" + opts.url;
			} else {
				the_url = services[parent_class] + opts.url;
			};
			var share_window = window.open(the_url, 'sharer', 'width=600,height=400,top=200,left=200');
			var watchClose = setInterval(function() {
			    try {
			        if (share_window.closed) {
			            clearTimeout(watchClose);
			        }
			    } catch (e) {}
			}, 200);
		});
	};
})(jQuery);
$.miniShare();

$(document).ready(function(){

    // Tooltip only Text
    function social_tooltip_in($this){
        var title = $($this).attr('title');
        $($this).data('tipText', title).removeAttr('title');
        $('<p class="tooltip-hover"></p>')
        .text(title)
        .appendTo('body')
        .fadeIn('slow');
    }
    function social_tooltip_out($this){
        console.log($this);
        $($this).attr('title', $($this).data('tipText'));
        $('.tooltip-hover').remove();
    }
    function social_tooltip_move(e){
        var mousex = e.pageX -180; //Get X coordinates
        var mousey = e.pageY - 20; //Get Y coordinates
        $('.tooltip-hover').css({ top: mousey, left: mousex });
    }
    
    if ('ontouchstart' in document.documentElement) {
        $('.social_tooltip').on('touchstart', function(e){
            social_tooltip_in(this);
        });

        $('.social_tooltip').on('touchend', function(e){
            social_tooltip_out(this);
        });
        $('.social_tooltip').on('touchmove', function(e){
            var e = window.event;

            var mousex = e.touches[0].pageX - 180; //Get X coordinates
            var mousey = e.touches[0].pageY - 20; //Get Y coordinates
            $('.tooltip-hover').css({ top: mousey, left: mousex });

        });
    }else{
            $('.social_tooltip').hover(function(){
                // Hover over code
                social_tooltip_in(this);
            }, function() {
                // Hover out code
                social_tooltip_out(this);
            }).mousemove(function(e) {
                social_tooltip_move(e);
            });
    }

    

});
</script>
{/literal}

{include file="footer.tpl"}