{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}

<section class="content-header">
    <h1>
        Booking
        <small>Export your Booking</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{site_url url="admin/dashboard"}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><i class="fa fa-envelope-o"></i> Booking</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Booking list</h3>
            <div class="box-tools pull-right">
                <a href="{site_url url="admin/booking/export"}" class="btn btn-success"><span class="glyphicon glyphicon-download"></span> Export CSV</a>
            </div>
        </div>
        <div class="box-body">
            <!--<p class="text-danger">
                <b>Note:</b>
                WPanel does not send bulk messages, use a separate service for this.
            </p>-->

            <table id="grid" class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>E-mail</th>
                        <th>Phone</th>
                        <th>Project</th>
                        <th>Room type</th>
                        <th>Floor</th>
                        <th>Room</th>
                        <th>Booking price</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    {foreach from=$booking item=row}
                        <tr id="item-{$row.booking_id}" style="cursor:move;">
                            <td>{$row.booking_id}</td>
                            <td>{$row.fullname}</td>
                            <td>{$row.email}</td>
                            <td>{$row.phone}</td>
                            <td>{$row.project_name_th}</td>
                            <td>{$row.roomtype_name_th}</td>
                            <td>{$row.floor_name_th}</td>
                            <td>{$row.room_name_th}</td>
                            <td>{$row.total_paid}</td>
                            <td>{$row.create_date|date_format:"%d/%m/%Y %H:%M"}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>

        </div>
    </div>
</section>