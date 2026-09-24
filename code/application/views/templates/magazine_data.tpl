{include file="header.tpl"}

<script src="{$base_url}/assets/js/sticky.jquery.js"></script>

{*foreach from=$articles->data item=article}
    <li>{$article->category_name}</li>
{/foreach*}
{ci_config name="base_url"}
  <div class="tp-fullscreen-container revolution">
    <div class="tp-fullscreen">
      <ul>
        <!--<li data-transition="fade"> <img src="{$base_url}/assets/images/big-banner.jpg"  alt="" data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat" />
          <h1 class="tp-caption large sfr" data-x="30" data-y="263" data-speed="900" data-start="800" data-easing="Sine.easeOut">ทางเลือก</h1>
          <div class="tp-caption large sfr" data-x="30" data-y="348" data-speed="900" data-start="1500" data-easing="Sine.easeOut">ของความอยู่รอด</div>
        </li>-->
        <li data-transition="fade"> <img src="{$base_url}/assets/images/banner-magazine.jpg"  alt="" data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat" />
          <div class="tp-caption large text-center sfl" data-x="center" data-y="130" data-speed="900" data-start="800" data-easing="Sine.easeOut"></div>
          <div class="tp-caption large text-center sfr" data-x="center" data-y="330" data-speed="900" data-start="1500" data-easing="Sine.easeOut"></div>
        </li>
        <!--<li data-transition="fade"> <img src="{$base_url}/assets/images/big-banner.jpg"  alt="" data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat" />
          <div class="tp-caption large text-<!--center sfl" data-x="center" data-y="130" data-speed="900" data-start="800" data-easing="Sine.easeOut">ทางเลือก</div>
          <div class="tp-caption large text-center sfr" data-x="center" data-y="330" data-speed="900" data-start="1500" data-easing="Sine.easeOut">ของความอยู่รอด</div>
        </li>
        <li data-transition="fade"> <img src="{$base_url}/assets/images/banner-crop.jpg"  alt="" data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat" />
          <div class="tp-caption large text-center sfl" data-x="center" data-y="130" data-speed="900" data-start="800" data-easing="Sine.easeOut">ทางเลือก</div>
          <div class="tp-caption large text-center sfr" data-x="center" data-y="330" data-speed="900" data-start="1500" data-easing="Sine.easeOut">ของความอยู่รอด</div>
        </li>-->

      </ul>
      <div class="tp-bannertimer tp-bottom"></div>
    </div>
    <!-- /.tp-fullscreen-container -->
  </div>
  <!-- /.revolution -->

  <div class="show_fade_pop_mobile"></div>
  <div class="light-wrapper">
    <div class="container inner">
      <h3 class="section-title text-center">{$publication_data.publication_subject}</h3>
      <hr class="hr-title" color=#e99418 style="margin-bottom: 10px;padding-top: 5px;width: 55px;">
      <div class="divide10"></div>
      <!--<h4 class="text-center">นิตยสารคิด (Creative Thailand)</h4>-->
      <div class="divide10"></div>
        <div class="divide10 hide_mobile"></div>
        <div class="divide10 hide_mobile"></div>
        <div class="row">
            <div class="col-12 col-md-1" style="margin-bottom: 10px;">
                <div class="row social-share share-mobile">
                    <div class="col-8 col-md-12"></div>
                </div>
            </div>

              <div class="col-0 col-md-3" style="text-align: center;"><img src="{$publication_data.image_url}" alt="{$publication_data.publication_subject}" /></a></div>
              <div class="col-12 col-md-7">
                  <div class="main-content">
                      <div class="content-section publication_detail">
                          {$publication_data.publication_detail}
                      </div>
                  </div>
                  <div class="row">

                    <div class="col-md-5">
                        <div class="bt_read_download_pop bt_more_magazine bt_read_magazine">
                            <a class="bt_read" href="{$publication_data.file_url}" target="_blank">อ่าน ONLINE</a>
                        </div>
                    </div>
                    <div class="d-none d-sm-block col-md-1"></div>
                    <div class="col-md-5">
                        <div class="bt_read_download_pop bt_more_magazine bt_seemore_magazine">
                            <a href="{$base_url}/magazine" class="more_magazine">MORE MAGAZINE</a>
                        </div>
                    </div>
                    <div class="col-md-1"></div>
                    <div class="divide50 d-block d-md-none"></div>
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


	  <!-- Modal -->


      <!--/.carousel-wrapper -->
    </div>
    <!--/.container -->

  </div>

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
          }, {
            breakpoint: 500,
            settings: {
                dots: false,
                infinite: false,
                arrows: true,
                centerMode: false,
                slidesToShow: 1,
                slidesToScroll: 1,
                centerPadding: '25px',
                lazyLoad: 'ondemand'
            }
          }]
      });
})();

(function($) {
	$.miniShare = function(options) {
		var def_html =
			[	"<div class=\"col-2 col-md-12\"><div class=\"facebook\"><a><div class=\"icon-slide-right ic-fb masterTooltip\" title=\"Facebook\"></div></a></div></div>"
			,	"<div class=\"col-2 col-md-12\"><div class=\"twitter\"><a><div class=\"icon-slide-right ic-tw\" title=\"Twitter\"></div></a></div></div>"
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
    function getShareURL(){
        $.ajax({
            url: 'https://graph.facebook.com/?id='+ document.location,
              dataType: 'jsonp',
              type: 'GET',
              contentType: false,
              processData: false,
              success: function(data){
                //console.log(data);
                $('.ic-fb').attr('title', 'แชร์แล้ว ทั้งหมด '+data.share.share_count+' ครั้ง');
              }
        });
    };
    getShareURL();

    // Tooltip only Text
    $('.masterTooltip').hover(function(){
        getShareURL();
        // Hover over code
        var title = $(this).attr('title');
        $(this).data('tipText', title).removeAttr('title');
        $('<p class="tooltip-hover"></p>')
        .text(title)
        .appendTo('body')
        .fadeIn('slow');
    }, function() {
        // Hover out code
        $(this).attr('title', $(this).data('tipText'));
        $('.tooltip-hover').remove();
    }).mousemove(function(e) {
        var mousex = e.pageX + 20; //Get X coordinates
        var mousey = e.pageY + 10; //Get Y coordinates
        $('.tooltip-hover').css({ top: mousey, left: mousex });
    });

    //jQuery(".publication_detail table td:first>div>div>div>div:contains('นิตยสารคิด (Creative Thailand)')").find("div:contains('นิตยสารคิด (Creative Thailand)')").hide();
    //jQuery(".publication_detail table td:first>div>div>div>div:contains('นิตยสารคิด (Creative Thailand)')").find("div:contains('นิตยสารคิด (Creative Thailand)')").next().hide();
});
</script>
{/literal}

{include file="footer.tpl"}