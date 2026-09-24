{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}

<section class="content-header">
    <h1>
        {ci_language line="mod_roomtype"}
        <small>Manage the Room type displayed on the site here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{site_url url="admin/dashboard"}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><i class="fa fa-braille" aria-hidden="true"></i> {ci_language line="mod_roomtype"}</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">List of {ci_language line="mod_roomtype"}</h3>
            <div class="box-tools pull-right">
                <a href="{site_url url="admin/roomtype/add"}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>New Room Type</a>
            </div>
        </div>
        <div class="box-body">
            <table id="grid" class="table table-striped">
			    <thead>
			        <tr>
			            <th>#</th>
                                    {foreach from=$lang_field item=lang}
                                        <th>Room Type ({$lang|upper})</th>
                                    {/foreach}
			            <th>Sequence</th>
			            <th>Status</th>
			            <th>Created</th>
			            <th>Action</th>
			        </tr>
			    </thead>
			    <tbody class="sortable">

                                {foreach from=$query item=row}
                                    <tr id="item-{$row.roomtype_id}"  style="cursor:move;">
                                      <td>{$row.roomtype_id}</td>
                                      {foreach from=$lang_field item=lang}
                                        {$roomtype_name = "roomtype_name_{$lang}"}
                                        <td>{$row.{$roomtype_name}}</td>
                                      {/foreach}
                                      <td style="text-align:center;">{$row.seq}</td>
                                      <td>{active_status($row.is_active)}</td>
                                      <td>{$row.create_date|date_format:"%d/%m/%Y %H:%M"}</td>
                                      <td>
                                            <div class="btn-group btn-group-xs">
                                                <a href="{site_url url="admin/roomtype/edit"}/{$row.roomtype_id}" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></a>
                                                <button class="btn btn-default" onclick="return confirm('{site_url url="admin/roomtype/delete"}/{$row.roomtype_id}');"><span class="glyphicon glyphicon-trash"></span></button>
                                            </div>
                                      </td>
                                    </tr>
                                {/foreach}
			    </tbody>
			</table>
        </div>
    </div>
</section>

<script type="text/javascript">
    $(function(){
        $(".sortable").sortable({
            axis: 'y',
            update: function (event, ui) {
                var data = $(this).sortable('serialize');
                alert(data);
                $.ajax({
                    data: data,
                    type: 'POST',
                    url: '{site_url url="admin/banners/update_sequence"}'
                });
            }
        });
    });

</script>
{include file="footer.tpl"}