{foreach from=$publications key=k item=a}
    {if is_numeric($k) }
        <div class="item post col-6  col-md-4 col-lg-3">
            <figure class="main magazine">
                <a href="{$base_url}/magazine/{$a.publication_id}/{$a.publication_slug}">
                    <img src="{$a.image_url}" class="" data-toggle="" data-target="#item_post_{$a.publication_id}" data-value="item_post_{$a.publication_id}" alt="{$a.publication_subject}" />
                </a>
                <div class="txt_new_magz"><b>{$a.publication_subject}</b></br>{$a.active_from}</div>
            </figure>
        </div>
    {/if}
{/foreach}