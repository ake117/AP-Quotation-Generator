{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}
<link rel="stylesheet" href="{$base_url}/lib/plugins/jQueryUI/jquery-ui.css">
<section class="content-header">
    <h1>
        Quotation
        <small>Manage Quotation here.</small>
    </h1>
    <ol class="breadcrumb">
        
        <li><a href="{$base_url}/admin/quotation"><i class="fa fa-users"></i> {ci_language line="mod_useraccount"}</a></li>
        <li>Edit Quotation</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Quotation</h3>
        </div>
        <div class="box-body">
              <form action="{$base_url}/admin/quotation/edit/{$row.quotation_id}" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <ul class="nav nav-pills" role="tablist" style="margin-bottom:20px;">
                </ul>
                <div class="tab-content">
                    <!--Painel de configuração geral-->
                    <div class="tab-pane active panel panel-default" id="userdata">
                        <div class="panel-heading">
                            Quotation data
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="status">Production Cost</label>
                                                {html_options name='production' options=$production_select_options selected=$row.production_id id='production' class='form-control disabled'}
                                                {form_error('project')}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Project</label>
                                                {html_options name='project' options=$projects_options selected=$row.project_id id='project' class='form-control'}
                                                {form_error('project')}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" id="name" value="{$row.production_name}" class="form-control" />
                                                {form_error('name')}
                                            </div>
                                        </div>
                                    </div> <!-- end row -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Duration</label>
                                                <input type="text" id="from" name="from" autocomplete="off" value="{$row.production_start}" class="form-control">
                                                {form_error('from')}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">    
                                                <label for="to">To</label>
                                                <input type="text" id="to" name="to" autocomplete="off" value="{$row.production_end}" class="form-control">
                                            </div>
                                        </div>
                                    </div> <!-- end row -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="pm">PM</label>
                                                {html_options name='pm' id='pm' options=$member_options selected=$row.issue_member_id class='form-control'}
                                                {form_error('pm')}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="job_no">Job No.</label>
                                                <input type="text" id="job_no" name="job_no" autocomplete="off" value="{$row.job_no}" class="form-control">
                                            </div>
                                        </div>
                                    </div> <!-- end row -->
                                    <div class="row">        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Customer</label>
                                                {html_options name='customer' id='customer' options=$customers_options selected=$row.customer_id class='form-control'}
                                                {form_error('status')}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="quo_no">Quo. No.</label>
                                                <input type="text" id="quo_no" name="quo_no" autocomplete="off" value="{$row.quotation_no}" class="form-control">
                                            </div>
                                        </div>
                                    </div> <!-- end row -->
                                    <div class="row">        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                {html_options name='status' id='status' options=$status_options selected=$row.quotation_status class='form-control'}
                                                {form_error('status')}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="quo_date">Quo. date</label>
                                                <input type="text" id="quo_date" name="quo_date" autocomplete="off" value="{$row.quotation_date}" class="form-control">
                                            </div>
                                        </div>
                                    </div> <!-- end row -->
                                </div> 
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="thumbnail">
                                                {if !empty($row.quotation_image)}
                                                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
                                                    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
                                                    <a data-fancybox="gallery" href="{$base_url}/upload/quotation/{$row.quotation_image}">
                                                        <embed src="{$base_url}/upload/quotation/{$row.quotation_image}" class="img-responsive" style="max-height: 300px;margin: 0 auto;">
                                                    </a>
                                                    {if $row.quotation_image|strstr:".pdf"}
                                                        <a target="_blank" href="{$base_url}/upload/quotation/{$row.quotation_image}">PDF File</a>
                                                    {/if}
                                                {else}
                                                    <img src="{$base_url}/lib/img/no-user.jpg" class="img-responsive" style="max-height: 180px;" />
                                                {/if}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="userfile">Upload Production Cost Image</label>
                                                <input type="file" name="file_quotation_image" class="form-control" />
                                                <input type="hidden" name="quotation_image" value="{$row.production_cost_image}"/>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="change_quotation_image" value="1">
                                                        Change Quotation Image
                                                    </label>
                                                </div>
                                                {form_error('image')}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                                    
<div class="panel-body" style="margin-bottom: 50px;">
    <div class="row" id="data_load">
        <div class="col-md-12 column">
                <table class="table table-bordered table-hover" id="tab_logic">
                        <thead>
                                <tr>
                                        <th class="text-center" style="width: 5%;">
                                                #
                                        </th>
                                        <th class="text-center" style="width: 5%">
                                                No.
                                        </th>
                                        <th class="text-center" style="width: 35%">
                                                Name
                                        </th>
                                        <th class="text-center" style="width: 15%">
                                                Quantity
                                        </th>
                                        <th class="text-center" style="width: 15%">
                                                Unit Price
                                        </th>
                                        <th class="text-center" style="width: 25%">
                                                Amount
                                        </th>
                                         <th class="text-center" style="width: 5%">
                                                Delete
                                        </th>
                                </tr>
                        </thead>
                        <tbody>
                            {foreach from=$row_task key=k item=v}
                                <tr id='addr{$k+1}'>
                                    <td valign="center">
                                        <span class="glyphicon glyphicon-sort" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Move"></span>
                                        <span class="glyphicon glyphicon-plus" onclick="plus_desc_task(this)" data-toggle="tooltip" data-placement="bottom" title="Expand"></span>
                                    </td>
                                    <td>
                                        <input type="text" name='no[]'  placeholder='No.' value="{$v.quotation_detail_no}" class="form-control disabled number_no" readonly style="text-align:center"/>
                                    </td>
                                    <td>
                                        <input type="text" name='name_task[]' placeholder='' value="{$v.quotation_detail_name}" class="form-control"/>
                                        <div class="form-group desc_task">
                                            <label for="comment">Description:</label>
                                            <textarea class="form-control" rows="5" name="description[]" id="description">{$v.quotation_detail_description}</textarea>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name='quantity[]' placeholder='' value="{$v.quotation_detail_qty}" class="form-control text-right numberfomat"/>
                                    </td>
                                    <td>
                                        <input type="text" name='unit_price[]' placeholder='' value="{$v.quotation_detail_price}" class="form-control text-right numberfomat"/>
                                    </td>
                                    <td>
                                        <input type="text" name='amount[]' placeholder='' value="{$v.quotation_detail_amount}" class="form-control text-right numberfomat"/>
                                    </td>
                                    <td>
                                        <span class="glyphicon glyphicon-remove btns_delete" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Delete"></span>
                                    </td>
                                </tr>
                            {/foreach}
                        </tbody>
                </table>
        </div>
    </div>
	<span id="add_row" class="btn btn-default pull-left">Add Row</span><span id='delete_row' class="pull-right btn btn-default">Delete Row</span>       
</div>                             
                                    
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        {if $row.quotation_status != 'approved'}
                            <button type="submit" name="submit" class="btn btn-primary">Save</button>
                            <a class="btn btn-danger" href='{$base_url}/admin/quotation'>Cancel</a>
                        {else}
                            <a class="btn btn-danger" href='{$base_url}/admin/quotation'>Back</a>
                        {/if}
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
                    
<style>
#tab_logic td {
    transition:all 1s ease-out;
}
#tab_logic td .glyphicon{
    cursor: pointer;
    padding: 0 3px;
}
#tab_logic td:first-child {
    text-align: center; 
    vertical-align: middle;
}
#tab_logic td:last-child {
    text-align: center; 
    vertical-align: middle;
}
textarea#description {
    resize: vertical;
}
.desc_task { display: none;}
.show_desc .desc_task { display: block;}
.glyphicon-sort { cursor: -webkit-grab !important; cursor: grab !important; }
.glyphicon-sort:active, .glyphicon-sort:focus { cursor: -webkit-grabbing !important; cursor: grabbing !important; }
.form-control.disabled {
    pointer-events: none;
    background: #e4e4e4;
}
.modal {
    text-align: center;
    padding: 0!important;
}

.modal:before {
  content: '';
  display: inline-block;
  height: 100%;
  vertical-align: middle;
  margin-right: -4px;
}

.modal-dialog {
  display: inline-block;
  text-align: left;
  vertical-align: middle;
}
</style>
                        
<script type="text/javascript">
    function plus_desc_task(elmnt){
        var a = $(elmnt);
        a.toggleClass("glyphicon-plus");
        a.toggleClass("glyphicon-minus");
        a.closest( "tr" ).toggleClass( "show_desc" );
    }
</script>
<script>
$(document).ready(function() {
    $('[data-toggle="tooltip"]').tooltip();
    function td_order() {
        var b = 1;
        $('#tab_logic > tbody > tr').each(function () {
            $(this).attr("id","addr"+b);
            $(this).find('.number_no').val(b);
            b++;
        });
    }
    $('tbody').sortable({
        group: 'no-drop',
        handle: '.glyphicon-sort',
        stop: function( event, ui ) {
           td_order();
        }
    });
    $('body').on('click','.btns_delete',function(){
        var bt_delete = $(this);
        var txt_row = bt_delete.parent().parent().find('.number_no').val();
        bootbox.confirm("ยืนยันที่จะลบ Row :: "+txt_row+" นี้", function(result) {
            if(result){
                bt_delete.parent().parent().remove();
                td_order();
                sumWorks();
            }
         }); 
    });

    $("#add_row").click(function(){ 
        var b = $("#tab_logic > tbody > tr").length;
        console.log(b);
        var i = b+1;
        $('#tab_logic').append('<tr id="addr'+i+'"></tr>');
        $('#addr'+i).html($('#addr'+b).html()).find('td:first-child').html('<span class="glyphicon glyphicon-sort" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Move"></span><span class="glyphicon glyphicon-plus" onclick="plus_desc_task(this)"></span>')
        $('#addr'+i).find('td:nth-child(3) input').val('');
        $('#addr'+i).find('td:nth-child(4) input').val('');
        $('#addr'+i).find('td:nth-child(5) input').val('');
        $('#addr'+i).find('td:nth-child(6) input').val('');
        $('#addr'+i).find('td:nth-child(2) input').val(i);
        i++; 
    } );
    
    $("#delete_row").click(function() {
        var i = $("#tab_logic > tbody > tr").length;
        if(i>1) {
            $("#tab_logic > tbody > tr:last-child").remove();
        }
    } );
    
    $('#production').change(function() {
        var p_val = $(this).val();
        if( p_val ) {
            $('#project').addClass('disabled');
            $('#customer').addClass('disabled');
            $('#name').addClass('disabled');
            $('#from').addClass('disabled');
            $('#to').addClass('disabled');
            $('#pm').addClass('disabled');
            $('#job_no').addClass('disabled');
            $.ajax( {
                url: '/admin/quotation/load_ajax_estimated/'+p_val,
                dataType: "json",
                success: function(data) {
                    $('#project').val(data.project_id);
                    $('#customer').val(data.customer_id);
                    $('#name').val(data.production_name);
                    $('#from').val(data.production_start);
                    $('#to').val(data.production_end);
                    $('#pm').val(data.pm_member_id);
                    $('#job_no').val(data.job_no);
                }
            } );
        }else{
            $('#project').removeClass('disabled');
            $('#customer').removeClass('disabled');
            $('#name').removeClass('disabled');
            $('#from').removeClass('disabled');
            $('#to').removeClass('disabled');
            $('#pm').removeClass('disabled');
            $('#job_no').removeClass('disabled');
            $('#project').val('');
            $('#name').val('');
            $('#from').val('');
            $('#to').val('');
            $('#pm').val('');
            $('#job_no').val('');
        }
        load_data(p_val);
    } );

    function load_data(id) {
        $('#data_load').load('/admin/quotation/load_estimated/'+id);
    }

});

  $( function() {
      
    $( "#quo_date" ).datepicker({
          dateFormat: "yy-mm-dd",
          
          changeMonth: true,
          numberOfMonths: 3,
    });
    var dateFormat = "yy-mm-dd",
      from = $( "#from" )
        .datepicker({
          dateFormat: dateFormat,
          
          changeMonth: true,
          numberOfMonths: 3,
          maxDate:$( "#to" ).val()
        })
        .on( "change", function() {
            to.datepicker( "option", "minDate", getDate( this ) );
        }),
      to = $( "#to" ).datepicker({
        dateFormat: dateFormat,
        
        changeMonth: true,
        numberOfMonths: 3,
        minDate:$( "#from" ).val()
      })
      .on( "change", function() {
            from.datepicker( "option", "maxDate", getDate( this ) );
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
  } );
</script>

{include file="footer.tpl"}