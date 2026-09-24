{include file="header.tpl"}

{ci_config name="base_url"}

  <!-- /.navbar -->

  <div class="tp-fullscreen-container revolution">
    <div class="tp-fullscreen">
      <ul>
        <li data-transition="fade"> <img src="{$base_url}/assets/images/banner-search.jpg"  alt="" data-bgposition="center top" data-bgfit="cover" data-bgrepeat="no-repeat" />
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
                    <h3 class="section-title text-center" >Search</h3>
                    <hr class="hr-title">
                    <h2 class="text-center">ค้นหาข่าวสาร บทความที่น่าสนใจ</h2>
                </section>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12 col-md-12 search_result">
                <p>SORTED BY:  RELEVANCE DATE</p>
                <p>ABOUT <b>{$articles.total} RESULTS</b></p>
                <p>SEARCH RESULTS FOR KEYWORD : <b>"{$keyword_search}"</b></p>
            </div>
        </div>

        {if $articles.total > 0}
            <div class="row row_masonry" style="padding-top: 20px;">
                {include file="includes/search_load_more.tpl"}
            </div>
        {/if}

        {if !empty($articles.next_page_url) }
            {include file="includes/search_load_more.js.tpl"}
        {/if}

    </div>
    <!-- /.container -->
  </div>
  <!-- /.light-wrapper -->

{include file="footer.tpl"}