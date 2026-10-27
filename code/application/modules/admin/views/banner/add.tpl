{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}
<section class="content-header">
    <h1>
        {ci_language line="mod_banner"}
        <small>Manage the Banner displayed on the site here.</small>
    </h1>
    <ol class="breadcrumb">
        
        <li><a href="{site_url url="admin/banner"}"><i class="fa fa-shirtsinbulk" aria-hidden="true"></i> {ci_language line="mod_banner"}</a></li>
        <li>Add {ci_language line="mod_banner"}</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Add {ci_language line="mod_banner"}</h3>
        </div>
        <div class="box-body">
            <form action="{site_url url="admin/banner/add"}" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="row">
                {foreach from=$lang_field item=lang}
                    {$banner_name = "banner_name_{$lang}"}
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                        <label for="{$banner_name}">Banner name ({$lang|upper})</label>
                        <input type="text" name="{$banner_name}" value="{${$banner_name}}" class="form-control">
                        {form_error($banner_name)}
                        </div>
                    </div>
                {/foreach}
            </div>


            <div class="row">
                {foreach from=$lang_field item=lang}
                    {$banner_detail = "banner_detail_{$lang}"}
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="{$banner_detail}">Description ({$lang|upper})</label>
                            <textarea name="{$banner_detail}" cols="40" rows="3" class="form-control">{${$banner_detail}}</textarea>
                            {form_error($banner_detail)}
                        </div>
                    </div>
                {/foreach}
            </div>

            <div class="row">
                {foreach from=$lang_field item=lang}
                    {$banner_image = "banner_image_{$lang}"}
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="{$banner_image}">Banner ({$lang|upper})</label>
                            <input type="file" name="{$banner_image}" value="" class="form-control">
                            {form_error($banner_image)}
                        </div>
                    </div>
                {/foreach}
            </div>

            <div class="row ">
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="seq">Display order</label>
                        <input type="text" name="seq" value="{$seq}" class="form-control">
                        {form_error('seq')}
                    </div>
                </div>
                <div class="col-md-3" >
                    <div class="form-group">
                        <label for="is_active">Status</label>
                        {html_options name='is_active' options=$published_options selected=$is_active class='form-control'}
                    </div>
                </div>
            </div>
            <hr>
            <div class="row ">
                <div class="col-md-12">
                <button name="submit" type="submit" class="btn btn-primary">Create</button>
                &nbsp;<a href="{site_url url="admin/banner"}" class="btn btn-danger">Cancel</a></div>
            </div>
        </form>

        </div>
    </div>
</section>

{include file="footer.tpl"}