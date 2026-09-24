{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}
<section class="content-header">
    <h1>
        {ci_language line="mod_roomtype"}
        <small>Manage the Room types displayed on the site here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{site_url url="admin/dashboard"}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{site_url url="admin/roomtype"}"><i class="fa fa-braille" aria-hidden="true"></i> {ci_language line="mod_roomtype"}</a></li>
        <li>Edit Room type</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Room type</h3>
        </div>
        <div class="box-body">
            <form action="{site_url url='admin/roomtype/edit'}/{$row.roomtype_id}" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">

                <div class="row">
                    {foreach from=$lang_field item=lang}
                        {$roomtype_name = "roomtype_name_{$lang}"}
                        <div class="col-sm-6 col-md-6">
                            <div class="form-group">
                                <label for="{$roomtype_name}">Room type ({$lang|upper})</label>
                                <input type="text" name="{$roomtype_name}" value="{$row.{$roomtype_name}}" class="form-control">
                                {form_error($roomtype_name)}
                            </div>
                        </div>
                    {/foreach}
                </div>
                <div class="row">
                {foreach from=$lang_field item=lang}
                    {$roomtype_detail = "roomtype_detail_{$lang}"}
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="{$roomtype_detail}">Description ({$lang|upper})</label>
                            <textarea name="{$roomtype_detail}" cols="40" rows="3" class="form-control">{$row.{$roomtype_detail}}</textarea>
                            {form_error($roomtype_detail)}
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
                        &nbsp;<a href="{site_url url="admin/roomtype"}" class="btn btn-danger">Cancel</a></div>
                </div>
            </form>

        </div>
    </div>
</section>

{include file="footer.tpl"}