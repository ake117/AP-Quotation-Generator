{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}
<section class="content-header">
    <h1>
        {ci_language line="mod_facility"}
        <small>Manage the {ci_language line="mod_facility"} displayed on the site here.</small>
    </h1>
    <ol class="breadcrumb">
        
        <li><a href="{site_url url="admin/facility"}"><i class="fa fa-tree" aria-hidden="true"></i> {ci_language line="mod_facility"}</a></li>
        <li>Edit {ci_language line="mod_facility"}</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Edit {ci_language line="mod_facility"}</h3>
        </div>
        <div class="box-body">
            <form action="{site_url url='admin/facility/edit'}/{$row.facility_id}" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <div class="row">

                    <div class="col-sm-4 col-md-4">
                        <div class="form-group">
                            <label for="project_id">{ci_language line="mod_project"}</label>
                            {html_options name='project_id' disabled=1 options=$project selected=$row.project_id class='form-control'}
                            {form_error($project_name)}
                        </div>
                    </div>

                    {foreach from=$lang_field item=lang}
                        {$facility_name = "facility_name_{$lang}"}
                        <div class="col-sm-4 col-md-4">
                            <div class="form-group">
                                <label for="{$facility_name}">{ci_language line="mod_facility"} name ({$lang|upper})</label>
                                <input type="text" name="{$facility_name}" value="{$row.{$facility_name}}" class="form-control">
                                {form_error($facility_name)}
                            </div>
                        </div>
                    {/foreach}
                </div>

                <div class="row">
                {foreach from=$lang_field item=lang}
                    {$facility_detail = "facility_detail_{$lang}"}
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="{$facility_detail}">Description ({$lang|upper})</label>
                            <textarea name="{$facility_detail}" cols="40" rows="3" class="form-control">{$row.{$facility_detail}}</textarea>
                            {form_error($facility_detail)}
                        </div>
                    </div>
                {/foreach}
                </div>

                <div class="row">
                    {foreach from=$lang_field item=lang}
                        {$facility_image = "facility_image_{$lang}"}
                        {$alter_facility_image = "alter_{$facility_image}"}
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <img src="{$base_url}/media/facility/{$row.{$facility_image}}" class="img-responsive img-thumbnail" style="margin-top:5px;display: block;" alt="">
                                <input type="file" name="{$facility_image}" value="" class="form-control input_file">
                                <label class="lbl_file" >Facility image ({$lang|upper}):</label>
                                <label class="alter_file">
                                    <input type="checkbox" name="{$alter_facility_image}" value="1" {if $smarty.post.{$alter_facility_image}=='1'}checked{/if}  />
                                    Alter image
                                </label>
                                {form_error($facility_image)}
                            </div>
                        </div>
                    {/foreach}
                </div>

                <div class="row ">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="seq">Display order</label>
                            <input type="text" name="seq" value="{$row.seq}" class="form-control">
                            {form_error('seq')}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="is_active">Status</label>
                            {html_options name='is_active' options=$published_options selected=$row.is_active class='form-control'}
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row ">
                    <div class="col-md-12">
                        <button name="submit" type="submit" class="btn btn-primary">Save change</button>
                        &nbsp;<a href="{site_url url="admin/facility"}" class="btn btn-danger">Cancel</a></div>
                </div>
            </form>

        </div>
    </div>
</section>

{include file="footer.tpl"}