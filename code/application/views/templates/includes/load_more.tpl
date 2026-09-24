    {foreach from=$articles key=k item=a}
        {if !empty($a.result) }
            {foreach from=$a.result item=i}
                <div class="article_masonry col-sm-12 col-md-6 col-lg-4" >
                          <div class="item post">
                              <figure class="main icon-overlay lightbox"><a class="article_dest" {*if $a.display_type==1} onclick="return false;" {/if*}  href="{$base_url}/article/{$k}/{$i.article_id}/{$i.article_slug}"><img src="{$i.image_url}" alt="" /></a></figure>
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
                                        <!--<span class="comments"><a href="#"><i class="icon-chat-1"></i> 15</a></span>-->
                                    </div>
                                </div>
                            <!-- /.box -->
                            </div>
                </div>
            {/foreach}
        {/if}
    {/foreach}