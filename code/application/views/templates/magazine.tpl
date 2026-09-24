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
        <h3 class="section-title text-center">MAGAZINE</h3>
        <hr class="hr-title" color=#e99418 style="margin-bottom: 10px;padding-top: 5px;width: 55px;">
        <div class="divide10"></div>
        <h2 class="text-center">นิตยสารคิด (CREATIVE THAILAND)</h2>
        <div class="divide10"></div>
        <div class="divide10"></div>
        <div class="divide10"></div>
        <div class="carousel-wrapper">
            <div class="show_fade_pop"></div>
            <div class="row magazine_content">
                {foreach from=$publications key=k item=a}
                    {if is_numeric($k) }
                        <div class="item post col-6 col-md-4 col-lg-3">
                            <figure class="main magazine">
                                <a href="{$base_url}/magazine/{$a.publication_id}/{$a.publication_slug}">
                                    <img src="{$a.image_url}" class="" data-toggle="" data-target="#item_post_{$a.publication_id}" data-value="item_post_{$a.publication_id}" alt="{$a.publication_subject}" />
                                </a>
                                <div class="txt_new_magz"><b>{$a.publication_subject}</b></br>{$a.active_from}</div>
                            </figure>
                        </div>
                    {/if}
                {/foreach}
              <!-- /.post -->
            </div>

            <div style="clear:both"></div>
            {if !empty($publications.next_page_url) }
                {include file="includes/magazine_load_more.js.tpl"}
            {/if}
            <!--/.carousel -->
        </div>
	  <!-- Modal -->


      <!--/.carousel-wrapper -->
    </div>
    <!--/.container -->

  </div>

{include file="footer.tpl"}