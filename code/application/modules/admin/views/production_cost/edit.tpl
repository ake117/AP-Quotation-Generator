{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<section class="content-header">
    <h1>
        Production Cost
        <small>Manage Production Cost here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{site_url url="admin/dashboard"}"><i class="fa fa-dashboard"></i> {ci_language line="mod_dashboard"}</a></li>
        <li><a href="{site_url url="admin/estimated"}"><i class="fa fa-users"></i> {ci_language line="mod_useraccount"}</a></li>
        <li>Edit Production Cost</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">Edit Production Cost</h3>
        </div>
        <div class="box-body">
              <form action="{site_url url="admin/production_cost/edit/{$row.production_id}"}" id="target" role="form" enctype="multipart/form-data" method="post" accept-charset="utf-8">
                <ul class="nav nav-pills" role="tablist" style="margin-bottom:20px;">
                </ul>
                <div class="tab-content">
                    <!--Painel de configuração geral-->
                    <div class="tab-pane active panel panel-default" id="userdata">
                        <div class="panel-heading">
                            Production Cost Data
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Project</label>
                                                {html_options name='project' options=$projects_options selected=$row.project_id class='form-control disabled'}
                                                {form_error('project')}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="name">Name</label>
                                                <input type="text" name="name" value="{$row.production_name}" class="form-control disabled" />
                                                {form_error('name')}
                                            </div>
                                        </div>
                                    </div> <!-- end row -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Duration</label>
                                                <input type="text" id="from" name="from" autocomplete="off" value="{$row.production_start}" class="form-control disabled">
                                                {form_error('from')}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">    
                                                <label for="to">To</label>
                                                <input type="text" id="to" name="to" autocomplete="off" value="{$row.production_end}" class="form-control disabled">
                                            </div>
                                        </div>
                                    </div> <!-- end row -->
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="pm">PM</label>
                                                {html_options name='pm' options=$member_options selected=$row.pm_member_id class='form-control disabled'}
                                                {form_error('pm')}
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="pm">Job No. <span id="error_more_jobno"></span></label>
                                                <input type="text" id="job_no" name="job_no" autocomplete="off" value="{$row.job_no}" class="form-control">
                                            </div>
                                        </div>
                                    </div> <!-- end row -->
                                    <div class="row">        
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="status">Status</label>
                                                {html_options name='status' id='status'  options=$status_options selected=$row.production_status class='form-control'}
                                                {form_error('status')}
                                            </div>
                                        </div>
                                    </div> <!-- end row -->
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="thumbnail">
                                                {if !empty($row.production_cost_image)}
                                                    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
                                                    <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
                                                    <a data-fancybox="gallery" href="{$base_url}/upload/production_cost/{$row.production_cost_image}">
                                                    <embed src="{$base_url}/upload/production_cost/{$row.production_cost_image}" class="img-responsive" width="100%" height="auto" style="max-height: 180px;">
                                                    </a>
                                                    {if $row.production_cost_image|strstr:".pdf"}
                                                        <a target="_blank" href="{$base_url}/upload/production_cost/{$row.production_cost_image}">PDF File</a>
                                                    {/if}
                                                {else}
                                                    <img src="{$base_url}/lib/img/no-user.jpg" class="img-responsive" style="max-height: 180px;" />
                                                {/if}
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="userfile">Upload Production Cost Image</label>
                                                <input type="file" name="file_production_cost_image" class="form-control" />
                                                <input type="hidden" name="production_cost_image" value="{$row.production_cost_image}"/>
                                                <div class="checkbox">
                                                    <label>
                                                        <input type="checkbox" name="change_production_cost_image" value="1">
                                                        Change Production Cost Image
                                                    </label>
                                                </div>
                                                {form_error('image')}
                                            </div>
                                        </div>
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
                                        <th class="text-center" style="width: 5%">
                                                No.
                                        </th>
                                        <th class="text-center" style="width: 54%">
                                                Job Title
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                                Discount (%)
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                                Rate
                                        </th>
                                        <th class="text-center" style="width: 10%">
                                                Work Hours
                                        </th>
                                        <th class="text-center" style="width: 20%">
                                                Amount
                                        </th>
                                </tr>
                        </thead>
                        <tbody>
                            {assign var='workhours_all' value='0'} 
                            {assign var='n_cost_rate_all' value='0'} 
                            {assign var='n_cost_amount_all' value='0'} 
                            {foreach from=$row_cost key=k item=v}
                                <tr id='addr{$k}'>
                                    <td>
                                        <input type="hidden" name='job_id[]' value="{$v.job_id}" class="form-control" />
                                        <input type="text" name='no[]'  placeholder='No.' value="{$k+1}" class="form-control" readonly style="text-align:center"/>
                                    </td>
                                    <td>
                                        <input type="text" name='job_title[]' value="{$v.job_title}" class="form-control disabled"/>
                                    </td>
                                    <td>
                                        <input type="hidden" name='job_rate[]' value="{$v.job_rate}" class="form-control" />
                                        <input type="hidden" name='job_discount[]' value="{$v.job_discount}" class="form-control" />
                                        <input type="text" name='n_cost_discount[]' min="0" max="100" value="{$v.n_cost_discount}" class="form-control n_cost_discount"/>
                                    </td>
                                    <td>
                                        <input type="text" name='n_cost_rate[]' style="text-align: right;" value="{$v.n_cost_rate}" class="form-control disabled n_cost_rate numberfomat"/>
                                    </td>
                                    <td>
                                        <input type="text" name='work_hours[]' style="text-align: right;" value="{$v.n_work_hours}" class="form-control disabled n_work_hours numberfomat"/>
                                    </td>
                                    <td>
                                        <input type="text" name='n_cost_amount[]' style="text-align: right;" value="{$v.n_cost_amount}" class="form-control disabled n_cost_amount numberfomat"/>
                                    </td>
                                </tr>
                                {assign var="workhours_all" value=$workhours_all+$v.n_work_hours}
                                {assign var="n_cost_rate_all" value=$n_cost_rate_all+$v.n_cost_rate}
                                {assign var="n_cost_amount_all" value=$n_cost_amount_all+$v.n_cost_amount}
                            {/foreach}
                            <tr id='addr{$k+1}'>
                                    <td colspan="4" style="text-align: right;">
                                        <input type="text" name='n_cost_rate_all' style="text-align: right;display:none;" value="{$n_cost_rate_all}" class="form-control disabled numberfomat"/>
                                        Total 
                                    </td>
                                    <td>
                                        <input type="text" name='workhours_all' style="text-align: right;" value="{$workhours_all}" class="form-control disabled numberfomat"/>
                                    </td>
                                    <td>
                                        <input type="text" name='n_cost_amount_all' style="text-align: right;" value="{$n_cost_amount_all}" disabled class="form-control disabled all_n_cost_amount numberfomat"/>
                                    </td>
                                </tr>
                        </tbody>
                </table>
        </div>
    </div>
     
</div>                             
                                    
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        {if $row.production_status != 'approved'}
                            <button type="submit" name="submit" class="btn btn-primary">Save</button>
                            <a class="btn btn-danger" href='{site_url url="admin/production_cost"}'>Cancel</a>
                        {else}
                            <button type="submit" name="submit" class="btn btn-primary" style="display:none">Save</button>
                            <a class="btn btn-danger" href='{site_url url="admin/production_cost"}'>Back</a>
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
.form-control.disabled {
    pointer-events: none;
    background: #e4e4e4;
}
#error_more_jobno {
    color: red;
}
{assign var='status' value=','|explode:"approved,close"} 
{if $row.production_status|in_array:$status }    
    button[name="submit"] { display: none; }
{/if}
</style>                       
                        
<script>
$(document).ready(function() {
    if("close" === "{$row.production_status}"){
        document.getElementById("status").disabled = true;
        document.getElementById("job_no").disabled = true;
    }
    if("close" === "{$row.production_status}"){
        $("form :input").prop("disabled", true);
    }
    {if $row.production_status=="approved" }    
        $('select[name="status"]').prop("disabled", false);
            
        $('body').on('change','select[name="status"]',function(){
            if($(this).val() === 'close'){
                $('button[name="submit"]').show();
                $('button[name="submit"]').prop("disabled", false);
            }else{
                $('button[name="submit"]').hide();
                $('button[name="submit"]').prop("disabled", true);
            }
        });
    {/if} 
    //$(".form-control.disabled").attr('style', 'pointer-events: none;background: #e4e4e4;');
    var i={$k+1};
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
    
    $(".n_cost_discount").change(function() {
        var div_s = $(this).parent().parent();
        var discount = $(this).val();
        var rate = div_s.find('.n_cost_rate').val();
        rate = rate.replace(",", "");
        var work_hours = div_s.find('.n_work_hours').val();
        work_hours = work_hours.replace(",", "");
        rate = parseInt(rate);
        work_hours = parseInt(work_hours);
        var amount = (((100 - discount)/100)*rate)*work_hours;
        div_s.find('.n_cost_amount').val(amount).trigger( 'input' );
        var all_amount = 0;
        $('input.n_cost_amount').each(function(){
            var t_val = this.value.replace(",", "");
            t_val = parseInt(t_val);
            all_amount += parseFloat(t_val);
        });
        $('.all_n_cost_amount').val(all_amount).trigger( 'input' );
    });
 } );
 
$(document).on('keyup', 'input.n_cost_discount', function () {
    var _this = $(this);
    var min = parseInt(_this.attr('min')) || 0; // if min attribute is not defined, 1 is default
    var max = parseInt(_this.attr('max')) || 100; // if max attribute is not defined, 100 is default
    var val = parseInt(_this.val()) || (min - 1); // if input char is not a number the value will be (min - 1) so first condition will be true
    if(val < min)
        _this.val( min );
    if(val > max)
        _this.val( max );
});

$( "#target" ).submit(function( event ) {
    var error_more_jobno = $('#error_more_jobno').html();
    if(error_more_jobno !== ""){
        alert( $('#error_more_jobno').html() );
        event.preventDefault();
    }
});
 
$(document).on('keyup', '#job_no', function () {
    var p_val = $(this).val();
    $.ajax( {
        url: '/admin/production_cost/jobno/'+p_val,
        dataType: "json",
        success: function(data) {
            $('#error_more_jobno').html(data.txt);
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