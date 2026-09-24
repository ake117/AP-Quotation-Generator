{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}
<section class="content-header">
    <h1>
        {ci_language line="mod_project_room"}
        <small>Manage the {ci_language line="mod_project_room"} displayed on the site here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{site_url url="admin/dashboard"}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><a href="{site_url url="admin/rooms"}"><i class="fa fa-building" aria-hidden="true"></i> {ci_language line="mod_project_room"}</a></li>
        <li>Add {ci_language line="mod_project_room"}</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Add {ci_language line="mod_project_room"}</h3>
        </div>
        <div class="box-body">
            <form action="{site_url url="admin/rooms/add"}" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">

            <div class="row">
                {foreach from=$lang_field item=lang}
                    {$room_name = "room_name_{$lang}"}
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                        <label for="{$room_name}">Room name ({$lang|upper})</label>
                        <input type="text" name="{$room_name}" value="{${$room_name}}" class="form-control">
                        {form_error($room_name)}
                        </div>
                    </div>
                {/foreach}
            </div>

            <div class="row">
                <div class="col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="project_id">{ci_language line="mod_project"}</label>
                        {html_options id='project_id' name='project_id' options=$project selected=$project_id class='form-control'}
                        {form_error('project_id')}
                    </div>
                </div>

                <div class="col-xs-6 col-sm-3 col-md-3">
                    <div class="form-group">
                        <label for="floor_id">{ci_language line="mod_floor"}</label>
                        {html_options  id='floor_id' name='floor_id' options=$floor selected=$floor_id class='form-control'}
                        {form_error('floor_id')}
                    </div>
                </div>

                <div class="col-xs-6 col-sm-3 col-md-3">
                    <div class="form-group">
                        <label for="roomtype_id">{ci_language line="mod_roomtype"}</label>
                        {html_options name='roomtype_id' options=$roomtype selected=$roomtype_id class='form-control'}
                        {form_error('roomtype_id')}
                    </div>
                </div>
            </div>

            <div class="row">
                {foreach from=$lang_field item=lang}
                    {$room_detail = "room_detail_{$lang}"}
                    <div class="col-sm-6 col-md-6">
                    <div class="form-group">
                        <label for="{$room_detail}">Description ({$lang|upper})</label>
                        <textarea name="{$room_detail}" cols="40" rows="3" class="form-control">{${$room_detail}}</textarea>
                        {form_error($room_detail)}
                    </div>
                    </div>
                {/foreach}
            </div>

            <div class="row">
                {foreach from=$lang_field item=lang}
                    {$room_image = "room_plan_image_{$lang}"}
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="{$room_image}">Floor Plan ({$lang|upper})</label>
                            <input type="file" name="{$room_image}" value="" class="form-control">
                            {form_error($room_image)}
                        </div>
                    </div>
                {/foreach}
            </div>

            <div class="row">
                {foreach from=$lang_field item=lang}
                    {$room_image = "room_floor_plan_image_{$lang}"}
                    <div class="col-sm-6 col-md-6">
                        <div class="form-group">
                            <label for="{$room_image}">Floor room Plan ({$lang|upper})</label>
                            <input type="file" name="{$room_image}" value="" class="form-control">
                            {form_error($room_image)}
                        </div>
                    </div>
                {/foreach}
            </div>

            <div class="row ">

                <div class="col-sm-3 col-md-2">
                    <div class="form-group">
                        <label for="seq">Room price</label>
                        <input type="text" name="room_price" value="{$room_price}" class="form-control">
                        {form_error('room_price')}
                    </div>
                </div>

                {foreach from=$lang_field item=lang}
                    {$booking_price = "booking_price_{$lang}"}
                    <div class="col-xs-6 col-sm-3 col-md-2">
                        <div class="form-group">
                        <label for="{$booking_price}">Booking price ({$lang|upper})</label>
                        <input type="text" name="{$booking_price}" value="{${$booking_price}}" class="form-control">
                        {form_error($booking_price)}
                        </div>
                    </div>
                {/foreach}


                <div class="col-md-3">
                    <div class="form-group">
                        <label for="seq">Room size</label>
                        <input type="text" name="room_size" value="{$room_size}" class="form-control">
                        {form_error('room_size')}
                    </div>
                </div>

                <div class="col-md-3" >
                    <div class="form-group">
                        <label for="booking_status">Booking Status</label>
                        {html_options name='booking_status' options=$booking_status_option selected=$booking_status class='form-control'}
                        {form_error('booking_status')}
                    </div>
                </div>
            </div>

            <div class='row'>
                    <div class="col-xs-6 col-sm-3 col-md-2">
                        <div class="form-group">
                            <label for="seq">Display order</label>
                            <input type="text" name="seq" value="{$seq}" class="form-control">
                            {form_error('seq')}
                        </div>
                    </div>

                    <div class="col-xs-6 col-sm-3 col-md-2" >
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
                    &nbsp;<a href="{site_url url="admin/rooms"}" class="btn btn-danger">Cancel</a>
                </div>
            </div>
        </form>

        </div>
    </div>
</section>

{include file="footer.tpl"}
{include file="script.tpl"}