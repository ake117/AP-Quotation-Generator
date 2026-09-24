{include file="header.tpl"}

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
        <li data-transition="fade"> <img src="{$base_url}/assets/images/banner-crop.jpg"  alt="" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" />
          <div class="tp-caption large text-center sfl" data-x="center" data-y="130" data-speed="900" data-start="800" data-easing="Sine.easeOut">ทางเลือก</div>
          <div class="tp-caption large text-center sfr" data-x="center" data-y="330" data-speed="900" data-start="1500" data-easing="Sine.easeOut">ของความอยู่รอด</div>
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

  <div class="article">
    <div class="container inner home_article">

        <div class="thin">
		<section class="color-6" >
                <h3 class="section-title text-center">ARTICLE</h3>
                <hr class="hr-title" color=#e99418>
		</section>
	</div>
      <!-- /.thin -->

      <div class="divide10"></div>
      <div class="row row_masonry">

        {foreach from=$articles key=k item=a}
                <div class="article_masonry col-sm-12 col-md-6 col-lg-4 {if $a.display_type==1}slick_div{else}show3_div{/if}" >
                    {foreach from=$a.result item=i}
                          <div class="item post">
                              <figure class="main icon-overlay lightbox"><a class="article_dest {if $a.display_type!==1} article_thumbnail {else} article_carousel {/if}"  href="#"  data-href2="{$base_url}/article/{$k}/{$i.article_id}/{$i.article_slug}"><img class="article_cover" src="{$i.image_url}" alt="" /></a></figure>
                                <div class="box text-left">
                                    <div class="category {$k}"><span><a href="{$base_url}/article/{$k}/">{$a.txt}</a></span></div>
                                    <h4 class="post-title">
                                        <a href="{$base_url}/article/{$k}/{$i.article_id}/{$i.article_slug}">
                                              {*<span class="show">THE POSIBILITY</span>*}
                                              <span>{$i.article_name}</span>
                                        </a>
                                    </h4>
                                    <p>
                                      {$i.article_intro}
                                    </p>
                                    <div class="row">
                                        <div class="col-8">
                                            <div class="meta text-let">
                                                <span class="date">{$i.article_read} Min. Read</span>
                                            </div>
                                        </div>
                                        <div class="col-4">
                                            <div class="meta text-right">
                                                <span class="seemore"><a href="{$base_url}/article/{$k}/">See more</a></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <!-- /.box -->
                            </div>

                    {/foreach}
                </div>
        {/foreach}

        <!-- /column -->
      </div>
      <!-- /.row
	  <div class="seemore">
		<span class="icon_seemore"><img src="{$base_url}/assets/images/see-more.png" style="width: 70%;margin: 0 auto;display: block;" alt=""/></span>
	  </div>
      -->
    </div>
    <!-- /.container -->
  </div>
  <!-- /.light-wrapper -->



      <div class="container-fluid alt-grid" style="background-color: #eaeaea;">
        <div class="row">
            <div class="col-md-12">
                <div class="container shared-secret" style="">
                    <a class="js-share-facebook" style="cursor: pointer;" data-url="{$base_url}/#quote" data-title="THE SECRET IS TO NOT LET YOUR TRADITION UNREASONABLY BURDEN YOU." data-description='"THE SECRET IS TO NOT LET YOUR TRADITION UNREASONABLY BURDEN YOU. เคล็ดลับก็คือ อย่าให้วิถีเดิมๆ มาจำกัดคุณโดยไม่จำเป็น </br>John Lyman lll ผู้บริหารรุ่นที่ 8 ของตระกูลธุรกิจฟาร์เมอร์ มาร์เก็ต Lyman Orchards ในเมืองมิดเดิลฟิล์ด รัฐคอนเนตทิคัต สหรัฐอเมริกา"' data-img="{$base_url}/assets/images/quote-mobile.png?id=1"><img class="" style="" src="{$base_url}/assets/images/share-icon.png" alt=""></a>
                </div>
              <figure class="main">
                    <img class="d-block d-sm-none" style="max-width:100%;max-height:100%" src="{$base_url}/assets/images/quote-mobile.png?id=1"  alt="">
                    <img class="d-none d-sm-block" name="quote" style="max-width:100%;max-height:100%" src="{$base_url}/assets/images/quote-secret.jpg"  alt="">
              </figure>
            </div>
        </div>
      </div>

    <div class="container-fluid alt-grid" style="background-color: #eaeaea;">
        <div class="row">
            <div class="col-md-12 no_padding_bottom">
                <h3 class="section-title text-center" style="padding-top: 20px;">VDO</h3>
                <hr class="hr-title" color=#e99418>
            </div>
        </div>

        <div class="container inner div_video">
            <div class="row">

                {foreach  item=yt from=$youtube name=foo}

                    {assign var="description" value=$yt.short_description}

                    {if $smarty.foreach.foo.index===0}
                        <div class="col-sm-12 col-md-8 y_large">
                        {assign var="description" value=$yt.description}
                    {elseif $smarty.foreach.foo.index===1}
                            <div class="col-sm-12 col-md-4">
                                <div class="row">
                                <div class="col-6 col-md-12 y_small">
                    {elseif $smarty.foreach.foo.index===2}
                                    <div class="col-6 col-md-12 y_small">
                    {/if}

                    <div class="thumbnail">
                        <figure class="main icon-player lightbox sixteen-nine-img">
                            <a data-fancybox href="https://www.youtube.com/watch?v={$yt.id}">
                                <img src="{$yt.cover}" alt="" style="max-width: 100%;">
                            </a>
                        </figure>

                        <div class="caption">
                            <h4>{$yt.title}</h4>
                            <p>{$description}</p>
                        </div>
                    </div>

                    {if $smarty.foreach.foo.index===0}
                                    </div>
                    {elseif $smarty.foreach.foo.index===1}
                                </div>
                    {elseif $smarty.foreach.foo.index===2}
                        <div class="meta thumbnail text-right" style="padding-top: 0px;margin: 0px;">
                            <span class="seemore"><a href="https://www.youtube.com/channel/{$channel_id}/" target="_blank">See more</a></span>
                        </div>
                            </div></div>
                        </div>
                        <div class="col-12">

                        </div>
                    {/if}

                {/foreach}
            </div>
        </div>
    </div>

<div class="show_fade_pop_mobile"></div>
{if !empty($publications)}
<div class="light-wrapper divgoto" >
    <div class="container inner">
        <h3 class="section-title text-center">NEW MAGAZINE</h3>
	<hr class="hr-title" color=#e99418 style="margin-bottom: 10px;padding-top: 5px;width: 55px;">
        <div class="carousel-wrapper">
          <div class="show_fade_pop"></div>
          <div class="carousel carousel-boxed blog">

              {foreach from=$publications key=k item=a}
                  {if is_numeric($k) }
                    <div class="item post">
                      <figure class="main"><img src="{$a.image_url}" class="carousel_img_click" data-toggle="modal" data-target="#item_post_{$a.publication_id}" data-value="item_post_{$a.publication_id}" alt="" /></figure>
                      <div class="txt_new_magz"><b>{$a.publication_subject}</b></br>{$a.active_from}</div>
                    </div>
                  {/if}
              {/foreach}


          </div>
	  <!-- Modal -->
          {foreach from=$publications item=a}
            <div class="modal fade" id="item_post_{$a.publication_id}" role="dialog">
                    <!-- Modal content-->
                    <div class="modal-content">
                            <div class="close_fade_pop" onclick="$('.modal.show').hide().removeClass('show');$('.modal-backdrop.show').hide();$('.show_fade_pop').hide('slow');$('.show_fade_pop_mobile').hide('slow');$('.carousel-wrapper').height('inherit');$('html, body').animate({ scrollTop: $('.divgoto').offset().top }, 1000);" data-dismiss="modal">X</div>
                            <div class="modal-body">
                                <div style="width: 100%;height: auto;position: relative;padding:20px 20px;">
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-sm-12 col-md-4">
                                                    <img src="{$a.image_url}" alt="" style="height: auto;max-height: 350px;display: block;margin: 0 auto;"/>
                                            </div>
                                            <div class="col-sm-12 col-md-8">
                                                    <div>
                                                            <!--<h4>นิตยสารคิด (Creative Thailand) ฉบับเดือนกุมภาพันธ์ 2561</h4>
                                                            <h4>New Value of Publication </h4>
                                                            <h4>{$a.publication_subject}</h4>-->
                                                    </div>
                                                    <div class="publication_detail">
                                                    {$a.publication_detail}
                                                    </div>
                                                    <a class="js-share-facebook" style="cursor: pointer;" data-url="{$base_url}/magazine/{$a.publication_id}/{$a.publication_slug}" data-title="{$a.publication_subject}" data-description='{$a.publication_intro}' data-img="{$a.image_url_share}">
                                                        <img src="{$base_url}/assets/images/share-icon-black.png" alt="" style="height: auto;display: block;padding-top: 25px;"/>
                                                    </a>

                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-12 col-md-4"></div>
                                            <div class="col-sm-12 col-md-3">
                                                <div class="bt_read_download_pop bt_more_magazine">
                                                    <a class="bt_read" href="{$a.file_url}" target="_blank">อ่าน ONLINE</a>
                                                </div>
                                            </div>
                                            <div class="col-sm-12 col-md-3">
                                                <div class="bt_read_download_pop bt_more_magazine">
                                                    <a href="{$base_url}/magazine" class="more_magazine">MORE MAGAZINE</a>
                                                </div>
                                            </div>
                                            <div class="divide50 d-block d-md-none"></div>
                                        </div>
                                    </div>
                                            <div style="clear:both"></div>
                                    </div>
                            </div>
                    </div>
            </div>
        {/foreach}
          <div style="clear:both"></div>
          <div class="bt_read_download bt_more_magazine">
              <a href="{$base_url}/magazine" class="more_magazine">MORE MAGAZINE</a>
          </div>
          <div style="clear:both"></div>
          <!--/.carousel -->
        </div>


      <!--/.carousel-wrapper -->
    </div>
    <!--/.container -->

  </div>
{/if}

<script>
$(document).ready(function() {

    $('.article_thumbnail').click(function(e){
        e.preventDefault();
        window.location = $(this).data("href2");
    });

    $('.article_carousel').click(function(e){
        e.preventDefault();
        var this_ar = $(this);
        (function($, document, window, viewport){
            console.log(viewport.current());
            if(viewport.is("<sm")){
                if (this_ar.parents('.slick-current').length) {
                    window.location = this_ar.data("href2");
                }
            }else{
                window.location = this_ar.data("href2");
            }

        })(jQuery, document, window, ResponsiveBootstrapToolkit);

    });

});

</script>
{include file="footer.tpl"}
