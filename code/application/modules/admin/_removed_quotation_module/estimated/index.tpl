{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<section class="content-header">
    <h1>
        Estimated
        <small>Manage Estimated here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{site_url url="admin/dashboard"}"><i class="fa fa-dashboard"></i> {ci_language line="mod_dashboard"}</a></li>
        <li><i class="fa fa-users"></i> {ci_language line="mod_useraccount"}</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status">Duration</label>
                        <input type="text" id="from" name="from" autocomplete="off" value="{$smarty.session.e_t_from}" class="form-control">
                        {form_error('from')}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">    
                        <label for="to">To</label>
                        <input type="text" id="to" name="to" autocomplete="off" value="{$smarty.session.e_t_to}" class="form-control">
                    </div>
                </div>
            </div> <!-- end row -->   
            <div class="row">        
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status">Status</label>
                        {html_options name='status' id='status' options=$status_options selected=$smarty.session.e_status class='form-control'}
                        {form_error('status')}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="projects">Project</label>
                        {html_options name='projects' id='projects' options=$projects_options selected=$smarty.session.e_projects class='form-control'}
                        {form_error('projects')}
                    </div>
                </div>
            </div> <!-- end row -->
            <div class="row">
                <div class="col-md-12 column">
                    <h3 class="box-title">List of Estimated</h3>
                    <div class="box-tools pull-right">
                        <a href="{site_url url="admin/estimated/add"}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>New Estimated</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-body">
            <table id="grid" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Project</th>
                        <th>Name</th>
                        <th style="text-align: right;">Work Hrs.</th>
                        <th>Job No.</th>
                        <th>Status</th>
                        <th>PM</th>
                        <th>Actions</th>
                        <th>Export</th>
                        <th>Created Date</th>
                      </tr>
                    </thead>
                    <tbody>
                        {foreach from=$accounts item=row}
                            <tr>
                              <td>{$row->production_id}</td>
                              <td>{$row->project_id}</td>
                              <td>{$row->production_name}</td>
                              <td style="text-align: right;">{$row->total_work_hours}</td>
                              <td>{$row->job_no}</td>
                              <td>{$row->production_status}</td>
                              <td>{$row->pm_member_id}</td>
                              <td>{$row->actions}</td>
                              <td><a href="{site_url url="admin/estimated/export"}/{$row->production_id}" class="btn btn-sm btn-primary link_export"><span class="glyphicon glyphicon-download"></span> Export</a></td>
                              <td>{$row->create_date}</td>
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
    $( function() {
        var dateFormat = "yy-mm-dd",
          from = $( "#from" )
            .datepicker({
              dateFormat: dateFormat,
              defaultDate: "-1m",
              changeMonth: true,
              numberOfMonths: 3,
              maxDate:$( "#to" ).val()
            })
            .on( "change", function() {
                to.datepicker( "option", "minDate", getDate( this ) );
        load_table();
            }),
          to = $( "#to" ).datepicker({
            dateFormat: dateFormat,
            defaultDate: "-2m",
            changeMonth: true,
            numberOfMonths: 3,
            minDate:$( "#from" ).val()
          })
          .on( "change", function() {
                from.datepicker( "option", "maxDate", getDate( this ) );
        load_table();
          });

        function getDate( element ) {
          var date;
          try {
            date = $.datepicker.parseDate( dateFormat, element.value );
          } catch( error ) {
            date = null;
          }

          return date;
        }
        
        var table = $('#grid').DataTable();
        table.order( [ 9, 'desc' ] ).draw();
        
        $('#status').change(function() {
            load_table();
        } );
                
        $('#projects').change(function() {
            load_table();
        } );
        
        load_table();
{*        $(document).on('keyup', 'input.form-control.input-sm', function () {
            var search = $(this).val();
            if (search === undefined || search === null) {
                search = "";
            }    
            var t_from = $("#from").val();
            var t_to = $("#to").val();
            var status = $( "#status option:selected" ).val();
            var projects = $( "#projects option:selected" ).val();
            $('.box-body').load('/admin/estimated/load?status='+status+"&projects="+projects+"&t_from="+t_from+"&t_to="+t_to+"&search="+search);
        });*}
        
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
            var t_from = $("#from").val();
            var t_to = $("#to").val();
            var status = $( "#status option:selected" ).val();
            var projects = $( "#projects option:selected" ).val();
            $('.box-body').load('/admin/estimated/load?status='+status+"&projects="+projects+"&t_from="+t_from+"&t_to="+t_to+"&search="+search);
            
            setTimeout(function() { 
                var table = $('#grid').DataTable( {
                    "scrollX": true,
                    //"search": {
                    //    "search": "{$smarty.session.e_search}"
                    //}
                } );
                table.order( [ order0 , order1 ] ).draw();
            }, 500);
        }
    } );
</script>
<script>
    //$('#combobox').combobox();
    $(document).ready(function() {
        
    } );
</script>
{include file="footer.tpl"}