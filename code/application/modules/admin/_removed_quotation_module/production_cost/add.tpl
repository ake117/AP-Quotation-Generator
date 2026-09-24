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
              <form action="{site_url url="admin/estimated/add"}" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
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
                                        <th class="text-center" style="width: 1%;">
                                                #
                                        </th>
                                        <th class="text-center" style="width: 5%">
                                                No.
                                        </th>
                                        <th class="text-center" style="width: 54%">
                                                Name
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                                Work Hours
                                        </th>
                                        <th class="text-center" style="width: 30%">
                                                Assignee
                                        </th>
                                </tr>
                        </thead>
                        <tbody>
                                <tr id='addr0'>
                                    <td valign="center"><span class="glyphicon glyphicon-plus" onclick="plus_desc_task(this)"></span></td>
                                    <td>
                                        <input type="text" name='no[]'  placeholder='No.' value="1" class="form-control" readonly style="text-align:center"/>
                                    </td>
                                    <td>
                                        <input type="text" name='name_task[]' placeholder='Enter Name' class="form-control"/>
                                        <div class="form-group desc_task">
                                            <label for="comment">Description:</label>
                                            <textarea class="form-control" rows="5" name="description[]" id="description"></textarea>
                                        </div>
                                    </td>
                                    <td>
                                        <input type="number" name='workhours[]' placeholder='Enter workhours' class="form-control"/>
                                    </td>
                                    <td>
                                        {html_options name='assignee[]' options=$member_options selected=$mySelect class='form-control'}
                                    </td>
                                </tr>
            <tr id='addr1'></tr>
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
}
#tab_logic td:first-child {
    text-align: center; 
    vertical-align: middle;
}
textarea#description {
    resize: vertical;
}
.desc_task { display: none;}
.show_desc .desc_task { display: block;}
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
    var i=1;
    $("#add_row").click(function(){ b=i-1;
        $('#addr'+i).html($('#addr'+b).html()).find('td:first-child').html('<span class="glyphicon glyphicon-plus" onclick="plus_desc_task(this)"></span>');
        $('#addr'+i).find('td:nth-child(2) input').val(i+1);
        $('#tab_logic').append('<tr id="addr'+(i+1)+'"></tr>');
        i++; 
    } );
    $("#delete_row").click(function() {
        if(i>1) {
            $("#addr"+(i-1)).html('');
            i--;
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