{include file="header.tpl"}
{ci_config name="base_url"}

  <!-- /.navbar -->

  <div class="tp-fullscreen-container revolution">
    <div class="tp-fullscreen">
      <ul>
        <li data-transition="fade"> <img src="{$base_url}/assets/images/banner-category.jpg"  alt="" data-bgposition="center center" data-bgfit="cover" data-bgrepeat="no-repeat" />
        </li>

      </ul>
      <div class="tp-bannertimer tp-bottom"></div>
    </div>
    <!-- /.tp-fullscreen-container -->
  </div>
  <!-- /.revolution -->

  <div class="article">
    <div class="container inner">
        <div class="row" style="position: relative;display: block;padding-top: 20px;">
            <div class="thin">
                <section class="color-6" >
                    <h3 class="section-title text-center" >{$article_title|upper}</h3>
                    <hr class="hr-title">
                    <h2 class="text-center">{$article_short_desc}</h2>
                </section>
            </div>
        </div>

        <div class="row row_masonry" style="padding-top: 20px;">
            {include file="includes/load_more.tpl"}
        </div>

        {if !empty($articles.next_page_url) }
            {include file="includes/load_more.js.tpl"}
        {/if}

    </div>
    <!-- /.container -->
  </div>
  <!-- /.light-wrapper -->

{include file="footer.tpl"}