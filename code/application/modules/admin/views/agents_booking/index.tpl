{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}
<script src="/assets/js/bootstrap-combobox.js"></script>
<script type="text/javascript" src="/assets/js/moment.min.js"></script>
<script src="/assets/js/daterangepicker.min.js"></script>
<link rel="stylesheet" type="text/css" href="/assets/css/daterangepicker.css" />
<section class="content-header">
    <h1>
        Agents Booking
        <small>Export your Agents Booking</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{site_url url="admin/dashboard"}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><i class="fa fa-envelope-o"></i> Agents Booking</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Agents Booking list</h3>
            <div class="box-tools pull-right">
                <a href="{site_url url='admin/agents_booking/export'}" class="btn btn-success link_export"><span class="glyphicon glyphicon-download"></span> Export CSV</a>
            </div>
        </div>
        <div class="box-header with-border">
            <h3 class="box-title">Filter</h3>
            <div class="row">
                <div class="col-sm-3 col-md-3">
                    <div class="form-group">
                        <label for="is_active">Agents Name</label>
                        {html_options name='agent_id' options=$get_agents_name selected=$agent_selected class='form-control' id='agent_id'}
                    </div>
                </div>
                <div class="col-sm-3 col-md-3">
                    <div class="form-group">
                        <label for="is_active">Payment Status</label>
                        {html_options name='stauts_pay' options=$stauts_pay selected=$agent_selected class='form-control' id='stauts_pay'}
                    </div>
                </div>
                <div class="col-sm-3 col-md-3">
                     <label for="is_active">Create Date</label>
                    <input type="text" name="datefilter" id="datefilter" value="" class='form-control'/>
                </div>
                <div class="col-sm-3 col-md-3">
                </div>
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
                        {*<th>#</th>*}
                        <th>Transaction Date</th>
                        <th>Merchant Ref.</th>
                        <th>System Ref.</th>
                        <th>Name</th>
                        <th>E-mail</th>
                        <th>Mobile</th>
                        <th>Project</th>
                        <th>Booking Price</th>
                        <th>Agent Name</th>
                        <th>Status</th>
                        <th>Created</th>
                    </tr>
                </thead>
                <tbody class="sortable">
                    {foreach from=$booking item=row}
                        <tr id="item-{$row.payment_id}" style="/*cursor:move;*/">
                            {*<td>{$row.payment_id}</td>*}
                            <td>{$row.txtime|date_format:"%d/%m/%Y %H:%M"}</td>
                            <td>{$row.token}</td>
                            <td style="text-align: right;">{$row.payref}</td>
                            <td>{$row.prefix} {$row.name} {$row.surname}</td>
                            <td>{$row.email}</td>
                            <td>{$row.phone}</td>
                            <td>{$row.project_unit}</td>
                            <td>{$row.total_paid}</td>
                            <td>{$row.agent_name}</td>
                            <td>{$row.payment_status}</td>
                            <td>{$row.create_date|date_format:"%d/%m/%Y %H:%M"}</td>
                        </tr>
                    {/foreach}
                </tbody>
            </table>

        </div>
    </div>
</section>
<style>
.input-group {
    width: 100%;
}    
ul.typeahead.typeahead-long.dropdown-menu {
    width: 100%;
    font-size: 1.5rem;
}
#grid_wrapper {
    overflow: hidden;
}
#grid_wrapper .row:nth-child(2){
    overflow-x: scroll;
}
div.dataTables_wrapper , table.dataTable {
    min-width: 100%;
    max-width: 100%;
    margin: 0 auto;
}
table.dataTable td{
    padding: 10px 15px !important;
    white-space: nowrap;
}
table.dataTable th {
    white-space: nowrap;
}
</style>
<script>
    //$('#combobox').combobox();
    $(document).ready(function() {
        var table = $('#grid').DataTable();
        table.order( [ 8, 'desc' ] ).draw();
                
        $('#stauts_pay').change(function() {
            load_table();
        } );
        
        $('#agent_id').change(function() {
            load_table();
        } );
        
        $('input.form-control.input-sm').change(function() {
            var table = $('#grid').DataTable();
            var order = table.order();
            var sort = order[0][0]+','+order[0][1];
            
            var search = $("input.form-control.input-sm").val();
            var date = $("#datefilter").val();
            var datefilter = date.replace(" - ", ",");
            var agent_id = $( "#agent_id option:selected" ).val();
            var stauts_pay = $( "#stauts_pay option:selected" ).val();
            $('.link_export').attr("href", '{$base_url}/admin/agents_booking/export?agent_id='+agent_id+"&stauts_pay="+stauts_pay+"&search="+search+"&sort="+sort+"&datefilter="+datefilter);
        } );
        
        $('.sorting').click(function() {
            var table = $('#grid').DataTable();
            var order = table.order();
            var sort = order[0][0]+','+order[0][1];
            
            var search = $("input.form-control.input-sm").val();
            var date = $("#datefilter").val();
            var datefilter = date.replace(" - ", ",");
            var agent_id = $( "#agent_id option:selected" ).val();
            var stauts_pay = $( "#stauts_pay option:selected" ).val();
            $('.link_export').attr("href", '{$base_url}/admin/agents_booking/export?agent_id='+agent_id+"&stauts_pay="+stauts_pay+"&search="+search+"&sort="+sort+"&datefilter="+datefilter);
        } );
        
        function load_table() {
            var table = $('#grid').DataTable();
            var order = table.order();
            var order0 = order[0][0];
            var order1 = order[0][1];
            var sort = order0+','+order[0][1];
            
            var search = $("input.form-control.input-sm").val();
            if (search === undefined || search === null) {
                search = "";
            }    
            var date = $("#datefilter").val();
            var datefilter = date.replace(" - ", ",");
            var agent_id = $( "#agent_id option:selected" ).val();
            var stauts_pay = $( "#stauts_pay option:selected" ).val();
            $('.link_export').attr("href", '{$base_url}/admin/agents_booking/export?agent_id='+agent_id+"&stauts_pay="+stauts_pay+"&search="+search+"&sort="+sort+"&datefilter="+datefilter);
            $('.box-body').load('/admin/agents_booking/load?agent_id='+agent_id+"&stauts_pay="+stauts_pay+"&search="+search+"&sort="+sort+"&datefilter="+datefilter);
            
            setTimeout(function() { 
                var table = $('#grid').DataTable( {
                    "scrollX": true
                } );
                table.order( [ order0 , order1 ] ).draw();
            }, 500);
        }
        
        $(function() {

            $('input[name="datefilter"]').daterangepicker( {
                autoUpdateInput: false,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY'
                }
            } );

            $('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
                load_table();
            } );

            $('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val('');
                load_table();
            } );

        } );
    } );
</script>
                    