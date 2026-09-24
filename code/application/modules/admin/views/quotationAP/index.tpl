{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<div class="modal_loading"></div>
<section class="content-header">
    <h1>
        Quotation AP
        <small>Manage Quotation AP here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{site_url url="admin/dashboard"}"><i class="fa fa-dashboard"></i> {ci_language line="mod_dashboard"}</a></li>
        <li><i class="fa fa-users"></i> Quotation</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <div class="row">
                <div class="col-md-12">
                    <form id="submit_upload1" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" style="position:relative;">
                                    <label for="userfile">Upload AP Data Referrence</label>
                                    <input type="file" name="file_quotation_ref" class="form-control" data-data="{($quotation_doc.reference.create_date=='')?'0':'1'}" />
                                    <div class="update_doc file_quotation_ref">Last Update : {$quotation_doc.reference.create_date}</div>
                                </div>
                            </div>    
                        </div>  
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group" style="position:relative;">
                                    <label for="userfile">Upload AP Data Referrence Synonym</label>
                                    <input type="file" name="file_quotation_synonym" class="form-control" data-data="{($quotation_doc.synonym.create_date=='')?'0':'1'}"/>
                                    <div class="update_doc file_quotation_synonym">Last Update : {$quotation_doc.synonym.create_date}</div>
                                </div>
                            </div>    
                        </div>
                        <div class="row">
                            <div class="col-md-12">        
                                <div class="form-group" style="position:relative;">
                                    <label for="userfile">Upload AP Online Media Plan</label>
                                    <input type="file" name="file_quotation_data" class="form-control" data-data="{($quotation_doc.data.create_date=='')?'0':'1'}"/>  
                                    <div class="update_doc file_quotation_data">Last Update : {$quotation_doc.data.create_date}</div>
                                </div>
                            </div>    
                        </div>    
                        <div class="row">
                            <div class="col-md-6">        
                                <div class="form-group">
                                    <label for="status">Quotation Date</label>
                                    <input type="text" id="quotation_date" name="quotation_date" autocomplete="off" value="" class="form-control">                                    
                                </div>
                            </div>
                            <div class="col-md-6">        
                                <div class="form-group">
                                    <label for="status">Quotation Running</label>
                                    <input type="text" id="quotation_running" name="quotation_running" autocomplete="off" value="" class="form-control">                                    
                                </div>
                            </div>
                        </div>    
                        <button type="" name="btn_upload1" class="btn btn-primary">Process</button>
                        <button type="" name="btn_export1" class="btn btn-primary" style="display:none">Export</button>
                    </form>
                </div>
            </div>        
            <div class="row" style="display:none">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="from">Duration</label>
                        <input type="text" id="from" name="from" autocomplete="off" value="{$smarty.session.q_t_from}" class="form-control">
                        {form_error('from')}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">    
                        <label for="to">To</label>
                        <input type="text" id="to" name="to" autocomplete="off" value="{$smarty.session.q_t_to}" class="form-control">
                    </div>
                </div>
            </div> <!-- end row -->   
            <div class="row" style="display:none">        
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="status">Status</label>
                        {html_options name='status' id='status' options=$status_options selected=$smarty.session.q_status class='form-control'}
                        {form_error('status')}
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="projects">Project</label>
                        {html_options name='projects' id='projects' options=$projects_options selected=$smarty.session.q_projects class='form-control'}
                        {form_error('projects')}
                    </div>
                </div>
            </div> <!-- end row -->
            <div class="row" style="display:none">
                <div class="col-md-12 column">
                    <h3 class="box-title">List of Quotation</h3>
                    <div class="box-tools pull-right">
                        <a href="{site_url url="admin/quotation/add"}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>New Quotation</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row" >        
            <div class="col-md-12" >
                <div id="process_message" class="form-group" style="padding: 10px;border: 1px solid #CCC;margin: 10px;">
                    
                </div>
            </div>   
        </div>    
        <div class="box-body" style="display:none">
            <table id="grid" class="table table-bordered table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Project</th>
                        <th>Name</th>
                        <th style="text-align: right;">Work Hrs.</th>
                        <th>Status</th>
                        <th>PM</th>
                        <th>Actions</th>
                        <th>Export</th>
                        <th>Created Date</th>
                      </tr>
                    </thead>
                    <tbody>
                        {*foreach from=$accounts item=row}
                            <tr>
                              <td>{$row->quotation_id}</td>
                              <td>{$row->project_id}</td>
                              <td>{$row->production_name}</td>
                              <td style="text-align: right;">{$row->total_work_hours}</td>
                              <td>{$row->quotation_status}</td>
                              <td>{$row->pm_member_id}</td>
                              <td>{$row->actions}</td>
                              <td><a href="{site_url url="admin/quotation/export"}/{$row->quotation_id}" class="btn btn-sm btn-primary link_export"><span class="glyphicon glyphicon-download"></span> Export</a></td>
                              <td>{$row->create_date}</td>
                            </tr>
                        {/foreach*}
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
.update_doc {
    position: absolute;
    right: 0;
    top: 30px;
    padding: 2px 10px;
    background-color: #3c8dbc;
    margin-right: 10px;
    color: #FFF;
}
.modal_loading {
    display: none;
    position: fixed;
    z-index: 10000;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    background: rgba( 255, 255, 255, .8 ) url(/lib/img/spinner40.gif) 50% 50% no-repeat;
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
        table.order( [ 8, 'desc' ] ).draw();
                
        $('#status').change(function() {
            load_table();
        } );
        
        $('#projects').change(function() {
            load_table();
        } );
        
        load_table();
        
        function load_table() {
            {*var table = $('#grid').DataTable();
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
            $('.box-body').load('/admin/quotation/load?status='+status+"&projects="+projects+"&t_from="+t_from+"&t_to="+t_to+"&search="+search);
            
            setTimeout(function() { 
                var table = $('#grid').DataTable( {
                    "scrollX": true
                } );
                table.order( [ order0 , order1 ] ).draw();
            }, 500);*}
        }
    } );
</script>
<script>
    //$('#combobox').combobox();
    
    function submit_quotation($form) {
        
        $("#process_message").html("");
        $(".modal_loading").show();
        
        $.ajax({
            url:'{site_url url="admin/quotationAP/quotationAP/"}',
            type:"post",
            data:new FormData($form),
            processData:false,
            contentType:false,
            cache:false,
            success: function(data){
                $(".modal_loading").hide();
                
                data = JSON.parse(data);
                console.log(data.status);
                
                if (data.status=='success') {
                    $("#process_message").html("Process Success<br>");
                } else {
                    $("#process_message").html("Process Error<br>");
                }
                
                if (typeof(data.file_quotation_ref)!='undefined') {
                    $(".file_quotation_ref").html("Last Update : "+data.file_quotation_ref);
                }
                if (typeof(data.file_quotation_synonym)!='undefined') {
                    $(".file_quotation_synonym").html("Last Update : "+data.file_quotation_synonym);
                }
                if (typeof(data.file_quotation_data)!='undefined') {
                    $(".file_quotation_data").html("Last Update : "+data.file_quotation_data);
                }
                
                $("#process_message").append(data.message); 
                
                //alert("Quotation AP Successful.");
            },
            error: function() {
                $(".modal_loading").hide();
                $("#process_message").append("Quotation AP Error."); 
                
                //alert("Quotation AP Error.");
            }
            
        });
    }
    
    function get_quotation_running() {
            
        $quotation_date = $("#quotation_date").val();
        
        $.ajax({
            url:'{site_url url="admin/quotationAP/get_quotationAP_running/"}',
            type:"post",
            data: { quotation_date : $quotation_date },
            cache:false,
            async:false,
            success: function(data){
                $("#quotation_running").val(data);
            }
        });
    }
    
    $(document).ready(function() {
        
        $("#quotation_date").datepicker({
            dateFormat: "yy-mm-dd",
            defaultDate: "-1m",
            changeMonth: true,
            numberOfMonths: 1,
            gotoCurrent: true
        }).on( "change", function() {            
            get_quotation_running();
        });
        
        $('#submit_upload1').submit(function(e){
            e.preventDefault();
            
            $validate = true;
            
            $filehasref = $("input[name='file_quotation_ref']").data('data');
            $fileref = $("input[name='file_quotation_ref']").val();
            if ($fileref == '' && $filehasref==0) {
                if ($("input[name='file_quotation_ref']").next().find(".label-danger").length == 0) {
                    $("input[name='file_quotation_ref']").after( '<p><span class="label label-danger">AP Data Reference is required.</span></p>' );
                }
                $validate = false;
            } else {
                $("input[name='file_quotation_ref']").next().find(".label-danger").remove();
            }
            
            $filehassynonym = $("input[name='file_quotation_synonym']").data('data');
            $filesynonym = $("input[name='file_quotation_synonym']").val();
            if ($filesynonym == '' && $filehassynonym==0) {
                if ($("input[name='file_quotation_synonym']").next().find(".label-danger").length == 0) {
                    $("input[name='file_quotation_synonym']").after( '<p><span class="label label-danger">AP Reference Synonym is required.</span></p>' );
                }
                $validate = false;
            } else {
                $("input[name='file_quotation_synonym']").next().find(".label-danger").remove();
            }
            
            $filehasdata = $("input[name='file_quotation_data']").data('data');
            $fileplan = $("input[name='file_quotation_data']").val();
            if ($fileplan == '' && $filehasdata==0) {    
                if ($("input[name='file_quotation_data']").next().find(".label-danger").length == 0) {
                    $("input[name='file_quotation_data']").after( '<p><span class="label label-danger">Media Plan is required.</span></p>' );
                } 
                $validate = false;
            } else {
                $("input[name='file_quotation_data']").next().find(".label-danger").remove();                
            }
            
            $quotation_date = $("#quotation_date").datepicker("getDate");
            if ($quotation_date == null) {                    
                if ($("#quotation_date").next().find(".label-danger").length == 0) {
                    $("#quotation_date").after( '<p><span class="label label-danger">Quotation Date is required.</span></p>' );
                } 
                $validate = false;
            } else {
                $("#quotation_date").next().find(".label-danger").remove();                
            }
            
            $quotation_running = $("#quotation_running").val();
            if ($quotation_running == '') {                    
                if ($("#quotation_running").next().find(".label-danger").length == 0) {
                    $("#quotation_running").after( '<p><span class="label label-danger">Quotation Running is required.</span></p>' );
                } 
                $validate = false;
            } else {
                $("#quotation_running").next().find(".label-danger").remove();                
            }
            
            
            
            if ($validate == false) {
                return;
            } else {
                submit_quotation(this); 
            }            
        });
        
        $('#submit_export1').click(function(e){
            $("#process_message").html("");
            $(".modal_loading").show();

            $.ajax({
                url:'{site_url url="admin/quotationAP/quotationAP_export/"}',
                type:"post",
                data:new FormData($form),
                processData:false,
                contentType:false,
                cache:false,
                success: function(data){
                    $(".modal_loading").hide();

                    data = JSON.parse(data);
                    console.log(data.status);

                    if (data.status=='success') {
                        $("#process_message").html("Process Success<br>");
                    } else {
                        $("#process_message").html("Process Error<br>");
                    }

                    $("#process_message").append(data.message);                
                    alert("Quotation AP Successful.");
                },

            });
        });
        
    } );
</script>
{include file="footer.tpl"}