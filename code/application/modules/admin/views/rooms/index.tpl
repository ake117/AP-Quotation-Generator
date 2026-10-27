{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}

<section class="content-header">
    <h1>
        {ci_language line="mod_project_room"}
        <small>Manage the {ci_language line="mod_project_room"} displayed on the site here.</small>
    </h1>
    <ol class="breadcrumb">
        
        <li><i class="fa fa-building" aria-hidden="true"></i> {ci_language line="mod_project_room"}</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">List of {ci_language line="mod_project_room"}</h3>
            <div class="box-tools pull-right">
                <a href="{site_url url="admin/rooms/add"}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>New Project Room</a>
            </div>
        </div>
        <div class="box-body">
            <table id="grid" class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Room</th>
                        <!--<th>Room image</th>-->
                        <th>Room price</th>
                        <th>Booking price(TH)</th>
                        <th>Booking price(EN)</th>

                        <th>Project</th>
                        <th>Room type</th>
                        <th>Floor</th>

                        <th>Sequence</th>
                        <th>Status</th>
                        <th>Created</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody class="sortable">

                    {foreach from=$query item=row}
                        <tr id="item-{$row.room_id}"  style="cursor:move;">
                          <td>{$row.room_id}</td>
                          <td>{$row.room_name_th}</td>
                          <!--<td><img src="{$base_url}/media/rooms/{$row.room_plan_image_th}" class="img-responsive" width="120" alt="{$row.room_name_th}"></td>-->
                          <td>{$row.room_price|number_format:2}</td>
                          <td>{$row.booking_price_th|number_format:2}</td>
                          <td>{$row.booking_price_en|number_format:2}</td>

                          <td>{$row.project_name_th}</td>
                          <td>{$row.roomtype_name_th}</td>
                          <td>{$row.floor_name_th}</td>

                          <td style="text-align:center;">{$row.seq}</td>
                          <td>{active_status($row.is_active)}</td>
                          <td>{$row.create_date|date_format:"%d/%m/%Y %H:%M"}</td>
                          <td>
                                <div class="btn-group btn-group-xs">
                                    <a href="{site_url url="admin/rooms/edit"}/{$row.room_id}" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></a>
                                    <button class="btn btn-default" onclick="return confirm('{site_url url="admin/rooms/delete"}/{$row.room_id}');"><span class="glyphicon glyphicon-trash"></span></button>
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