{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}
<section class="content-header">
    <h1>
        {ci_language line="mod_roomtype_gallery"}
        <small>Manage the Roomtype gallery displayed on the site here.</small>
    </h1>
    <ol class="breadcrumb">
        
        <li><a href="{site_url url="admin/roomtype_gallery"}"><i class="fa fa-picture-o" aria-hidden="true"></i> {ci_language line="mod_roomtype_gallery"}</a></li>
        <li>Add {ci_language line="mod_roomtype_gallery"}</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Add {ci_language line="mod_roomtype_gallery"}</h3>
        </div>
        <div class="box-body">
            <form action="{site_url url="admin/roomtype_gallery/add"}" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
            <div class="row">
                <div class="col-sm-4 col-md-4">
                    <div class="form-group">
                        <label for="project_id">{ci_language line="mod_project"}</label>
                        {html_options name='project_id' options=$project selected=$project_id class='form-control'}
                        {form_error($project_name)}
                    </div>
                </div>
                {foreach from=$lang_field item=lang}
                    {$roomtype_gallery_name = "roomtype_gallery_name_{$lang}"}
                    <div class="col-sm-4 col-md-4">
                        <div class="form-group">
                        <label for="{$roomtype_gallery_name}">Roomtype gallery name ({$lang|upper})</label>
                        <input type="text" name="{$roomtype_gallery_name}" value="{${$roomtype_gallery_name}}" class="form-control">
                        {form_error($roomtype_gallery_name)}
                        </div>
                    </div>
                {/foreach}
            </div>


            <div class="row">
                {foreach from=$lang_field item=lang}
                    {$roomtype_gallery_detail = "roomtype_gallery_detail_{$lang}"}
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="{$roomtype_gallery_detail}">Description ({$lang|upper})</label>
                            <textarea name="{$roomtype_gallery_detail}" cols="40" rows="3" class="form-control">{${$roomtype_gallery_detail}}</textarea>
                            {form_error($roomtype_gallery_detail)}
                        </div>
                    </div>
                {/foreach}
            </div>

            <div class="row">
                {foreach from=$lang_field item=lang}
                    {$roomtype_gallery_image = "roomtype_gallery_image_{$lang}"}
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="{$roomtype_gallery_image}">Roomtype gallery ({$lang|upper})</label>
                            <input type="file" name="{$roomtype_gallery_image}" value="" class="form-control">
                            {form_error($roomtype_gallery_image)}
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
                    &nbsp;<a href="{site_url url="admin/roomtype_gallery"}" class="btn btn-danger">Cancel</a></div>
            </div>
        </form>

        </div>
    </div>
</section>

{include file="footer.tpl"}