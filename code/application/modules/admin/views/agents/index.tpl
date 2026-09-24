{include file="header.tpl"}
{ci_config name="base_url"}
{ci_language file="wpn_common" lang="$lang"}

<section class="content-header">
    <h1>
        {ci_language line="mod_agents"}
        <small>Manage the Agents displayed on the site here.</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="{site_url url="admin/dashboard"}"><i class="fa fa-dashboard"></i> Dashboard</a></li>
        <li><i class="fa fa-shirtsinbulk" aria-hidden="true"></i> {ci_language line="mod_agents"}</li>
    </ol>
</section>

<section class="content">
    <div class="box">
        <div class="box-header with-border">
            <h3 class="box-title">List of {ci_language line="mod_agents"}</h3>
            <div class="box-tools pull-right">
                <a href="{site_url url="admin/agents/add"}" class="btn btn-sm btn-primary"><span class="glyphicon glyphicon-plus-sign"></span>New {ci_language line="mod_agents"}</a>
            </div>
        </div>
        <div class="box-body">
            <table id="grid" class="table table-striped">
			    <thead>
			        <tr>
			            <th>#</th>
			            <th>Name</th>
			            <th>Email</th>
			            <th>Phone</th>
			            <th>Link</th>
			            <th>Status</th>
			            <th>Created</th>
			            <th>Action</th>
			        </tr>
			    </thead>
			    <tbody class="sortable">
                                {foreach from=$query item=row}
                                    <tr id="item-{$row.agent_id}"  style="">
                                      <td>{$row.agent_id}</td>
                                      <td>{$row.prefix} {$row.name} {$row.surname}</td>
                                      <td>{$row.email}</td>
                                      <td>{$row.phone}</td>
                                      <td>
<div class="btn-group">
    <a class="btn btn-primary btn-email" href="{$row.link}" target="_blank">{$row.link}</a>
    <button type="button" class="btn btn-default btn-copy js-tooltip js-copy" data-toggle="tooltip" data-placement="bottom" data-copy="{$row.link}" title="Copy to clipboard">
        <svg class="icon" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="24" height="24" viewBox="0 0 24 24"><path d="M17,9H7V7H17M17,13H7V11H17M14,17H7V15H14M12,3A1,1 0 0,1 13,4A1,1 0 0,1 12,5A1,1 0 0,1 11,4A1,1 0 0,1 12,3M19,3H14.82C14.4,1.84 13.3,1 12,1C10.7,1 9.6,1.84 9.18,3H5A2,2 0 0,0 3,5V19A2,2 0 0,0 5,21H19A2,2 0 0,0 21,19V5A2,2 0 0,0 19,3Z" /></svg>
    </button>
</div>
                                      </td>
                                      <td>{$row.agent_status|ucfirst}</td>
                                      <td>{$row.create_date|date_format:"%d/%m/%Y %H:%M"}</td>
                                      <td>
                                            <div class="btn-group btn-group-xs">
                                                <a href="{site_url url="admin/agents/edit"}/{$row.agent_id}" class="btn btn-default"><span class="glyphicon glyphicon-edit"></span></a>
                                                <button class="btn btn-default" onclick="return confirm('{site_url url="admin/agents/delete"}/{$row.agent_id}');"><span class="glyphicon glyphicon-trash"></span></button>
                                            </div>
                                      </td>
                                    </tr>
                                {/foreach}
			    </tbody>
			</table>
        </div>
    </div>
</section>

<!--script type="text/javascript">
    $(function(){
        $(".sortable").sortable({
            axis: 'y',
            update: function (event, ui) {
                var data = $(this).sortable('serialize');
                alert(data);
                $.ajax({
                    data: data,
                    type: 'POST',
                    url: '{site_url url="admin/agentss/update_sequence"}'
                });
            }
        });
    });
</script-->
<style>
.icon {
  width: 16px;
  height: 16px;
  padding: 0;
  margin: 0;
  vertical-align: middle;
}
</style>

<script>
// COPY TO CLIPBOARD
// Attempts to use .execCommand('copy') on a created text field
// Falls back to a selectable alert if not supported
// Attempts to display status in Bootstrap tooltip
// ------------------------------------------------------------------------------

function copyToClipboard(text, el) {
  var copyTest = document.queryCommandSupported('copy');
  var elOriginalText = el.attr('data-original-title');

  if (copyTest === true) {
    var copyTextArea = document.createElement("textarea");
    copyTextArea.value = text;
    document.body.appendChild(copyTextArea);
    copyTextArea.select();
    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'Copied!' : 'Whoops, not copied!';
      el.attr('data-original-title', msg).tooltip('show');
    } catch (err) {
      console.log('Oops, unable to copy');
    }
    document.body.removeChild(copyTextArea);
    el.attr('data-original-title', elOriginalText);
  } else {
    // Fallback if browser doesn't support .execCommand('copy')
    window.prompt("Copy to clipboard: Ctrl+C or Command+C, Enter", text);
  }
}

$(document).ready(function() {
  // Initialize
  // ---------------------------------------------------------------------

  // Tooltips
  // Requires Bootstrap 3 for functionality
  $('.js-tooltip').tooltip();

  // Copy to clipboard
  // Grab any text in the attribute 'data-copy' and pass it to the 
  // copy function
  $('.js-copy').click(function() {
    var text = $(this).attr('data-copy');
    var el = $(this);
    copyToClipboard(text, el);
  });
});
</script>
{include file="footer.tpl"}