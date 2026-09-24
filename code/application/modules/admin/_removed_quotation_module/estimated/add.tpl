{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<section class="content-header">
    <h1>
        Estimated
        <small>Manage estimated here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{site_url url="admin/dashboard"}"><i class="fa fa-dashboard"></i> {ci_language line="mod_dashboard"}</a></li>
        <li><a href="{site_url url="admin/estimated"}"><i class="fa fa-users"></i> {ci_language line="mod_useraccount"}</a></li>
        <li>Create user acccount</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Create Estimated</h3>
        </div>
        <div class="box-body">
              <form action="{site_url url="admin/estimated/add"}" onsubmit="return validateForm()" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <ul class="nav nav-pills" role="tablist" style="margin-bottom:20px;">
                </ul>
                <div class="tab-content">
                    <!--Painel de configuração geral-->
                    <div class="tab-pane active panel panel-default" id="userdata">
                        <div class="panel-heading">
                            Estimated data
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Project</label>
                                        {html_options name='project' options=$projects_options selected=$mySelect class='form-control'}
                                        {form_error('project')}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" name="name" class="form-control" />
                                        {form_error('name')}
                                    </div>
                                </div>
                            </div> <!-- end row -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Duration</label>
                                        <input type="text" id="from" name="from" autocomplete="off" value="" class="form-control">
                                        {form_error('from')}
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">    
                                        <label for="to">To</label>
                                        <input type="text" id="to" name="to" autocomplete="off" class="form-control">
                                    </div>
                                </div>
                            </div> <!-- end row -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="pm">PM</label>
                                        {html_options name='pm' options=$member_options selected=$mySelect class='form-control'}
                                        {form_error('pm')}
                                    </div>
                                </div>
                            </div> <!-- end row -->
                            <div class="row">        
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        {html_options name='status' options=$status_options selected=$mySelect class='form-control'}
                                        {form_error('status')}
                                    </div>
                                </div>
                            </div> <!-- end row -->
                        </div>
                                    
<div class="panel-body" style="margin-bottom: 50px;">
    <div class="row">
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
                                        <th class="text-center" style="width: 50%">
                                                Name
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                                Work Hours
                                        </th>
                                        <th class="text-center" style="width: 25%">
                                                Assignee
                                        </th>
                                        <th class="text-center" style="width: 5%">
                                                Delete
                                        </th>
                                </tr>
                        </thead>
                        <tbody class="newtr">
                            <tr id='addr1'>
                                <td valign="center">
                                    <span class="glyphicon glyphicon-sort" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Move"></span>
                                    <span class="glyphicon glyphicon-plus" onclick="plus_desc_task(this)" data-toggle="tooltip" data-placement="bottom" title="Expand"></span>
                                </td>
                                <td>
                                    <input type="text" name='no[]'  placeholder='No.' value="1" class="form-control disabled number_no" readonly style="text-align:center"/>
                                </td>
                                <td>
                                    <input type="text" name='name_task[]' placeholder='Enter Name' value="" class="form-control"/>
                                    <div class="form-group desc_task">
                                        <label for="comment">Description:</label>
                                        <textarea class="form-control" rows="5" name="description[]" id="description"></textarea>
                                    </div>
                                </td>
                                <td>
                                        <input type="text" name='workhours[]' placeholder='Enter workhours' class="form-control workhours text-right"/>
                                </td>
                                <td>
                                    {html_options name='assignee[]' options=$member_options selected=$val class='form-control'}
                                </td>
                                <td>
                                    <span class="glyphicon glyphicon-remove btns_delete" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Delete"></span>
                                </td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr style="background: #f6f6f6;">
                                <td colspan="3" style="text-align: right;">Total </td>
                                <td>
                                    <input type="text" name='sum_all' style="text-align: right;" value="" class="form-control disabled text-right numberfomat sum_all"/>
                                </td>
                                <td></td>
                                <td></td>
                            </tr>
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
                        <button type="submit" name="submit" class="btn btn-primary">Save</button>
                        <a class="btn btn-danger" href='{site_url url="admin/estimated"}'>Cancel</a>
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
.form-control.disabled {
    pointer-events: none;
    background: #e4e4e4;
}
textarea#description {
    resize: vertical;
}
.desc_task { display: none; }
.show_desc .desc_task { display: block; }
.glyphicon-sort { cursor: -webkit-grab !important; cursor: grab !important; }
.glyphicon-sort:active, .glyphicon-sort:focus { cursor: -webkit-grabbing !important; cursor: grabbing !important; }
</style>
                        
<script type="text/javascript">
    function plus_desc_task(elmnt){
        var a = $(elmnt);
        a.toggleClass("glyphicon-plus");
        a.toggleClass("glyphicon-minus");
        a.closest( "tr" ).toggleClass( "show_desc" );
    }
    function td_order() {
        var b = 1;
        $('#tab_logic > tbody.newtr > tr').each(function () {
            $(this).attr("id","addr"+b);
            $(this).find('.number_no').val(b);
            b++;
        });
    }
    function validateForm() {
    var x = $("input[name=name]").val();
        if (x == "") {
          alert("กรุณากรอกชื่อ Estimated");
          return false;
        }
    }
    function sumWorks() {
        var all_amount = 0;
        $('input.workhours').each(function(){
            var t_val = this.value.replace(",", "");
            t_val = parseFloat(t_val);
            all_amount += parseFloat(t_val);
        });
        $('.sum_all').val(all_amount).trigger( 'input' );
    }
</script>
<script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
        sumWorks();
        $('body').on('input','.workhours',function(){    
            sumWorks();
        });
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
            var b = $("#tab_logic > tbody.newtr > tr").length;
            var i = b+1;
            $('#tab_logic').append('<tr id="addr'+i+'"></tr>');
            $('#addr'+i).html($('#addr'+b).html()).find('td:first-child').html('<span class="glyphicon glyphicon-sort" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" title="Move"></span><span class="glyphicon glyphicon-plus" onclick="plus_desc_task(this)"></span>')
            $('#addr'+i).find('td:nth-child(3) input').val('');
            $('#addr'+i).find('td:nth-child(4) input').val('');
            $('#addr'+i).find('td:nth-child(5) select').val('');
            $('#addr'+i).find('td:nth-child(6) input').val('');
            $('#addr'+i).find('td:nth-child(2) input').val(i);
            i++; 
        } );
        
        $("#delete_row").click(function() {
            var i = $("#tab_logic > tbody.newtr > tr").length;
            if(i>1) {
                $("#tab_logic > tbody.newtr > tr:last-child").remove();
            }
        } );

    });

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